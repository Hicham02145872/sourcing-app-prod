# Flux Complet : Auto-Update des Statuts depuis les Événements de Tracking
## Diagramme et Explication du Processus

---

## 🔄 Flux Complet du Système

```
┌─────────────────────────────────────────────────────────────────┐
│                    CRON JOB (Toutes les 4h)                      │
│  * * * * * php artisan schedule:run                              │
└───────────────────────┬─────────────────────────────────────────┘
                        │
                        ▼
┌─────────────────────────────────────────────────────────────────┐
│         Commande Artisan: orders:auto-update-statuses            │
│  app/Console/Commands/AutoUpdateOrderStatusesFromTracking.php   │
└───────────────────────┬─────────────────────────────────────────┘
                        │
                        ▼
┌─────────────────────────────────────────────────────────────────┐
│  1. Récupération des Commandes Éligibles                         │
│  - tracking_number IS NOT NULL                                   │
│  - real_tracking_assigned_at IS NOT NULL ⚠️                     │
│  - status IN (shipment_preparing, in_transit_china,              │
│              arrival_uae, customs_clearance_uae, ...)            │
│  - status NOT IN (on_hold, shipment_canceled)                    │
└───────────────────────┬─────────────────────────────────────────┘
                        │
                        ▼
┌─────────────────────────────────────────────────────────────────┐
│  2. Pour Chaque Commande                                         │
│  AutoUpdateOrderStatusFromTracking::updateOrderStatusFromTracking│
└───────────────────────┬─────────────────────────────────────────┘
                        │
                        ▼
┌─────────────────────────────────────────────────────────────────┐
│  3. Récupération des Données de Tracking                         │
│  UnifiedTrackingService::track(tracking_number)                 │
│                                                                   │
│  ┌─────────────────────────────────────────────────────────┐   │
│  │ Appelle le provider approprié :                         │   │
│  │ - FasterTrackingService                                 │   │
│  │ - ItdidaTrackingService                                 │   │
│  │ - ChoiceXPTrackingService                                │   │
│  │ - UPSTrackingService                                     │   │
│  └─────────────────────────────────────────────────────────┘   │
│                                                                   │
│  Retourne :                                                      │
│  {                                                               │
│    "success": true,                                              │
│    "events": [                                                   │
│      {                                                           │
│        "date": "2026-02-16 22:11:42",                            │
│        "status": "Delivered",                                    │
│        "status_en": "Delivered",                                 │
│        "status_fr": "Delivered",                                 │
│        "location": "DUBAÏ"                                       │
│      },                                                          │
│      ...                                                         │
│    ],                                                            │
│    "provider": "FSB"                                             │
│  }                                                               │
└───────────────────────┬─────────────────────────────────────────┘
                        │
                        ▼
┌─────────────────────────────────────────────────────────────────┐
│  4. Mapping des Événements vers Statut                           │
│  TrackingStatusMapper::mapTrackingEventsToStatus()              │
│                                                                   │
│  Pour chaque événement (du plus récent au plus ancien) :        │
│                                                                   │
│  ┌─────────────────────────────────────────────────────────┐   │
│  │ a) Vérifier mappings spécifiques au provider            │   │
│  │    (Faster, ITDIDA, ChoiceXP, UPS, 17Track)            │   │
│  └─────────────────────────────────────────────────────────┘   │
│                                                                   │
│  ┌─────────────────────────────────────────────────────────┐   │
│  │ b) Analyser les keywords dans status_en, status_fr,      │   │
│  │    status (raw), keywords_cn                             │   │
│  │                                                           │   │
│  │    Exemple :                                             │   │
│  │    "Delivered" → keywords_en: ['delivered']              │   │
│  │    → Détecte: 'delivered'                                │   │
│  └─────────────────────────────────────────────────────────┘   │
│                                                                   │
│  ┌─────────────────────────────────────────────────────────┐   │
│  │ c) Vérifier les locations                                │   │
│  │                                                           │   │
│  │    Exemple :                                             │   │
│  │    "DUBAÏ" → locations: ['dubai', 'dubaï']              │   │
│  │    → Match pour 'arrival_uae'                           │   │
│  └─────────────────────────────────────────────────────────┘   │
│                                                                   │
│  ┌─────────────────────────────────────────────────────────┐   │
│  │ d) Vérifier les exclusions                              │   │
│  │                                                           │   │
│  │    Exemple :                                             │   │
│  │    Location "China" → exclude_locations: ['china']       │   │
│  │    → Ne matche PAS pour 'arrival_uae'                    │   │
│  └─────────────────────────────────────────────────────────┘   │
│                                                                   │
│  ┌─────────────────────────────────────────────────────────┐   │
│  │ e) Vérifier les patterns regex (si spécifiés)           │   │
│  │                                                           │   │
│  │    Exemple :                                             │   │
│  │    "Shipment had arrived destination DUBAI"             │   │
│  │    → Pattern: /arrived\s+destination\s+(dubai)/i        │   │
│  │    → Match pour 'arrival_uae'                           │   │
│  └─────────────────────────────────────────────────────────┘   │
│                                                                   │
│  ┌─────────────────────────────────────────────────────────┐   │
│  │ f) Vérifier la progression (pas de régression)          │   │
│  │                                                           │   │
│  │    Statut actuel: 'arrival_uae' (ordre 5)                │   │
│  │    Statut détecté: 'delivered' (ordre 11)                │   │
│  │    → 11 >= 5 → Progression valide ✅                     │   │
│  └─────────────────────────────────────────────────────────┘   │
│                                                                   │
│  Retourne : 'delivered' (ou null si aucun match)                 │
└───────────────────────┬─────────────────────────────────────────┘
                        │
                        ▼
┌─────────────────────────────────────────────────────────────────┐
│  5. Validation et Mise à Jour                                   │
│  AutoUpdateOrderStatusFromTracking::updateOrderStatusFromTracking│
│                                                                   │
│  ┌─────────────────────────────────────────────────────────┐   │
│  │ a) Vérifier si statut détecté ≠ statut actuel            │   │
│  │    Si identique → Skip                                   │   │
│  └─────────────────────────────────────────────────────────┘   │
│                                                                   │
│  ┌─────────────────────────────────────────────────────────┐   │
│  │ b) Vérifier transition autorisée                         │   │
│  │    order->canTransitionTo('delivered')                   │   │
│  │    → Vérifie les règles de transition                    │   │
│  └─────────────────────────────────────────────────────────┘   │
│                                                                   │
│  ┌─────────────────────────────────────────────────────────┐   │
│  │ c) Mettre à jour le statut                               │   │
│  │    order->status = 'delivered'                           │   │
│  │    order->save()                                          │   │
│  └─────────────────────────────────────────────────────────┘   │
│                                                                   │
│  ┌─────────────────────────────────────────────────────────┐   │
│  │ d) Déclencher l'événement                                │   │
│  │    event(new SourcingOrderStatusChanged($order))         │   │
│  │                                                           │   │
│  │    → Déclenche les listeners :                           │   │
│  │      - SendSourcingOrderStatusUpdatedNotification        │   │
│  │      - SyncOrderToSheet                                  │   │
│  │      - Autres listeners configurés                       │   │
│  └─────────────────────────────────────────────────────────┘   │
└───────────────────────┬─────────────────────────────────────────┘
                        │
                        ▼
┌─────────────────────────────────────────────────────────────────┐
│  6. Logging et Statistiques                                      │
│  - Nombre de commandes traitées                                  │
│  - Nombre de statuts mis à jour                                  │
│  - Nombre d'erreurs                                              │
│  - Logs détaillés dans storage/logs/auto-update-statuses.log    │
└─────────────────────────────────────────────────────────────────┘
```

---

## 📋 Exemple Concret : De l'Événement au Changement de Statut

### Scénario : Commande avec Tracking Faster

**Commande initiale** :
- ID: 59
- Status: `arrival_uae`
- Tracking Number: `ME06635307`
- Provider: Faster

**Étape 1 : Cron Job s'exécute**
```bash
* * * * * php artisan schedule:run
→ Déclenche orders:auto-update-statuses (toutes les 4h)
```

**Étape 2 : Récupération des événements**
```php
UnifiedTrackingService::track('ME06635307', 'faster')
→ Retourne :
{
  "success": true,
  "events": [
    {
      "date": "2026-02-16 22:11:42",
      "status": "Delivered",
      "status_en": "Delivered",
      "status_fr": "Delivered",
      "location": "DUBAÏ"
    },
    {
      "date": "2026-02-15 19:37:21",
      "status": "Arrived",
      "status_en": "Arrived",
      "location": "Hub de Dubaï Ras al Khor"
    }
  ],
  "provider": "FSB"
}
```

**Étape 3 : Mapping**
```php
TrackingStatusMapper::mapTrackingEventsToStatus($trackingResult, $order)

// Analyse du premier événement :
Event 1: "Delivered" + location "DUBAÏ"
  → keywords_en: ['delivered'] ✅ Match
  → Pattern: 'delivered' → Statut détecté: 'delivered'
  → Vérification progression: 'delivered' (11) >= 'arrival_uae' (5) ✅
  → Retourne: 'delivered'
```

**Étape 4 : Validation**
```php
// Statut détecté: 'delivered'
// Statut actuel: 'arrival_uae'
// Différent ? Oui ✅

// Transition autorisée ?
order->canTransitionTo('delivered')
→ Vérifie: 'arrival_uae' peut aller vers 'delivered' ?
→ Non directement, mais via la chaîne : arrival_uae → customs_clearance_uae → ... → delivered
→ Pour l'auto-update, on permet les sauts si détecté par tracking ✅
```

**Étape 5 : Mise à jour**
```php
$order->status = 'delivered';
$order->save();

event(new SourcingOrderStatusChanged($order));
→ Notification envoyée au client
→ Sync vers Google Sheets (si configuré)
```

**Résultat** :
- ✅ Statut mis à jour : `arrival_uae` → `delivered`
- ✅ Client notifié
- ✅ Logs enregistrés

---

## 🔍 Détails du Mapping

### Configuration Utilisée (`config/tracking_status_mapping.php`)

**Pour "Delivered"** :
```php
'delivered' => [
    'keywords_en' => ['delivered', 'signed for', 'picked up and sign-off'],
    'keywords_fr' => ['livré', 'signé pour'],
    'keywords_cn' => ['已签收', '签收'],
    'priority' => 1,
]
```

**Pour "Arrival UAE"** :
```php
'arrival_uae' => [
    'keywords_en' => ['arrived', 'arrival', 'flight arrival'],
    'keywords_fr' => ['arrivée', 'arrivé'],
    'keywords_cn' => ['航班到港：抵达迪拜', '抵达迪拜'],
    'locations' => ['dubai', 'dubaï', 'dxb'],
    'exclude_locations' => ['china', 'chine'],
    'priority' => 1,
]
```

---

## ✅ Checklist de Fonctionnement

- [x] **Cron Job** : Exécute `schedule:run` toutes les minutes
- [x] **Scheduler Laravel** : Exécute `orders:auto-update-statuses` toutes les 4h
- [x] **Récupération** : Récupère les commandes avec `real_tracking_assigned_at IS NOT NULL`
- [x] **Tracking** : Appelle `UnifiedTrackingService` pour récupérer les événements
- [x] **Mapping** : Utilise `TrackingStatusMapper` pour mapper événements → statut
- [x] **Validation** : Vérifie `canTransitionTo()` avant mise à jour
- [x] **Mise à jour** : Change le statut de la commande
- [x] **Événement** : Déclenche `SourcingOrderStatusChanged`
- [x] **Notifications** : Envoie notification au client (via listener)
- [x] **Logs** : Enregistre tout dans `storage/logs/auto-update-statuses.log`

---

## 🎯 Résumé

**OUI**, le système inclut bien :

1. ✅ **Récupération des statuts depuis les événements** via cron job
2. ✅ **Mapping** des statuts récupérés vers les statuts de commande du codebase
3. ✅ **Changement automatique** du statut de la commande selon le statut récupéré

Le flux complet est opérationnel et prêt à être utilisé !
