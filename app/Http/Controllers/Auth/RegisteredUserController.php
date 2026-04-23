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
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        $urlLocale = User::normalizeUrlLocale(Session::get('locale'));
        $defaultPhoneIso = match ($urlLocale) {
            'ar' => 'MA',
            'fr' => 'FR',
            default => 'US',
        };

        $phoneCountries = collect(config('phone_dial_codes'))
            ->map(fn (array $data, string $iso) => ['iso' => $iso, 'dial' => $data['dial'], 'name' => $data['name']])
            ->sortBy('name', SORT_NATURAL | SORT_FLAG_CASE)
            ->values()
            ->all();

        return view('auth.register', [
            'phoneCountries' => $phoneCountries,
            'defaultPhoneIso' => $defaultPhoneIso,
        ]);
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
            'phone_country_iso' => ['required', 'string', 'size:2', Rule::in(array_keys(config('phone_dial_codes')))],
            'phone' => ['required', 'string', 'max:30'],
            'password' => [
                'required',
                'confirmed',
                'string',
                'min:8',
                function (string $attribute, mixed $value, \Closure $fail): void {
                    $password = (string) $value;
                    $isStrong = preg_match('/[a-z]/', $password)
                        && preg_match('/[A-Z]/', $password)
                        && preg_match('/\d/', $password)
                        && preg_match('/[^a-zA-Z0-9]/', $password);

                    if (! $isStrong) {
                        $fail(__('Strong password validation message'));
                    }
                },
            ],
        ]);

        $nationalDigits = preg_replace('/\D+/', '', $request->phone);
        if (strlen($nationalDigits) < 6 || strlen($nationalDigits) > 15) {
            throw ValidationException::withMessages([
                'phone' => [__('Phone must contain between 6 and 15 digits (without country code).')],
            ]);
        }

        $dial = config('phone_dial_codes')[$request->phone_country_iso]['dial'];
        $fullPhone = '+'.$dial.$nationalDigits;

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $fullPhone,
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
