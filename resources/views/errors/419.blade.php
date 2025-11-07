<x-app-layout>
    <div class="min-h-screen bg-gray-100 flex flex-col justify-center items-center">
        <div class="text-center">
            <h1 class="text-9xl font-extrabold text-blue-600 tracking-wider">419</h1>
            <h2 class="text-3xl font-bold text-gray-800 mt-4">Page expirée</h2>
            <p class="text-gray-600 mt-2">Désolé, votre session a expiré. Veuillez rafraîchir et réessayer.</p>
            <a href="{{ url()->previous() }}" class="mt-6 inline-block px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg shadow-md hover:bg-blue-700 transition-colors duration-300">
                Rafraîchir la page
            </a>
        </div>
    </div>
</x-app-layout>
