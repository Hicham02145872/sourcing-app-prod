# Plan d'Amélioration des Performances — Tracking Number Scraping

> **Projet :** sourcing-app  
> **Date d'analyse :** 2026-05-18  
> **Auteur :** Analyse automatisée — Claude Code  
> **Branche analysée :** `feature/production-setup`

---

## Résumé Exécutif

Le système de tracking actuel repose sur une architecture hybride : API REST (Faster, 17Track) et scraping Selenium (ITDIDA, ChoiceXP, UPS). L'analyse du code révèle **20+ problèmes de performance** répartis en 5 catégories. Les plus critiques sont la spawn répétée de Chrome à chaque requête, l'écriture en base de données sur chaque cache hit, et le double appel à la résolution d'alias FSB.

**Impact estimé :**
- Temps de réponse Selenium : **30–120s → 3–15s** (réutilisation du driver)
- Écriture DB inutile : **–60%** (filtrage des cache hits)
- Throughput du cron : **×3–5** (traitement concurrent)

---

## Cartographie de l'Architecture Actuelle

```
Requête utilisateur
      │
      ▼
UnifiedTrackingService::track()
      │
      ├─ Cache HIT ──────────────────────────────► logSearch() [DB INSERT] ─► retour
      │
      ├─ Circuit Breaker bloqué ─────────────────► retour erreur
      │
      └─ Cache MISS
            │
            ├─ resolveAlias() [1ère fois] ──► SourcingOrder DB
            ├─ detectProvider()
            │
            ├─ Provider Selenium (ITDIDA/ChoiceXP/UPS)
            │     └─ dispatch RunSeleniumTrackingJob
            │           └─ Process::run("python script.py") ← SPAWN CHROME
            │
            └─ Provider API (Faster)
                  └─ Http::get() synchrone
                        └─ performTracking()
                              └─ resolveAlias() [2ème fois] ◄── DOUBLON DB
```

---

## Problèmes Identifiés et Recommandations

---

### CATÉGORIE 1 — Selenium / Python (Impact CRITIQUE)

---

#### P1.1 — Spawn Chrome à chaque requête (CRITIQUE)

**Fichier :** `app/Services/Tracking/AbstractSeleniumTrackingService.php:80`  
**Fichier Python :** `scraper/itdida_tracker.py:21`

**Problème :** Chaque appel à `runScript()` lance un nouveau processus Python via `Process::run()`, qui lui-même instancie un nouveau `webdriver.Chrome()`. Le démarrage de Chrome seul prend **2–5 secondes** avant même de charger la page cible.

```php
// AbstractSeleniumTrackingService.php:80 — spawn à chaque fois
$result = Process::env($this->getEnvironment())
    ->timeout(120)
    ->run("{$this->pythonPath} \"{$this->scriptPath}\" \"{$trackingNumber}\"");
```

**Impact :** 5–10 secondes de latence incompressible par requête Selenium.

**Recommandation :**

Option A (court terme) — **Selenium Wire + serveur persistant** : remplacer le modèle spawn-par-requête par un micro-serveur Flask/FastAPI Python qui garde le driver Chrome en mémoire, exposé via HTTP local (`localhost:5001`). Laravel appelle `Http::post('http://127.0.0.1:5001/track', [...])` au lieu de lancer un process.

```python
# scraper/tracking_server.py (NOUVEAU)
from flask import Flask, request, jsonify
from itdida_tracker import OptimizedOrderTrackerSelenium

app = Flask(__name__)
tracker = OptimizedOrderTrackerSelenium(headless=True)
tracker._init_driver()  # Démarré une seule fois au boot

@app.route('/track/itdida', methods=['POST'])
def track():
    number = request.json['tracking_number']
    return jsonify(tracker.get_order_status(number))
```

Option B (moyen terme) — **Playwright HTTP API** : utiliser `playwright` en mode serveur avec `browser.new_context()` par requête (contexte léger, navigateur partagé).

---

#### P1.2 — Concurrence Selenium limitée à 1 global (ÉLEVÉ)

**Fichier :** `app/Jobs/RunSeleniumTrackingJob.php:43`

**Problème :** `WithoutOverlapping('selenium-tracker-global')` force toutes les requêtes Selenium à s'exécuter en série. Si 5 utilisateurs cliquent "voir tracking" simultanément, 4 attendent derrière 1.

```php
// Résultat : file d'attente sérialisée
return [(new WithoutOverlapping('selenium-tracker-global'))->releaseAfter(120)];
```

**Recommandation :** Avec le serveur persistant (P1.1), supprimer ce verrou global. Si Selenium reste en mode process, augmenter à 2–3 workers avec des verrous par numéro de tracking plutôt que globaux :

```php
// Verrou par numéro, pas global
return [(new WithoutOverlapping("selenium-{$this->trackingNumber}"))->releaseAfter(60)];
```

---

#### P1.3 — `user-data-dir` avec timestamp = fuite disque (MOYEN)

**Fichier :** `scraper/itdida_tracker.py:33`

**Problème :** Chaque exécution crée un nouveau profil Chrome temporaire dans `/tmp/chrome-user-data-itdida-{timestamp}`. Ces dossiers (50–200 MB chacun) ne sont jamais supprimés.

```python
# Fuite disque progressive
options.add_argument("--user-data-dir=/tmp/chrome-user-data-itdida-" + str(time.time()))
```

**Recommandation :** Utiliser un profil fixe par provider et ajouter une rotation :

```python
options.add_argument("--user-data-dir=/tmp/chrome-itdida")  # Fixe
```

Ou nettoyer dans le bloc `finally` :

```python
finally:
    tracker.close()
    import shutil, glob
    for d in glob.glob('/tmp/chrome-user-data-itdida-*'):
        shutil.rmtree(d, ignore_errors=True)
```

---

#### P1.4 — `$_SERVER` entier injecté dans l'environnement du processus (MOYEN)

**Fichier :** `app/Services/Tracking/AbstractSeleniumTrackingService.php:69`

**Problème :** `array_merge($_SERVER, $env)` passe l'intégralité du superglobal `$_SERVER` (headers HTTP, chemins, cookies, données de session) au processus Python. Cela inclut des informations potentiellement sensibles et augmente la taille de l'environnement.

```php
return array_merge($_SERVER, $env);  // Risque : $_SERVER contient les headers de la requête HTTP
```

**Recommandation :** N'injecter que les variables nécessaires :

```php
return $env;  // Seulement les variables explicitement définies dans getEnvironment()
```

---

### CATÉGORIE 2 — Cache et Logging (Impact ÉLEVÉ)

---

#### P2.1 — DB INSERT sur chaque cache HIT (ÉLEVÉ)

**Fichier :** `app/Services/Tracking/UnifiedTrackingService.php:58`

**Problème :** `logSearch()` est appelé même quand la donnée vient du cache. Sur un système avec 100 commandes et 50 utilisateurs qui rafraîchissent, cela génère des centaines d'inserts inutiles par heure.

```php
// track() — appelé même sur cache HIT
$cached = Cache::get($cacheKey);
if ($cached) {
    $this->logSearch($trackingNumber, $cached);  // DB INSERT inutile
    return $cached;
}
```

**Recommandation :** Logger les cache hits en mémoire/compteur, pas en DB à chaque fois. Ou ajouter un paramètre `$log = false` pour les cache hits :

```php
if ($cached) {
    $cached['source'] = 'cache';
    return $cached;  // Pas de log DB sur cache hit
}
```

Si l'historique des consultations est nécessaire, utiliser un compteur Redis : `Cache::increment("tracking_views:{$trackingNumber}")` et persister périodiquement.

---

#### P2.2 — Double résolution d'alias FSB (ÉLEVÉ)

**Fichier :** `app/Services/Tracking/UnifiedTrackingService.php:69` et `:299`

**Problème :** `resolveAlias()` est appelé deux fois pour chaque numéro FSB : une fois dans `track()` pour détecter le provider, et une deuxième fois dans `performTracking()` → `resolveAlias()`. Chaque appel exécute une requête DB (`SourcingOrder::resolveFsbNumberToOrderAndDestinationIndex()`).

```php
// track() — 1er appel
[$realNumber, $realCarrier, $isAlias, $error] = $this->resolveAlias($trackingNumber, $carrier);

// performTracking() — 2ème appel (depuis le même chemin)
[$realNumber, $realCarrier, $isAlias, $error] = $this->resolveAlias($trackingNumber, $carrier);
```

**Recommandation :** Passer le résultat résolu en paramètre ou mémoriser dans une propriété d'instance pour la durée de la requête :

```php
// Résoudre une seule fois, passer en paramètre
protected function performTracking(string $trackingNumber, ?string $carrier, array $resolvedAlias = []): array
```

---

#### P2.3 — Logging Info/Debug excessif en production (MOYEN)

**Fichier :** `app/Services/Tracking/UnifiedTrackingService.php` — multiples `Log::info()` avec emojis

**Problème :** Chaque requête de tracking génère 8–12 entrées de log au niveau `info`, même sur cache hit. Avec 200 commandes et un cron toutes les 4h, cela représente ~2400 lignes de log par cycle.

**Recommandation :** Réduire à `debug` pour les opérations routinières, garder `info` uniquement pour les transitions d'état significatives :

```php
// Cache HIT → debug (pas info)
Log::debug('[Tracking] Cache HIT', ['number' => $trackingNumber]);

// Changement de statut → info (significatif)
Log::info('[Tracking] Status updated', ['number' => $trackingNumber, 'status' => $status]);
```

---

#### P2.4 — Détection du statut pour le TTL fragile (FAIBLE)

**Fichier :** `app/Services/Tracking/UnifiedTrackingService.php:219`

**Problème :** Le TTL du cache est déterminé par `str_contains($status, 'livré')` — couplé à la locale. Si le statut est retourné en anglais alors qu'on cherche en français (ou inversement), le TTL optimal n'est pas appliqué.

**Recommandation :** S'appuyer sur le champ `current_status_key` (standardisé) plutôt que sur le texte traduit :

```php
// Utiliser une clé normalisée plutôt que du texte
$statusKey = $result['status_key'] ?? null;  // ex: 'delivered', 'in_transit'
$cacheTtl = $ttlByStatus[$statusKey] ?? $baseTtl;
```

---

### CATÉGORIE 3 — Cron / Batch Processing (Impact MOYEN)

---

#### P3.1 — Traitement séquentiel dans le cron (ÉLEVÉ)

**Fichier :** `app/Services/OrderStatus/AutoUpdateOrderStatusFromTracking.php:179`

**Problème :** `processBatch()` traite les commandes une par une dans une boucle `foreach`. Pour 100 commandes avec le provider Faster (API REST, ~300ms par appel), le batch complet prend **30 secondes** en série alors qu'il pourrait prendre **3 secondes** en parallèle.

```php
foreach ($orders as $order) {
    $updated = $this->updateOrderStatusFromTracking($order);  // Bloquant
}
```

**Recommandation :** Pour les providers API (Faster, 17Track), utiliser des requêtes concurrentes. Laravel 11 supporte `Http::pool()` :

```php
// Regrouper les numéros Faster et appeler l'API en batch
$fasterOrders = $orders->filter(fn($o) => $this->isFasterProvider($o));
$responses = Http::pool(fn($pool) =>
    $fasterOrders->map(fn($o) =>
        $pool->get('https://op-api.faster.ae/...', ['bookingNos' => $o->tracking_number])
    )->all()
);
```

Ou vérifier si l'API Faster accepte plusieurs numéros dans un seul appel (le paramètre s'appelle `bookingNos` — pluriel — ce qui suggère un support batch).

---

#### P3.2 — Chargement de toutes les commandes en mémoire (MOYEN)

**Fichier :** `app/Console/Commands/AutoUpdateOrderStatusesFromTracking.php:151`

**Problème :** `$query->get()` charge jusqu'à 100 commandes complètes en mémoire. Si les relations sont chargées ultérieurement avec `$order->load(...)`, on risque des N+1.

**Recommandation :** Utiliser `chunk()` ou `cursor()` :

```php
$query->chunk(20, function ($batch) {
    $this->autoUpdateService->processBatch($batch);
});
```

---

#### P3.3 — `last_tracking_check_at` non mis à jour en cas de skip (FAIBLE)

**Fichier :** `app/Services/OrderStatus/AutoUpdateOrderStatusFromTracking.php`

**Problème :** Quand une commande est "skippée" (statut inchangé), `last_tracking_check_at` n'est pas mis à jour. La commande sera donc retraitée au prochain cycle même si rien n'a changé.

**Recommandation :** Mettre à jour le timestamp même en cas de skip :

```php
// Toujours marquer la vérification même sans changement
$order->last_tracking_check_at = now();
$order->saveQuietly();  // Sans déclencher d'événements
```

---

### CATÉGORIE 4 — Intégration et Routing (Impact MOYEN)

---

#### P4.1 — SeventeenTrackService non intégré dans UnifiedTrackingService (ÉLEVÉ)

**Fichier :** `app/Services/SeventeenTrackService.php` — non utilisé par `UnifiedTrackingService`

**Problème :** `SeventeenTrackService` existe et est fonctionnel mais n'est pas injecté ni appelé par `UnifiedTrackingService`. Le `match()` dans `getService()` n'a pas de case pour `17track`. Le service est donc mort code.

**Recommandation :** Intégrer 17Track comme provider de fallback pour les numéros non reconnus :

```php
// UnifiedTrackingService::getService()
'17track' => $this->seventeenTrackService,
default => $this->seventeenTrackService,  // Fallback universel
```

Et ajouter la détection dans `detectProvider()` :

```php
// Numéros non reconnus → 17Track comme fallback
if (preg_match('/^[A-Z]{2}\d{9}[A-Z]{2}$/', $number)) {  // Format postal universel
    return '17track';
}
```

---

#### P4.2 — Rate limiting configuré mais non appliqué (MOYEN)

**Fichier :** `config/tracking.php:83` — `rate_limit => 10` jamais utilisé

**Problème :** La configuration `rate_limit` est définie mais aucun middleware ou vérification dans `UnifiedTrackingService` ne l'applique. Un utilisateur peut appeler le tracking en boucle sans limitation.

**Recommandation :** Ajouter un rate limiter dans `track()` :

```php
use Illuminate\Support\Facades\RateLimiter;

$key = "tracking-rate:{$trackingNumber}:" . (auth()->id() ?? request()->ip());
if (RateLimiter::tooManyAttempts($key, config('tracking.rate_limit', 10))) {
    return ['success' => false, 'error' => 'Trop de requêtes. Réessayez dans une minute.'];
}
RateLimiter::hit($key, 60);
```

---

#### P4.3 — Faster listé comme provider Selenium alors que c'est une API (BUG)

**Fichier :** `app/Services/Tracking/UnifiedTrackingService.php:81`

**Problème :** `$seleniumProviders = ['itdida', 'faster', 'choicexp', 'gcc', 'ups']` — Faster est listé comme Selenium, mais `FasterTrackingService` est purement API REST (pas de Selenium). Conséquence : les requêtes Faster sont mises en queue inutilement et bénéficient du délai de job asynchrone.

```php
// INCORRECT — Faster est une API REST, pas Selenium
$seleniumProviders = ['itdida', 'faster', 'choicexp', 'gcc', 'ups'];
```

**Recommandation :**

```php
$seleniumProviders = ['itdida', 'choicexp', 'ups'];  // Supprimer 'faster' et 'gcc'
```

---

### CATÉGORIE 5 — Observabilité et Résilience (Impact FAIBLE-MOYEN)

---

#### P5.1 — Pas d'endpoint de health check pour le système de tracking (MOYEN)

**Problème :** Aucun moyen de monitorer l'état du système de tracking (jobs en attente, taux d'erreur par provider, état du circuit breaker) sans aller dans les logs.

**Recommandation :** Ajouter une route admin `/admin/tracking/health` qui retourne :

```json
{
  "selenium_queue_depth": 3,
  "circuit_breakers_active": ["ITDIDA-1Z123"],
  "cache_hit_rate_last_hour": 0.72,
  "provider_error_rates": {
    "faster": 0.02,
    "itdida": 0.15
  }
}
```

---

#### P5.2 — Pas de retry sur les scripts Python (FAIBLE)

**Fichier :** `scraper/itdida_tracker.py:85`

**Problème :** Si Chrome crashe ou la page ne charge pas (timeout réseau), le script retourne immédiatement une erreur sans réessayer. Le circuit breaker comptera cet échec.

**Recommandation :** Ajouter 1 retry automatique avec délai minimal :

```python
def get_order_status(self, tracking_number: str, retry: int = 1) -> Dict:
    try:
        # ... logique actuelle
    except Exception as e:
        if retry > 0:
            time.sleep(2)
            return self.get_order_status(tracking_number, retry - 1)
        return {"success": False, "error": str(e)}
```

---

#### P5.3 — Timeout incohérent entre PHP et Python (FAIBLE)

**Fichier PHP :** `AbstractSeleniumTrackingService.php:81` → `timeout(120)`  
**Fichier Job :** `RunSeleniumTrackingJob.php:23` → `$timeout = 300`  
**Fichier Python :** `itdida_tracker.py:93` → `WebDriverWait(driver, 15)`

**Problème :** Le processus PHP attend 120s, le job attend 300s, mais le WebDriverWait Python ne dépasse pas 15s. En cas d'échec silencieux du script, PHP attend 120s pour rien.

**Recommandation :** Aligner les timeouts. La valeur PHP devrait être légèrement supérieure au timeout Python :

```php
// PHP Process timeout = WebDriverWait timeout + marge (ex: 25s)
->timeout(25)

// Job timeout = Process timeout × nombre de retries + marge
public $timeout = 60;
```

---

## Tableau de Priorisation

| ID | Problème | Impact | Effort | Priorité |
|----|----------|--------|--------|----------|
| P1.1 | Spawn Chrome par requête → serveur persistant | CRITIQUE | Élevé | 🔴 P0 |
| P4.3 | Faster classé comme Selenium (bug routing) | ÉLEVÉ | Très faible | 🔴 P0 |
| P2.2 | Double résolution FSB alias | ÉLEVÉ | Faible | 🟠 P1 |
| P2.1 | DB INSERT sur cache hit | ÉLEVÉ | Très faible | 🟠 P1 |
| P4.1 | 17Track non intégré (dead code) | ÉLEVÉ | Faible | 🟠 P1 |
| P1.2 | Concurrence Selenium = 1 global | ÉLEVÉ | Moyen | 🟠 P1 |
| P3.1 | Traitement séquentiel cron | MOYEN | Moyen | 🟡 P2 |
| P1.3 | Fuite disque user-data-dir | MOYEN | Très faible | 🟡 P2 |
| P4.2 | Rate limit non appliqué | MOYEN | Faible | 🟡 P2 |
| P1.4 | $_SERVER injecté dans env Python | MOYEN | Très faible | 🟡 P2 |
| P2.3 | Logging excessif en production | MOYEN | Faible | 🟡 P2 |
| P3.2 | get() vs chunk() sur commandes | MOYEN | Très faible | 🟡 P2 |
| P5.1 | Pas de health check endpoint | MOYEN | Moyen | 🟡 P2 |
| P2.4 | Détection TTL fragile | FAIBLE | Faible | 🟢 P3 |
| P3.3 | last_tracking_check_at non mis à jour | FAIBLE | Très faible | 🟢 P3 |
| P5.2 | Pas de retry Python | FAIBLE | Faible | 🟢 P3 |
| P5.3 | Timeouts incohérents | FAIBLE | Très faible | 🟢 P3 |

---

## Plan d'Exécution par Sprint

### Sprint 1 — Corrections immédiates (1–2 jours)

Ces changements sont à risque minimal et n'impactent pas l'architecture :

- [ ] **P4.3** — Retirer `faster` de `$seleniumProviders` dans `UnifiedTrackingService.php:81`
- [ ] **P2.1** — Supprimer `logSearch()` sur cache hit dans `track()`
- [ ] **P1.3** — Fixer le `user-data-dir` fixe dans les scrapers Python
- [ ] **P1.4** — Remplacer `array_merge($_SERVER, $env)` par `$env` seul
- [ ] **P3.3** — Ajouter `last_tracking_check_at = now()` même en cas de skip
- [ ] **P5.3** — Aligner les timeouts PHP Process / Job / Python

### Sprint 2 — Améliorations de logique (3–5 jours)

- [ ] **P2.2** — Refactoriser pour éviter le double `resolveAlias()` 
- [ ] **P4.2** — Ajouter `RateLimiter` dans `UnifiedTrackingService::track()`
- [ ] **P2.3** — Passer les logs routiniers de `info` à `debug`
- [ ] **P4.1** — Intégrer `SeventeenTrackService` dans `UnifiedTrackingService`
- [ ] **P3.2** — Passer `getEligibleOrders()` de `get()` à `chunk()`
- [ ] **P5.2** — Ajouter 1 retry dans les scripts Python

### Sprint 3 — Refactoring architecture (1–2 semaines)

- [ ] **P1.1** — Créer `scraper/tracking_server.py` (Flask) avec driver persistent
- [ ] **P1.2** — Adapter `RunSeleniumTrackingJob` pour appeler l'API Flask locale
- [ ] **P3.1** — Implémenter `Http::pool()` pour les providers API dans `processBatch()`
- [ ] **P5.1** — Créer endpoint `/admin/tracking/health`
- [ ] **P2.4** — Normaliser les clés de statut pour le TTL

---

## Gains Attendus

| Métrique | Avant | Après Sprint 1+2 | Après Sprint 3 |
|----------|-------|-----------------|----------------|
| Latence Selenium (p50) | 30–60s | 25–50s | 3–10s |
| Latence Faster (p50) | 300ms + délai job | 300ms direct | 300ms direct |
| DB inserts / heure (50 users) | ~3000 | ~1200 | ~800 |
| Throughput cron (100 commandes) | ~30s séquentiel | ~20s | ~5s concurrent |
| Occupation disque /tmp Chrome | Croissance infinie | Stable | Stable |

---

## Notes d'Implémentation

### Serveur Python Flask (P1.1) — Architecture cible

```
[Laravel App]
     │
     │ Http::post('http://127.0.0.1:5001/track/itdida')
     ▼
[tracking_server.py — Flask]
     │  ← Chrome instance unique, partagée
     │
     ├─ /track/itdida
     ├─ /track/choicexp
     ├─ /track/ups
     └─ /health
```

Le serveur Flask doit être géré comme un daemon système (via `supervisord` ou Windows Service). Il faut prévoir :
- Restart automatique si Chrome crashe (`subprocess.Popen` avec watch)
- Timeout de requête Flask indépendant (20s max par track)
- Queue interne si concurrent (threading.Lock par provider)

### Vérification de la compatibilité de l'API Faster pour batch (P3.1)

Avant d'implémenter `Http::pool()`, vérifier si l'API Faster accepte plusieurs `bookingNos` dans un seul appel. Le nom du paramètre (`bookingNos` = pluriel) suggère que oui. Si confirmé, un seul appel pour 100 commandes Faster remplace 100 appels séquentiels.

```php
// Test — appel batch Faster
Http::get('https://op-api.faster.ae/service/status-logs/listWithBooking', [
    'bookingNos' => implode(',', $trackingNumbers),  // ou array
    'isOpen' => 1,
]);
```
