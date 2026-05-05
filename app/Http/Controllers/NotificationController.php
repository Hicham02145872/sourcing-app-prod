<?php

namespace App\Http\Controllers;

use App\Traits\NotificationFilterTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class NotificationController extends Controller
{
    use NotificationFilterTrait;

    /**
     * Traduit un statut dans la locale courante.
     */
    private function translateStatus(string $status): string
    {
        $map = [
            'pending'                               => __('Pending'),
            'in_review'                             => __('In review'),
            'quoted'                                => __('Quoted'),
            'rejected'                              => __('Rejected'),
            'accepted'                              => __('Accepted'),
            'cancelled'                             => __('Cancelled'),
            'negotiating'                           => __('Negotiating'),
            'pending_payment'                       => __('Pending payment'),
            'paid'                                  => __('Paid'),
            'shipment_preparing'                    => __('Shipment Preparing'),
            'in_transit_china'                      => __('In Transit (Departure from China)'),
            'arrival_uae'                           => __('Arrival in UAE'),
            'customs_clearance_uae'                 => __('Customs Clearance in UAE'),
            'in_transit_uae'                        => __('In Transit (Departure from UAE)'),
            'arrival_destination_country'           => __('Arrival in Destination Country'),
            'customs_clearance_destination_country' => __('Customs Clearance in Destination Country'),
            'out_for_delivery'                      => __('Out for Delivery'),
            'delivered'                             => __('Delivered'),
            'delivery_failed'                       => __('Delivery Failed'),
            'shipment_delayed'                      => __('Shipment Delayed'),
            'shipment_returned'                     => __('Shipment Returned'),
            'shipment_canceled'                     => __('Shipment Canceled'),
            'order_completed'                       => __('Order Completed'),
            'on_hold'                               => __('On Hold'),
            'approved'                              => __('Approved'),
        ];

        return $map[$status] ?? ucfirst(str_replace('_', ' ', $status));
    }

    /**
     * Met à jour le token FCM de l'utilisateur connecté.
     */
    public function updateToken(Request $request)
    {
        try {
            $request->validate([
                'fcm_token' => 'required|string|max:500',
            ]);

            $user = Auth::user();

            if (! $user) {
                Log::warning('FCM Token Update: Tentative sans authentification', [
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ]);

                return response()->json(['message' => 'Utilisateur non authentifié.'], 401);
            }

            $oldToken = $user->fcm_token;
            $newToken = $request->fcm_token;

            // Vérifier si le token a changé
            if ($oldToken === $newToken) {
                Log::debug("FCM Token Update: User {$user->id} — Token identique, aucune mise à jour nécessaire.");

                return response()->json([
                    'message' => 'Token déjà à jour.',
                    'updated' => false,
                ]);
            }

            // Mettre à jour le token
            $updated = $user->update(['fcm_token' => $newToken]);

            if ($updated) {
                Log::info("FCM Token Update: User {$user->id} — Token mis à jour avec succès", [
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'old_token_length' => strlen($oldToken ?? ''),
                    'new_token_length' => strlen($newToken),
                ]);

                // Invalider le cache des notifications si utilisé
                Cache::forget("user_notifications_{$user->id}");

                return response()->json([
                    'message' => 'Token FCM mis à jour avec succès.',
                    'updated' => true,
                ]);
            }

            Log::error("FCM Token Update: Échec de la mise à jour pour User {$user->id}");

            return response()->json([
                'message' => 'Erreur lors de la mise à jour du token.',
                'updated' => false,
            ], 500);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Données invalides.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            Log::error('FCM Token Update: Exception', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'message' => 'Une erreur est survenue lors de la mise à jour du token.',
            ], 500);
        }
    }

    /**
     * Retourne toutes les notifications de l'utilisateur connecté.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        if (! $user) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Utilisateur non authentifié.'], 401);
            }

            return redirect()->route('login');
        }

        $notificationsQuery = $user->notifications()->orderBy('created_at', 'desc');

        // Filter notifications by assignment for admins
        $allNotifications = $notificationsQuery->get();
        $filteredNotifications = $this->filterNotificationsByAssignment($allNotifications, $user);

        // Manually paginate the filtered collection
        $perPage = 15;
        $currentPage = \Illuminate\Pagination\Paginator::resolveCurrentPage();
        $currentItems = $filteredNotifications->slice(($currentPage - 1) * $perPage, $perPage)->all();
        $notifications = new \Illuminate\Pagination\LengthAwarePaginator(
            $currentItems,
            $filteredNotifications->count(),
            $perPage,
            $currentPage,
            ['path' => \Illuminate\Pagination\Paginator::resolveCurrentPath()]
        );

        Log::debug("Fetched notifications for user {$user->id}", ['count' => $notifications->count(), 'notifications' => $notifications->toArray()]);

        $mappedNotifications = $notifications->getCollection()->map(function ($notification) {
            $data = is_string($notification->data)
                ? json_decode($notification->data, true) ?? []
                : ($notification->data ?? []);
            $title = $data['title'] ?? $data['notification']['title'] ?? 'Notification';
            $body = $data['message'] ?? $data['body'] ?? $data['notification']['body'] ?? 'Nouvelle notification';

            // Fallback for older literal strings stored in DB
            if (is_string($title)) {
                if (str_starts_with($title, 'Update: ')) {
                    $rawStatus = trim(str_replace('Update: ', '', $title));
                    $translatedStatus = $this->translateStatus(str_replace(' ', '_', strtolower($rawStatus)));
                    $title = __('Update: ') . $translatedStatus;
                } elseif (str_contains($title, 'Order Status Update: ')) {
                    $emoji = str_contains($title, '📦') ? '📦' : (str_contains($title, '✅') ? '✅' : (str_contains($title, '🇦🇪') ? '🇦🇪' : '🔔'));
                    $rawStatus = trim(last(explode('Order Status Update: ', $title)));
                    $translatedStatus = $this->translateStatus(str_replace(' ', '_', strtolower($rawStatus)));
                    $title = __(':emoji Order Status Update: :status', ['emoji' => $emoji, 'status' => $translatedStatus]);
                } elseif (str_contains($title, 'New Quotation: ')) {
                    preg_match('/New Quotation: ([\d\.]+) (\w+)/', $title, $matches);
                    if (count($matches) >= 3) {
                        $title = __('📄 New Quotation: :amount :currency', ['amount' => $matches[1], 'currency' => $matches[2]]);
                    }
                } elseif (str_contains($title, 'Tracking number for order #')) {
                    $orderId = last(explode('#', $title));
                    $title = __('📦 Tracking number for order #:orderId', ['orderId' => $orderId]);
                }
            }

            if (is_string($body)) {
                if (str_contains($body, 'is now ')) {
                    $parts = explode('is now ', $body);
                    if (count($parts) > 1) {
                        $prefix = trim($parts[0]);
                        $rawStatus = trim(trim($parts[1]), "'.");
                        
                        if (str_contains($prefix, 'Your order #')) {
                            preg_match('/Your order #(\d+)/', $prefix, $matches);
                            $orderId = $matches[1] ?? '';
                            $translatedStatus = $this->translateStatus(str_replace(' ', '_', strtolower($rawStatus)));
                            $body = __('Your order #:orderId is now :status.', [
                                'orderId' => $orderId,
                                'status' => $translatedStatus
                            ]);
                        } elseif (str_contains($prefix, 'Your request for')) {
                            preg_match("/Your request for '([^']+)'/", $prefix, $matches);
                            $productName = $matches[1] ?? '';
                            $translatedStatus = $this->translateStatus(str_replace(' ', '_', strtolower($rawStatus)));
                            $body = __("Your request for ':productName' is now ':status'.", [
                                'productName' => $productName,
                                'status' => $translatedStatus
                            ]);
                        }
                    }
                } elseif (str_contains($body, 'can now be tracked')) {
                    if (str_contains($body, 'FSB tracking number:')) {
                        preg_match('/Your order "([^"]+)" can now be tracked\. Your FSB tracking number: (\w+)/', $body, $matches);
                        if (count($matches) >= 3) {
                            $body = __('Your order ":productName" can now be tracked. Your FSB tracking number: :number', [
                                'productName' => $matches[1],
                                'number' => $matches[2]
                            ]);
                        }
                    } else {
                        preg_match('/Your order "([^"]+)" can now be tracked\. Your tracking reference: (\w+)/', $body, $matches);
                        if (count($matches) >= 3) {
                            $body = __('Your order ":productName" can now be tracked. Your tracking reference: :number', [
                                'productName' => $matches[1],
                                'number' => $matches[2]
                            ]);
                        }
                    }
                } elseif (str_contains($body, "You've received a new quote for '")) {
                    preg_match("/You've received a new quote for '([^']+)'./", $body, $matches);
                    if (count($matches) >= 2) {
                        $body = __("You've received a new quote for ':productName'. Click to view details.", ['productName' => $matches[1]]);
                    }
                }
            }

            // If it's the new structured format, we can refine it
            if (isset($data['title_key'])) {
                $title = __($data['title_key'], $data['title_params'] ?? []);
            } elseif ($title === 'order_status_update_title') {
                $statusLabel = $this->translateStatus($data['status'] ?? '');
                $emoji = $data['emoji'] ?? '🔔';
                $title = __(':emoji Order Status Update: :status', ['emoji' => $emoji, 'status' => $statusLabel]);
            } else {
                $title = __($title);
            }

            if (isset($data['body_key'])) {
                $params = $data['body_params'] ?? [];
                if (isset($params['status'])) {
                    $params['status'] = $this->translateStatus($params['status']);
                }
                $body = __($data['body_key'], $params);
            } elseif (isset($data['title']) && $data['title'] === 'order_status_update_title') {
                 $statusLabel = $this->translateStatus($data['status'] ?? '');
                 if (isset($data['status']) && $data['status'] === 'paid') {
                     $body = __('Payment confirmed! Next step: Shipment preparation.');
                 } elseif (isset($data['status']) && $data['status'] === 'arrival_uae') {
                     $body = __('Great news! Your package has arrived in the UAE.');
                 } elseif (isset($data['status']) && $data['status'] === 'delivery_failed') {
                     $body = __('Delivery failed. Please check your order details to reschedule.');
                 } else {
                     $body = __('Your order #:orderId is now :status.', [
                         'orderId' => $data['sourcing_order_id'] ?? '',
                         'status' => $statusLabel,
                     ]);
                 }
            } else {
                $body = __($body);
            }

            $clickAction = $data['click_action']
                ?? $data['notification']['click_action']
                ?? null;

            $icon = $data['icon']
                ?? $data['notification']['icon']
                ?? null;

            return [
                'id' => $notification->id,
                'title' => $title,
                'body' => $body,
                'click_action' => $clickAction,
                'icon' => $icon,
                'read_at' => $notification->read_at?->toIso8601String(),
                'created_at' => $notification->created_at->toIso8601String(),
                'is_read' => ! is_null($notification->read_at),
                'type' => $data['type'] ?? $notification->type ?? null,
                'tracking_number' => $data['tracking_number'] ?? null,
                'category' => $data['category'] ?? null,
                'sourcing_request_id' => $data['sourcing_request_id'] ?? null,
                'quotation_id' => $data['quotation_id'] ?? null,
                'sourcing_order_id' => $data['sourcing_order_id'] ?? null,
                'refund_request_id' => $data['refund_request_id'] ?? null,
            ];
        });

        $unreadCount = $notifications->where('read_at', null)->count();

        if ($request->expectsJson()) {
            return response()->json([
                'notifications' => $mappedNotifications->values(),
                'unread_count' => $unreadCount,
                'total_count' => $notifications->total(),
                'per_page' => $notifications->perPage(),
                'current_page' => $notifications->currentPage(),
                'last_page' => $notifications->lastPage(),
                'from' => $notifications->firstItem(),
                'to' => $notifications->lastItem(),
                'links' => $notifications->linkCollection(),
            ]);
        }

        return view('notifications.index', [
            'notifications' => $mappedNotifications,
            'unreadCount' => $unreadCount,
        ]);
    }

    /**
     * Marque une notification comme lue.
     */
    public function markAsRead($notificationId)
    {
        try {
            $user = Auth::user();

            if (! $user) {
                return response()->json(['message' => 'Utilisateur non authentifié.'], 401);
            }

            $notification = $user->notifications()->where('id', $notificationId)->first();

            if (! $notification) {
                return response()->json(['message' => 'Notification introuvable.'], 404);
            }

            // Si déjà lue, pas besoin de mettre à jour
            if ($notification->read_at) {
                return response()->json([
                    'message' => 'Notification déjà marquée comme lue.',
                    'already_read' => true,
                ]);
            }

            $notification->markAsRead();

            // Invalider le cache
            Cache::forget("user_notifications_{$user->id}");

            Log::info('Notification marquée comme lue', [
                'user_id' => $user->id,
                'notification_id' => $notificationId,
            ]);

            return response()->json([
                'message' => 'Notification marquée comme lue.',
                'already_read' => false,
            ]);

        } catch (\Exception $e) {
            Log::error('Mark As Read: Exception', [
                'user_id' => Auth::id(),
                'notification_id' => $notificationId,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'message' => 'Erreur lors de la mise à jour de la notification.',
            ], 500);
        }
    }

    /**
     * Marque toutes les notifications comme lues.
     */
    public function markAllAsRead()
    {
        try {
            $user = Auth::user();

            if (! $user) {
                return response()->json(['message' => 'Utilisateur non authentifié.'], 401);
            }

            $count = $user->unreadNotifications()->update(['read_at' => now()]);

            // Invalider le cache
            Cache::forget("user_notifications_{$user->id}");

            Log::info('Toutes les notifications marquées comme lues', [
                'user_id' => $user->id,
                'count' => $count,
            ]);

            return response()->json([
                'message' => 'Toutes les notifications ont été marquées comme lues.',
                'count' => $count,
            ]);

        } catch (\Exception $e) {
            Log::error('Mark All As Read: Exception', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'message' => 'Erreur lors de la mise à jour des notifications.',
            ], 500);
        }
    }

    /**
     * Supprime une notification.
     */
    public function destroy($notificationId)
    {
        try {
            $user = Auth::user();

            if (! $user) {
                return response()->json(['message' => 'Utilisateur non authentifié.'], 401);
            }

            $notification = $user->notifications()->where('id', $notificationId)->first();

            if (! $notification) {
                return response()->json(['message' => 'Notification introuvable.'], 404);
            }

            $notification->delete();

            // Invalider le cache
            Cache::forget("user_notifications_{$user->id}");

            Log::info('Notification supprimée', [
                'user_id' => $user->id,
                'notification_id' => $notificationId,
            ]);

            return response()->json(['message' => 'Notification supprimée avec succès.']);

        } catch (\Exception $e) {
            Log::error('Delete Notification: Exception', [
                'user_id' => Auth::id(),
                'notification_id' => $notificationId,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'message' => 'Erreur lors de la suppression de la notification.',
            ], 500);
        }
    }

    /**
     * Supprime toutes les notifications de l'utilisateur.
     */
    public function clearAll()
    {
        try {
            $user = Auth::user();

            if (! $user) {
                return response()->json(['message' => 'Utilisateur non authentifié.'], 401);
            }

            $count = $user->notifications()->delete();

            // Invalider le cache
            Cache::forget("user_notifications_{$user->id}");

            Log::info('Toutes les notifications supprimées', [
                'user_id' => $user->id,
                'count' => $count,
            ]);

            return response()->json([
                'message' => 'Toutes les notifications ont été supprimées.',
                'count' => $count,
            ]);

        } catch (\Exception $e) {
            Log::error('Clear All Notifications: Exception', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'message' => 'Erreur lors de la suppression des notifications.',
            ], 500);
        }
    }
}
