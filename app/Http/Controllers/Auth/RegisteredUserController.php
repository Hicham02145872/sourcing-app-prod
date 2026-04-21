<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\AuthLogService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        Log::info('RegisteredUserController@store method called.');

        $urlLocale = User::normalizeUrlLocale($request->route('locale') ?: Session::get('locale'));
        Session::put('locale', $urlLocale);
        App::setLocale($urlLocale === 'eng' ? 'en' : $urlLocale);

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'phone' => ['required', 'string', 'max:255'],
            'password' => [
                'required',
                'confirmed',
                Rules\Password::defaults()
                    ->min(8)
                    ->mixedCase()
                    ->numbers()
                    ->symbols(),
            ],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'preferred_locale' => $urlLocale,
        ]);

        event(new Registered($user));
        Log::info('Registered event dispatched for user: '.$user->id);

        // Log registration
        app(AuthLogService::class)->logRegistration($user);

        Auth::login($user);

        if (! $user->hasVerifiedEmail()) {
            return redirect(route('verification.notice', ['locale' => $urlLocale]));
        }

        return redirect(route('dashboard', absolute: false));
    }
}
