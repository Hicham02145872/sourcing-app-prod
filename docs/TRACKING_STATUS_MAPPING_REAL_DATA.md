# Analyse des Statuts Réels - Données de Production
## Payloads JSON réels des Shipping Companies

---

## 📊 Payload 1 : Faster.ae (FSB)

**Provider** : Faster (affiché comme "FSB")  
**Tracking Number** : ME06635307  
**Format** : API JSON

### Statuts trouvés :
- `Delivered` → Livré
- `Arrived` → Arrivé
- `Customs` → Dédouanement
- `Departed` → Départ
- `In-Hub` → En hub
- `Booked` → Réservé

### Localisations trouvées :
- `DUBAÏ` / `DUBAI`
- `Hub de Dubaï Ras al Khor`
- `Chine Hub de Shenzhen`

### Mapping recommandé :
```php
'arrival_uae' => [
    'keywords_en' => ['arrived'],
    'locations' => ['dubai', 'dubaï', 'dubai ras al khor', 'hub de dubai'],
],
'customs_clearance_uae' => [
    'keywords_en' => ['customs'],
    'locations' => ['dubai', 'dubaï'],
],
'in_transit_uae' => [
    'keywords_en' => ['departed'],
    'locations' => ['dubai', 'dubaï'],
    'exclude_locations' => ['china', 'chine', 'shenzhen'],
],
'delivered' => [
    'keywords_en' => ['delivered'],
],
```

---

## 📊 Payload 2 : ITDIDA (YDL)

**Provider** : ITDIDA (affiché comme "FSB")  
**Tracking Number** : K0121112B  
**Format** : Scraping Selenium

### Statuts bruts (chinois) :
- `已签收` → "Signed for" → Livré
- `清关完成` → "Customs clearance completed" → Dédouanement terminé
- `航班到港：抵达迪拜` → "Flight Arrival: Arrival in Dubai" → Arrivée à Dubaï
- `离开香港` → "Leave Hong Kong" → Départ de Hong Kong
- `到货扫描` → "Arrival scan" → Scan d'arrivée
- `空运预报` → "air freight forecast" → Prévision fret aérien
- `离开扫描` → "Leave Scan" → Scan de départ
- `收货扫描` → "Receipt scanning" → Scan de réception
- `已预报` → "Forecast" → Prévision

### Localisations trouvées :
- `DXB` (Dubaï)
- `HKG` (Hong Kong)
- `Groupe des opérations` / `运营组`
- `Service client` / `客服部`

### Mapping recommandé :
```php
'arrival_uae' => [
    'keywords_cn' => ['航班到港：抵达迪拜', '抵达迪拜', '抵达'],
    'keywords_en' => ['flight arrival', 'arrival in dubai', 'arrived'],
    'keywords_fr' => ['arrivée', 'arrivée à dubai'],
    'locations' => ['dxb', 'dubai', 'dubaï', 'dubai'],
],
'customs_clearance_uae' => [
    'keywords_cn' => ['清关完成', '清关'],
    'keywords_en' => ['customs clearance completed', 'customs clearance'],
    'keywords_fr' => ['dédouanement terminé', 'déclaration en douane'],
    'locations' => ['dxb', 'dubai', 'dubaï'],
],
'in_transit_uae' => [
    'keywords_cn' => ['离开香港'],
    'keywords_en' => ['leave hong kong', 'departed'],
    'keywords_fr' => ['départ de hong kong', 'départ'],
    'locations' => ['hkg'],
    'exclude_locations' => ['china', 'chine'],
],
'delivered' => [
    'keywords_cn' => ['已签收', '签收'],
    'keywords_en' => ['signed for', 'delivered'],
    'keywords_fr' => ['signé pour', 'livré'],
],
```

---

## 📊 Payload 3 : ChoiceXP

**Provider** : ChoiceXP (affiché comme "FSB")  
**Tracking Number** : FSB000051  
**Format** : Scraping Selenium (Timeline)

### Statuts trouvés :
- `Shipment had picked up and sign-off` → Livré
- `The goods have completed entering the destination` → Arrivée destination
- `Shipment had finished the customs clearance` → Dédouanement terminé
- `Shipment had arrived destination DUBAI` → Arrivée à Dubaï
- `Shipment planned for flight` → Planifié pour vol
- `Shipment had leaved from warehouse` → Départ entrepôt
- `Shipment had entryed into warehouse` → Entrée entrepôt
- `Create the Order` → Commande créée

### Mapping recommandé :
```php
'arrival_uae' => [
    'keywords_en' => ['arrived destination dubai', 'arrived destination', 'arrived dubai'],
    'pattern' => '/arrived\s+destination\s+(dubai|dubai)/i',
],
'customs_clearance_uae' => [
    'keywords_en' => ['finished the customs clearance', 'customs clearance', 'cleared customs'],
    'pattern' => '/finished\s+the\s+customs\s+clearance/i',
],
'arrival_destination_country' => [
    'keywords_en' => ['completed entering the destination', 'entered destination'],
    'exclude_locations' => ['dubai', 'dubaï'],
],
'delivered' => [
    'keywords_en' => ['picked up and sign-off', 'sign-off', 'picked up'],
],
```

---

## 🎯 Mappings Unifiés Basés sur les Données Réelles

### Configuration Complète (`config/tracking_status_mapping.php`)

```php
return [
    'status_patterns' => [
        'arrival_uae' => [
            // Faster
            'keywords_en' => ['arrived'],
            'keywords_fr' => ['arrivée', 'arrivé'],
            // ITDIDA
            'keywords_cn' => ['航班到港：抵达迪拜', '抵达迪拜', '抵达'],
            'keywords_en' => array_merge(['arrived'], ['flight arrival', 'arrival in dubai']),
            // ChoiceXP
            'keywords_en' => array_merge(['arrived'], ['arrived destination dubai', 'arrived destination']),
            'locations' => ['dubai', 'dubaï', 'dxb', 'hub de dubai', 'dubai ras al khor'],
            'exclude_locations' => ['china', 'chine', 'shenzhen', 'hong kong', 'hkg'],
            'priority' => 1,
        ],
        
        'customs_clearance_uae' => [
            // Faster
            'keywords_en' => ['customs'],
            // ITDIDA
            'keywords_cn' => ['清关完成', '清关'],
            'keywords_en' => array_merge(['customs'], ['customs clearance completed', 'customs clearance']),
            'keywords_fr' => ['déclaration en douane', 'dédouanement', 'dédouanement terminé'],
            // ChoiceXP
            'keywords_en' => array_merge(['customs'], ['finished the customs clearance', 'customs clearance']),
            'locations' => ['dubai', 'dubaï', 'dxb'],
            'priority' => 1,
        ],
        
        'in_transit_uae' => [
            // Faster
            'keywords_en' => ['departed'],
            // ITDIDA
            'keywords_cn' => ['离开香港'],
            'keywords_en' => array_merge(['departed'], ['leave hong kong']),
            'keywords_fr' => ['départ', 'départ de hong kong'],
            // ChoiceXP
            'keywords_en' => array_merge(['departed'], ['leaved from warehouse']),
            'locations' => ['dubai', 'dubaï', 'dxb'],
            'exclude_locations' => ['china', 'chine', 'shenzhen', 'hong kong', 'hkg'],
            'priority' => 1,
        ],
        
        'arrival_destination_country' => [
            // ChoiceXP
            'keywords_en' => ['completed entering the destination', 'entered destination', 'arrived destination'],
            'exclude_locations' => ['dubai', 'dubaï', 'dxb', 'china', 'chine'],
            'priority' => 1,
        ],
        
        'customs_clearance_destination_country' => [
            // ChoiceXP (si applicable)
            'keywords_en' => ['finished the customs clearance'],
            'exclude_locations' => ['dubai', 'dubaï', 'dxb'],
            'priority' => 1,
        ],
        
        'out_for_delivery' => [
            // À compléter avec données réelles si disponibles
            'keywords_en' => ['out for delivery', 'on the way'],
            'keywords_fr' => ['en livraison'],
            'priority' => 1,
        ],
        
        'delivered' => [
            // Faster
            'keywords_en' => ['delivered'],
            // ITDIDA
            'keywords_cn' => ['已签收', '签收'],
            'keywords_en' => array_merge(['delivered'], ['signed for']),
            'keywords_fr' => ['livré', 'signé pour'],
            // ChoiceXP
            'keywords_en' => array_merge(['delivered'], ['picked up and sign-off', 'sign-off']),
            'priority' => 1,
        ],
    ],
    
    'provider_specific_mappings' => [
        'Faster' => [
            'status_patterns' => [
                'arrival_uae' => [
                    'keywords_en' => ['arrived'],
                    'locations' => ['dubai', 'dubaï', 'hub de dubai'],
                ],
                'customs_clearance_uae' => [
                    'keywords_en' => ['customs'],
                    'locations' => ['dubai', 'dubaï'],
                ],
                'in_transit_uae' => [
                    'keywords_en' => ['departed'],
                    'locations' => ['dubai', 'dubaï'],
                    'exclude_locations' => ['china', 'chine', 'shenzhen'],
                ],
                'delivered' => [
                    'keywords_en' => ['delivered'],
                ],
            ],
        ],
        
        'ITDIDA' => [
            'status_patterns' => [
                'arrival_uae' => [
                    'keywords_cn' => ['航班到港：抵达迪拜', '抵达迪拜'],
                    'keywords_en' => ['flight arrival', 'arrival in dubai'],
                    'locations' => ['dxb', 'dubai', 'dubaï'],
                ],
                'customs_clearance_uae' => [
                    'keywords_cn' => ['清关完成'],
                    'keywords_en' => ['customs clearance completed'],
                    'locations' => ['dxb', 'dubai'],
                ],
                'in_transit_uae' => [
                    'keywords_cn' => ['离开香港'],
                    'keywords_en' => ['leave hong kong'],
                    'locations' => ['hkg'],
                ],
                'delivered' => [
                    'keywords_cn' => ['已签收'],
                    'keywords_en' => ['signed for'],
                ],
            ],
        ],
        
        'ChoiceXP' => [
            'status_patterns' => [
                'arrival_uae' => [
                    'keywords_en' => ['arrived destination dubai'],
                    'pattern' => '/arrived\s+destination\s+(dubai|dubai)/i',
                ],
                'customs_clearance_uae' => [
                    'keywords_en' => ['finished the customs clearance'],
                    'pattern' => '/finished\s+the\s+customs\s+clearance/i',
                ],
                'arrival_destination_country' => [
                    'keywords_en' => ['completed entering the destination'],
                    'exclude_locations' => ['dubai', 'dubaï'],
                ],
                'delivered' => [
                    'keywords_en' => ['picked up and sign-off'],
                ],
            ],
        ],
    ],
];
```

---

## 📝 Observations Importantes

1. **Faster** : Statuts simples en anglais, localisations traduites en français
2. **ITDIDA** : Statuts bruts en chinois, nécessite traduction. Format "航班到港：抵达迪拜" très spécifique
3. **ChoiceXP** : Phrases complètes en anglais, format "Shipment had [action]"

4. **Patterns communs** :
   - "Arrived" + "Dubai/DXB" → `arrival_uae`
   - "Customs" + "Dubai" → `customs_clearance_uae`
   - "Departed" + "Dubai" (sans Chine) → `in_transit_uae`
   - "Delivered" / "Signed for" / "Sign-off" → `delivered`

5. **Localisations clés** :
   - `DXB` = Dubaï
   - `HKG` = Hong Kong
   - `DUBAI` / `DUBAÏ` = Dubaï
   - `Shenzhen` = Chine

---

## ✅ Prochaines Étapes

1. ✅ Analyser les payloads réels
2. ⏳ Créer `config/tracking_status_mapping.php` avec ces mappings
3. ⏳ Tester avec les numéros de tracking réels
4. ⏳ Affiner les patterns si nécessaire
