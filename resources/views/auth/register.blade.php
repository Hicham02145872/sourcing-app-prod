<x-guest-layout>
    <!-- En-tête -->
    <div class="text-center mb-8">
        <h2 class="text-2xl font-bold bg-gradient-to-r from-blue-600 to-cyan-600 bg-clip-text text-transparent">
            Créer un compte SmartSource
        </h2>
        <p class="text-gray-500 mt-1 text-sm">Rejoignez notre communauté 🚀</p>
    </div>

    <!-- Formulaire -->
    <form method="POST" action="{{ route('register') }}" class="space-y-6">
        @csrf

        <!-- Nom complet -->
        <div>
            <x-input-label for="name" :value="__('Nom complet')" class="text-gray-700 font-medium" />
            <x-text-input id="name"
                class="block mt-1 w-full border-gray-200 bg-white text-gray-900 rounded-lg focus:border-blue-500 focus:ring focus:ring-blue-200"
                type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Adresse e-mail -->
        <div>
            <x-input-label for="email" :value="__('Adresse e-mail')" class="text-gray-700 font-medium" />
            <x-text-input id="email"
                class="block mt-1 w-full border-gray-200 bg-white text-gray-900 rounded-lg focus:border-blue-500 focus:ring focus:ring-blue-200"
                type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Mot de passe -->
        <div>
            <x-input-label for="password" :value="__('Mot de passe')" class="text-gray-700 font-medium" />
            <x-text-input id="password"
                class="block mt-1 w-full border-gray-200 bg-white text-gray-900 rounded-lg focus:border-blue-500 focus:ring focus:ring-blue-200"
                type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirmation du mot de passe -->
        <div>
            <x-input-label for="password_confirmation" :value="__('Confirmer le mot de passe')" class="text-gray-700 font-medium" />
            <x-text-input id="password_confirmation"
                class="block mt-1 w-full border-gray-200 bg-white text-gray-900 rounded-lg focus:border-blue-500 focus:ring focus:ring-blue-200"
                type="password" name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- Bouton d'inscription -->
        <div>
            <button type="submit"
                    class="w-full py-3 bg-gradient-to-r from-blue-600 to-cyan-600 text-white rounded-xl font-semibold hover:shadow-xl hover:scale-[1.02] transition-all duration-300">
                {{ __('Créer mon compte') }}
            </button>
        </div>
    </form>

    <!-- Séparateur -->
    <div class="my-6 border-t border-gray-200"></div>

    <!-- Lien vers la connexion -->
    <p class="text-center text-gray-600 text-sm">
        Vous avez déjà un compte ?
        <a href="{{ route('login') }}" class="font-medium text-blue-600 hover:underline">
            Se connecter
        </a>
    </p>
</x-guest-layout>
