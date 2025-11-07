<x-app-layout>
    <div class="min-h-screen bg-gray-100 flex flex-col justify-center items-center">
        <div class="text-center">
            <h1 class="text-9xl font-extrabold text-yellow-500 tracking-wider">503</h1>
            <h2 class="text-3xl font-bold text-gray-800 mt-4">Service indisponible</h2>
            <p class="text-gray-600 mt-2">Désolé, nous sommes en maintenance. Veuillez réessayer plus tard.</p>
            <a href="{{ url('/') }}" class="mt-6 inline-block px-6 py-3 bg-yellow-500 text-white font-semibold rounded-lg shadow-md hover:bg-yellow-600 transition-colors duration-300">
                Retour à l'accueil
            </a>
        </div>
    </div>
</x-app-layout>
