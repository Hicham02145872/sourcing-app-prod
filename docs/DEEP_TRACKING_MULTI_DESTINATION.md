## Deep Tracking – Multi‑destination & Shipping Companies avec plusieurs transporteurs

### 1. Objectif

- **Multi‑destination** : chaque destination doit être trackée avec le **transporteur réel** lié à sa shipping company assignée et à son numéro de suivi.
- **Shipping company avec plusieurs transporteurs (ex : GCC / UPS / Air Choice)** : le système doit **déduire automatiquement** le bon provider à partir du **numéro de tracking** (et éventuellement du champ `carrier`) et retourner le statut correspondant.

Ce document décrit :
- La logique actuelle dans le code.
- Comment elle s’applique au cas « multi‑destination + company avec plusieurs transporteurs ».

---

### 2. Où se trouve la logique dans le code

- **Admin – Page Order / Deep Tracking**
  - `app/Livewire/Admin/SourcingOrderWorkflow.php`
  - `resources/views/livewire/admin/sourcing-order-workflow.blade.php`
    - Bouton **Deep Tracking** (single & multi‑destination).

- **Service de tracking unifié**
  - `app/Services/Tracking/UnifiedTrackingService.php`
    - `track()` / `refreshTracking()`
    - `performTracking()`
    - `resolveAlias()` (FSB → vrai numéro + shipping company / destination)
    - `detectProvider()`
    - `getService()`

- **Config**
  - `config/tracking.php`
    - `carrier_labels` (labels pour les dropdowns quand une shipping company a des « child carriers »).

---

### 3. Comportement actuel – Deep Tracking côté admin

#### 3.1. Single destination

Dans `SourcingOrderWorkflow::fetchTrackingStatus()` :

- Si la commande **n’a pas** plusieurs destinations :
  - On utilise les champs globaux :
    - `$this->tracking_number`
    - `$this->tracking_carrier` (facultatif, sinon fallback sur `shippingCompany->name`)
  - Appel :
    - `UnifiedTrackingService::refreshTracking($tracking_number, $carrier)`
  - Le résultat est stocké dans :
    - `$this->deepTrackingResult`
  - La vue affiche la carte **Single‑destination deep tracking result** avec :
    - `provider`
    - dernier statut
    - dernière localisation
    - historique des événements.

#### 3.2. Multi‑destination

Dans `SourcingOrderWorkflow::fetchMultiDestinationTracking()` :

- Si `hasMultipleDestinations()` :
  - Pour chaque destination `dest` :
    - On lit :
      - `$trackingNumber = destinationTrackings[dest->id]['tracking_number']`
      - `$carrier = destinationTrackings[dest->id]['tracking_carrier']` (texte libre)
    - Si pas de numéro : on retourne une erreur spécifique pour cette destination.
    - Sinon : appel
      - `UnifiedTrackingService::refreshTracking($trackingNumber, $carrier ?: null)`
    - On ajoute au résultat par destination :
      - `success`, `error`, `events`, `current_status[_fr]`, `provider`, etc.
      - `dest_label` (nom du pays + info quantité).
  - Les résultats sont stockés dans :
    - `$this->deepTrackingResults[dest_id]`
  - La vue affiche un bloc par destination, avec :
    - statut, provider détecté, bouton **Details** (events).

**Important** : en multi‑destination, la logique est **déjà par destination**.  
Ce qui reste à bien maîtriser est **quel provider est choisi** en fonction de la shipping company et du tracking number.

---

### 4. Logique de sélection du provider dans `UnifiedTrackingService`

#### 4.1. Alias FSB (côté client + interne)

- Si le tracking commence par `FSB` :
  - `resolveAlias()` appelle `SourcingOrder::resolveFsbNumberToOrderAndDestinationIndex($fsb)`.
  - Pour une commande **multi‑destination** :
    - On récupère la destination correspondante via l’index dans `quotation->sourcingRequest->destinations`.
    - On obtient :
      - `realNumber = $order->getTrackingNumberForDestination($destId)`
      - `shippingCompany = $order->getShippingCompanyForDestination($destId)`
      - `realCarrier = $shippingCompany?->name`
  - Si aucun vrai tracking pour cette destination :
    - soit on renvoie un tracking **virtuel** (via `VirtualTrackingStatusService`) si configuré,
    - soit une erreur « tracking non encore assigné ».
  - Si tout est OK :
    - `resolveAlias()` retourne `(realNumber, realCarrier, isAlias = true, error = null)`.

Ensuite, `performTracking()` appelle :

- `detectProvider($realNumber, $realCarrier)` pour déterminer le provider réel.

#### 4.2. `detectProvider()` – règle générale

Dans `UnifiedTrackingService::detectProvider(string $trackingNumber, ?string $carrier = null)` :

1. **Si un `carrier` texte est fourni** (ex: saisie admin ou nom de shipping company) :
   - On normalise : `$carrierLower = strtolower($carrier)`.
   - Règles :
     - contient `itdida` ou `ydl` → provider = `itdida`
     - contient `choice` → provider = `choicexp`
     - contient `faster` ou `gcc` → provider = `faster`
     - contient `ups` → provider = `ups`

2. **Sinon / en complément : auto‑détection par pattern du numéro** :
   - On normalise : `$number = strtoupper($trackingNumber)`.
   - Règles :
     - commence par `1Z` → provider = `ups`
     - commence par `DBC` → provider = `choicexp`
     - commence par `ME` → provider = `faster` (GCC / Faster)

3. **Fallback** :
   - Si rien ne matche :
     - `return $carrier ?: 'unknown';`

#### 4.3. Mapping provider → service concret

Dans `getService()` :

- `itdida` → `ItdidaTrackingService`
- `faster` ou `gcc` → `FasterTrackingService`
- `choicexp` → `ChoiceXPTrackingService`
- `ups` → `UPSTrackingService`

Puis, dans `performTracking()` :

- `getTrackingInfo($realNumber)` est appelé sur ce service.
- Le `provider` retourné est « brandé » pour l’UI :
  - `faster`, `itdida`, `choicexp`, `gcc` → affichés comme `FSB`
  - `ups` → affiché comme `UPS`

---

### 5. Cas spécifique : Shipping company avec plusieurs transporteurs (GCC / UPS / Air Choice)

#### 5.1. Côté configuration / UI

- Une shipping company (par ex. `YNPS`) peut avoir plusieurs transporteurs réels :
  - `gcc` (GCC / Faster)
  - `ups` (UPS)
  - `choicexp` ou équivalent pour Air Choice
- Dans `config/tracking.php`, on a des labels :
  - `'gcc' => 'GCC / Faster'`
  - `'ups' => 'UPS'`
  - `'choicexp' => 'Choice XP'`
- Dans le workflow admin (single destination) :
  - Si la shipping company a des « child carriers », le champ `tracking_carrier` devient un **dropdown** basé sur `getCarrierOptionsWithLabels()` (qui s’appuie sur cette config).

#### 5.2. Comportement souhaité pour Deep Tracking

1. **Multi‑destination :**
   - Chaque destination a :
     - un `tracking_number`
     - un `tracking_carrier` (texte ou clé du transporteur, ex: `gcc`, `ups`, `choicexp`).
   - `fetchMultiDestinationTracking()` appelle :
     - `refreshTracking(tracking_number, tracking_carrier)`
   - `detectProvider()` :
     - Si `tracking_carrier` contient `gcc`, `faster` → provider = `faster` (GCC / Faster).
     - Si `tracking_carrier` contient `ups` → provider = `ups`.
     - Si `tracking_carrier` contient `choice` → provider = `choicexp`.
     - Sinon, bascule sur les patterns du **numéro** (`ME`, `1Z`, `DBC`, etc.).
   - Résultat :
     - Pour une destination assignée à une shipping company qui regroupe **GCC / UPS / Air Choice**, le système choisit le **bon provider réel** en fonction du **numéro** (et/ou du champ carrier), et retourne le bon statut.

2. **Single destination :**
   - Même logique, mais en utilisant les champs globaux `tracking_number` + `tracking_carrier`.
   - Si la shipping company a plusieurs transporteurs :
     - L’admin choisit un carrier explicite dans le dropdown (ex: `GCC / Faster`, `UPS`, `Choice XP`).
     - `detectProvider()` route vers le service correct.
   - Si aucun carrier explicite n’est choisi :
     - la détection se fait par **pattern du numéro** (`ME` → Faster/GCC, `1Z` → UPS, etc.).

---

### 6. Résumé comportement attendu (en français simple)

- **En cas de multi‑destination** :
  - La page Orders / Deep Tracking **tracke chaque destination séparément** avec son propre numéro de suivi.
  - Le provider est choisi automatiquement par `UnifiedTrackingService` via :
    - le champ `tracking_carrier` (si renseigné),
    - sinon, par la forme du numéro (ME / 1Z / DBC…).

- **Si une destination utilise une shipping company qui a 3 transporteurs (GCC / UPS / Air Choice)** :
  - L’admin peut indiquer ou laisser deviner le transporteur réel :
    - soit via le champ/déroulant `tracking_carrier` (gcc / ups / choice…),
    - soit uniquement via le format du numéro (`ME...` → GCC/Faster, `1Z...` → UPS, etc.).
  - `UnifiedTrackingService` retourne alors le **statut correspondant au vrai transporteur** identifié parmi ces 3.

Ce document peut servir de référence pour :
- vérifier que l’implémentation actuelle correspond bien au besoin métier,
- ou guider des ajustements futurs (par exemple forcer un mapping supplémentaire par shipping company si nécessaire).

