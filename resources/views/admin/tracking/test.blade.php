<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Test Tracking ITDIDA') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <form method="POST" action="{{ route('admin.tracking.test.run') }}" class="mb-6">
                        @csrf
                        <div class="flex gap-4 items-end">
                            <div class="flex-grow">
                                <x-input-label for="tracking_number" :value="__('Numéro de suivi')" />
                                <x-text-input id="tracking_number" class="block mt-1 w-full" type="text" name="tracking_number" :value="old('tracking_number', 'K0121112B')" required autofocus />
                                <x-input-error :messages="$errors->get('tracking_number')" class="mt-2" />
                            </div>
                            
                            <x-primary-button>
                                {{ __('Lancer le Test') }}
                            </x-primary-button>
                        </div>
                        <p class="text-sm text-gray-500 mt-2">Note: Le test peut prendre 10 à 20 secondes (lancement du navigateur headless).</p>
                    </form>

                    @if (session('result'))
                        <div class="mt-8 border-t pt-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Résultats</h3>
                            
                                                        @php
                                $result = session('result');
                            @endphp

                            @if ($result['success'] ?? false)
                                <div class="bg-green-50 border border-green-200 rounded-md p-4 mb-4">
                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <h3 class="text-sm font-medium text-green-800">Succès</h3>
                                            <div class="mt-2 text-sm text-green-700">
                                                <p>Statut Actuel : <strong>{{ $result['current_status'] }}</strong></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Étape</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut (CN)</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut (FR)</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Référence</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @foreach ($result['events'] as $event)
                                                <tr>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $event['date'] }}</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $event['step'] }}</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $event['status'] }}</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-blue-600">{{ $event['status_fr'] }}</td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $event['reference'] }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="bg-red-50 border border-red-200 rounded-md p-4">
                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <h3 class="text-sm font-medium text-red-800">Erreur</h3>
                                            <div class="mt-2 text-sm text-red-700">
                                                {{ $result['error'] ?? 'Erreur inconnue' }}
                                            </div>
                                            @if(isset($result['raw_output']))
                                                <div class="mt-4 p-2 bg-gray-800 text-white font-mono text-xs rounded">
                                                    {{ Str::limit($result['raw_output'], 500) }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <div class="mt-8">
                                <h4 class="text-sm font-medium text-gray-500">Données Brutes (JSON)</h4>
                                <pre class="bg-gray-100 p-4 rounded mt-2 text-xs overflow-auto max-h-60">{{ json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
