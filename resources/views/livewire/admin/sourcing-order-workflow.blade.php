<div class="space-y-6">
    <!-- 1. Order Status & Workflow -->
    <div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
            <h3 class="text-sm font-semibold text-slate-900 flex items-center gap-2">
                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ __('Workflow & Statut') }}
            </h3>
            <span class="text-xs text-slate-500">{{ __('Dernière maj:') }} {{ $sourcingOrder->updated_at->format('d/m/Y H:i') }}</span>
        </div>
        <div class="p-6">
            <div class="flex flex-col sm:flex-row gap-4 items-end">
                <div class="w-full sm:flex-1">
                    <label for="status" class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1">{{ __('Mettre à jour le statut') }}</label>
                    <select wire:model="status" id="status" class="w-full px-3 py-2 border border-slate-300 rounded text-sm focus:ring-1 focus:ring-slate-900 focus:border-slate-900 bg-slate-50">
                        @foreach (App\Models\SourcingOrder::STATUSES as $statusOption)
                            <option value="{{ $statusOption }}">
                                {{ ucfirst(str_replace('_', ' ', $statusOption)) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <button wire:click="updateStatus" wire:loading.attr="disabled" class="w-full sm:w-auto px-4 py-2 bg-slate-900 hover:bg-slate-800 text-white text-sm font-medium rounded transition-colors shadow-sm h-[38px] flex items-center justify-center gap-2">
                    <span wire:loading.remove wire:target="updateStatus">{{ __('Mettre à jour') }}</span>
                    <span wire:loading wire:target="updateStatus">{{ __('Action...') }}</span>
                    <svg wire:loading wire:target="updateStatus" class="animate-spin h-4 w-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                </button>
            </div>
            
            @if(auth()->user()->isSuperAdmin())
                <div class="mt-6 pt-6 border-t border-slate-100">
                    <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-2">{{ __('Assignation (Super Admin)') }}</label>
                    <div class="flex items-center gap-3">
                        <div class="flex-1">
                            <select wire:change="assignTo($event.target.value)" class="w-full px-3 py-2 border border-slate-300 rounded text-sm focus:ring-1 focus:ring-orange-500 focus:border-orange-500 bg-white">
                                <option value="">{{ __('Non assigné') }}</option>
                                @foreach($admins as $admin)
                                    <option value="{{ $admin->id }}" {{ $sourcingOrder->assigned_to_admin_id == $admin->id ? 'selected' : '' }}>
                                        {{ $admin->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @if($sourcingOrder->assigned_to_admin_id)
                            <div class="flex items-center gap-2 px-3 py-2 bg-emerald-50 border border-emerald-100 rounded text- emerald-700 text-xs">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                <span class="font-semibold">{{ $sourcingOrder->assignedAdmin->name }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            @if(auth()->user()->isSuperAdmin() || $sourcingOrder->assigned_to_admin_id === auth()->id())
                <!-- Shipping Company Assignment -->
                <div class="mt-4 pt-4 border-t border-slate-100">
                    <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-2">{{ __('Société de Transport') }}</label>
                    <div class="flex items-center gap-3">
                        <div class="flex-1">
                            <select wire:change="assignShippingCompany($event.target.value)" class="w-full px-3 py-2 border border-slate-300 rounded text-sm focus:ring-1 focus:ring-orange-500 focus:border-orange-500 bg-white">
                                <option value="">{{ __('Non assignée') }}</option>
                                @foreach($shippingCompanies as $company)
                                    <option value="{{ $company->id }}" {{ $sourcingOrder->shipping_company_id == $company->id ? 'selected' : '' }}>
                                        {{ $company->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @if($sourcingOrder->shipping_company_id && $sourcingOrder->shippingCompany)
                            <div class="flex items-center gap-2 px-3 py-2 bg-blue-50 border border-blue-100 rounded text-blue-700 text-xs">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 0 0-3.213-9.193 2.056 2.056 0 0 0-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 0 0-10.026 0 1.106 1.106 0 0 0-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12"/></svg>
                                <span class="font-semibold">{{ $sourcingOrder->shippingCompany->name }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- 2. Shipping & Tracking -->
    <div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden relative group">
        <!-- Coming Soon Overlay -->
        <div class="absolute inset-0 z-10 flex items-center justify-center p-6 bg-white/10 backdrop-blur-[2px]">
            <div class="bg-slate-900/90 text-white px-6 py-3 rounded-xl shadow-2xl flex flex-col items-center gap-2 transform group-hover:scale-105 transition-transform duration-300 border border-white/20">
                <span class="text-[10px] font-bold uppercase tracking-[0.2em] text-orange-400">{{ __('Feature Development') }}</span>
                <div class="flex items-center gap-3">
                    <h4 class="text-xl font-black uppercase italic tracking-tighter">{{ __('Tracking') }}</h4>
                    <span class="h-6 w-px bg-white/20"></span>
                    <span class="text-lg font-light text-slate-300 italic">{{ __('Soon') }}</span>
                </div>
            </div>
        </div>

        <!-- Content (Blurred) -->
        <div class="filter blur-[4px] opacity-40 select-none pointer-events-none">
            <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                <h3 class="text-sm font-semibold text-slate-900 flex items-center gap-2">
                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/></svg>
                    {{ __('Expédition & Suivi') }}
                </h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="tracking_number" class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1">{{ __('Numéro de suivi') }}</label>
                        <input type="text" wire:model.defer="tracking_number" id="tracking_number" class="w-full px-3 py-2 border border-slate-300 rounded text-sm focus:ring-1 focus:ring-slate-900 focus:border-slate-900 bg-slate-50" placeholder="Ex: ME49508327">
                    </div>
                    <div>
                        <label for="tracking_carrier" class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1">{{ __('Transporteur') }}</label>
                        <input type="text" wire:model.defer="tracking_carrier" id="tracking_carrier" class="w-full px-3 py-2 border border-slate-300 rounded text-sm focus:ring-1 focus:ring-slate-900 focus:border-slate-900 bg-slate-50" placeholder="Ex: Faster.ae, DHL...">
                    </div>
                </div>
                <div class="mt-4 flex flex-col sm:flex-row justify-end gap-3">
                    <button type="button" class="px-4 py-2 bg-slate-100 text-slate-600 border border-slate-200 text-sm font-medium rounded transition-colors shadow-sm flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 21a9.004 9.004 0 0 0 8.716-6.747M12 21a9.004 9.004 0 0 1-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9s-2.015-9-4.5-9m0 18c-2.485 0-4.5-4.03-4.5-9s2.015-9 4.5-9m0 0a8.997 8.997 0 0 1 7.843 4.582M12 3a8.997 8.997 0 0 0-7.843 4.582m15.686 0A11.953 11.953 0 0 1 12 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0 1 21 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0 1 12 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 0 1 3 12c0-.778.099-1.533.284-2.253" /></svg>
                        {{ __('Deep Tracking') }}
                    </button>

                    <button type="button" class="px-4 py-2 bg-slate-900 text-white text-sm font-medium rounded transition-colors shadow-sm flex items-center gap-2">
                        <span>{{ __('Enregistrer le suivi') }}</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
