<?php

namespace App\Services\OrderStatus;

use App\Events\SourcingOrderStatusChanged;
use App\Models\SourcingOrder;
use App\Services\Tracking\TrackingStatusMapper;
use App\Services\Tracking\UnifiedTrackingService;
use Illuminate\Support\Facades\Log;

/**
 * Service pour mettre à jour automatiquement les statuts de commande
 * basés sur les événements de tracking réels.
 */
class AutoUpdateOrderStatusFromTracking
{
    public function __construct(
        protected UnifiedTrackingService $trackingService,
        protected TrackingStatusMapper $statusMapper
    ) {}

    /**
     * Mettre à jour le statut d'une commande basé sur les données de tracking.
     * 
     * @param SourcingOrder $order La commande à mettre à jour
     * @return bool True si le statut a été mis à jour, false sinon
     */
    public function updateOrderStatusFromTracking(SourcingOrder $order): bool
    {
        // Vérifier que la commande a un tracking réel assigné
        if (!$order->hasRealTracking()) {
            Log::debug('[AutoUpdateOrderStatus] Order does not have real tracking', [
                'order_id' => $order->id,
                'tracking_number' => $order->tracking_number,
                'real_tracking_assigned_at' => $order->real_tracking_assigned_at,
            ]);
            return false;
        }

        // Vérifier que la commande n'est pas dans un statut qui bloque les mises à jour
        if (in_array($order->status, ['on_hold', 'shipment_canceled', 'order_completed'])) {
            Log::debug('[AutoUpdateOrderStatus] Order status blocks auto-update', [
                'order_id' => $order->id,
                'status' => $order->status,
            ]);
            return false;
        }

        // Vérifier que la commande est dans un statut éligible pour l'auto-update
        $eligibleStatuses = [
            'shipment_preparing',
            'in_transit_china',
            'arrival_uae',
            'customs_clearance_uae',
            'in_transit_uae',
            'arrival_destination_country',
            'customs_clearance_destination_country',
            'out_for_delivery',
        ];

        if (!in_array($order->status, $eligibleStatuses)) {
            Log::debug('[AutoUpdateOrderStatus] Order status not eligible for auto-update', [
                'order_id' => $order->id,
                'status' => $order->status,
            ]);
            return false;
        }

        try {
            // Récupérer les données de tracking (cache d'abord)
            $trackingResult = $this->trackingService->track($order->tracking_number, $order->tracking_carrier);

            // Si "pending" (job Selenium dispatché): fetch synchrone uniquement pour les providers rapides (API)
            // Selenium (ITDIDA, ChoiceXP, UPS) prend du temps → on ne bloque pas, on skip et on utilisera le cache plus tard
            $syncProviders = config('tracking.sync_providers_for_cron', ['Faster', 'FSB']);
            $provider = $trackingResult['provider'] ?? '';
            $isPending = ($trackingResult['status'] ?? null) === 'pending' || (empty($trackingResult['success']) && str_contains($trackingResult['error'] ?? '', 'refresh in a few minutes'));

            if ($isPending && in_array($provider, $syncProviders, true)) {
                Log::info('[AutoUpdateOrderStatus] Pending but provider is sync-safe, fetching now', [
                    'order_id' => $order->id,
                    'provider' => $provider,
                ]);
                $trackingResult = $this->trackingService->refreshTracking($order->tracking_number, $order->tracking_carrier);
            } elseif ($isPending) {
                Log::debug('[AutoUpdateOrderStatus] Skipping sync fetch for slow provider', [
                    'order_id' => $order->id,
                    'provider' => $provider,
                    'message' => 'Uses cache only; data will be available after job or user refresh',
                ]);
                return false;
            }

            if (empty($trackingResult['success'])) {
                Log::warning('[AutoUpdateOrderStatus] Failed to fetch tracking data', [
                    'order_id' => $order->id,
                    'tracking_number' => $order->tracking_number,
                    'error' => $trackingResult['error'] ?? 'Unknown error',
                ]);
                return false;
            }

            // Mapper les événements vers un statut
            $detectedStatus = $this->statusMapper->mapTrackingEventsToStatus($trackingResult, $order);

            if (!$detectedStatus) {
                Log::debug('[AutoUpdateOrderStatus] No status detected from tracking events', [
                    'order_id' => $order->id,
                    'tracking_number' => $order->tracking_number,
                ]);
                return false;
            }

            // Vérifier si le statut détecté est différent du statut actuel
            if ($detectedStatus === $order->status) {
                Log::debug('[AutoUpdateOrderStatus] Detected status is same as current status', [
                    'order_id' => $order->id,
                    'status' => $detectedStatus,
                ]);
                return false;
            }

            // Vérifier si la transition est autorisée (règles assouplies pour le tracking : progression dans la chaîne)
            if (!$order->canTransitionToFromTracking($detectedStatus)) {
                Log::warning('[AutoUpdateOrderStatus] Transition not allowed', [
                    'order_id' => $order->id,
                    'current_status' => $order->status,
                    'detected_status' => $detectedStatus,
                ]);
                return false;
            }

            // Mettre à jour le statut (capturer l'ancien avant modification pour le log)
            $oldStatus = $order->status;
            $order->status = $detectedStatus;
            $order->save();

            Log::info('[AutoUpdateOrderStatus] Order status updated successfully', [
                'order_id' => $order->id,
                'old_status' => $oldStatus,
                'new_status' => $detectedStatus,
                'tracking_number' => $order->tracking_number,
                'provider' => $trackingResult['provider'] ?? 'unknown',
            ]);

            // Déclencher l'événement de changement de statut
            // Recharger les relations nécessaires pour les notifications
            $order->load('quotation.sourcingRequest', 'user');
            event(new SourcingOrderStatusChanged($order));

            return true;

        } catch (\Exception $e) {
            Log::error('[AutoUpdateOrderStatus] Exception during status update', [
                'order_id' => $order->id,
                'tracking_number' => $order->tracking_number,
                'error' => $e->getMessage(),
                'trace' => substr($e->getTraceAsString(), 0, 500),
            ]);
            return false;
        }
    }

    /**
     * Traiter plusieurs commandes en lot.
     * 
     * @param \Illuminate\Database\Eloquent\Collection|array $orders Les commandes à traiter
     * @return array Statistiques (processed, updated, failed)
     */
    public function processBatch($orders): array
    {
        $stats = [
            'processed' => 0,
            'updated' => 0,
            'failed' => 0,
            'skipped' => 0,
        ];

        foreach ($orders as $order) {
            $stats['processed']++;

            try {
                $updated = $this->updateOrderStatusFromTracking($order);
                
                if ($updated) {
                    $stats['updated']++;
                } else {
                    $stats['skipped']++;
                }
            } catch (\Exception $e) {
                $stats['failed']++;
                Log::error('[AutoUpdateOrderStatus] Batch processing error', [
                    'order_id' => $order->id ?? null,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        return $stats;
    }
}
