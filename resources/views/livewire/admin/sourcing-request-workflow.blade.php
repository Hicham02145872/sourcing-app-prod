<div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden">
    <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
        <h3 class="text-sm font-semibold text-slate-900 flex items-center gap-2">
            <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
            {{ __('Workflow & Assignation') }}
        </h3>
    </div>
    <div class="p-6">
        <!-- Assignment Status -->
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4 mb-6 pb-6 border-b border-slate-100">
            <div class="flex items-center gap-3 w-full sm:w-auto">
                <div class="h-10 w-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-500">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                </div>
                <div>
                    <p class="text-[10px] uppercase tracking-wider font-bold text-slate-500">{{ __('Responsable actuel') }}</p>
                    @if($sourcingRequest->assigned_to_admin_id)
                        <div class="flex items-center gap-2">
                            <span class="text-sm font-bold text-slate-900">{{ $sourcingRequest->assignedAdmin->name }}</span>
                            @if($sourcingRequest->assigned_to_admin_id == auth()->id())
                                <span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-700 border border-emerald-200">{{ __("C'est vous") }}</span>
                            @endif
                        </div>
                    @else
                        <span class="text-sm italic text-slate-400">{{ __('Non assigné') }}</span>
                    @endif
                </div>
            </div>

            <!-- Assignment Actions -->
            <div class="flex items-center gap-2 w-full sm:w-auto">
                @if($sourcingRequest->assigned_to_admin_id == auth()->id() || auth()->user()->isSuperAdmin())
                    @if($sourcingRequest->assigned_to_admin_id)
                        <button type="button" 
                                wire:click="release" 
                                wire:confirm="{{ __('Confirmer la libération de la requête ?') }}"
                                class="text-xs text-red-600 hover:text-red-800 hover:bg-red-50 px-3 py-1.5 rounded transition-colors border border-transparent hover:border-red-100">
                            {{ __('Libérer la requête') }}
                        </button>
                    @endif
                @endif

                @if(auth()->user()->isSuperAdmin())
                    <div class="flex-1 min-w-[180px]">
                        <select wire:change="assignTo($event.target.value)" class="w-full text-xs py-1.5 pl-2 pr-8 border-slate-300 rounded focus:ring-1 focus:ring-orange-500 focus:border-orange-500 bg-white">
                            <option value="">{{ __('Réassigner à...') }}</option>
                            @foreach($admins as $admin)
                                <option value="{{ $admin->id }}" {{ $sourcingRequest->assigned_to_admin_id == $admin->id ? 'selected' : '' }}>
                                    {{ $admin->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif
            </div>
        </div>

        <!-- Workflow Buttons -->
        <div>
            <p class="text-[10px] uppercase tracking-wider font-bold text-slate-500 mb-3">{{ __('Changer le statut') }}</p>
            <div class="flex flex-wrap gap-2">
                @foreach (\App\Models\SourcingRequest::STATUSES as $status)
                    @php if(in_array($status, ['quoted', 'negotiating', 'completed'])) continue; @endphp
                    @if ($sourcingRequest->canTransitionTo($status, auth()->user()) || $sourcingRequest->status === $status)
                        <button type="button" 
                                wire:click="updateStatus('{{ $status }}')"
                                @if($sourcingRequest->status !== $status) wire:confirm="{{ __('Confirmer le changement de statut vers : :status ?', ['status' => ucfirst(str_replace('_', ' ', $status))]) }}" @endif
                                @if($sourcingRequest->status === $status) disabled @endif
                                class="px-3 py-1.5 rounded border text-xs font-medium transition-all duration-200
                                {{ $sourcingRequest->status === $status ? 'bg-slate-300 text-slate-600 border-slate-300 cursor-default shadow-inner' : 'bg-white border-slate-200 text-slate-600 hover:border-orange-300 hover:text-orange-700 hover:shadow-sm' }}">
                            {{ ucfirst(str_replace('_', ' ', $status)) }}
                        </button>
                    @endif
                @endforeach
            </div>
        </div>

        <!-- Loading State for Actions -->
        <div wire:loading class="fixed inset-0 bg-slate-900/10 backdrop-blur-[1px] flex items-center justify-center z-50 rounded-lg">
            <div class="bg-white p-3 rounded-full shadow-xl border border-slate-100">
                <svg class="animate-spin h-5 w-5 text-orange-600" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
            </div>
        </div>
    </div>
</div>
