<x-app-layout>
    <div class="min-h-screen bg-gray-100 flex flex-col justify-center items-center">
        <div class="text-center">
            <h1 class="text-9xl font-extrabold text-purple-600 tracking-wider">403</h1>
            <h2 class="text-3xl font-bold text-gray-800 mt-4">Accès refusé</h2>
            <p class="text-gray-600 mt-2">Désolé, vous n'êtes pas autorisé à accéder à cette page.</p>
            <a href="{{ url('/') }}" class="mt-6 inline-block px-6 py-3 bg-purple-600 text-white font-semibold rounded-lg shadow-md hover:bg-purple-700 transition-colors duration-300">
                Retour à l'accueil
            </a>
        </div>
    </div>
</x-app-layout>
