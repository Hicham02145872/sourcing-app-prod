<?php

/**
 * Configuration pour le mapping des statuts de tracking vers les statuts de commande.
 *
 * Règle importante : la dernière destination des événements de tracking est souvent Dubaï.
 * "Delivered" avec lieu Dubaï = livré au hub à Dubaï, pas au client final. Le statut
 * interne `delivered` (livraison au client) n'est donc appliqué que si la localisation
 * n'est PAS Dubaï/UAE (exclude_locations sur le pattern 'delivered').
 *
 * Voir docs/TRACKING_STATUS_MAPPING_REAL_DATA.md pour les détails.
 */

return [
    // Mappings génériques (tous providers)
    'status_patterns' => [
        'arrival_uae' => [
            'keywords_en' => ['arrived', 'arrival', 'flight arrival', 'arrival in dubai', 'arrived destination dubai'],
            'keywords_fr' => ['arrivée', 'arrivé'],
            'keywords_cn' => ['航班到港：抵达迪拜', '抵达迪拜', '抵达'],
            'locations' => ['dubai', 'dubaï', 'dxb', 'hub de dubai', 'dubai ras al khor'],
            'exclude_locations' => ['china', 'chine', 'shenzhen', 'hong kong', 'hkg'],
            'priority' => 1,
        ],
        
        'customs_clearance_uae' => [
            'keywords_en' => ['customs', 'customs clearance', 'customs clearance completed', 'finished the customs clearance'],
            'keywords_fr' => ['déclaration en douane', 'dédouanement', 'dédouanement terminé'],
            'keywords_cn' => ['清关完成', '清关', '通关'],
            'locations' => ['dubai', 'dubaï', 'dxb'],
            'priority' => 1,
        ],
        
        'in_transit_uae' => [
            'keywords_en' => ['departed', 'departure', 'leave hong kong', 'leaved from warehouse', 'left'],
            'keywords_fr' => ['départ', 'départ de hong kong', 'en transit'],
            'keywords_cn' => ['离开香港', '离开扫描'],
            'locations' => ['dubai', 'dubaï', 'dxb'],
            'exclude_locations' => ['china', 'chine', 'shenzhen', 'hong kong', 'hkg'],
            'priority' => 1,
        ],
        
        'arrival_destination_country' => [
            'keywords_en' => ['completed entering the destination', 'entered destination', 'arrived destination'],
            'keywords_fr' => ['arrivée', 'arrivé'],
            'keywords_cn' => ['已到达', '到达'],
            'exclude_locations' => ['dubai', 'dubaï', 'dxb', 'china', 'chine', 'shenzhen'],
            'priority' => 1,
        ],
        
        'customs_clearance_destination_country' => [
            'keywords_en' => ['customs clearance', 'cleared customs', 'customs cleared', 'finished the customs clearance'],
            'keywords_fr' => ['déclaration en douane', 'dédouanement terminé'],
            'keywords_cn' => ['清关完成', '清关'],
            'exclude_locations' => ['dubai', 'dubaï', 'dxb'],
            'priority' => 1,
        ],
        
        'out_for_delivery' => [
            'keywords_en' => ['out for delivery', 'on the way', 'en route'],
            'keywords_fr' => ['en livraison', 'en cours de livraison'],
            'keywords_cn' => ['派送中', '配送中'],
            'priority' => 1,
        ],
        
        // Livraison finale au client uniquement. "Delivered" + Dubai = livré au hub Dubaï, pas au client.
        'delivered' => [
            'keywords_en' => ['delivered', 'signed for', 'picked up and sign-off', 'sign-off', 'completed', 'received'],
            'keywords_fr' => ['livré', 'livraison effectuée', 'signé pour', 'réceptionné'],
            'keywords_cn' => ['已签收', '已送达', '签收'],
            'exclude_locations' => ['dubai', 'dubaï', 'dxb', 'uae', 'emirates', 'hub de dubai', 'dubai ras al khor'],
            'priority' => 1,
        ],
        
        // Statuts optionnels (déjà gérés par FSB virtuel, mais disponibles pour tracking réel précoce)
        'shipment_preparing' => [
            'keywords_en' => ['booked', 'forecast', 'receipt scanning', 'create the order'],
            'keywords_fr' => ['réservé', 'prévision', 'scan de réception'],
            'keywords_cn' => ['已预报', '收货扫描'],
            'locations' => ['china', 'chine', 'shenzhen'],
            'priority' => 2, // Lower priority
        ],
        
        'in_transit_china' => [
            'keywords_en' => ['departed', 'leave scan', 'leaved from warehouse', 'shipped'],
            'keywords_fr' => ['départ', 'scan de départ', 'expédié'],
            'keywords_cn' => ['离开扫描', '已发货'],
            'locations' => ['china', 'chine', 'shenzhen'],
            'exclude_locations' => ['dubai', 'dubaï', 'dxb'],
            'priority' => 2, // Lower priority
        ],
    ],
    
    // Mappings spécifiques par provider
    'provider_specific_mappings' => [
        'Faster' => [
            'status_patterns' => [
                'arrival_uae' => [
                    'keywords_en' => ['arrived'],
                    'locations' => ['dubai', 'dubaï', 'hub de dubai', 'dubai ras al khor'],
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
                // Ne pas mapper "Delivered" si lieu = Dubaï (livré au hub, pas au client).
                'delivered' => [
                    'keywords_en' => ['delivered'],
                    'exclude_locations' => ['dubai', 'dubaï', 'dxb', 'hub de dubai', 'dubai ras al khor'],
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
                    'exclude_locations' => ['china', 'chine'],
                ],
                // Ne pas mapper si lieu = Dubaï (livré au hub, pas au client).
                'delivered' => [
                    'keywords_cn' => ['已签收'],
                    'keywords_en' => ['signed for'],
                    'exclude_locations' => ['dubai', 'dubaï', 'dxb'],
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
                // Ne pas mapper si lieu = Dubaï (livré au hub, pas au client).
                'delivered' => [
                    'keywords_en' => ['picked up and sign-off'],
                    'exclude_locations' => ['dubai', 'dubaï', 'dxb'],
                ],
            ],
        ],
        
        'UPS' => [
            'status_patterns' => [
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
                // Ne pas mapper "Delivered" si lieu = Dubaï (livré au hub, pas au client).
                'delivered' => [
                    'keywords_en' => ['delivered'],
                    'exclude_locations' => ['dubai', 'dubaï', 'dxb', 'uae', 'emirates'],
                ],
            ],
        ],
        
        '17Track' => [
            'status_codes' => [
                '3000' => 'in_transit_uae', // In Transit
                '3010' => 'arrival_destination_country', // Arrived at Destination
                '3020' => 'customs_clearance_destination_country', // Customs Clearance
                '3030' => 'out_for_delivery', // Out for Delivery
                '3040' => 'delivered', // Delivered
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
    
    // Ordre de vérification des champs
    'field_check_order' => [
        'status_en',
        'status_fr',
        'status', // Raw status
        'status_cn', // Si disponible
    ],
    
    // Normalisation des localisations
    'location_normalization' => [
        'dubai' => ['dubai', 'dubaï', 'dxb', 'hub de dubai', 'dubai ras al khor'],
        'hong_kong' => ['hong kong', 'hkg'],
        'china' => ['china', 'chine', 'shenzhen'],
    ],
];
