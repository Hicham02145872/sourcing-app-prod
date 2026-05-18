# Code Review — Flask Tracking Server

> **Date :** 2026-05-18  
> **Fichiers analysés :**  
> `scraper/tracking_server.py` · `scraper/test_server.py`  
> `scraper/itdida_tracker.py` · `scraper/choicexp_tracker.py` · `scraper/ups_tracker.py`  
> `scraper/requirements.txt`  
> `app/Services/Tracking/AbstractSeleniumTrackingService.php` (côté Laravel)

---

## Verdict global

Le serveur Flask est **bien conçu** dans l'ensemble (architecture `TrackerWrapper`, lock par provider, retry auto-reinit, health endpoint, waitress en production). Cependant **4 bugs bloquants** empêchent de le déployer en l'état, et **le point le plus critique : Laravel ne l'appelle pas encore** — `AbstractSeleniumTrackingService` continue de spawner des process Python à chaque requête, rendant le serveur Flask actuellement sans effet.

---

## BLOQUANT — À corriger avant tout déploiement

---

### B1 — Laravel n'est PAS intégré au serveur Flask

**Fichier :** `app/Services/Tracking/AbstractSeleniumTrackingService.php:80`

C'est le problème le plus critique. `ItdidaTrackingService`, `ChoiceXPTrackingService`, et `UPSTrackingService` héritent tous de `AbstractSeleniumTrackingService::runScript()` qui continue de spawner un process Python à chaque requête. Le serveur Flask tourne en parallèle mais n'est **jamais appelé**.

```php
// AbstractSeleniumTrackingService.php:80 — INCHANGÉ, ignore le serveur Flask
$result = Process::env($this->getEnvironment())
    ->timeout(120)
    ->run("{$this->pythonPath} \"{$this->scriptPath}\" \"{$trackingNumber}\"");
```

**Action requise :** Remplacer `runScript()` par un appel HTTP à `http://127.0.0.1:5001/track/{provider}`. Voir la section "Intégration Laravel" en bas de ce document.

---

### B2 — Conflit de port 9222 entre ITDIDA et ChoiceXP

**Fichier :** `scraper/itdida_tracker.py:29` et `scraper/choicexp_tracker.py:26`

Quand le serveur Flask démarre avec `--no-eager-init` désactivé (mode par défaut), il initialise les 3 drivers simultanément. ITDIDA et ChoiceXP essaient tous les deux de binder `--remote-debugging-port=9222`. Chrome en mode embedded n'échoue pas silencieusement — cela peut provoquer un crash ou un comportement imprévisible du second driver.

```python
# itdida_tracker.py:29
options.add_argument("--remote-debugging-port=9222")  # ← conflit
options.add_argument("--remote-debugging-pipe")        # ← écrase le port, OK pour ITDIDA

# choicexp_tracker.py:26
options.add_argument("--remote-debugging-port=9222")  # ← conflit avec ITDIDA
# pas de --remote-debugging-pipe → choicexp essaie vraiment de binder le port
```

**Action requise :**
- ITDIDA : supprimer `--remote-debugging-port=9222` (inutile car `--remote-debugging-pipe` est déjà présent).
- ChoiceXP : utiliser `--remote-debugging-pipe` comme ITDIDA, ou assigner un port différent (ex: 9223).
- UPS : déjà correct (aucun des deux).

```python
# itdida_tracker.py — SUPPRIMER cette ligne :
options.add_argument("--remote-debugging-port=9222")

# choicexp_tracker.py — REMPLACER par :
options.add_argument("--remote-debugging-pipe")
```

---

### B3 — ITDIDA : fuite disque `user-data-dir` avec timestamp (non corrigé)

**Fichier :** `scraper/itdida_tracker.py:33`

ChoiceXP (ligne 29) et UPS (ligne 32) utilisent des profils fixes (`/tmp/chrome-choicexp`, `/tmp/chrome-ups`). ITDIDA utilise encore le timestamp qui génère un nouveau dossier de 50–200 MB à chaque démarrage du driver — soit à chaque `restart`, à chaque crash/reinit.

```python
# itdida_tracker.py:33 — INCORRECT (fuite disque)
options.add_argument("--user-data-dir=/tmp/chrome-user-data-itdida-" + str(time.time()))
```

**Action requise :**
```python
# itdida_tracker.py — REMPLACER par :
options.add_argument("--user-data-dir=/tmp/chrome-itdida")
```

---

### B4 — Bug de calcul du temps écoulé dans `test_server.py`

**Fichier :** `scraper/test_server.py:101`

```python
# test_server.py:101 — BUG : toujours 0.0s
elapsed = round(time.time() - time.time(), 2)
```

`time.time() - time.time()` est toujours ~0. La variable `start` est capturée ligne 76 mais n'est pas accessible dans le bloc `except` car elle est définie après le `try`. Le temps affiché sur échec est donc toujours `0.0s`.

**Action requise :**
```python
def test_provider(base_url, provider, number):
    start = time.time()          # ← déplacer ICI, avant le try
    try:
        status_code, result = http_post(...)
        elapsed = round(time.time() - start, 2)
        ...
    except Exception as e:
        elapsed = round(time.time() - start, 2)   # ← maintenant correct
        fail(f"Request failed after {elapsed}s: {e}")
```

---

## MAJEUR — À corriger dans le même sprint

---

### M1 — Pas de timeout au niveau Flask (Selenium peut bloquer indéfiniment)

**Fichier :** `scraper/tracking_server.py:161`

```python
with wrapper.lock:
    result = wrapper.scrape(number)   # ← peut bloquer infiniment
```

Si Chrome crashe après le `WebDriverWait` (ex: OOM, SIGKILL externe), `scrape()` peut rester bloqué indéfiniment, maintenant le lock acquis. Tous les appels suivants au même provider seront en attente infinie.

Les timeouts internes des scrapers (`WebDriverWait(driver, 15)` pour UPS, `WebDriverWait(driver, 20)` pour ChoiceXP) protègent contre les timeouts réseau, mais pas contre un crash complet du driver.

**Action requise :** Wrapper le scrape dans un thread avec timeout :

```python
import concurrent.futures

def scrape_with_timeout(wrapper, tracking_number, timeout=45):
    with concurrent.futures.ThreadPoolExecutor(max_workers=1) as executor:
        future = executor.submit(wrapper.scrape, tracking_number)
        try:
            return future.result(timeout=timeout)
        except concurrent.futures.TimeoutError:
            wrapper._destroy()   # Force-kill le driver bloqué
            return {"success": False, "error": f"Scrape timeout after {timeout}s"}

# Dans la route :
with wrapper.lock:
    result = scrape_with_timeout(wrapper, number, timeout=45)
```

---

### M2 — Pas de handler SIGTERM/SIGINT (Chrome non nettoyé à l'arrêt)

**Fichier :** `scraper/tracking_server.py:195`

Quand le serveur est arrêté (Ctrl+C, `supervisorctl stop`, redémarrage), les 3 instances Chrome restent en mémoire en processus zombies jusqu'au prochain reboot. Sur un VPS, 3 Chrome abandonnés représentent ~300–600 MB de RAM bloquée.

**Action requise :**
```python
import signal, atexit

def shutdown(signum=None, frame=None):
    logger.info("Shutting down — closing Chrome drivers …")
    for name, wrapper in PROVIDERS.items():
        try:
            wrapper._destroy()
            logger.info(f"[{name}] Driver closed.")
        except Exception as e:
            logger.warning(f"[{name}] Error during shutdown: {e}")

atexit.register(shutdown)
signal.signal(signal.SIGTERM, shutdown)
signal.signal(signal.SIGINT, shutdown)
```

---

### M3 — Pas de validation de la longueur du `tracking_number`

**Fichier :** `scraper/tracking_server.py:153`

Le serveur accepte n'importe quelle chaîne comme numéro de tracking. Une chaîne de 10 000 caractères sera passée directement au driver Selenium comme URL parameter ou saisie dans un champ, ce qui peut provoquer des comportements inattendus.

**Action requise :**
```python
if not number:
    return jsonify({"success": False, "error": "tracking_number is required"}), 400

if len(number) > 100:
    return jsonify({"success": False, "error": "tracking_number too long (max 100)"}), 400

# Optionnel : validation de format basique
import re
if not re.match(r'^[A-Za-z0-9\-_]+$', number):
    return jsonify({"success": False, "error": "Invalid tracking_number format"}), 400
```

---

### M4 — `requirements.txt` non versionné

**Fichier :** `scraper/requirements.txt`

```
selenium
webdriver-manager
flask
waitress
```

Sans versions pinned, `pip install` peut installer des versions incompatibles. Flask 3.x a changé plusieurs APIs par rapport à Flask 2.x. Selenium 4.x vs 3.x est une breaking change majeure.

**Action requise :** Pincer les versions testées :
```
selenium==4.20.0
webdriver-manager==4.0.2
flask==3.0.3
waitress==3.0.0
```

Générer avec `pip freeze > requirements.txt` après validation en environnement de test.

---

## MINEUR — Améliorations recommandées

---

### m1 — `init_providers()` charge 3 Chrome instances au démarrage (~600 MB)

**Fichier :** `scraper/tracking_server.py:186`

En mode par défaut (eager), 3 Chrome headless sont chargés simultanément au boot. Sur un VPS 1 GB, cela peut épuiser la RAM avant la première requête.

**Recommandation :** Utiliser `--no-eager-init` sur VPS à faible RAM et initialiser les providers à la première requête (lazy). Ajouter une doc claire dans le README sur les prérequis RAM :

```
# RAM estimée par provider :
# - Chrome headless : ~100–200 MB par instance
# - 3 providers = ~300–600 MB
# Recommandé : VPS 2 GB minimum pour eager mode
```

---

### m2 — Pas d'ID de corrélation dans les logs

**Fichier :** `scraper/tracking_server.py:158`

```python
logger.info(f"[{provider}] → tracking: {number}")
# ...
logger.info(f"[{provider}] ← result for {number}: success={result.get('success')}")
```

Avec plusieurs requêtes concurrentes (ex: itdida + choicexp en parallèle), les logs sont entrelacés et difficiles à tracer. Un UUID par requête aide au débogage.

**Recommandation :**
```python
import uuid

@app.route("/track/<provider>", methods=["POST"])
def track(provider: str):
    request_id = str(uuid.uuid4())[:8]
    logger.info(f"[{provider}][{request_id}] → tracking: {number}")
    with wrapper.lock:
        result = wrapper.scrape(number)
    logger.info(f"[{provider}][{request_id}] ← success={result.get('success')}")
    result["request_id"] = request_id
    return jsonify(result)
```

---

### m3 — `test_server.py` : `success=False` traité comme avertissement, pas comme échec de test

**Fichier :** `scraper/test_server.py:87`

```python
if result.get("success"):
    ok(f"Success in {elapsed}s")
else:
    print(f"{YELLOW}⚠ Tracking returned success=false{RESET}")
    # ...
return True   # ← retourne True même si le tracking a échoué
```

La fonction retourne `True` même quand `success=False`, donc `sys.exit(1)` ne sera jamais déclenché sur un vrai échec de scraping. Le test passe "au vert" même quand rien ne fonctionne.

**Recommandation :** Distinguer "serveur accessible mais numéro inconnu" (acceptable) de "serveur inaccessible ou exception Python" (échec test) :

```python
return result.get("success") is not None  # False seulement si HTTP error / exception
```

---

### m4 — `/restart/<provider>` sans authentification

**Fichier :** `scraper/tracking_server.py:168`

N'importe quel processus local peut forcer un restart Chrome mid-scrape. Acceptable en localhost-only, mais si la config est modifiée pour écouter sur `0.0.0.0` (erreur courante), cela devient un vecteur d'abus.

**Recommandation :** Ajouter un header secret statique configuré via variable d'environnement :

```python
ADMIN_TOKEN = os.environ.get("TRACKING_SERVER_ADMIN_TOKEN", "")

@app.route("/restart/<provider>", methods=["POST"])
def restart_provider(provider: str):
    if ADMIN_TOKEN and request.headers.get("X-Admin-Token") != ADMIN_TOKEN:
        return jsonify({"ok": False, "error": "Unauthorized"}), 401
    ...
```

---

### m5 — `choicexp_tracker.py` : `except: continue` silencieux

**Fichier :** `scraper/choicexp_tracker.py:128`

```python
for block in blocks:
    try:
        date = block.find_element(By.CLASS_NAME, "cd-timeline-date").text
        ...
    except:
        continue   # ← avale toutes les exceptions silencieusement
```

Un `except: pass/continue` sans logger rend impossible le débogage si la structure HTML du site change.

**Recommandation :**
```python
except Exception as e:
    logger.debug(f"[ChoiceXP] Skipping block: {e}")
    continue
```

---

## Intégration Laravel — Code à écrire

Le serveur Flask est prêt côté Python. Voici le code Laravel manquant pour compléter l'intégration.

### Option A — Remplacer `AbstractSeleniumTrackingService` par un client HTTP

Créer une nouvelle classe de base `FlaskTrackingService` :

```php
// app/Services/Tracking/FlaskTrackingService.php (NOUVEAU)
namespace App\Services\Tracking;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

abstract class FlaskTrackingService implements TrackingServiceInterface
{
    protected string $provider;

    protected function callFlaskServer(string $trackingNumber): array
    {
        $baseUrl = config('tracking.flask_server_url', 'http://127.0.0.1:5001');
        $timeout = (int) config('tracking.flask_timeout', 45);

        try {
            $response = Http::timeout($timeout)
                ->post("{$baseUrl}/track/{$this->provider}", [
                    'tracking_number' => $trackingNumber,
                ]);

            if ($response->successful()) {
                return $response->json();
            }

            return [
                'success' => false,
                'error' => "Flask server HTTP {$response->status()}: {$response->body()}",
            ];

        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::error("[Flask:{$this->provider}] Server unreachable — falling back to spawn", [
                'error' => $e->getMessage(),
            ]);
            // Fallback : si le serveur Flask est down, spawner le process (dégradé)
            return ['success' => false, 'error' => 'Flask server unavailable: ' . $e->getMessage()];
        }
    }
}
```

### Mettre à jour `ItdidaTrackingService`

```php
class ItdidaTrackingService extends FlaskTrackingService
{
    protected string $provider = 'itdida';

    public function __construct(
        protected \App\Services\TrackingTranslationService $translationService
    ) {}

    public function getTrackingInfo(string $trackingNumber): array
    {
        $result = $this->callFlaskServer($trackingNumber);
        return $this->parseAndTranslate($result);
    }

    // parseAndTranslate() = logique de parseScriptOutput() actuelle
}
```

### Ajouter dans `config/tracking.php`

```php
'flask_server_url'  => env('TRACKING_FLASK_SERVER_URL', 'http://127.0.0.1:5001'),
'flask_timeout'     => env('TRACKING_FLASK_TIMEOUT', 45),
'flask_enabled'     => env('TRACKING_FLASK_ENABLED', false),
```

### Health check depuis Laravel

```php
// app/Http/Controllers/Admin/TrackingTestController.php — ajouter :
public function flaskHealth()
{
    $url = config('tracking.flask_server_url') . '/health';
    try {
        $response = Http::timeout(3)->get($url);
        return response()->json($response->json());
    } catch (\Exception $e) {
        return response()->json(['ok' => false, 'error' => $e->getMessage()], 503);
    }
}
```

---

## Checklist de déploiement

```
□ B1 — Intégrer Laravel → Flask (FlaskTrackingService)
□ B2 — Corriger conflit port 9222 (itdida_tracker.py + choicexp_tracker.py)
□ B3 — Corriger user-data-dir ITDIDA (supprimer timestamp)
□ B4 — Corriger bug elapsed time dans test_server.py
□ M1 — Ajouter timeout Flask-level avec ThreadPoolExecutor
□ M2 — Ajouter SIGTERM/atexit handler
□ M3 — Ajouter validation longueur tracking_number
□ M4 — Versionner requirements.txt (pip freeze)
□ m1 — Documenter RAM requise (README)
□ m2 — Ajouter request_id dans les logs
□ m3 — Corriger comportement de retour des tests
□ m4 — Protéger /restart avec token optionnel
□ m5 — Logger les exceptions dans ChoiceXP
□     — Tester avec: python test_server.py --all
□     — Vérifier: GET /health retourne les 3 providers "ready"
□     — Vérifier: TRACKING_FLASK_ENABLED=true en .env
```

---

## Résumé des priorités

| ID | Problème | Sévérité |
|----|----------|----------|
| B1 | Laravel n'appelle pas le serveur Flask | 🔴 BLOQUANT |
| B2 | Conflit port 9222 ITDIDA ↔ ChoiceXP | 🔴 BLOQUANT |
| B3 | Fuite disque user-data-dir ITDIDA | 🔴 BLOQUANT |
| B4 | Bug elapsed time test_server.py | 🔴 BLOQUANT |
| M1 | Pas de timeout Flask-level | 🟠 MAJEUR |
| M2 | Pas de handler SIGTERM | 🟠 MAJEUR |
| M3 | Pas de validation tracking_number | 🟠 MAJEUR |
| M4 | requirements.txt non versionné | 🟠 MAJEUR |
| m1 | RAM documentation manquante | 🟡 MINEUR |
| m2 | Pas de request_id dans logs | 🟡 MINEUR |
| m3 | Tests success=false non détectés | 🟡 MINEUR |
| m4 | /restart sans auth | 🟡 MINEUR |
| m5 | except silencieux ChoiceXP | 🟡 MINEUR |
