<x-guest-layout>
    <!-- En-tête -->
    <div class="text-center mb-8">
        <h2 class="text-2xl font-bold bg-gradient-to-r from-blue-600 to-cyan-600 bg-clip-text text-transparent">
            Connexion à SmartSource
        </h2>
        <p class="text-gray-500 mt-1 text-sm">Ravi de vous revoir 👋</p>
    </div>

    <!-- Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <!-- Formulaire -->
    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

        <!-- Email -->
        <div>
            <x-input-label for="email" :value="__('Adresse e-mail')" class="text-gray-700 font-medium" />
            <x-text-input id="email"
                class="block mt-1 w-full border-gray-200 rounded-lg focus:border-blue-500 focus:ring focus:ring-blue-200"
                type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Mot de passe -->
        <div>
            <x-input-label for="password" :value="__('Mot de passe')" class="text-gray-700 font-medium" />
            <x-text-input id="password"
                class="block mt-1 w-full border-gray-200 rounded-lg focus:border-blue-500 focus:ring focus:ring-blue-200"
                type="password" name="password" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Options -->
        <div class="flex items-center justify-between">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox"
                       class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500"
                       name="remember">
                <span class="ml-2 text-sm text-gray-600">{{ __('Se souvenir de moi') }}</span>
            </label>

            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-sm font-medium text-blue-600 hover:underline">
                    {{ __('Mot de passe oublié ?') }}
                </a>
            @endif
        </div>

        <!-- Bouton -->
        <div>
            <button type="submit"
                    class="w-full py-3 bg-gradient-to-r from-blue-600 to-cyan-600 text-white rounded-xl font-semibold hover:shadow-xl hover:scale-[1.02] transition-all duration-300">
                {{ __('Se connecter') }}
            </button>
        </div>
    </form>

    <!-- Séparateur -->
    <div class="my-6 border-t border-gray-200"></div>

    <!-- Lien inscription -->
    <p class="text-center text-gray-600 text-sm">
        Vous n'avez pas encore de compte ?
        <a href="{{ route('register') }}" class="font-medium text-blue-600 hover:underline">
            Créer un compte
        </a>
    </p>
</x-guest-layout>
