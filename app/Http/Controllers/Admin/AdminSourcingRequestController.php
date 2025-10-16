<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SourcingRequest;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Http;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use App\Notifications\SourcingRequestStatusUpdated;

class AdminSourcingRequestController extends Controller
{
    /**
     * Display a listing of all sourcing requests.
     */
    public function index(): View
    {
        $sourcingRequests = SourcingRequest::with('category', 'user', 'destinations.country', 'destinations.service')->get();
        return view('admin.sourcing-requests.index', compact('sourcingRequests'));
    }

    /**
     * Display the specified sourcing request.
     */
    public function show(SourcingRequest $sourcingRequest): View
    {
        $sourcingRequest->load('category', 'user', 'destinations.country', 'destinations.service');
        return view('admin.sourcing-requests.show', compact('sourcingRequest'));
    }

    /**
     * Update the status of the specified sourcing request and send FCM notification (API v1).
     */
   public function updateStatus(Request $request, SourcingRequest $sourcingRequest): RedirectResponse
{
    $validated = $request->validate([
        'status' => 'required|in:pending,handling,completed,cancelled',
    ]);

    // Mise à jour du statut de la demande
    $sourcingRequest->update([
        'status' => $validated['status'],
    ]);

    // Envoyer la notification de base de données Laravel
    $sourcingRequest->user->notify(new SourcingRequestStatusUpdated($sourcingRequest));

    // Invalider le cache des notifications de l'utilisateur
    Cache::forget("user_notifications_{$sourcingRequest->user->id}");

    // Envoi de notification FCM si le token existe
    $fcmToken = $sourcingRequest->user?->fcm_token;

    if ($fcmToken) {
        try {
            $factory = (new Factory)->withServiceAccount(env('GOOGLE_APPLICATION_CREDENTIALS'));
            $messaging = $factory->createMessaging();

            $notification = Notification::create(
                'Mise à jour de votre demande de sourcing',
                'Votre demande #' . $sourcingRequest->id . ' a été mise à jour au statut : ' . $validated['status']
            );

            $data = [
                'sourcing_request_id' => (string) $sourcingRequest->id,
                'status' => (string) $validated['status'],
                'click_action' => route('client.sourcing-requests.show', $sourcingRequest),
            ];

            $message = CloudMessage::withTarget('token', $fcmToken)
                ->withNotification($notification)
                ->withData($data);

            $messaging->send($message);

            Log::info('Firebase Notification sent successfully', [
                'token' => $fcmToken,
                'payload' => $message->jsonSerialize(),
            ]);
        } catch (\Kreait\Firebase\Exception\Messaging\InvalidMessage $e) {
            Log::error('Firebase Notification Error: Invalid message', ['exception' => $e->getMessage()]);
        } catch (\Kreait\Firebase\Exception\Messaging\NotFound $e) {
            Log::error('Firebase Notification Error: Token not found', ['token' => $fcmToken, 'exception' => $e->getMessage()]);
        } catch (\Exception $e) {
            Log::error('Firebase Notification Exception: ' . $e->getMessage());
        }
    }

    return redirect()
        ->route('admin.sourcing-requests.show', $sourcingRequest)
        ->with('status', 'Sourcing request status updated successfully!');
}

}
