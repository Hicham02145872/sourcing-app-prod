<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class NotificationController extends Controller
{
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

            if (!$user) {
                Log::warning('FCM Token Update: Tentative sans authentification', [
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent()
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
                    'updated' => false
                ]);
            }

            // Mettre à jour le token
            $updated = $user->update(['fcm_token' => $newToken]);

            if ($updated) {
                Log::info("FCM Token Update: User {$user->id} — Token mis à jour avec succès", [
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'old_token_length' => strlen($oldToken ?? ''),
                    'new_token_length' => strlen($newToken)
                ]);

                // Invalider le cache des notifications si utilisé
                Cache::forget("user_notifications_{$user->id}");

                return response()->json([
                    'message' => 'Token FCM mis à jour avec succès.',
                    'updated' => true
                ]);
            }

            Log::error("FCM Token Update: Échec de la mise à jour pour User {$user->id}");
            return response()->json([
                'message' => 'Erreur lors de la mise à jour du token.',
                'updated' => false
            ], 500);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Données invalides.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('FCM Token Update: Exception', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'message' => 'Une erreur est survenue lors de la mise à jour du token.'
            ], 500);
        }
    }

    /**
     * Retourne toutes les notifications de l'utilisateur connecté.
     */
    public function index(Request $request)
    {
        try {
            $user = Auth::user();

            if (!$user) {
                return response()->json(['message' => 'Utilisateur non authentifié.'], 401);
            }

            // Paramètres de pagination optionnels
            $limit = $request->input('limit', 50);
            $limit = min($limit, 100); // Maximum 100 notifications

            // Cache des notifications (5 minutes)
            $cacheKey = "user_notifications_{$user->id}";
            $notifications = Cache::remember($cacheKey, 300, function () use ($user, $limit) {
                return $user->notifications()
                    ->orderBy('created_at', 'desc')
                    ->limit($limit)
                    ->get()
                    ->map(function ($notification) {
                        // Décodage sécurisé des données
                        $data = is_string($notification->data)
                            ? json_decode($notification->data, true) ?? []
                            : ($notification->data ?? []);

                        // Gestion de différents formats de données (Firebase, Laravel, custom)
                        // Titre dynamique basé sur le statut si disponible
                        $status = $data['status'] ?? null;
                        $productName = $data['product_name'] ?? null;
                        
                        $defaultTitle = 'Mise à jour de demande';
                        if ($status && $productName) {
                            $statusLabels = [
                                'pending' => 'En attente',
                                'processing' => 'En traitement',
                                'completed' => 'Terminée',
                                'cancelled' => 'Annulée',
                            ];
                            $statusLabel = $statusLabels[$status] ?? ucfirst($status);
                            $defaultTitle = "Demande $statusLabel";
                        }
                        
                        $title = $data['title'] 
                            ?? $data['notification']['title'] 
                            ?? $notification->data['title'] 
                            ?? $defaultTitle;
                        
                        $body = $data['message']  // VOTRE FORMAT (message)
                            ?? $data['body']      // Format standard
                            ?? $data['notification']['body']  // Format Firebase
                            ?? $notification->data['body'] 
                            ?? $notification->data['message']
                            ?? 'Nouvelle notification';
                        
                        $clickAction = $data['click_action'] 
                            ?? $data['notification']['click_action'] 
                            ?? null;
                        
                        $icon = $data['icon'] 
                            ?? $data['notification']['icon'] 
                            ?? null;

                        // Log pour debug (à retirer en production)
                        Log::debug("Notification Mapping", [
                            'notification_id' => $notification->id,
                            'raw_data' => $data,
                            'mapped_title' => $title,
                            'mapped_body' => $body,
                            'body_length' => strlen($body)
                        ]);

                        return [
                            'id' => $notification->id,
                            'title' => $title,
                            'body' => $body,
                            'click_action' => $clickAction,
                            'icon' => $icon,
                            'read_at' => $notification->read_at?->toIso8601String(),
                            'created_at' => $notification->created_at->toIso8601String(),
                            'is_read' => !is_null($notification->read_at),
                            'type' => $notification->type ?? null,
                        ];
                    });
            });

            $unreadCount = $notifications->where('is_read', false)->count();

            Log::info("Notifications récupérées", [
                'user_id' => $user->id,
                'total' => $notifications->count(),
                'unread' => $unreadCount
            ]);

            return response()->json([
                'notifications' => $notifications->values(), // Réindexer le tableau
                'unread_count' => $unreadCount,
                'total_count' => $notifications->count()
            ]);

        } catch (\Exception $e) {
            Log::error('Notifications Index: Exception', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'message' => 'Erreur lors de la récupération des notifications.',
                'notifications' => [],
                'unread_count' => 0,
                'total_count' => 0
            ], 500);
        }
    }

    /**
     * Marque une notification comme lue.
     */
    public function markAsRead($notificationId)
    {
        try {
            $user = Auth::user();

            if (!$user) {
                return response()->json(['message' => 'Utilisateur non authentifié.'], 401);
            }

            $notification = $user->notifications()->where('id', $notificationId)->first();

            if (!$notification) {
                return response()->json(['message' => 'Notification introuvable.'], 404);
            }

            // Si déjà lue, pas besoin de mettre à jour
            if ($notification->read_at) {
                return response()->json([
                    'message' => 'Notification déjà marquée comme lue.',
                    'already_read' => true
                ]);
            }

            $notification->markAsRead();

            // Invalider le cache
            Cache::forget("user_notifications_{$user->id}");

            Log::info("Notification marquée comme lue", [
                'user_id' => $user->id,
                'notification_id' => $notificationId
            ]);

            return response()->json([
                'message' => 'Notification marquée comme lue.',
                'already_read' => false
            ]);

        } catch (\Exception $e) {
            Log::error('Mark As Read: Exception', [
                'user_id' => Auth::id(),
                'notification_id' => $notificationId,
                'error' => $e->getMessage()
            ]);
            return response()->json([
                'message' => 'Erreur lors de la mise à jour de la notification.'
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

            if (!$user) {
                return response()->json(['message' => 'Utilisateur non authentifié.'], 401);
            }

            $count = $user->unreadNotifications()->update(['read_at' => now()]);

            // Invalider le cache
            Cache::forget("user_notifications_{$user->id}");

            Log::info("Toutes les notifications marquées comme lues", [
                'user_id' => $user->id,
                'count' => $count
            ]);

            return response()->json([
                'message' => "Toutes les notifications ont été marquées comme lues.",
                'count' => $count
            ]);

        } catch (\Exception $e) {
            Log::error('Mark All As Read: Exception', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);
            return response()->json([
                'message' => 'Erreur lors de la mise à jour des notifications.'
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

            if (!$user) {
                return response()->json(['message' => 'Utilisateur non authentifié.'], 401);
            }

            $notification = $user->notifications()->where('id', $notificationId)->first();

            if (!$notification) {
                return response()->json(['message' => 'Notification introuvable.'], 404);
            }

            $notification->delete();

            // Invalider le cache
            Cache::forget("user_notifications_{$user->id}");

            Log::info("Notification supprimée", [
                'user_id' => $user->id,
                'notification_id' => $notificationId
            ]);

            return response()->json(['message' => 'Notification supprimée avec succès.']);

        } catch (\Exception $e) {
            Log::error('Delete Notification: Exception', [
                'user_id' => Auth::id(),
                'notification_id' => $notificationId,
                'error' => $e->getMessage()
            ]);
            return response()->json([
                'message' => 'Erreur lors de la suppression de la notification.'
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

            if (!$user) {
                return response()->json(['message' => 'Utilisateur non authentifié.'], 401);
            }

            $count = $user->notifications()->delete();

            // Invalider le cache
            Cache::forget("user_notifications_{$user->id}");

            Log::info("Toutes les notifications supprimées", [
                'user_id' => $user->id,
                'count' => $count
            ]);

            return response()->json([
                'message' => "Toutes les notifications ont été supprimées.",
                'count' => $count
            ]);

        } catch (\Exception $e) {
            Log::error('Clear All Notifications: Exception', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);
            return response()->json([
                'message' => 'Erreur lors de la suppression des notifications.'
            ], 500);
        }
    }
}