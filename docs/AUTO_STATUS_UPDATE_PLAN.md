# Plan Détaillé : Automatisation des Statuts basée sur les Événements de Tracking
## Approche 2 : Event-Driven Automation (Chine-Dubaï)

> **📚 Documentation Complémentaire** :
> - `docs/CRON_JOB_SETUP_WALKTHROUGH.md` : Guide complet pour configurer le cron job sur VPS
> - `docs/TRACKING_STATUS_MAPPING_ANALYSIS.md` : Analyse des statuts par provider
> - `docs/TRACKING_STATUS_MAPPING_REAL_DATA.md` : Analyse des payloads JSON réels
> - `docs/AUTO_UPDATE_FLOW_DIAGRAM.md` : **Diagramme complet du flux** (récupération → mapping → mise à jour)

---

## 📋 Vue d'ensemble

Ce plan décrit l'implémentation d'un système qui met automatiquement à jour les statuts des commandes (`SourcingOrder`) en analysant les événements de tracking réels retournés par les APIs de transporteurs.

### Objectif
Automatiser les transitions de statut dans la chaîne Chine-Dubaï en se basant sur les événements réels de tracking plutôt que sur des délais fixes.

### ⚠️ Important : Distinction Tracking Virtuel vs Réel

**Tracking Virtuel (FSB)** :
- Déjà implémenté et fonctionnel
- Gère automatiquement : `shipment_preparing` (immédiatement) et `in_transit_china` (après 24h)
- Fonctionne **SANS** tracking number réel
- Utilisé pour donner une visibilité immédiate au client

**Tracking Réel (Auto-Update)** :
- Ce système ne s'active **QUE** lorsqu'un tracking number réel est assigné
- Condition : `real_tracking_assigned_at IS NOT NULL`
- Analyse les événements réels des transporteurs
- Met à jour les statuts à partir de `arrival_uae` et au-delà

---

## 🎯 Chaîne de Statuts Chine-Dubaï

```
paid → shipment_preparing → in_transit_china → arrival_uae → 
customs_clearance_uae → in_transit_uae → arrival_destination_country → 
customs_clearance_destination_country → out_for_delivery → delivered → order_completed
```

---

## ✅ Vérification des Statuts à Mettre à Jour Automatiquement

### Tableau Récapitulatif

| Statut | Géré par | Auto-Update | Transition depuis | Transition vers | Priorité |
|--------|----------|-------------|-------------------|-----------------|----------|
| `paid` | ❌ Manuel | ❌ Non | `pending_payment` | `shipment_preparing` | - |
| `shipment_preparing` | ✅ **Tracking Virtuel FSB** | ⚠️ Optionnel* | `paid` | `in_transit_china` | Basse |
| `in_transit_china` | ✅ **Tracking Virtuel FSB** | ⚠️ Optionnel* | `shipment_preparing` | `arrival_uae` | Basse |
| `arrival_uae` | ❌ | ✅ **OUI** | `in_transit_china` | `customs_clearance_uae` | **HAUTE** |
| `customs_clearance_uae` | ❌ | ✅ **OUI** | `arrival_uae` | `in_transit_uae` | **HAUTE** |
| `in_transit_uae` | ❌ | ✅ **OUI** | `customs_clearance_uae` | `arrival_destination_country` | **HAUTE** |
| `arrival_destination_country` | ❌ | ✅ **OUI** | `in_transit_uae` | `customs_clearance_destination_country` | **HAUTE** |
| `customs_clearance_destination_country` | ❌ | ✅ **OUI** | `arrival_destination_country` | `out_for_delivery` | **HAUTE** |
| `out_for_delivery` | ❌ | ✅ **OUI** | `customs_clearance_destination_country` | `delivered` | **HAUTE** |
| `delivered` | ❌ | ✅ **OUI** | `out_for_delivery` | `order_completed` | **HAUTE** |
| `order_completed` | ❌ Manuel | ❌ Non | `delivered` | `refunded` | - |

**Légende** :
- ✅ **OUI** : Statut mis à jour automatiquement par le système de tracking réel
- ✅ **Tracking Virtuel FSB** : Déjà géré par le système FSB (sans tracking réel)
- ⚠️ Optionnel* : Peut être détecté si tracking réel disponible très tôt, mais déjà géré par FSB
- ❌ : Non géré automatiquement

### Statuts Exclus de l'Auto-Update

| Statut | Raison |
|--------|--------|
| `on_hold` | Mise en pause intentionnelle par l'admin |
| `shipment_canceled` | Commande annulée, ne doit pas être mise à jour |
| `shipment_delayed` | Peut être mis à jour vers un statut normal si détecté |
| `delivery_failed` | Nécessite intervention manuelle |
| `shipment_returned` | Nécessite intervention manuelle |
| `refunded`, `waiting_for_refund`, `refund_approved`, `refund_rejected` | Gérés par le système de remboursement |

### Transitions Autorisées (selon `canTransitionTo()`)

**Chaîne principale** :
```
shipment_preparing → in_transit_china → arrival_uae → 
customs_clearance_uae → in_transit_uae → arrival_destination_country → 
customs_clearance_destination_country → out_for_delivery → delivered
```

**Transitions alternatives autorisées** :
- Tous les statuts peuvent aller vers `shipment_delayed` (sauf `delivered`)
- Tous les statuts peuvent aller vers `shipment_canceled` (sauf `delivered`, `order_completed`)
- `shipment_delayed` peut revenir vers n'importe quel statut normal
- `delivered` → `order_completed` (manuel)

### Critères de Sélection pour l'Auto-Update

**Commandes à inclure** :
```sql
WHERE status IN (
    'shipment_preparing',      -- Optionnel, déjà géré par FSB
    'in_transit_china',        -- Optionnel, déjà géré par FSB
    'arrival_uae',             -- ✅ PRIORITÉ
    'customs_clearance_uae',   -- ✅ PRIORITÉ
    'in_transit_uae',          -- ✅ PRIORITÉ
    'arrival_destination_country', -- ✅ PRIORITÉ
    'customs_clearance_destination_country', -- ✅ PRIORITÉ
    'out_for_delivery'         -- ✅ PRIORITÉ
)
AND tracking_number IS NOT NULL
AND real_tracking_assigned_at IS NOT NULL  -- ⚠️ CRITIQUE
AND status NOT IN ('on_hold', 'shipment_canceled')
```

**Statuts prioritaires pour l'auto-update** :
1. ✅ `arrival_uae` - Arrivée à Dubaï
2. ✅ `customs_clearance_uae` - Dédouanement EAU
3. ✅ `in_transit_uae` - Transit depuis Dubaï
4. ✅ `arrival_destination_country` - Arrivée pays de destination
5. ✅ `customs_clearance_destination_country` - Dédouanement destination
6. ✅ `out_for_delivery` - En livraison
7. ✅ `delivered` - Livré

---

## 🏗️ Architecture du Système

### 1. Service de Mapping : `TrackingStatusMapper`

**Rôle** : Analyser les événements de tracking et les mapper vers nos statuts internes.

**Fichier** : `app/Services/Tracking/TrackingStatusMapper.php`

**Responsabilités** :
- Analyser les événements de tracking (status, location, date)
- Identifier les mots-clés et patterns dans les statuts
- Mapper vers nos statuts internes
- Gérer les différents providers (Faster, 17Track, UPS, ITDIDA, etc.)

**Méthodes principales** :
```php
public function mapTrackingEventsToStatus(array $trackingResult, SourcingOrder $order): ?string
public function analyzeEvent(array $event, string $currentOrderStatus): ?string
public function detectStatusFromLocation(string $location): ?string
public function detectStatusFromStatusText(string $statusText, string $language = 'en'): ?string
```

**Logique de mapping** :

⚠️ **Note importante** : `shipment_preparing` et `in_transit_china` sont déjà gérés par le tracking virtuel FSB. 
Ce mapper se concentre sur les statuts à partir de `arrival_uae` :

**Mappings détaillés par statut** :

- **arrival_uae** :
  - Keywords EN: "Arrived", "Arrival", "Reached"
  - Keywords FR: "Arrivée", "Arrivé"
  - Keywords CN: "已到达", "到达"
  - Locations: "Dubai", "UAE", "Emirates", "United Arab Emirates", "Dubaï"
  - Exclude: "China", "Chine"

- **customs_clearance_uae** :
  - Keywords EN: "Customs clearance", "Cleared customs", "Customs cleared", "Customs processing"
  - Keywords FR: "Déclaration en douane", "Dédouanement", "Dédouanement terminé"
  - Keywords CN: "清关完成", "清关", "通关"
  - Locations: "Dubai", "UAE", "Emirates"

- **in_transit_uae** :
  - Keywords EN: "Departed from", "Left", "In transit", "Shipped from"
  - Keywords FR: "Départ de", "Départ", "En transit depuis"
  - Keywords CN: "已发货", "出发"
  - Locations: "Dubai", "UAE", "Emirates"
  - Exclude: "China", "Chine"

- **arrival_destination_country** :
  - Keywords EN: "Arrived", "Arrival", "Reached"
  - Keywords FR: "Arrivée", "Arrivé"
  - Keywords CN: "已到达", "到达"
  - Exclude: "Dubai", "UAE", "Emirates", "China", "Chine"

- **customs_clearance_destination_country** :
  - Keywords EN: "Customs clearance", "Cleared customs", "Customs cleared"
  - Keywords FR: "Déclaration en douane", "Dédouanement terminé"
  - Keywords CN: "清关完成", "清关"
  - Exclude: "Dubai", "UAE", "Emirates"

- **out_for_delivery** :
  - Keywords EN: "Out for delivery", "On the way", "Out for delivery"
  - Keywords FR: "En livraison", "En cours de livraison", "En route"
  - Keywords CN: "派送中", "配送中"

- **delivered** :
  - Keywords EN: "Delivered", "Completed", "Received", "Signed"
  - Keywords FR: "Livré", "Livraison effectuée", "Réceptionné", "Signé"
  - Keywords CN: "已签收", "已送达", "签收"

**Statuts optionnels** (pour détection précoce si nécessaire) :
- **shipment_preparing** : "Prepared", "Packed", "Ready", "Collected", "已准备", "已打包" (si tracking réel disponible très tôt)
- **in_transit_china** : "Departed", "Shipped", "Left origin", "In transit", "已发货", "已发出" (si tracking réel disponible très tôt)

**Référence** : Voir `docs/TRACKING_STATUS_MAPPING_ANALYSIS.md` pour l'analyse détaillée des statuts par provider.

---

### 2. Service d'Auto-Update : `AutoUpdateOrderStatusFromTracking`

**Rôle** : Orchestrer la mise à jour automatique des statuts.

**Fichier** : `app/Services/OrderStatus/AutoUpdateOrderStatusFromTracking.php`

**Responsabilités** :
- Récupérer les données de tracking pour une commande
- Analyser les événements via `TrackingStatusMapper`
- Vérifier si une transition de statut est nécessaire
- Mettre à jour le statut si valide
- Déclencher les événements et notifications

**Méthodes principales** :
```php
public function updateOrderStatusFromTracking(SourcingOrder $order): bool
public function shouldUpdateStatus(SourcingOrder $order, string $detectedStatus): bool
public function updateStatus(SourcingOrder $order, string $newStatus): void
```

**Logique de validation** :
- Vérifier que la transition est autorisée (`canTransitionTo`)
- Vérifier que le statut détecté est plus avancé que le statut actuel
- Ignorer si la commande est en `on_hold` ou `shipment_canceled`
- Ignorer si le statut détecté est identique au statut actuel

---

### 3. Commande Artisan : `AutoUpdateOrderStatusesFromTracking`

**Rôle** : Tâche planifiée qui vérifie et met à jour les statuts automatiquement.

**Fichier** : `app/Console/Commands/AutoUpdateOrderStatusesFromTracking.php`

**Signature** : `php artisan orders:auto-update-statuses`

**Fréquence recommandée** : Toutes les 4-6 heures (via cron)

**Logique** :
1. Récupérer toutes les commandes avec :
   - Statut dans la chaîne Chine-Dubaï (`shipment_preparing` à `out_for_delivery`)
   - `tracking_number` non null
   - `real_tracking_assigned_at` non null ⚠️ **OBLIGATOIRE** : Seulement les commandes avec tracking réel assigné
   - Pas en `on_hold` ou `shipment_canceled`
   
   **Exclusion explicite** :
   - Les commandes avec seulement `fsb_tracking_created_at` (sans `real_tracking_assigned_at`) 
     sont gérées par le système de tracking virtuel et ne doivent PAS être incluses

2. Pour chaque commande :
   - Appeler `UnifiedTrackingService` pour récupérer les données de tracking
   - Utiliser `TrackingStatusMapper` pour détecter le statut
   - Utiliser `AutoUpdateOrderStatusFromTracking` pour mettre à jour si nécessaire

3. Logger les résultats :
   - Nombre de commandes vérifiées
   - Nombre de statuts mis à jour
   - Erreurs éventuelles

**Configuration du Cron Job** :
> **📖 Voir le guide complet** : `docs/CRON_JOB_SETUP_WALKTHROUGH.md`

**Méthode recommandée** : Utiliser Laravel Scheduler (configuré dans `app/Console/Kernel.php`) :
```bash
# Dans crontab, ajouter cette ligne (Laravel Scheduler s'occupe du reste) :
* * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1
```

**Alternative** : Exécution directe (moins flexible) :
```bash
# Toutes les 4 heures
0 */4 * * * cd /path-to-project && php artisan orders:auto-update-statuses >> /dev/null 2>&1
```

---

### 4. Configuration : Mappings de Statuts

**Fichier** : `config/tracking_status_mapping.php`

**📋 Analyse des Statuts Réels** :
Avant d'implémenter, exécuter la commande d'analyse pour voir les statuts réels :
```bash
php artisan tracking:analyze-statuses --save-results
```

Cette commande teste tous les providers et génère un fichier JSON avec les statuts réels retournés.
Voir `docs/TRACKING_STATUS_MAPPING_ANALYSIS.md` pour l'analyse détaillée basée sur les scripts de scraping.

**Structure** : Voir `docs/TRACKING_STATUS_MAPPING_ANALYSIS.md` pour la configuration complète.

**Structure simplifiée** :
```php
return [
    'status_patterns' => [
        'arrival_uae' => [
            'keywords_en' => ['arrived', 'arrival', 'reached'],
            'keywords_fr' => ['arrivée', 'arrivé'],
            'keywords_cn' => ['已到达', '到达'],
            'locations' => ['dubai', 'uae', 'emirates', 'united arab emirates', 'dubaï'],
            'exclude_locations' => ['china', 'chine'],
            'priority' => 1,
        ],
        'customs_clearance_uae' => [
            'keywords_en' => ['customs clearance', 'cleared customs', 'customs cleared'],
            'keywords_fr' => ['déclaration en douane', 'dédouanement', 'dédouanement terminé'],
            'keywords_cn' => ['清关完成', '清关', '通关'],
            'locations' => ['dubai', 'uae', 'emirates'],
            'priority' => 1,
        ],
        'in_transit_uae' => [
            'keywords_en' => ['departed from', 'left', 'in transit', 'shipped from'],
            'keywords_fr' => ['départ de', 'départ', 'en transit depuis'],
            'keywords_cn' => ['已发货', '出发'],
            'locations' => ['dubai', 'uae', 'emirates'],
            'exclude_locations' => ['china', 'chine'],
            'priority' => 1,
        ],
        // ... autres statuts (voir TRACKING_STATUS_MAPPING_ANALYSIS.md)
    ],
    
    'provider_specific_mappings' => [
        'Faster' => [
            // Patterns spécifiques Faster
        ],
        'ITDIDA' => [
            // Patterns spécifiques ITDIDA (généralement chinois)
        ],
        'ChoiceXP' => [
            // Patterns spécifiques ChoiceXP (format "Status at Location")
        ],
        'UPS' => [
            // Patterns spécifiques UPS (format "Status City, Country")
        ],
        '17Track' => [
            'status_codes' => [
                '3000' => 'in_transit_uae',
                '3010' => 'arrival_destination_country',
                '3020' => 'customs_clearance_destination_country',
                '3030' => 'out_for_delivery',
                '3040' => 'delivered',
            ],
        ],
    ],
];
```

**Note** : La configuration complète avec tous les mappings détaillés est disponible dans `docs/TRACKING_STATUS_MAPPING_ANALYSIS.md`.

---

## 🔄 Flux de Traitement

### Étape 1 : Récupération des Commandes
```
Commande Artisan → Query SourcingOrder
- status IN (shipment_preparing, in_transit_china, arrival_uae, customs_clearance_uae, in_transit_uae, arrival_destination_country, customs_clearance_destination_country, out_for_delivery)
- tracking_number IS NOT NULL
- real_tracking_assigned_at IS NOT NULL  ⚠️ CRITIQUE : Seulement les commandes avec tracking réel
- status NOT IN (on_hold, shipment_canceled)

Note : Les commandes avec seulement fsb_tracking_created_at (sans real_tracking_assigned_at) 
sont gérées par le système de tracking virtuel et NE DOIVENT PAS être incluses ici.
```

### Étape 2 : Récupération des Données de Tracking
```
Pour chaque commande :
→ UnifiedTrackingService::track(tracking_number)
→ Retourne : { success, events[], current_status, provider }
```

### Étape 3 : Analyse des Événements
```
TrackingStatusMapper::mapTrackingEventsToStatus()
→ Analyse events[] (derniers événements en premier)
→ Détecte patterns dans status_en, status_fr, location
→ Retourne : statut détecté ou null
```

### Étape 4 : Validation et Mise à Jour
```
AutoUpdateOrderStatusFromTracking::updateOrderStatusFromTracking()
→ Vérifie si transition autorisée
→ Vérifie si statut détecté > statut actuel
→ Met à jour le statut si valide
→ Déclenche SourcingOrderStatusChanged event
```

### Étape 5 : Notifications et Logs
```
Event SourcingOrderStatusChanged déclenché
→ Listeners existants :
  - SendSourcingOrderStatusUpdatedNotification
  - InitializeFsbTracking (si applicable)
  - SyncOrderToSheet
→ Notification envoyée au client
```

---

## 📊 Exemples de Mapping

### Exemple 1 : Détection "Arrival UAE"
```json
{
  "events": [
    {
      "status_en": "Arrived in Dubai",
      "status_fr": "Arrivée à Dubaï",
      "location": "Dubai, UAE",
      "date": "2026-02-20 10:30:00"
    }
  ]
}
```
**Mapping** : `arrival_uae` ✅

### Exemple 2 : Détection "In Transit China"
```json
{
  "events": [
    {
      "status_en": "Departed from Shenzhen",
      "status_fr": "Départ de Shenzhen",
      "location": "Shenzhen, China",
      "date": "2026-02-18 14:00:00"
    }
  ]
}
```
**Mapping** : `in_transit_china` ✅

### Exemple 3 : Détection "Customs Clearance UAE"
```json
{
  "events": [
    {
      "status_en": "Customs clearance completed",
      "status_fr": "Dédouanement terminé",
      "location": "Dubai Airport, UAE",
      "date": "2026-02-21 08:00:00"
    }
  ]
}
```
**Mapping** : `customs_clearance_uae` ✅

---

## 🛡️ Gestion des Cas Spéciaux

### 1. Pas de tracking réel assigné (`real_tracking_assigned_at` est null)
- **Action** : Ignorer complètement la commande
- **Raison** : Ces commandes utilisent le tracking virtuel FSB qui gère déjà `shipment_preparing` et `in_transit_china`
- **Vérification** : `WHERE real_tracking_assigned_at IS NOT NULL` dans la requête

### 2. Statut "on_hold"
- **Action** : Ignorer la commande, ne pas mettre à jour automatiquement
- **Raison** : L'admin a mis la commande en pause intentionnellement

### 3. Statut "shipment_delayed"
- **Action** : Permettre la mise à jour vers un statut plus avancé si détecté
- **Raison** : Le retard peut être résolu, on doit pouvoir reprendre la progression

### 4. Pas de données de tracking disponibles
- **Action** : Logger l'erreur, passer à la commande suivante
- **Raison** : Ne pas bloquer le processus pour une commande problématique

### 5. Statut détecté identique au statut actuel
- **Action** : Ignorer, pas de mise à jour nécessaire
- **Raison** : Éviter les événements inutiles

### 6. Statut détecté en arrière (régression)
- **Action** : Ignorer, ne pas permettre les régressions automatiques
- **Raison** : Seuls les admins peuvent corriger les erreurs

### 7. Transition non autorisée
- **Action** : Logger un avertissement, ignorer
- **Raison** : Respecter les règles de transition définies dans `canTransitionTo()`

---

## 📝 Logging et Monitoring

### Logs à enregistrer

1. **Début de la commande** :
   - Nombre de commandes à vérifier
   - Timestamp

2. **Pour chaque commande** :
   - Order ID
   - Tracking number
   - Statut actuel
   - Statut détecté
   - Action prise (updated / skipped / error)

3. **Résumé final** :
   - Total vérifié
   - Total mis à jour
   - Total ignoré (avec raisons)
   - Erreurs

### Niveaux de log
- **INFO** : Mises à jour réussies, commandes ignorées (raisons normales)
- **WARNING** : Transitions non autorisées, données manquantes
- **ERROR** : Erreurs de tracking, exceptions

---

## 🔧 Configuration et Paramètres

### Variables d'environnement
```env
# Activer/désactiver l'auto-update
AUTO_UPDATE_ORDER_STATUSES_ENABLED=true

# Fréquence de vérification (en heures)
AUTO_UPDATE_CHECK_INTERVAL_HOURS=4

# Nombre maximum de commandes à traiter par exécution
AUTO_UPDATE_MAX_ORDERS_PER_RUN=100

# Délai minimum entre deux vérifications pour la même commande (en minutes)
AUTO_UPDATE_MIN_INTERVAL_MINUTES=60
```

### Paramètres dans `config/tracking_status_mapping.php`
- Patterns de keywords
- Patterns de locations
- Mappings spécifiques par provider
- Priorités de détection

---

## 🧪 Tests et Validation

### Tests unitaires à créer

1. **TrackingStatusMapperTest** :
   - Test de mapping de chaque statut
   - Test avec différents providers
   - Test avec différents formats de données
   - Test de cas limites (données manquantes, formats invalides)

2. **AutoUpdateOrderStatusFromTrackingTest** :
   - Test de validation des transitions
   - Test de mise à jour réussie
   - Test d'ignorance des cas spéciaux
   - Test de déclenchement des événements

3. **AutoUpdateOrderStatusesFromTrackingCommandTest** :
   - Test de sélection des commandes
   - Test de traitement en batch
   - Test de gestion des erreurs
   - Test de logging

### Tests d'intégration
- Test du flux complet avec données réelles de tracking
- Test avec différents providers
- Test de performance avec un grand nombre de commandes

---

## 📅 Plan d'Implémentation

### Phase 0 : Analyse des Statuts Réels (Avant implémentation)
1. ✅ Créer `app/Console/Commands/AnalyzeTrackingStatuses.php`
2. ✅ Créer `docs/TRACKING_STATUS_MAPPING_ANALYSIS.md` avec l'analyse basée sur les scripts
3. ⏳ Exécuter `php artisan tracking:analyze-statuses` avec de vrais numéros de tracking
4. ⏳ Analyser les résultats et affiner les mappings dans `TRACKING_STATUS_MAPPING_ANALYSIS.md`

### Phase 1 : Infrastructure de Base (Semaine 1)
1. Créer `config/tracking_status_mapping.php` basé sur l'analyse
2. Créer `TrackingStatusMapper` service
3. Tests unitaires pour le mapper

### Phase 2 : Service d'Auto-Update (Semaine 1-2)
1. ✅ Créer `AutoUpdateOrderStatusFromTracking` service
2. ✅ Intégrer avec `TrackingStatusMapper`
3. ✅ Tests unitaires pour le service

### Phase 3 : Commande Artisan (Semaine 2)
1. ✅ Créer `AutoUpdateOrderStatusesFromTracking` command
2. ✅ Intégrer avec les services
3. ✅ Ajouter logging complet
4. ✅ Tests de la commande

### Phase 4 : Tests et Ajustements (Semaine 2-3)
1. ✅ Tests d'intégration
2. ✅ Tests avec données réelles
3. ✅ Ajustements des mappings basés sur les résultats
4. ✅ Optimisation des performances

### Phase 5 : Déploiement (Semaine 3)
1. ✅ Configuration du cron job
2. ✅ Monitoring initial
3. ✅ Documentation pour l'équipe
4. ✅ Formation si nécessaire

---

## 🚀 Améliorations Futures

1. **Machine Learning** : Entraîner un modèle pour améliorer la précision des mappings
2. **Webhooks** : Écouter les webhooks des transporteurs pour des mises à jour en temps réel
3. **Dashboard** : Interface admin pour voir les mises à jour automatiques et ajuster les mappings
4. **Notifications Admin** : Alerter les admins en cas de détections ambiguës ou d'erreurs
5. **Historique** : Stocker l'historique des détections pour analyse et amélioration

---

## ⚠️ Points d'Attention

1. **⚠️ CRITIQUE : Distinction Tracking Virtuel vs Réel**
   - Ne JAMAIS traiter les commandes sans `real_tracking_assigned_at`
   - Le tracking virtuel FSB gère déjà `shipment_preparing` et `in_transit_china`
   - L'auto-update ne doit s'activer qu'après l'assignation d'un tracking réel par l'admin

2. **Performance** : Limiter le nombre de commandes traitées par exécution pour éviter les timeouts
3. **Rate Limiting** : Respecter les limites des APIs de tracking
4. **Cache** : Utiliser le cache existant pour éviter les appels API inutiles
5. **Rollback** : Prévoir un mécanisme pour annuler les mises à jour automatiques si nécessaire
6. **Validation Admin** : Permettre aux admins de désactiver l'auto-update pour certaines commandes
7. **Cohérence** : S'assurer que les statuts mis à jour automatiquement sont cohérents avec le tracking virtuel

---

## 📚 Fichiers à Créer/Modifier

### Nouveaux fichiers
- `app/Services/Tracking/TrackingStatusMapper.php`
- `app/Services/OrderStatus/AutoUpdateOrderStatusFromTracking.php`
- `app/Console/Commands/AutoUpdateOrderStatusesFromTracking.php`
- `app/Console/Commands/AnalyzeTrackingStatuses.php` ✅ (Créé pour analyser les statuts réels)
- `config/tracking_status_mapping.php`
- `docs/TRACKING_STATUS_MAPPING_ANALYSIS.md` ✅ (Créé avec l'analyse des statuts par provider)
- `tests/Unit/Services/Tracking/TrackingStatusMapperTest.php`
- `tests/Unit/Services/OrderStatus/AutoUpdateOrderStatusFromTrackingTest.php`
- `tests/Feature/Commands/AutoUpdateOrderStatusesFromTrackingCommandTest.php`

### Fichiers à modifier
- `app/Providers/EventServiceProvider.php` (si besoin de nouveaux listeners)
- `.env.example` (ajouter les nouvelles variables)
- `app/Console/Kernel.php` (ajouter la commande au schedule si nécessaire)

---

## ✅ Checklist de Validation

- [ ] ⚠️ **CRITIQUE** : Seules les commandes avec `real_tracking_assigned_at IS NOT NULL` sont traitées
- [ ] ⚠️ **CRITIQUE** : Les commandes avec seulement `fsb_tracking_created_at` sont exclues (gérées par tracking virtuel)
- [ ] Le mapper détecte correctement les statuts à partir de `arrival_uae`
- [ ] Les transitions respectent les règles de `canTransitionTo()`
- [ ] Les commandes en `on_hold` sont ignorées
- [ ] Les notifications sont envoyées aux clients lors des mises à jour
- [ ] Les logs sont complets et exploitables
- [ ] Les performances sont acceptables (pas de timeout)
- [ ] Les erreurs sont gérées gracieusement
- [ ] La configuration est flexible et facile à ajuster
- [ ] Les tests couvrent tous les cas d'usage
- [ ] La documentation est à jour
- [ ] Test de non-interférence avec le système de tracking virtuel FSB

---

**Date de création** : 2026-02-19  
**Version** : 1.0  
**Auteur** : Système d'automatisation des statuts
