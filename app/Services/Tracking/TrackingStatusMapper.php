<?php

namespace App\Services\Tracking;

use App\Models\SourcingOrder;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * Service pour mapper les événements de tracking vers les statuts de commande.
 * 
 * Analyse les événements de tracking retournés par les providers et détermine
 * le statut approprié pour la commande basé sur les keywords, locations et patterns.
 */
class TrackingStatusMapper
{
    protected array $config;

    public function __construct()
    {
        $this->config = config('tracking_status_mapping', []);
    }

    /**
     * Mapper les événements de tracking vers un statut de commande.
     * 
     * @param array $trackingResult Résultat du tracking (events[], provider, current_status, etc.)
     * @param SourcingOrder $order La commande à analyser
     * @return string|null Le statut détecté ou null si aucun statut ne correspond
     */
    public function mapTrackingEventsToStatus(array $trackingResult, SourcingOrder $order): ?string
    {
        if (empty($trackingResult['success']) || empty($trackingResult['events'])) {
            Log::debug('[TrackingStatusMapper] No events to analyze', [
                'order_id' => $order->id,
                'tracking_number' => $trackingResult['tracking_number'] ?? null,
            ]);
            return null;
        }

        $provider = $trackingResult['provider'] ?? 'unknown';
        $events = $trackingResult['events'] ?? [];
        $currentOrderStatus = $order->status;

        Log::info('[TrackingStatusMapper] Analyzing tracking events', [
            'order_id' => $order->id,
            'provider' => $provider,
            'events_count' => count($events),
            'current_order_status' => $currentOrderStatus,
        ]);

        // Analyser les événements du plus récent au plus ancien
        // (les événements sont généralement triés du plus récent au plus ancien)
        foreach ($events as $event) {
            $detectedStatus = $this->analyzeEvent($event, $currentOrderStatus, $provider);
            
            if ($detectedStatus) {
                Log::info('[TrackingStatusMapper] Status detected from event', [
                    'order_id' => $order->id,
                    'detected_status' => $detectedStatus,
                    'event' => [
                        'date' => $event['date'] ?? null,
                        'status' => $event['status'] ?? null,
                        'status_en' => $event['status_en'] ?? null,
                        'location' => $event['location'] ?? null,
                    ],
                ]);
                
                return $detectedStatus;
            }
        }

        Log::debug('[TrackingStatusMapper] No matching status found', [
            'order_id' => $order->id,
            'provider' => $provider,
        ]);

        return null;
    }

    /**
     * Analyser un événement individuel pour détecter un statut.
     * 
     * @param array $event L'événement de tracking
     * @param string $currentOrderStatus Le statut actuel de la commande
     * @param string $provider Le provider de tracking
     * @return string|null Le statut détecté ou null
     */
    public function analyzeEvent(array $event, string $currentOrderStatus, string $provider = 'unknown'): ?string
    {
        $statusText = $this->getStatusText($event);
        $location = $this->getLocation($event);

        // 1. Vérifier les mappings spécifiques au provider d'abord
        $providerSpecificStatus = $this->checkProviderSpecificMappings($event, $provider);
        if ($providerSpecificStatus) {
            return $providerSpecificStatus;
        }

        // 2. Vérifier les patterns génériques par priorité
        $statusPatterns = $this->config['status_patterns'] ?? [];
        
        // Trier par priorité (plus haute priorité en premier)
        uasort($statusPatterns, function ($a, $b) {
            $priorityA = $a['priority'] ?? 0;
            $priorityB = $b['priority'] ?? 0;
            return $priorityB <=> $priorityA;
        });

        foreach ($statusPatterns as $targetStatus => $pattern) {
            if ($this->matchesPattern($event, $pattern, $targetStatus, $currentOrderStatus)) {
                return $targetStatus;
            }
        }

        return null;
    }

    /**
     * Vérifier les mappings spécifiques au provider.
     */
    protected function checkProviderSpecificMappings(array $event, string $provider): ?string
    {
        $providerMappings = $this->config['provider_specific_mappings'][$provider] ?? null;
        
        if (!$providerMappings) {
            return null;
        }

        // Vérifier les codes de statut (pour 17Track par exemple)
        if (isset($providerMappings['status_codes'])) {
            $statusCode = $event['status_code'] ?? null;
            if ($statusCode && isset($providerMappings['status_codes'][$statusCode])) {
                return $providerMappings['status_codes'][$statusCode];
            }
        }

        // Vérifier les patterns spécifiques
        if (isset($providerMappings['status_patterns'])) {
            foreach ($providerMappings['status_patterns'] as $targetStatus => $pattern) {
                if ($this->matchesPattern($event, $pattern, $targetStatus)) {
                    return $targetStatus;
                }
            }
        }

        return null;
    }

    /**
     * Vérifier si un événement correspond à un pattern donné.
     */
    protected function matchesPattern(array $event, array $pattern, string $targetStatus, string $currentOrderStatus = ''): bool
    {
        $statusText = $this->getStatusText($event);
        $location = $this->getLocation($event);

        // 1. Vérifier les keywords dans le statut
        $matchesKeywords = $this->matchesKeywords($statusText, $pattern);
        if (!$matchesKeywords) {
            return false;
        }

        // 2. Vérifier les locations (si spécifiées)
        if (isset($pattern['locations']) && !empty($pattern['locations'])) {
            $matchesLocation = $this->matchesLocation($location, $pattern['locations']);
            if (!$matchesLocation) {
                return false;
            }
        }

        // 3. Vérifier les exclusions de locations
        if (isset($pattern['exclude_locations']) && !empty($pattern['exclude_locations'])) {
            $excludedLocation = $this->matchesLocation($location, $pattern['exclude_locations']);
            if ($excludedLocation) {
                return false; // Location exclue, ne pas matcher
            }
        }

        // 4. Vérifier les patterns regex (si spécifiés)
        if (isset($pattern['pattern'])) {
            if (!preg_match($pattern['pattern'], $statusText)) {
                return false;
            }
        }

        // 5. Vérifier que le statut détecté est plus avancé que le statut actuel
        // (on ne veut pas régresser)
        if ($currentOrderStatus && !$this->isStatusProgression($currentOrderStatus, $targetStatus)) {
            Log::debug('[TrackingStatusMapper] Status would be a regression, skipping', [
                'current_status' => $currentOrderStatus,
                'detected_status' => $targetStatus,
            ]);
            return false;
        }

        return true;
    }

    /**
     * Vérifier si le texte du statut correspond aux keywords.
     */
    protected function matchesKeywords(string $statusText, array $pattern): bool
    {
        $statusTextLower = mb_strtolower($statusText, 'UTF-8');

        // Vérifier les keywords EN
        if (isset($pattern['keywords_en'])) {
            foreach ($pattern['keywords_en'] as $keyword) {
                if (Str::contains($statusTextLower, mb_strtolower($keyword, 'UTF-8'))) {
                    return true;
                }
            }
        }

        // Vérifier les keywords FR
        if (isset($pattern['keywords_fr'])) {
            foreach ($pattern['keywords_fr'] as $keyword) {
                if (Str::contains($statusTextLower, mb_strtolower($keyword, 'UTF-8'))) {
                    return true;
                }
            }
        }

        // Vérifier les keywords CN
        if (isset($pattern['keywords_cn'])) {
            foreach ($pattern['keywords_cn'] as $keyword) {
                if (Str::contains($statusText, $keyword)) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Vérifier si la localisation correspond aux locations spécifiées.
     */
    protected function matchesLocation(string $location, array $locations): bool
    {
        if (empty($location)) {
            return false;
        }

        $locationLower = mb_strtolower($location, 'UTF-8');

        foreach ($locations as $targetLocation) {
            $targetLocationLower = mb_strtolower($targetLocation, 'UTF-8');
            
            // Correspondance exacte ou partielle
            if (Str::contains($locationLower, $targetLocationLower) || 
                Str::contains($targetLocationLower, $locationLower)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Vérifier si le nouveau statut représente une progression (pas une régression).
     */
    protected function isStatusProgression(string $currentStatus, string $newStatus): bool
    {
        // Ordre de progression des statuts
        $statusOrder = [
            'pending_payment' => 1,
            'paid' => 2,
            'shipment_preparing' => 3,
            'in_transit_china' => 4,
            'arrival_uae' => 5,
            'customs_clearance_uae' => 6,
            'in_transit_uae' => 7,
            'arrival_destination_country' => 8,
            'customs_clearance_destination_country' => 9,
            'out_for_delivery' => 10,
            'delivered' => 11,
            'order_completed' => 12,
        ];

        $currentOrder = $statusOrder[$currentStatus] ?? 0;
        $newOrder = $statusOrder[$newStatus] ?? 0;

        // Le nouveau statut doit être supérieur ou égal au statut actuel
        // (on permet les mises à jour vers le même statut pour les cas où on veut forcer une mise à jour)
        return $newOrder >= $currentOrder;
    }

    /**
     * Extraire le texte du statut depuis l'événement.
     * 
     * Vérifie dans l'ordre : status_en, status_fr, status (raw)
     */
    protected function getStatusText(array $event): string
    {
        $fieldOrder = $this->config['field_check_order'] ?? ['status_en', 'status_fr', 'status'];
        
        foreach ($fieldOrder as $field) {
            if (!empty($event[$field])) {
                return (string) $event[$field];
            }
        }

        return '';
    }

    /**
     * Extraire la localisation depuis l'événement.
     */
    protected function getLocation(array $event): string
    {
        // Essayer location d'abord, puis location_fr, puis location_raw
        return $event['location'] ?? $event['location_fr'] ?? $event['location_raw'] ?? '';
    }

    /**
     * Normaliser une localisation selon la configuration.
     */
    protected function normalizeLocation(string $location): string
    {
        $normalization = $this->config['location_normalization'] ?? [];
        
        foreach ($normalization as $normalized => $variants) {
            foreach ($variants as $variant) {
                if (mb_stripos($location, $variant) !== false) {
                    return $normalized;
                }
            }
        }

        return mb_strtolower($location, 'UTF-8');
    }

    /**
     * Détecter un statut basé uniquement sur la localisation.
     * 
     * @param string $location La localisation
     * @return string|null Le statut détecté ou null
     */
    public function detectStatusFromLocation(string $location): ?string
    {
        if (empty($location)) {
            return null;
        }

        $normalizedLocation = $this->normalizeLocation($location);
        $statusPatterns = $this->config['status_patterns'] ?? [];

        foreach ($statusPatterns as $targetStatus => $pattern) {
            if (isset($pattern['locations']) && $this->matchesLocation($normalizedLocation, $pattern['locations'])) {
                // Vérifier qu'il n'y a pas d'exclusions
                if (isset($pattern['exclude_locations']) && 
                    $this->matchesLocation($normalizedLocation, $pattern['exclude_locations'])) {
                    continue;
                }
                
                return $targetStatus;
            }
        }

        return null;
    }

    /**
     * Détecter un statut basé uniquement sur le texte du statut.
     * 
     * @param string $statusText Le texte du statut
     * @param string $language La langue (en, fr, cn)
     * @return string|null Le statut détecté ou null
     */
    public function detectStatusFromStatusText(string $statusText, string $language = 'en'): ?string
    {
        if (empty($statusText)) {
            return null;
        }

        $statusPatterns = $this->config['status_patterns'] ?? [];

        foreach ($statusPatterns as $targetStatus => $pattern) {
            $keywordField = "keywords_{$language}";
            
            if (isset($pattern[$keywordField])) {
                foreach ($pattern[$keywordField] as $keyword) {
                    if (Str::contains(mb_strtolower($statusText, 'UTF-8'), mb_strtolower($keyword, 'UTF-8'))) {
                        return $targetStatus;
                    }
                }
            }
        }

        return null;
    }
}
