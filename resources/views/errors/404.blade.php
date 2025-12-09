@extends('layouts.error-layout')

@section('content')
    <div class="text-center">
        <h1 class="text-9xl font-extrabold text-indigo-600 tracking-wider">404</h1>
        <h2 class="text-3xl font-bold text-gray-800 mt-4">Page non trouvée</h2>
        <p class="text-gray-600 mt-2">Désolé, la page que vous recherchez n'existe pas.</p>
        <a href="{{ url('/') }}" class="mt-6 inline-block px-6 py-3 bg-indigo-600 text-white font-semibold rounded-lg shadow-md hover:bg-indigo-700 transition-colors duration-300">
            Retour à l'accueil
        </a>
    </div>
@endsection

