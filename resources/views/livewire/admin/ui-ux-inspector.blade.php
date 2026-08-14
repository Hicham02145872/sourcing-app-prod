<div class="flex flex-col h-screen bg-slate-50 overflow-hidden">
    <header class="flex-none bg-slate-900 border-b border-slate-800 px-6 py-4">
        <div class="flex items-center justify-between flex-wrap gap-3">
            <div class="flex items-center gap-4">
                <div class="bg-violet-600 px-2 py-1 text-xs font-black text-white tracking-tighter uppercase">UX</div>
                <h1 class="text-sm font-bold text-white uppercase tracking-widest">UI/UX Inspector / Gemini</h1>
            </div>
            <div class="flex items-center gap-4 flex-wrap">
                @php
                    $apiKeyConfigured = ! is_null(\App\Services\UiUxInspectionService::effectiveApiKey());
                @endphp
                <div class="text-[10px] font-bold text-slate-500 uppercase flex items-center gap-2">
                    <span class="w-2 h-2 {{ $apiKeyConfigured ? 'bg-emerald-500' : 'bg-amber-500' }}"></span>
                    {{ $apiKeyConfigured ? 'Gemini API configurée' : 'Clé Gemini manquante (Dev Console)' }}
                </div>
                <a href="{{ route('admin.dev-dashboard') }}" class="text-[10px] font-bold text-indigo-400 uppercase border border-indigo-400/30 px-2 py-1 hover:bg-indigo-400 hover:text-slate-900 transition-none">
                    ← Dev Console
                </a>
            </div>
        </div>
    </header>

    <main class="flex-1 overflow-y-auto custom-scrollbar">
        <div class="max-w-7xl mx-auto p-8 space-y-8">
            {{-- Form --}}
            <form wire:submit="inspect" class="bg-white border border-slate-200 p-6 space-y-4">
                <div class="grid grid-cols-12 gap-4 items-end">
                    <div class="col-span-12 lg:col-span-6">
                        <label for="ux-url" class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1.5">URL à analyser</label>
                        <input id="ux-url" type="url" wire:model="url" placeholder="https://..." required
                            class="w-full border border-slate-300 px-3 py-2.5 text-sm font-mono focus:border-violet-500 focus:ring-1 focus:ring-violet-500 rounded-none" />
                        @if(!empty($errors) && $errors->has('url'))
                            <p class="text-xs text-red-600 mt-1">{{ $errors->first('url') }}</p>
                        @endif
                    </div>
                    <div class="col-span-6 lg:col-span-2">
                        <label for="ux-viewport" class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1.5">Viewport</label>
                        <select id="ux-viewport" wire:model="viewport" class="w-full border border-slate-300 px-3 py-2.5 text-sm bg-white rounded-none">
                            <option value="desktop">Desktop · 1440px</option>
                            <option value="tablet">Tablette · 768px</option>
                            <option value="mobile">Mobile · 390px</option>
                        </select>
                    </div>
                    <div class="col-span-6 lg:col-span-2">
                        <label for="ux-lang" class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1.5">Langue de l'analyse</label>
                        <select id="ux-lang" wire:model="analysisLanguage" class="w-full border border-slate-300 px-3 py-2.5 text-sm bg-white rounded-none">
                            <option value="fr">Français</option>
                            <option value="en">English</option>
                            <option value="ar">العربية</option>
                        </select>
                    </div>
                    <div class="col-span-12 lg:col-span-2">
                        <button type="submit" wire:loading.attr="disabled" wire:target="inspect"
                            class="w-full bg-violet-600 hover:bg-violet-700 disabled:opacity-50 text-white px-6 py-2.5 text-xs font-black uppercase tracking-widest transition-none">
                            {{ $running ? 'Analyse…' : 'Inspecter' }}
                        </button>
                    </div>
                </div>
                <p class="text-[11px] text-slate-400">
                    Capture la page via Playwright (Chromium headless), puis envoie la capture au modèle
                    <code class="font-mono bg-slate-100 px-1">{{ config('services.gemini.model') }}</code> de Gemini pour une revue UI/UX senior.
                </p>
            </form>

            @if($running)
                <div class="bg-white border border-slate-200 p-10 flex flex-col items-center justify-center gap-4">
                    <div class="w-10 h-10 border-4 border-violet-200 border-t-violet-600 rounded-full animate-spin"></div>
                    <p class="text-sm font-bold text-slate-600 uppercase tracking-widest">Capture + analyse en cours…</p>
                    <p class="text-xs text-slate-400">Cela peut prendre 30 à 90 secondes.</p>
                </div>
            @endif

            @if($error)
                <div class="bg-red-50 border border-red-300 text-red-700 p-4 text-sm">
                    <span class="font-black uppercase tracking-widest text-xs">Erreur · </span>{{ $error }}
                </div>
            @endif

            @if($result && !$running)
                <div class="space-y-6">
                    {{-- Score global --}}
                    <div class="bg-white border border-slate-200 p-6 grid grid-cols-12 gap-6 items-center">
                        <div class="col-span-12 lg:col-span-3 flex flex-col items-center justify-center gap-2">
                            <div class="relative w-36 h-36">
                                @php
                                    $score = max(0, min(100, (int) ($result['overall_score'] ?? 0)));
                                    $angle = $score / 100 * 360;
                                    $color = $score >= 80 ? '#10b981' : ($score >= 60 ? '#f59e0b' : '#ef4444');
                                @endphp
                                <svg viewBox="0 0 120 120" class="w-full h-full -rotate-90">
                                    <circle cx="60" cy="60" r="52" fill="none" stroke="#e2e8f0" stroke-width="12"/>
                                    <circle cx="60" cy="60" r="52" fill="none" stroke="{{ $color }}" stroke-width="12"
                                        stroke-linecap="round" stroke-dasharray="{{ $angle }} 360"/>
                                </svg>
                                <div class="absolute inset-0 flex flex-col items-center justify-center">
                                    <span class="text-4xl font-black text-slate-900">{{ $score }}</span>
                                    <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">/100</span>
                                </div>
                            </div>
                            @if(!empty($result['grade']))
                                <span class="text-xs font-black uppercase tracking-widest px-3 py-1 {{ $score >= 80 ? 'bg-emerald-100 text-emerald-700' : ($score >= 60 ? 'bg-amber-100 text-amber-700' : 'bg-red-100 text-red-700') }}">{{ $result['grade'] }}</span>
                            @endif
                        </div>
                        <div class="col-span-12 lg:col-span-9 space-y-4">
                            <div>
                                <h2 class="text-xs font-black text-slate-500 uppercase tracking-widest mb-1">Synthèse</h2>
                                <p class="text-sm text-slate-700 leading-relaxed">{{ $result['summary'] ?? '' }}</p>
                            </div>
                            @if(!empty($result['strengths']))
                                <div>
                                    <h3 class="text-[10px] font-black text-emerald-600 uppercase tracking-widest mb-2">Points forts</h3>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($result['strengths'] as $strength)
                                            <span class="text-xs bg-emerald-50 border border-emerald-200 text-emerald-700 px-2.5 py-1">{{ $strength }}</span>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="grid grid-cols-12 gap-6">
                        {{-- Critères --}}
                        <div class="col-span-12 lg:col-span-7 bg-white border border-slate-200 p-6 space-y-4">
                            <h3 class="text-xs font-black text-slate-500 uppercase tracking-widest">Critères d'évaluation</h3>
                            @foreach(collect($result['criteria'] ?? [])->take(8) as $criterion)
                                <div class="space-y-1.5">
                                    <div class="flex items-center justify-between text-xs">
                                        <span class="font-bold text-slate-700">{{ $criterion['name'] ?? '' }}</span>
                                        <span class="font-black text-slate-900">{{ $criterion['score'] ?? 0 }}<span class="text-slate-400 font-normal">/{{ $criterion['max'] ?? 100 }}</span></span>
                                    </div>
                                    <div class="h-2 bg-slate-100 overflow-hidden">
                                        <div class="h-full {{ (($criterion['score'] ?? 0) >= 80) ? 'bg-emerald-500' : ((($criterion['score'] ?? 0) >= 60) ? 'bg-amber-500' : 'bg-red-500') }}"
                                            style="width: {{ max(0, min(100, (($criterion['score'] ?? 0) / max(1, $criterion['max'] ?? 100)) * 100)) }}%"></div>
                                    </div>
                                    @if(!empty($criterion['comment']))
                                        <p class="text-[11px] text-slate-500 leading-relaxed">{{ $criterion['comment'] }}</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>

                        {{-- Capture --}}
                        <div class="col-span-12 lg:col-span-5 bg-white border border-slate-200 p-6 space-y-4">
                            <h3 class="text-xs font-black text-slate-500 uppercase tracking-widest">Capture analysée</h3>
                            @if($screenshotUrl)
                                <a href="{{ $screenshotUrl }}" target="_blank" class="block border border-slate-200 overflow-hidden">
                                    <img src="{{ $screenshotUrl }}" alt="Capture de la page analysée" class="w-full h-64 object-cover object-top" />
                                </a>
                            @endif
                            <dl class="grid grid-cols-2 gap-3 text-xs">
                                <div class="space-y-0.5">
                                    <dt class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Status HTTP</dt>
                                    <dd class="font-mono font-bold {{ ($capture['status'] ?? null) >= 400 ? 'text-red-600' : 'text-emerald-600' }}">{{ $capture['status'] ?? 'n/a' }}</dd>
                                </div>
                                <div class="space-y-0.5">
                                    <dt class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Titre</dt>
                                    <dd class="text-slate-700 truncate" title="{{ $capture['page']['title'] ?? '' }}">{{ $capture['page']['title'] ?? '—' }}</dd>
                                </div>
                                <div class="space-y-0.5">
                                    <dt class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Hauteur page</dt>
                                    <dd class="text-slate-700">{{ $capture['page']['fullHeight'] ?? 0 }} px</dd>
                                </div>
                                <div class="space-y-0.5">
                                    <dt class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Liens visibles</dt>
                                    <dd class="text-slate-700">{{ $capture['page']['visibleLinks'] ?? 0 }}</dd>
                                </div>
                                <div class="space-y-0.5">
                                    <dt class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Erreurs console</dt>
                                    <dd class="font-black {{ !empty($capture['consoleMessages']) ? 'text-red-600' : 'text-emerald-600' }}">{{ count($capture['consoleMessages'] ?? []) }}</dd>
                                </div>
                                <div class="space-y-0.5">
                                    <dt class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Requêtes échouées</dt>
                                    <dd class="font-black {{ !empty($capture['failedRequests']) ? 'text-red-600' : 'text-emerald-600' }}">{{ count($capture['failedRequests'] ?? []) }}</dd>
                                </div>
                            </dl>
                            @if(!empty($capture['navigationError']))
                                <p class="text-[11px] text-red-600 bg-red-50 border border-red-200 p-2">{{ $capture['navigationError'] }}</p>
                            @endif
                            @if(!empty($capture['consoleMessages']))
                                <div class="max-h-32 overflow-y-auto border border-slate-200 bg-slate-50 p-2 space-y-1">
                                    @foreach($capture['consoleMessages'] as $msg)
                                        <p class="text-[10px] font-mono text-slate-500 truncate"><span class="uppercase text-red-500 font-black">{{ $msg['type'] }}</span> {{ $msg['text'] }}</p>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Findings --}}
                    <div class="bg-white border border-slate-200 p-6 space-y-4">
                        <h3 class="text-xs font-black text-slate-500 uppercase tracking-widest">Constats détaillés ({{ count($result['findings'] ?? []) }})</h3>
                        @php
                            $order = ['critical' => 0, 'major' => 1, 'minor' => 2, 'suggestion' => 3];
                            $badges = [
                                'critical' => 'bg-red-600 text-white',
                                'major' => 'bg-orange-500 text-white',
                                'minor' => 'bg-amber-400 text-slate-900',
                                'suggestion' => 'bg-slate-200 text-slate-700',
                            ];
                            $findings = collect($result['findings'] ?? [])->sortBy(fn ($f) => $order[strtolower($f['severity'] ?? '')] ?? 4);
                        @endphp
                        @forelse($findings as $finding)
                            <div class="border border-slate-200 p-4 space-y-2">
                                <div class="flex items-start justify-between gap-4">
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <span class="text-[9px] font-black uppercase tracking-widest px-2 py-0.5 {{ $badges[strtolower($finding['severity'] ?? '')] ?? 'bg-slate-200 text-slate-700' }}">{{ $finding['severity'] ?? 'n/a' }}</span>
                                        <span class="text-[9px] font-black uppercase tracking-widest text-violet-600 bg-violet-50 border border-violet-200 px-2 py-0.5">{{ $finding['area'] ?? 'UI' }}</span>
                                        <h4 class="text-sm font-black text-slate-900">{{ $finding['title'] ?? '' }}</h4>
                                    </div>
                                </div>
                                <p class="text-xs text-slate-600 leading-relaxed">{{ $finding['description'] ?? '' }}</p>
                                @if(!empty($finding['heuristic']))
                                    <p class="text-[11px] text-slate-400"><span class="font-black uppercase">Heuristique :</span> {{ $finding['heuristic'] }}</p>
                                @endif
                                @if(!empty($finding['recommendation']))
                                    <div class="bg-violet-50 border border-violet-100 p-3">
                                        <p class="text-[10px] font-black text-violet-500 uppercase tracking-widest mb-1">Recommandation</p>
                                        <p class="text-xs text-slate-700 leading-relaxed">{{ $finding['recommendation'] }}</p>
                                    </div>
                                @endif
                            </div>
                        @empty
                            <p class="text-sm text-slate-400">Aucun constat.</p>
                        @endforelse
                    </div>

                    <div class="grid grid-cols-12 gap-6">
                        {{-- Accessibilité --}}
                        <div class="col-span-12 lg:col-span-6 bg-white border border-slate-200 p-6 space-y-2">
                            <h3 class="text-xs font-black text-slate-500 uppercase tracking-widest">Accessibilité</h3>
                            @forelse($result['accessibility_issues'] ?? [] as $issue)
                                <p class="text-xs text-slate-700 flex gap-2"><span class="text-red-500">•</span>{{ $issue }}</p>
                            @empty
                                <p class="text-sm text-slate-400">Aucun problème signalé.</p>
                            @endforelse
                        </div>

                        {{-- Conversion --}}
                        <div class="col-span-12 lg:col-span-6 bg-white border border-slate-200 p-6 space-y-2">
                            <h3 class="text-xs font-black text-slate-500 uppercase tracking-widest">Opportunités de conversion</h3>
                            @forelse($result['conversion_opportunities'] ?? [] as $opportunity)
                                <p class="text-xs text-slate-700 flex gap-2"><span class="text-emerald-500">•</span>{{ $opportunity }}</p>
                            @empty
                                <p class="text-sm text-slate-400">Aucune opportunité signalée.</p>
                            @endforelse
                        </div>
                    </div>

                    {{-- Recommendations --}}
                    <div class="bg-white border border-slate-200 p-6 space-y-4">
                        <h3 class="text-xs font-black text-slate-500 uppercase tracking-widest">Plan d'action priorisé</h3>
                        @php
                            $priorityOrder = ['high' => 0, 'medium' => 1, 'low' => 2];
                            $recommendations = collect($result['recommendations'] ?? [])->sortBy(fn ($r) => $priorityOrder[strtolower($r['priority'] ?? '')] ?? 3);
                        @endphp
                        @forelse($recommendations as $recommendation)
                            <div class="grid grid-cols-12 gap-3 border-t border-slate-100 pt-3 items-start">
                                <div class="col-span-12 sm:col-span-7">
                                    <p class="text-sm font-bold text-slate-900">{{ $recommendation['action'] ?? '' }}</p>
                                    @if(!empty($recommendation['impact']))
                                        <p class="text-[11px] text-slate-500 mt-0.5">Impact : {{ $recommendation['impact'] }}</p>
                                    @endif
                                </div>
                                <div class="col-span-12 sm:col-span-5 flex gap-2 justify-start sm:justify-end">
                                    <span class="text-[9px] font-black uppercase tracking-widest px-2 py-1 {{ strtolower($recommendation['priority'] ?? '') === 'high' ? 'bg-red-100 text-red-700' : (strtolower($recommendation['priority'] ?? '') === 'medium' ? 'bg-amber-100 text-amber-700' : 'bg-slate-100 text-slate-600') }}">Priorité {{ $recommendation['priority'] ?? '' }}</span>
                                    @if(!empty($recommendation['effort']))
                                        <span class="text-[9px] font-black uppercase tracking-widest px-2 py-1 bg-slate-100 text-slate-600">Effort {{ $recommendation['effort'] }}</span>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-slate-400">Aucune recommandation.</p>
                        @endforelse
                    </div>
                </div>
            @endif
        </div>
    </main>
</div>
