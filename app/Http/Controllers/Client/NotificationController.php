<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index(Request $request, string $locale)
    {
        $user = Auth::user();
        $notifications = $user->notifications()->paginate(15); // Adjust pagination as needed

        return response()->json([
            'count' => $user->unreadNotifications->count(),
            'notifications' => $notifications,
        ]);
    }

    public function markAsRead(Request $request, string $locale, string $notificationId)
    {
        $user = Auth::user();
        $notification = $user->notifications()->where('id', $notificationId)->first();

        if ($notification) {
            $notification->markAsRead();

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => 'Notification not found or not owned by user.'], 404);
    }
}
