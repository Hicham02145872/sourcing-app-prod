"""
Selenium Tracking Server — Flask HTTP API
==========================================
Keeps one persistent Chrome instance per provider so Laravel does not need to
spawn a new process for every tracking request.

Usage (direct):
    python tracking_server.py [--host 127.0.0.1] [--port 5001] [--no-eager-init]

Usage (via Supervisor / Gunicorn — NOT recommended for this use case because
Gunicorn forks workers and each fork gets its own Chrome instance):
    python tracking_server.py   # single-process is fine; lock handles concurrency

Routes:
    GET  /health                   → {"ok": true, "providers": {...}}
    POST /track/<provider>         → {"tracking_number": "..."} → JSON result
    POST /restart/<provider>       → force-reinitialize a provider's Chrome
"""
import argparse
import atexit
import concurrent.futures
import logging
import os
import re
import signal
import sys
import threading

from flask import Flask, jsonify, request

# Make sure the scraper directory is importable regardless of cwd
sys.path.insert(0, os.path.dirname(os.path.abspath(__file__)))

from choicexp_tracker import ChoiceXPTracker
from itdida_tracker import OptimizedOrderTrackerSelenium
from ups_tracker import UPSTracker

# ---------------------------------------------------------------------------
# Logging
# ---------------------------------------------------------------------------
logging.basicConfig(
    level=logging.INFO,
    format="%(asctime)s [%(levelname)s] %(name)s: %(message)s",
    handlers=[logging.StreamHandler(sys.stdout)],
)
logger = logging.getLogger("tracking_server")

# ---------------------------------------------------------------------------
# Flask app
# ---------------------------------------------------------------------------
app = Flask(__name__)


# ---------------------------------------------------------------------------
# TrackerWrapper — manages one provider's Chrome lifetime
# ---------------------------------------------------------------------------
class TrackerWrapper:
    """
    Wraps a tracker class instance with:
    - Lazy or eager Chrome initialization
    - Per-provider threading lock (one request at a time per provider)
    - Auto-reinit on crash (one retry)
    """

    def __init__(self, name: str, factory, scrape_method: str = "scrape"):
        self.name = name
        self.factory = factory          # callable → tracker instance
        self.scrape_method = scrape_method
        self.lock = threading.Lock()
        self.tracker = None

    # ------------------------------------------------------------------
    def _create(self):
        logger.info(f"[{self.name}] Initializing Chrome driver …")
        instance = self.factory()
        instance._init_driver()
        self.tracker = instance
        logger.info(f"[{self.name}] Chrome driver ready.")

    def _destroy(self):
        if self.tracker is not None:
            try:
                self.tracker.close()
            except Exception:
                pass
            self.tracker = None

    # ------------------------------------------------------------------
    def scrape(self, tracking_number: str, timeout: int = 45) -> dict:
        """Call provider scraper with timeout; reinit once if Chrome has crashed."""
        if self.tracker is None:
            self._create()

        def _run():
            fn = getattr(self.tracker, self.scrape_method)
            return fn(tracking_number)

        for attempt in range(2):
            with concurrent.futures.ThreadPoolExecutor(max_workers=1) as executor:
                future = executor.submit(_run)
                try:
                    return future.result(timeout=timeout)
                except concurrent.futures.TimeoutError:
                    logger.error(f"[{self.name}] Scrape timed out after {timeout}s — reinitializing Chrome")
                    self._destroy()
                    if attempt == 0:
                        try:
                            self._create()
                        except Exception as e:
                            return {"success": False, "error": f"Reinit failed after timeout: {e}"}
                    else:
                        return {"success": False, "error": f"Scrape timed out after {timeout}s"}
                except Exception as exc:
                    if attempt == 0:
                        logger.warning(f"[{self.name}] Scrape failed ({exc}), reinitializing Chrome …")
                        self._destroy()
                        try:
                            self._create()
                        except Exception as e:
                            return {"success": False, "error": f"Reinit failed: {e}"}
                    else:
                        logger.error(f"[{self.name}] Retry also failed: {exc}")
                        return {"success": False, "error": str(exc)}

        return {"success": False, "error": "Scrape failed after retry"}

    # ------------------------------------------------------------------
    def restart(self):
        self._destroy()
        self._create()


# ---------------------------------------------------------------------------
# Provider registry
# ---------------------------------------------------------------------------
PROVIDERS: dict[str, TrackerWrapper] = {
    "itdida": TrackerWrapper(
        "ITDIDA",
        lambda: OptimizedOrderTrackerSelenium(headless=True),
        scrape_method="get_order_status",
    ),
    "choicexp": TrackerWrapper(
        "ChoiceXP",
        lambda: ChoiceXPTracker(headless=True),
        scrape_method="scrape",
    ),
    "ups": TrackerWrapper(
        "UPS",
        lambda: UPSTracker(headless=True),
        scrape_method="scrape",
    ),
}


# ---------------------------------------------------------------------------
# Routes
# ---------------------------------------------------------------------------
@app.route("/health", methods=["GET"])
def health():
    status = {
        name: ("ready" if w.tracker is not None else "not_initialized")
        for name, w in PROVIDERS.items()
    }
    return jsonify({"ok": True, "providers": status})


_TRACKING_NUMBER_RE = re.compile(r'^[A-Za-z0-9\-_]{1,100}$')

@app.route("/track/<provider>", methods=["POST"])
def track(provider: str):
    data = request.get_json(force=True, silent=True) or {}
    number = str(data.get("tracking_number", "")).strip()

    if not number:
        return jsonify({"success": False, "error": "tracking_number is required"}), 400

    if not _TRACKING_NUMBER_RE.match(number):
        return jsonify({"success": False, "error": "Invalid tracking_number format (max 100 alphanumeric chars)"}), 400

    if provider not in PROVIDERS:
        return jsonify({"success": False, "error": f"Unknown provider: {provider}"}), 404

    wrapper = PROVIDERS[provider]
    logger.info(f"[{provider}] → tracking: {number}")

    with wrapper.lock:
        result = wrapper.scrape(number)

    logger.info(f"[{provider}] ← result for {number}: success={result.get('success')}")
    return jsonify(result)


@app.route("/restart/<provider>", methods=["POST"])
def restart_provider(provider: str):
    if provider not in PROVIDERS:
        return jsonify({"ok": False, "error": f"Unknown provider: {provider}"}), 404

    wrapper = PROVIDERS[provider]
    with wrapper.lock:
        try:
            wrapper.restart()
            return jsonify({"ok": True, "provider": provider})
        except Exception as e:
            logger.error(f"[{provider}] restart failed: {e}")
            return jsonify({"ok": False, "error": str(e)}), 500


# ---------------------------------------------------------------------------
# Graceful shutdown
# ---------------------------------------------------------------------------
def _shutdown(signum=None, frame=None):
    logger.info("Shutting down — closing Chrome drivers …")
    for name, wrapper in PROVIDERS.items():
        try:
            wrapper._destroy()
            logger.info(f"[{name}] Driver closed.")
        except Exception as e:
            logger.warning(f"[{name}] Error during shutdown: {e}")

atexit.register(_shutdown)
signal.signal(signal.SIGTERM, _shutdown)
signal.signal(signal.SIGINT, _shutdown)


# ---------------------------------------------------------------------------
# Entry point
# ---------------------------------------------------------------------------
def init_providers():
    """Pre-initialize all Chrome drivers at startup (eager mode)."""
    for name, wrapper in PROVIDERS.items():
        try:
            wrapper._create()
        except Exception as e:
            logger.error(f"Failed to init driver for {name}: {e}")


if __name__ == "__main__":
    parser = argparse.ArgumentParser(description="Selenium Tracking Server")
    parser.add_argument("--host", default="127.0.0.1", help="Bind address (default: 127.0.0.1)")
    parser.add_argument("--port", type=int, default=5001, help="Port (default: 5001)")
    parser.add_argument(
        "--no-eager-init",
        action="store_true",
        help="Skip Chrome initialization at startup (lazy mode)",
    )
    parser.add_argument(
        "--dev",
        action="store_true",
        help="Use Flask built-in server (dev only). Default: waitress (production-grade).",
    )
    args = parser.parse_args()

    if not args.no_eager_init:
        logger.info("Pre-initializing Chrome drivers …")
        init_providers()

    logger.info(f"Starting tracking server on http://{args.host}:{args.port}")

    if args.dev:
        app.run(host=args.host, port=args.port, threaded=True)
    else:
        try:
            from waitress import serve
            logger.info("Using waitress WSGI server (production mode)")
            serve(app, host=args.host, port=args.port, threads=6)
        except ImportError:
            logger.warning("waitress not installed, falling back to Flask dev server. Run: pip install waitress")
            app.run(host=args.host, port=args.port, threaded=True)
