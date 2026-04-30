<?php

namespace App\Listeners;

use App\Models\User;
use App\Notifications\ClientRegisteredForAdmins;
use Illuminate\Auth\Events\Registered;

class NotifyAdminsOfClientRegistration
{
    public function handle(Registered $event): void
    {
        $user = $event->user;
        if (! $user instanceof User || $user->role !== 'client') {
            return;
        }

        $admins = User::query()
            ->whereIn('role', ['admin', 'super_admin'])
            ->get();

        foreach ($admins as $admin) {
            $admin->notify(new ClientRegisteredForAdmins($user));
        }
    }
}
