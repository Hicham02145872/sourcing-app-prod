<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Services\AuthLogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function __construct(
        protected \App\Services\ImageProcessingService $imageService
    ) {}

    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        // Update the user's profile information
        $request->user()->fill($request->validated());

        if ($request->hasFile('photo')) {
            $result = $this->imageService->compressAndStore(
                $request->file('photo'),
                'profile-photos',
                'public',
                500
            );

            if ($request->user()->profile_photo_path) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($request->user()->profile_photo_path);
            }

            $request->user()->profile_photo_path = $result->path;
        }
        // If the user is changing their email, reset the email verification status
        $oldEmail = $request->user()->email;
        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        // Log email change if changed
        if ($request->user()->wasChanged('email')) {
            app(AuthLogService::class)->logEmailChange($request->user(), $oldEmail);
            $request->user()->sendEmailVerificationNotification();
        }

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        // Delete the user's account
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();
        // Log out the user before deleting the account
        Auth::logout();
        // Delete the user record from the database
        $user->delete();
        // Invalidate the session and regenerate the CSRF token
        $request->session()->invalidate();
        // Regenerate the CSRF token
        $request->session()->regenerateToken();

        // Redirect to the homepage after account deletion
        return Redirect::to('/');
    }
}
