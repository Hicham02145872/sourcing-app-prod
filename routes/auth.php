<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;

$localePattern = 'eng|fr|ar';

Route::middleware('guest')->group(function () use ($localePattern) {
    Route::get('{locale?}/register', [RegisteredUserController::class, 'create'])
        ->where('locale', $localePattern)
        ->name('register');

    Route::post('{locale?}/register', [RegisteredUserController::class, 'store'])
        ->where('locale', $localePattern)
        ->middleware('throttle:5,1');

    Route::get('{locale?}/login', [AuthenticatedSessionController::class, 'create'])
        ->where('locale', $localePattern)
        ->name('login');

    Route::post('{locale?}/login', [AuthenticatedSessionController::class, 'store'])
        ->where('locale', $localePattern)
        ->middleware('throttle:5,1');

    Route::get('{locale?}/forgot-password', [PasswordResetLinkController::class, 'create'])
        ->where('locale', $localePattern)
        ->name('password.request');

    Route::post('{locale?}/forgot-password', [PasswordResetLinkController::class, 'store'])
        ->where('locale', $localePattern)
        ->middleware('throttle:5,1')
        ->name('password.email');

    Route::get('{locale?}/reset-password/{token}', [NewPasswordController::class, 'create'])
        ->where('locale', $localePattern)
        ->name('password.reset');

    Route::post('{locale?}/reset-password', [NewPasswordController::class, 'store'])
        ->where('locale', $localePattern)
        ->middleware('throttle:3,1')
        ->name('password.store');
});

Route::middleware('auth')->group(function () use ($localePattern) {
    Route::get('{locale?}/verify-email', [\App\Http\Controllers\Auth\EmailVerificationPromptController::class, '__invoke'])
        ->where('locale', $localePattern)
        ->name('verification.notice');

    Route::get('{locale?}/verify-email/{id}/{hash}', VerifyEmailController::class)
        ->where('locale', $localePattern)
        ->middleware(['signed', 'throttle:6,10'])
        ->name('verification.verify');

    Route::post('{locale?}/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->where('locale', $localePattern)
        ->middleware('throttle:6,10')
        ->name('verification.send');

    Route::get('{locale?}/confirm-password', [ConfirmablePasswordController::class, 'show'])
        ->where('locale', $localePattern)
        ->name('password.confirm');

    Route::post('{locale?}/confirm-password', [ConfirmablePasswordController::class, 'store'])
        ->where('locale', $localePattern);

    Route::put('{locale?}/password', [PasswordController::class, 'update'])
        ->where('locale', $localePattern)
        ->middleware('throttle:5,1')
        ->name('password.update');

    Route::post('{locale?}/logout', [AuthenticatedSessionController::class, 'destroy'])
        ->where('locale', $localePattern)
        ->name('logout');
});
