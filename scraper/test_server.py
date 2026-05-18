"""
Tracking Server — Test Script
==============================
Run this AFTER starting tracking_server.py to verify everything works.

Usage:
    python test_server.py
    python test_server.py --url http://127.0.0.1:5001
    python test_server.py --provider choicexp --number CHIL26155631
    python test_server.py --all   # test all providers with default numbers
"""
import argparse
import json
import sys
import time
import urllib.request
import urllib.error

BASE_URL = "http://127.0.0.1:5001"

# Default test numbers per provider — replace with real numbers if needed
DEFAULT_NUMBERS = {
    "itdida":   "TEST-ITDIDA-001",
    "choicexp": "CHIL26155631",
    "ups":      "1Z14V4W16890506495",
}

GREEN  = "\033[92m"
RED    = "\033[91m"
YELLOW = "\033[93m"
RESET  = "\033[0m"
BOLD   = "\033[1m"


def ok(msg):    print(f"{GREEN}✓ {msg}{RESET}")
def fail(msg):  print(f"{RED}✗ {msg}{RESET}")
def info(msg):  print(f"{YELLOW}→ {msg}{RESET}")


def http_get(url, timeout=5):
    req = urllib.request.Request(url)
    with urllib.request.urlopen(req, timeout=timeout) as r:
        return json.loads(r.read().decode())


def http_post(url, data, timeout=60):
    body = json.dumps(data).encode()
    req = urllib.request.Request(url, data=body, headers={"Content-Type": "application/json"})
    try:
        with urllib.request.urlopen(req, timeout=timeout) as r:
            return r.status, json.loads(r.read().decode())
    except urllib.error.HTTPError as e:
        return e.code, json.loads(e.read().decode())


def test_health(base_url):
    print(f"\n{BOLD}=== /health ==={RESET}")
    try:
        data = http_get(f"{base_url}/health", timeout=5)
        if data.get("ok"):
            ok(f"Server is up. Providers: {data['providers']}")
        else:
            fail(f"Unexpected response: {data}")
        return True
    except Exception as e:
        fail(f"Cannot reach server at {base_url}: {e}")
        info("Make sure tracking_server.py is running first:")
        info("  python tracking_server.py")
        return False


def test_provider(base_url, provider, number):
    print(f"\n{BOLD}=== /track/{provider} ({number}) ==={RESET}")
    start = time.time()
    try:
        status_code, result = http_post(
            f"{base_url}/track/{provider}",
            {"tracking_number": number},
            timeout=60,
        )
        elapsed = round(time.time() - start, 2)

        if status_code != 200:
            fail(f"HTTP {status_code}: {result}")
            return False

        if result.get("success"):
            ok(f"Success in {elapsed}s — status: {result.get('current_status', '?')}")
            events = result.get("events", [])
            info(f"{len(events)} event(s) returned")
            if events:
                info(f"Latest: {events[0]}")
        else:
            error_msg = result.get("error", "unknown error")
            print(f"{YELLOW}⚠ Tracking returned success=false in {elapsed}s{RESET}")
            info(f"Error: {error_msg[:200]}")

        return True

    except Exception as e:
        elapsed = round(time.time() - start, 2)
        fail(f"Request failed after {elapsed}s: {e}")
        return False


def test_invalid_provider(base_url):
    print(f"\n{BOLD}=== /track/invalid_provider (error handling) ==={RESET}")
    try:
        status_code, result = http_post(
            f"{base_url}/track/does_not_exist",
            {"tracking_number": "ABC123"},
            timeout=5,
        )
        if status_code == 404 and not result.get("success"):
            ok(f"Correctly returned 404 for unknown provider")
        else:
            fail(f"Expected 404, got {status_code}: {result}")
    except Exception as e:
        fail(f"Error: {e}")


def test_empty_number(base_url):
    print(f"\n{BOLD}=== /track/choicexp (empty tracking_number) ==={RESET}")
    try:
        status_code, result = http_post(
            f"{base_url}/track/choicexp",
            {"tracking_number": ""},
            timeout=5,
        )
        if status_code == 400 and not result.get("success"):
            ok(f"Correctly returned 400 for empty tracking number")
        else:
            fail(f"Expected 400, got {status_code}: {result}")
    except Exception as e:
        fail(f"Error: {e}")


def main():
    parser = argparse.ArgumentParser(description="Tracking Server Test Suite")
    parser.add_argument("--url", default=BASE_URL, help="Server base URL")
    parser.add_argument("--provider", choices=["itdida", "choicexp", "ups"], help="Test a single provider")
    parser.add_argument("--number", help="Tracking number to use for single-provider test")
    parser.add_argument("--all", action="store_true", help="Test all providers")
    args = parser.parse_args()

    base_url = args.url.rstrip("/")

    # 1. Health check
    if not test_health(base_url):
        sys.exit(1)

    # 2. Error handling (fast, no Chrome needed)
    test_invalid_provider(base_url)
    test_empty_number(base_url)

    # 3. Provider tests
    if args.provider:
        number = args.number or DEFAULT_NUMBERS.get(args.provider, "TEST123")
        test_provider(base_url, args.provider, number)
    elif args.all:
        for provider, number in DEFAULT_NUMBERS.items():
            test_provider(base_url, provider, number)
    else:
        print(f"\n{YELLOW}Tip: use --provider choicexp --number CHIL26155631 to test a real tracking{RESET}")
        print(f"{YELLOW}     or use --all to test all providers with default numbers{RESET}")

    print(f"\n{BOLD}Done.{RESET}")


if __name__ == "__main__":
    main()
