@extends('layouts.error-layout')

@section('content')
    <div class="text-center">
        <h1 class="text-9xl font-extrabold text-red-600 tracking-wider">500</h1>
        <h2 class="text-3xl font-bold text-gray-800 mt-4">Erreur interne du serveur</h2>
        <p class="text-gray-600 mt-2">Désolé, quelque chose s'est mal passé de notre côté.</p>
        <a href="{{ url('/') }}" class="mt-6 inline-block px-6 py-3 bg-red-600 text-white font-semibold rounded-lg shadow-md hover:bg-red-700 transition-colors duration-300">
            Retour à l'accueil
        </a>
    </div>
@endsection

