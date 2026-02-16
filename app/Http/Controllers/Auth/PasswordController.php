<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\AuthLogService;
use App\Services\SuspiciousActivityService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class PasswordController extends Controller
{
    /**
     * Update the user's password.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password' => [
                'required',
                Password::defaults()
                    ->min(8)
                    ->mixedCase()
                    ->numbers()
                    ->symbols(),
                'confirmed',
            ],
        ]);

        $user = $request->user();
        $ip = $request->ip();

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        // Log password change
        app(AuthLogService::class)->logPasswordChange($user);

        // Check for suspicious activity
        app(SuspiciousActivityService::class)->checkSuspiciousPasswordChange($user, $ip);

        return back()->with('status', 'password-updated');
    }
}
