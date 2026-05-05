<div>
    @if (session()->has('error'))
        <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 text-xs font-bold rounded shadow-sm animate-pulse flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <!-- Main Form Column -->
        <div class="lg:col-span-8 space-y-6">
            
            <!-- Section 1: Client Selection -->
            <div class="bg-white rounded-lg border border-slate-200 shadow-sm">
                <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex items-center gap-3 rounded-t-lg">
                    <span class="flex-shrink-0 w-6 h-6 rounded bg-slate-900 text-white flex items-center justify-center text-[10px] font-bold">1</span>
                    <h3 class="text-xs font-bold text-slate-900 uppercase tracking-widest">{{ __('Client Identification') }}</h3>
                </div>
                <div class="p-6 space-y-6">
                    <!-- Client Type Switch (Enterprise Style) -->
                    <div class="flex p-1 bg-slate-100 rounded-md w-full">
                        <button type="button" 
                                wire:click="$set('client_type', 'existing')" 
                                class="flex-1 px-4 py-2 text-xs font-bold rounded-md transition-all {{ $client_type === 'existing' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-500 hover:text-slate-700 font-medium' }}">
                            {{ __('Existing Client') }}
                        </button>
                        <button type="button" 
                                wire:click="$set('client_type', 'new')" 
                                class="flex-1 px-4 py-2 text-xs font-bold rounded-md transition-all {{ $client_type === 'new' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-500 hover:text-slate-700 font-medium' }}">
                            {{ __('New Client (Guest)') }}
                        </button>
                    </div>

                    @if($client_type === 'existing')
                        <div class="relative">
                            <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 ml-1">{{ __('Search Client') }}</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                </span>
                                <input type="text" 
                                       wire:model.live.debounce.300ms="search" 
                                       class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-md focus:ring-1 focus:ring-orange-500 focus:border-orange-500 bg-white transition-all text-sm placeholder-slate-400"
                                       placeholder="{{ __('Name, Email or Phone...') }}">
                            </div>

                            @if($showDropdown && !empty($this->clients))
                                <div class="absolute z-30 w-full mt-1 bg-white border border-slate-200 rounded-md shadow-xl overflow-hidden animate-in fade-in">
                                    @foreach($this->clients as $client)
                                        <button type="button" 
                                                wire:click="selectClient({{ $client->id }})"
                                                class="w-full px-4 py-3 text-left hover:bg-slate-50 flex items-center justify-between group transition-colors border-b border-slate-50 last:border-0">
                                            <div>
                                                <p class="text-sm font-bold text-slate-900 group-hover:text-orange-600">{{ $client->name }}</p>
                                                <p class="text-[10px] text-slate-500 font-medium">{{ $client->email }}</p>
                                            </div>
                                            <svg class="w-3 h-3 text-slate-300 group-hover:text-orange-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                        </button>
                                    @endforeach
                                </div>
                            @endif
                            <x-input-error :messages="$errors->get('user_id')" class="mt-1" />
                        </div>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="space-y-1">
                                <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">{{ __('Full Name') }} <span class="text-red-500">*</span></label>
                                <input type="text" wire:model="client_name" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-md focus:ring-1 focus:ring-orange-500 focus:border-orange-500 transition-all text-sm">
                                <x-input-error :messages="$errors->get('client_name')" />
                            </div>
                            <div class="space-y-1">
                                <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">{{ __('Email Address') }} <span class="text-red-500">*</span></label>
                                <input type="email" wire:model="client_email" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-md focus:ring-1 focus:ring-orange-500 focus:border-orange-500 transition-all text-sm">
                                <x-input-error :messages="$errors->get('client_email')" />
                            </div>
                            <div class="space-y-1 md:col-span-2">
                                <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">{{ __('Phone Number') }}</label>
                                <input type="text" wire:model="client_phone" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-md focus:ring-1 focus:ring-orange-500 focus:border-orange-500 transition-all text-sm" placeholder="+212 6 XX XX XX XX">
                                <x-input-error :messages="$errors->get('client_phone')" />
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Section 2: Product Details -->
            <div class="bg-white rounded-lg border border-slate-200 shadow-sm">
                <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex items-center gap-3 rounded-t-lg">
                    <span class="flex-shrink-0 w-6 h-6 rounded bg-slate-900 text-white flex items-center justify-center text-[10px] font-bold">2</span>
                    <h3 class="text-xs font-bold text-slate-900 uppercase tracking-widest">{{ __('Product Details') }}</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <div class="space-y-1">
                                <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">{{ __('Product Name') }} <span class="text-red-500">*</span></label>
                                <input type="text" wire:model="product_name" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-md focus:ring-1 focus:ring-orange-500 focus:border-orange-500 transition-all text-sm" placeholder="Ex: Montre Connectée X8">
                                <x-input-error :messages="$errors->get('product_name')" />
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div class="space-y-1">
                                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">{{ __('Category') }} <span class="text-red-500">*</span></label>
                                    <select wire:model="category_id" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-md focus:ring-1 focus:ring-orange-500 text-sm">
                                        <option value="">{{ __('Select...') }}</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                    <x-input-error :messages="$errors->get('category_id')" />
                                </div>
                                <div class="space-y-1">
                                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">{{ __('Source From') }} <span class="text-red-500">*</span></label>
                                    <select wire:model="sourcing_location" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-md focus:ring-1 focus:ring-orange-500 text-sm">
                                        <option value="china">{{ __('China') }}</option>
                                        <option value="dubai">{{ __('Dubai') }}</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('sourcing_location')" />
                                </div>
                            </div>

                            <div class="space-y-1">
                                <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">{{ __('Product Link (Optional)') }}</label>
                                <input type="url" wire:model="product_url" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-md focus:ring-1 focus:ring-orange-500 focus:border-orange-500 transition-all text-sm" placeholder="https://alibaba.com/...">
                                <x-input-error :messages="$errors->get('product_url')" />
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div class="space-y-1">
                                <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">{{ __('Product Photo') }}</label>
                                <div class="relative group h-40 rounded-lg border-2 border-dashed border-slate-200 bg-slate-50 overflow-hidden flex flex-col items-center justify-center transition-all hover:border-orange-300 hover:bg-orange-50/10">
                                    @if ($product_image)
                                        <img src="{{ $product_image->temporaryUrl() }}" class="absolute inset-0 w-full h-full object-cover">
                                        <div class="absolute inset-0 bg-slate-900/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                            <button type="button" wire:click="$set('product_image', null)" class="px-3 py-1.5 bg-white rounded text-[10px] font-bold text-red-600 hover:bg-red-50 transition-all shadow-lg">{{ __('Change Photo') }}</button>
                                        </div>
                                    @else
                                        <input type="file" wire:model="product_image" class="absolute inset-0 opacity-0 cursor-pointer z-10">
                                        <svg class="w-8 h-8 text-slate-300 mb-1 group-hover:text-orange-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        <p class="text-[10px] font-bold text-slate-400 tracking-tight">{{ __('Click to upload') }}</p>
                                    @endif
                                    <div wire:loading wire:target="product_image" class="absolute inset-0 bg-white/80 backdrop-blur-sm flex items-center justify-center z-20">
                                        <svg class="animate-spin h-5 w-5 text-orange-600" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                    </div>
                                </div>
                                <x-input-error :messages="$errors->get('product_image')" />
                            </div>

                            <div class="space-y-2">
                                <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">{{ __('Preferred Shipping') }}</label>
                                <div class="grid grid-cols-2 gap-3">
                                    <button type="button" 
                                            wire:click="$set('shipping_method', 'air')" 
                                            class="flex items-center justify-center gap-2 px-3 py-2.5 rounded border-2 transition-all font-bold text-xs {{ $shipping_method === 'air' ? 'bg-orange-50 border-orange-500 text-orange-700' : 'bg-white border-slate-100 text-slate-400 hover:border-slate-200' }}">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                                        {{ __('Air') }}
                                    </button>
                                    <button type="button" 
                                            wire:click="$set('shipping_method', 'sea')" 
                                            class="flex items-center justify-center gap-2 px-3 py-2.5 rounded border-2 transition-all font-bold text-xs {{ $shipping_method === 'sea' ? 'bg-orange-50 border-orange-500 text-orange-700' : 'bg-white border-slate-100 text-slate-400 hover:border-slate-200' }}">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                                        {{ __('Sea') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 space-y-1">
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 ml-1">{{ __('Notes & Specifications') }}</label>
                        <textarea wire:model="note" rows="3" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-md focus:ring-1 focus:ring-orange-500 transition-all text-sm placeholder-slate-400 font-medium" placeholder="{{ __('Describe exactly what you are looking for (color, size, material, quality...)') }}"></textarea>
                        <x-input-error :messages="$errors->get('note')" />
                    </div>
                </div>
            </div>
        </div>

        <!-- Destinations Sidebar -->
        <div class="lg:col-span-4 space-y-6">
            <div class="bg-white rounded-lg border border-slate-200 shadow-sm sticky top-24">
                <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between rounded-t-lg">
                    <div class="flex items-center gap-3">
                        <span class="flex-shrink-0 w-6 h-6 rounded bg-slate-900 text-white flex items-center justify-center text-[10px] font-bold">3</span>
                        <h3 class="text-xs font-bold text-slate-900 uppercase tracking-widest">{{ __('Destinations') }}</h3>
                    </div>
                    <button type="button" 
                            wire:click="addDestination" 
                            class="p-1.5 bg-slate-100 text-slate-500 rounded hover:bg-slate-200 transition-colors"
                            title="{{ __('Add Destination') }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    </button>
                </div>
                
                <div class="p-6 space-y-4 max-h-[calc(100vh-250px)] overflow-y-auto custom-scrollbar">
                    @foreach($destinations as $index => $destination)
                        <div class="p-4 bg-slate-50 rounded border border-slate-200 space-y-3 relative group">
                            @if(count($destinations) > 1)
                                <button type="button" 
                                        wire:click="removeDestination({{ $index }})" 
                                        class="absolute top-3 right-3 text-slate-300 hover:text-red-500 transition-colors opacity-0 group-hover:opacity-100">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            @endif

                            <div class="space-y-3">
                                <div class="space-y-1">
                                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400">{{ __('Destination Country') }}</label>
                                    <select wire:model="destinations.{{ $index }}.country_id" class="w-full px-2 py-2 bg-white border border-slate-200 rounded-md focus:ring-1 focus:ring-orange-500 text-sm font-bold text-slate-700">
                                        <option value="">{{ __('Select...') }}</option>
                                        @foreach($countries as $country)
                                            <option value="{{ $country->id }}">{{ $country->name }}</option>
                                        @endforeach
                                    </select>
                                    <x-input-error :messages="$errors->get('destinations.' . $index . '.country_id')" />
                                </div>

                                <div class="grid grid-cols-2 gap-3">
                                    <div class="space-y-1">
                                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400">{{ __('Service') }}</label>
                                        <select wire:model="destinations.{{ $index }}.service_id" class="w-full px-2 py-2 bg-white border border-slate-200 rounded-md focus:ring-1 focus:ring-orange-500 text-sm">
                                            <option value="">...</option>
                                            @foreach($services as $service)
                                                <option value="{{ $service->id }}">{{ $service->name }}</option>
                                            @endforeach
                                        </select>
                                        <x-input-error :messages="$errors->get('destinations.' . $index . '.service_id')" />
                                    </div>
                                    <div class="space-y-1">
                                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400">{{ __('Quantity') }}</label>
                                        <input type="number" wire:model="destinations.{{ $index }}.quantity" class="w-full px-2 py-2 bg-white border border-slate-200 rounded-md focus:ring-1 focus:ring-orange-500 text-sm font-bold text-center text-slate-900 shadow-inner">
                                        <x-input-error :messages="$errors->get('destinations.' . $index . '.quantity')" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <button type="button" 
                            wire:click="addDestination" 
                            class="w-full py-3 border border-dashed border-slate-300 rounded text-slate-400 text-[10px] font-bold uppercase tracking-wider hover:border-orange-400 hover:text-orange-500 transition-all flex items-center justify-center gap-2 group">
                        <svg class="w-3.5 h-3.5 transition-transform group-hover:rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        {{ __('Add destination') }}
                    </button>
                </div>

                <div class="p-6 bg-slate-900 rounded-b-lg">
                    <button wire:click="save" wire:loading.attr="disabled" class="w-full py-3.5 bg-orange-600 hover:bg-orange-500 text-white rounded-md text-xs font-black uppercase tracking-widest transition-all shadow-lg active:scale-[0.98] disabled:opacity-50 flex items-center justify-center gap-2">
                        <span wire:loading.remove wire:target="save">{{ __('Finalize & Create') }}</span>
                        <span wire:loading wire:target="save" class="flex items-center gap-2">
                             <svg class="animate-spin h-3 w-3 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                             {{ __('Creating...') }}
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
