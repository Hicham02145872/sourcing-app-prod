# Analyse des Statuts de Tracking par Provider
## Données réelles pour l'Auto-Update des Statuts

> **📋 Note** : Voir `docs/TRACKING_STATUS_MAPPING_REAL_DATA.md` pour l'analyse des payloads JSON réels de production.

---

## 📊 Format de Données Retourné par Chaque Provider

### Structure Commune des Événements

Tous les providers retournent des événements avec cette structure :
```json
{
  "success": true,
  "tracking_number": "...",
  "events": [
    {
      "date": "2026-02-20 10:30:00",
      "status": "Raw status text from provider",
      "status_en": "Translated English status",
      "status_fr": "Statut traduit en français",
      "location": "Location name",
      "location_fr": "Nom de lieu en français"
    }
  ],
  "current_status": "Latest status",
  "provider": "ProviderName"
}
```

---

## 🔍 Analyse par Provider

### 1. Faster.ae

**Format de réponse** : API JSON  
**Endpoint** : `https://op-api.faster.ae/service/status-logs/listWithBooking`  
**Champ de statut** : `status`, `statusName`, `statusDetails`  
**Champ de localisation** : `location`, `statusLocation`

**Statuts typiques retournés** (basés sur les données réelles) :
- Statuts trouvés dans les payloads réels :
  - `Delivered` → Livré
  - `Arrived` → Arrivé (à Dubaï)
  - `Customs` → Dédouanement
  - `Departed` → Départ
  - `In-Hub` → En hub
  - `Booked` → Réservé

**Mappings recommandés** (basés sur données réelles) :
```php
'arrival_uae' => [
    'keywords_en' => ['arrived'],
    'keywords_fr' => ['arrivée', 'arrivé'],
    'locations' => ['dubai', 'dubaï', 'hub de dubai', 'dubai ras al khor'],
],
'customs_clearance_uae' => [
    'keywords_cn' => ['清关完成', '清关', '通关'],
    'keywords_en' => ['customs', 'clearance', 'cleared'],
    'keywords_fr' => ['déclaration', 'douane', 'dédouanement'],
    'locations' => ['dubai', 'uae', 'emirates'],
],
'in_transit_uae' => [
    'keywords_cn' => ['已发货', '出发'],
    'keywords_en' => ['departed', 'transit', 'left', 'shipped'],
    'keywords_fr' => ['départ', 'en transit'],
    'locations' => ['dubai', 'uae'],
    'exclude_locations' => ['china', 'chine'],
],
'out_for_delivery' => [
    'keywords_cn' => ['派送中', '配送中'],
    'keywords_en' => ['out for delivery', 'on the way'],
    'keywords_fr' => ['en livraison', 'en cours de livraison'],
],
'delivered' => [
    'keywords_en' => ['delivered'],
    'keywords_fr' => ['livré', 'livraison effectuée'],
],
```

---

### 2. ITDIDA (YDL)

**Format de réponse** : Scraping Selenium (HTML tables)  
**URL** : `https://ydl.itdida.com/query.xhtml`  
**Champ de statut** : Extrait des cellules de table HTML  
**Champ de localisation** : `location_raw`

**Statuts typiques retournés** (basés sur les données réelles) :
- Statuts bruts (chinois) trouvés :
  - `已签收` → "Signed for" → Livré
  - `清关完成` → "Customs clearance completed" → Dédouanement terminé
  - `航班到港：抵达迪拜` → "Flight Arrival: Arrival in Dubai" → Arrivée à Dubaï
  - `离开香港` → "Leave Hong Kong" → Départ de Hong Kong
  - `到货扫描` → "Arrival scan" → Scan d'arrivée
  - `离开扫描` → "Leave Scan" → Scan de départ
  - `收货扫描` → "Receipt scanning" → Scan de réception
  - `已预报` → "Forecast" → Prévision

**Mappings recommandés** (basés sur données réelles) :

---

### 3. ChoiceXP

**Format de réponse** : Scraping Selenium (Timeline blocks)  
**URL** : `https://air.choicexp.com/air/webpage/com/jeecg/milestone/milestone.jsp`  
**Champ de statut** : `status` (texte complet de l'événement)  
**Champ de localisation** : `location` (extrait avec regex " at " ou " in ")

**Statuts typiques retournés** (basés sur les données réelles) :
- Format : Phrases complètes en anglais
- Statuts trouvés dans les payloads réels :
  - `Shipment had picked up and sign-off` → Livré
  - `The goods have completed entering the destination` → Arrivée destination
  - `Shipment had finished the customs clearance` → Dédouanement terminé
  - `Shipment had arrived destination DUBAI` → Arrivée à Dubaï
  - `Shipment planned for flight` → Planifié pour vol
  - `Shipment had leaved from warehouse` → Départ entrepôt
  - `Shipment had entryed into warehouse` → Entrée entrepôt
  - `Create the Order` → Commande créée

**Mappings recommandés** (basés sur données réelles) :
```php
'arrival_uae' => [
    'keywords_en' => ['arrived destination dubai', 'arrived destination'],
    'pattern' => '/arrived\s+destination\s+(dubai|dubai)/i',
],
'customs_clearance_uae' => [
    'keywords_en' => ['finished the customs clearance', 'customs clearance'],
    'pattern' => '/finished\s+the\s+customs\s+clearance/i',
],
'in_transit_uae' => [
    'keywords_en' => ['leaved from warehouse'],
    'locations' => ['dubai', 'dubaï'],
    'exclude_locations' => ['china', 'chine'],
],
'arrival_destination_country' => [
    'keywords_en' => ['completed entering the destination', 'entered destination'],
    'exclude_locations' => ['dubai', 'dubaï'],
],
'out_for_delivery' => [
    'keywords_en' => ['out for delivery', 'on the way'],
    'keywords_fr' => ['en livraison'],
],
'delivered' => [
    'keywords_en' => ['picked up and sign-off', 'sign-off'],
],
```

---

### 4. UPS

**Format de réponse** : Scraping Selenium (Milestones)  
**URL** : `https://www.ups.com/track?tracknum={number}`  
**Champ de statut** : `status` (parsed from milestone text)  
**Champ de localisation** : `location` (extrait avec regex)

**Statuts typiques retournés** :
- Format : "Status City, Country MM/DD/YYYY, H:MM A.M."
- Exemples :
  - "Delivered POME, IT 01/14/2026, 11:03 A.M."
  - "On the Way Roma, Italy 01/14/2026, 5:46 A.M."
  - "Out For Delivery Paris, FR 01/15/2026, 9:00 A.M."
  - "Order Processed Shenzhen, CN 01/01/2026, 10:00 A.M."

**Mappings recommandés** :
```php
'arrival_uae' => [
    'keywords_en' => ['arrived', 'arrival'],
    'locations' => ['dubai', 'uae', 'emirates', 'united arab emirates'],
    'pattern' => '/arrived.*(dubai|uae|emirates)/i',
],
'customs_clearance_uae' => [
    'keywords_en' => ['customs', 'clearance', 'cleared'],
    'locations' => ['dubai', 'uae', 'emirates'],
],
'in_transit_uae' => [
    'keywords_en' => ['on the way', 'in transit', 'departed'],
    'locations' => ['dubai', 'uae'],
    'exclude_locations' => ['china', 'chine'],
],
'arrival_destination_country' => [
    'keywords_en' => ['arrived', 'arrival'],
    'exclude_locations' => ['dubai', 'uae', 'emirates', 'china', 'chine'],
],
'out_for_delivery' => [
    'keywords_en' => ['out for delivery'],
],
'delivered' => [
    'keywords_en' => ['delivered'],
],
```

---

### 5. 17Track (API)

**Format de réponse** : API JSON  
**Champ de statut** : `z` (contenu de l'événement)  
**Champ de localisation** : `c` (localisation)

**Statuts typiques retournés** :
- Codes bruts (à traduire) :
  - "3000" → "In Transit"
  - "3010" → "Arrived at Destination"
  - "3020" → "Customs Clearance"
  - "3030" → "Out for Delivery"
  - "3040" → "Delivered"

**Mappings recommandés** :
```php
'arrival_uae' => [
    'status_codes' => ['3010'], // Arrived at Destination
    'keywords_en' => ['arrived', 'arrivée'],
    'locations' => ['dubai', 'uae', 'emirates'],
],
'customs_clearance_uae' => [
    'status_codes' => ['3020'], // Customs Clearance
    'keywords_en' => ['customs', 'clearance', 'déclaration'],
    'locations' => ['dubai', 'uae'],
],
'in_transit_uae' => [
    'status_codes' => ['3000'], // In Transit
    'keywords_en' => ['transit', 'departed'],
    'locations' => ['dubai', 'uae'],
    'exclude_locations' => ['china'],
],
'out_for_delivery' => [
    'status_codes' => ['3030'], // Out for Delivery
    'keywords_en' => ['out for delivery', 'en livraison'],
],
'delivered' => [
    'status_codes' => ['3040'], // Delivered
    'keywords_en' => ['delivered', 'livré'],
],
```

---

## 🎯 Mappings Unifiés Recommandés

### Configuration Complète (`config/tracking_status_mapping.php`)

```php
return [
    // Mappings génériques (tous providers)
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
            'keywords_en' => ['customs clearance', 'cleared customs', 'customs cleared', 'customs processing'],
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
        
        'arrival_destination_country' => [
            'keywords_en' => ['arrived', 'arrival', 'reached'],
            'keywords_fr' => ['arrivée', 'arrivé'],
            'keywords_cn' => ['已到达', '到达'],
            'exclude_locations' => ['dubai', 'uae', 'emirates', 'china', 'chine'],
            'priority' => 1,
        ],
        
        'customs_clearance_destination_country' => [
            'keywords_en' => ['customs clearance', 'cleared customs', 'customs cleared'],
            'keywords_fr' => ['déclaration en douane', 'dédouanement terminé'],
            'keywords_cn' => ['清关完成', '清关'],
            'exclude_locations' => ['dubai', 'uae', 'emirates'],
            'priority' => 1,
        ],
        
        'out_for_delivery' => [
            'keywords_en' => ['out for delivery', 'on the way', 'out for delivery'],
            'keywords_fr' => ['en livraison', 'en cours de livraison', 'en route'],
            'keywords_cn' => ['派送中', '配送中'],
            'priority' => 1,
        ],
        
        'delivered' => [
            'keywords_en' => ['delivered', 'completed', 'received', 'signed'],
            'keywords_fr' => ['livré', 'livraison effectuée', 'réceptionné', 'signé'],
            'keywords_cn' => ['已签收', '已送达', '签收'],
            'priority' => 1,
        ],
    ],
    
    // Mappings spécifiques par provider
    'provider_specific_mappings' => [
        'Faster' => [
            // Utilise les patterns génériques + keywords_cn
        ],
        
        'ITDIDA' => [
            // Utilise les patterns génériques + keywords_cn
        ],
        
        'ChoiceXP' => [
            'patterns' => [
                'arrival_uae' => '/arrived\s+(at|in)\s+(dubai|uae|emirates)/i',
                'customs_clearance_uae' => '/customs.*(dubai|uae|emirates)/i',
                'in_transit_uae' => '/departed\s+from\s+(dubai|uae)/i',
            ],
        ],
        
        'UPS' => [
            'patterns' => [
                'arrival_uae' => '/arrived.*(dubai|uae|emirates)/i',
            ],
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
    
    // Priorités de détection
    'detection_priority' => [
        'location_based' => 3, // Highest priority
        'keyword_based' => 2,
        'pattern_based' => 1,
        'code_based' => 1,
    ],
];
```

---

## 🧪 Commandes de Test

### Analyser les statuts d'un provider spécifique
```bash
php artisan tracking:analyze-statuses --provider=faster --tracking-number=ME49508327
php artisan tracking:analyze-statuses --provider=itdida --tracking-number=ME49508327
php artisan tracking:analyze-statuses --provider=choicexp --tracking-number=DBC123456
php artisan tracking:analyze-statuses --provider=ups --tracking-number=1Z14V4W16890506495
```

### Analyser tous les providers
```bash
php artisan tracking:analyze-statuses
```

### Sauvegarder les résultats pour analyse
```bash
php artisan tracking:analyze-statuses --save-results
```

Les résultats seront sauvegardés dans `storage/app/tracking_status_analysis_YYYY-MM-DD_HH-ii-ss.json`

---

## 📝 Prochaines Étapes

1. **Exécuter les tests** avec de vrais numéros de tracking depuis la base de données
2. **Analyser les résultats JSON** pour identifier les patterns réels
3. **Ajuster les mappings** dans ce document et dans `config/tracking_status_mapping.php`
4. **Tester le mapper** avec des données réelles
5. **Affiner les patterns** basés sur les résultats réels

---

**Note** : Ce document a été mis à jour avec les données réelles de production. Voir `docs/TRACKING_STATUS_MAPPING_REAL_DATA.md` pour l'analyse détaillée des payloads JSON réels.
