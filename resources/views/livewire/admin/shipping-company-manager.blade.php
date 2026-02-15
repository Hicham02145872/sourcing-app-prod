<div>
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-lg font-medium">{{ __('Shipping Companies') }}</h3>
        
        <button wire:click="openCreateModal" 
                class="inline-flex items-center gap-2 px-4 py-2 bg-slate-900 hover:bg-orange-600 text-white text-sm font-medium rounded-lg transition-all shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            {{ __('Add Company') }}
        </button>
    </div>

    <div class="overflow-x-auto relative shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left text-gray-500">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="py-3 px-6">{{ __('Company') }}</th>
                    <th scope="col" class="py-3 px-6">{{ __('Platform') }}</th>
                    <th scope="col" class="py-3 px-6">{{ __('Tracking') }}</th>
                    <th scope="col" class="py-3 px-6">{{ __('Integration Detail') }}</th>
                    <th scope="col" class="py-3 px-6">{{ __('Status') }}</th>
                    <th scope="col" class="py-3 px-6 text-right">{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody wire:loading.class="opacity-50 transition-opacity">
                @forelse($companies as $company)
                @php $type = $this->getIntegrationType($company); @endphp
                <tr class="bg-white border-b hover:bg-gray-50 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-600">
                    <td class="py-4 px-6">
                        <div class="font-medium text-gray-900 dark:text-white">{{ $company->name }}</div>
                    </td>
                    <td class="py-4 px-6">
                        @if($type === 'lark')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800 border border-orange-200">
                                <span class="w-2 h-2 mr-1.5 bg-orange-500 rounded-full"></span>
                                LarkSuite
                            </span>
                        @elseif($type === 'google')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 border border-blue-200">
                                <span class="w-2 h-2 mr-1.5 bg-blue-500 rounded-full"></span>
                                Google Sheets
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 border border-gray-200">
                                {{ __('None') }}
                            </span>
                        @endif
                    </td>
                    <td class="py-4 px-6">
                        @if($company->tracking_provider)
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold uppercase tracking-tighter {{ 
                                $company->tracking_provider == 'faster' ? 'bg-blue-50 text-blue-600' : (
                                $company->tracking_provider == 'itdida' ? 'bg-orange-50 text-orange-600' : 'bg-green-50 text-green-600'
                                )
                            }}">
                                {{ $company->tracking_provider }}
                            </span>
                        @else
                            <span class="text-gray-400 text-xs italic">{{ __('None') }}</span>
                        @endif
                    </td>
                    <td class="py-4 px-6 font-mono text-[10px] max-w-[200px] truncate">
                        @if($type === 'lark')
                            <span title="{{ $company->lark_base_token }}">ID: {{ Str::limit($company->lark_base_token, 15) }}</span>
                            <div class="text-[9px] text-gray-400">{{ $company->lark_table_id ?: 'Sheet1' }}</div>
                        @elseif($type === 'google')
                            <span title="{{ $company->google_sheet_id }}">ID: {{ Str::limit($company->google_sheet_id, 15) }}</span>
                            <div class="text-[9px] text-gray-400">{{ $company->sheet_name ?: 'sourcing' }}</div>
                        @else
                            -
                        @endif
                    </td>
                    <td class="py-4 px-6">
                        <button wire:click="toggleActive({{ $company->id }})" 
                                class="px-2 py-1 text-xs font-medium rounded-full {{ $company->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            {{ $company->is_active ? __('Active') : __('Inactive') }}
                        </button>
                    </td>
                    <td class="py-4 px-6 text-right whitespace-nowrap">
                        <div class="flex justify-end gap-1">
                            <button wire:click="testConnection({{ $company->id }})" 
                                    class="p-1.5 bg-indigo-100 text-indigo-700 hover:bg-indigo-600 hover:text-white rounded-lg transition-all"
                                    title="{{ __('Test Connection') }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                            </button>

                            @if($type === 'google')
                            <button wire:click="installHeaders({{ $company->id }})" 
                                    class="p-1.5 bg-blue-100 text-blue-700 hover:bg-blue-600 hover:text-white rounded-lg transition-all"
                                    title="{{ __('Install Headers') }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                                </svg>
                            </button>
                            @endif

                            @if($type === 'lark')
                            <button wire:click="installLarkHeaders({{ $company->id }})" 
                                    class="p-1.5 bg-orange-100 text-orange-700 hover:bg-orange-600 hover:text-white rounded-lg transition-all"
                                    title="{{ __('Install Headers') }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                                </svg>
                            </button>
                            @endif

                            <button wire:click="openEditModal({{ $company->id }})"  
                                    class="p-1.5 bg-gray-100 text-gray-700 hover:bg-slate-900 hover:text-white rounded-lg transition-all"
                                    title="{{ __('Edit') }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </button>
                            
                            <button wire:click="delete({{ $company->id }})" 
                                    wire:confirm="{{ __('Are you sure you want to delete this company?') }}"
                                    class="p-1.5 bg-red-100 text-red-700 hover:bg-red-600 hover:text-white rounded-lg transition-all"
                                    title="{{ __('Delete') }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="py-12 px-6 text-center">
                        <div class="flex flex-col items-center">
                            <div class="h-12 w-12 bg-slate-50 rounded-full flex items-center justify-center text-slate-300 mb-3 border border-slate-100">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                </svg>
                            </div>
                            <p class="text-slate-500 font-medium">{{ __('No shipping companies found.') }}</p>
                            <p class="text-slate-400 text-sm">{{ __('Add a company to get started.') }}</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($companies->hasPages())
        <div class="px-6 py-4 bg-slate-50 border-t border-slate-100">
            {{ $companies->links() }}
        </div>
    @endif

    <!-- Create/Edit Modal -->
    <div x-data="{ open: @entangle('showModal') }" 
         x-show="open" 
         x-cloak
         class="fixed inset-0 z-50 overflow-y-auto" 
         aria-labelledby="modal-title" role="dialog" aria-modal="true">
        
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <!-- Modal Background Overlay -->
            <div x-show="open" 
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" 
                 aria-hidden="true"
                 @click="open = false"></div>

            <!-- Modal Panel -->
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div x-show="open"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                
                <div class="bg-white">
                    <!-- Modal Header -->
                    <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="h-10 w-10 bg-orange-100 text-orange-600 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-slate-900" id="modal-title">
                                    {{ $editingId ? __('Edit Shipping Company') : __('Add Shipping Company') }}
                                </h3>
                            </div>
                        </div>
                        <button @click="open = false" class="text-slate-400 hover:text-slate-600 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    <!-- Modal Body Form -->
                    <div class="px-6 py-6">
                        <form wire:submit.prevent="save" class="space-y-4">
                            <!-- Name & Provider -->
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5">{{ __('Company Name') }} *</label>
                                    <input wire:model="name" type="text" 
                                           class="w-full px-3 py-2 bg-slate-50 border border-slate-200 focus:border-orange-500 focus:ring-1 focus:ring-orange-500 rounded-lg text-sm transition-all"
                                           placeholder="{{ __('e.g., Faster.ae, DHL Express') }}">
                                    @error('name') <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5">{{ __('Tracking Provider') }}</label>
                                    <select wire:model="tracking_provider" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 focus:border-orange-500 focus:ring-1 focus:ring-orange-500 rounded-lg text-sm transition-all cursor-pointer">
                                        <option value="">{{ __('None / Generic') }}</option>
                                        <option value="itdida">Itdida</option>
                                        <option value="choicexp">ChoiceXP</option>
                                        <option value="ups">UPS</option>
                                        <option value="faster">Faster / GCC</option>
                                    </select>
                                    @error('tracking_provider') <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            <!-- Integration Type Switcher -->
                            <div class="bg-slate-100 p-1 rounded-xl flex gap-1 mb-4">
                                <button type="button" @click="$wire.set('activeIntegration', 'lark')" 
                                        :class="$wire.activeIntegration === 'lark' ? 'bg-white shadow-sm text-orange-600' : 'text-slate-500 hover:text-slate-700'"
                                        class="flex-1 py-2 text-xs font-bold rounded-lg transition-all uppercase tracking-wider">
                                    LarkSuite
                                </button>
                                <button type="button" @click="$wire.set('activeIntegration', 'google')" 
                                        :class="$wire.activeIntegration === 'google' ? 'bg-white shadow-sm text-blue-600' : 'text-slate-500 hover:text-slate-700'"
                                        class="flex-1 py-2 text-xs font-bold rounded-lg transition-all uppercase tracking-wider">
                                    Google Sheets
                                </button>
                            </div>

                            <div x-show="$wire.activeIntegration === 'google'" x-transition class="space-y-4">
                                <!-- Google Sheet ID -->
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5">{{ __('Google Sheet ID') }}</label>
                                    <input wire:model="google_sheet_id" type="text" 
                                           class="w-full px-3 py-2 bg-slate-50 border border-slate-200 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 rounded-lg text-sm font-mono transition-all"
                                           placeholder="{{ __('e.g., 1ABC...XYZ') }}">
                                    <p class="mt-1 text-xs text-slate-400">{{ __('Found in the URL: docs.google.com/spreadsheets/d/{SHEET_ID}/...') }}</p>
                                    @error('google_sheet_id') <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p> @enderror
                                </div>

                                <!-- Sheet Name -->
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5">{{ __('Sheet Name (Tab)') }}</label>
                                    <input wire:model="sheet_name" type="text" 
                                           class="w-full px-3 py-2 bg-slate-50 border border-slate-200 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 rounded-lg text-sm transition-all"
                                           placeholder="sourcing">
                                    @error('sheet_name') <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            <div x-show="$wire.activeIntegration === 'lark'" x-transition class="space-y-4">
                                <!-- Lark App ID & Secret are now pre-filled and hidden -->

                                <!-- Lark Base Token -->
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5">{{ __('Lark Spreadsheet Token') }}</label>
                                    <input wire:model="lark_base_token" type="text" 
                                           class="w-full px-3 py-2 bg-slate-50 border border-slate-200 focus:border-orange-500 focus:ring-1 focus:ring-orange-500 rounded-lg text-sm font-mono transition-all"
                                           placeholder="{{ __('e.g., shtcn... or QKhMw...') }}">
                                    @error('lark_base_token') <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p> @enderror
                                </div>

                                <!-- Lark Table ID -->
                                <div>
                                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5">{{ __('Lark Sheet Title') }}</label>
                                    <input wire:model="lark_table_id" type="text" 
                                           class="w-full px-3 py-2 bg-slate-50 border border-slate-200 focus:border-orange-500 focus:ring-1 focus:ring-orange-500 rounded-lg text-sm transition-all"
                                           placeholder="{{ __('e.g., Sheet1') }}">
                                    @error('lark_table_id') <p class="mt-1 text-xs text-red-600 font-medium">{{ $message }}</p> @enderror
                                </div>

                                <div class="mt-2 text-right">
                                    <button type="button" wire:click="testLarkConnection"
                                            class="text-xs text-orange-600 hover:text-orange-700 font-bold hover:underline inline-flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                        </svg>
                                        {{ __('Test Lark Connection') }}
                                    </button>
                                </div>
                            </div>

                            <!-- Active Toggle -->
                            <div class="flex items-center gap-3">
                                <label for="is_active" class="relative inline-flex items-center cursor-pointer">
                                    <input wire:model="is_active" type="checkbox" id="is_active" class="sr-only peer">
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-orange-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-orange-500"></div>
                                </label>
                                <span class="text-sm font-medium text-slate-700">{{ __('Active') }}</span>
                            </div>

                            <!-- Form Actions -->
                            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                                <button type="button" @click="open = false" 
                                        class="px-4 py-2 text-xs font-bold text-slate-500 hover:text-slate-700 hover:bg-slate-50 rounded-xl transition-all uppercase tracking-wider">
                                    {{ __('Cancel') }}
                                </button>
                                <button type="submit" 
                                        class="inline-flex items-center gap-2 px-5 py-2 bg-[#EF7722] hover:bg-[#FAA533] text-white text-xs font-bold rounded-xl transition-all transform hover:scale-105 shadow-lg shadow-orange-200 uppercase tracking-wider">
                                    <div wire:loading wire:target="save">
                                        <svg class="animate-spin h-3 w-3 text-white" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                    </div>
                                    {{ $editingId ? __('Update') : __('Create') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        [x-cloak] { display: none !important; }
    </style>
</div>
