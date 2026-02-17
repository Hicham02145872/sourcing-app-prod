<x-app-layout>
    <div class="min-h-screen bg-slate-50/80 font-sans text-slate-900 pb-12">
        <div class="bg-white border-b border-slate-200 shadow-sm sticky top-0 z-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between h-auto md:h-16 py-4 md:py-0 gap-4">
                    <div class="flex items-center gap-2">
                        <span class="inline-flex items-center justify-center h-8 w-8 rounded bg-orange-100 text-orange-600">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="7" height="9" x="3" y="3" rx="1"/><rect width="7" height="5" x="14" y="3" rx="1"/><rect width="7" height="9" x="14" y="12" rx="1"/><rect width="7" height="5" x="3" y="16" rx="1"/></svg>
                        </span>
                        <div>
                            <h1 class="text-lg font-bold text-slate-900 leading-tight">{{ __('Import frais d\'expédition') }}</h1>
                            <p class="text-xs text-slate-500 hidden sm:block">{{ __('Air Freight DDP Table → frais dans l\'app') }}</p>
                        </div>
                    </div>
                    <a href="{{ route('admin.shipping-fees.index') }}" class="text-xs font-bold text-orange-600 hover:text-orange-700 uppercase tracking-wider">
                        ← {{ __('Retour aux frais') }}
                    </a>
                </div>
            </div>
        </div>

        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">
            @if(session('import_result'))
                @php $r = session('import_result'); @endphp
                <div class="bg-white rounded-lg border border-slate-200 shadow-sm p-6 space-y-4">
                    <h2 class="text-sm font-bold text-slate-900 uppercase tracking-tight">{{ __('Résultat de l\'import') }}</h2>
                    <div class="flex gap-6">
                        <div class="text-emerald-600 font-bold">{{ $r['imported'] }} {{ __('lignes importées') }}</div>
                        @if($r['skipped'] > 0)
                            <div class="text-slate-500">{{ $r['skipped'] }} {{ __('lignes ignorées') }}</div>
                        @endif
                    </div>
                    @if(!empty($r['errors']))
                        <div class="border border-amber-200 bg-amber-50 rounded-lg p-4">
                            <p class="text-xs font-bold text-amber-800 uppercase mb-2">{{ __('Avertissements / erreurs') }}</p>
                            <ul class="text-sm text-amber-900 space-y-1 list-disc list-inside">
                                @foreach(array_slice($r['errors'], 0, 20) as $err)
                                    <li>{{ $err }}</li>
                                @endforeach
                                @if(count($r['errors']) > 20)
                                    <li class="text-amber-700">… et {{ count($r['errors']) - 20 }} autres.</li>
                                @endif
                            </ul>
                        </div>
                    @endif
                </div>
            @endif

            <div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                    <h3 class="text-sm font-bold text-slate-900 uppercase tracking-tight">{{ __('Importer un fichier Excel') }}</h3>
                    <p class="text-xs text-slate-500 mt-1">{{ __('Format attendu : type Air Freight DDP (colonnes Pays, Type marchandise, Prix au kg, Délai en jours).') }}</p>
                </div>
                <form action="{{ route('admin.shipping-fees.import.run') }}" method="post" enctype="multipart/form-data" class="p-6 space-y-6">
                    @csrf
                    <div>
                        <label for="file" class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">{{ __('Fichier .xlsx ou .xls') }}</label>
                        <input type="file" name="file" id="file" accept=".xlsx,.xls" required
                               class="block w-full text-sm text-slate-700 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:bg-orange-50 file:text-orange-700 file:font-bold file:uppercase file:tracking-wider file:text-xs">
                        @error('file')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="flex items-center gap-4">
                        <button type="submit" class="px-6 py-2.5 bg-orange-600 hover:bg-orange-700 text-white text-xs font-bold uppercase tracking-widest rounded-lg transition-colors">
                            {{ __('Importer') }}
                        </button>
                        <a href="{{ route('admin.shipping-fees.index') }}" class="text-slate-500 hover:text-slate-700 text-xs font-bold uppercase">{{ __('Annuler') }}</a>
                    </div>
                </form>
            </div>

            <div class="bg-slate-900 text-slate-300 rounded-lg border border-slate-800 p-6 font-mono text-xs">
                <p class="text-slate-400 font-bold uppercase tracking-wider mb-2">{{ __('Colonnes reconnues (en-têtes du fichier)') }}</p>
                <ul class="space-y-1 list-disc list-inside">
                    <li><strong>Pays</strong> : country, pays, destination, country_name, code</li>
                    <li><strong>Type marchandise</strong> : item_style, type, category, item_type, style</li>
                    <li><strong>Prix au kg</strong> : price_per_kg, price, prix, rate, prix_au_kg</li>
                    <li><strong>Délai (optionnel)</strong> : estimation_days, days, delai, estimation</li>
                </ul>
                <p class="mt-3 text-slate-500">{{ __('Les pays doivent exister dans l\'app (nom ou code identique). Transport = Air.') }}</p>
            </div>
        </div>
    </div>
</x-app-layout>
