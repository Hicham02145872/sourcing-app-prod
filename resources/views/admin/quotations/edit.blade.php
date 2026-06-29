<x-app-layout>
    <!-- Main Container: Slate background for enterprise feel -->
    <div class="min-h-screen bg-slate-50/80 font-sans text-slate-900 pb-12">
        
        <!-- Top Navigation / Breadcrumb Area -->
        <div class="bg-white border-b border-slate-200 shadow-sm sticky top-0 z-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between h-auto md:h-16 py-4 md:py-0 gap-4">
                    <div class="flex items-center gap-2">
                        <span class="inline-flex items-center justify-center h-8 w-8 rounded bg-orange-100 text-orange-600">
                            <!-- Pen/Document Icon -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                        </span>
                        <div>
                            <h1 class="text-lg font-bold text-slate-900 leading-tight">{{ __('Update Quotation') }}</h1>
                            <nav class="flex text-xs text-slate-500" aria-label="Breadcrumb">
                                <span class="hover:text-slate-700">{{ __('Dashboard') }}</span>
                                <span class="mx-1.5">/</span>
                                <span class="hover:text-slate-700">{{ __('Quotations') }}</span>
                                <span class="mx-1.5">/</span>
                                <span class="font-medium text-slate-700">{{ __('Update Quote #') }}{{ $quotation->display_id }}</span>
                            </nav>
                        </div>
                    </div>
                    
                    <!-- Global Actions -->
                    <div class="flex items-center gap-3">
                        <div class="hidden sm:flex items-center gap-2 px-3 py-1.5 bg-blue-50 rounded border border-blue-200">
                            <span class="inline-flex items-center justify-center w-2 h-2 rounded-full bg-blue-500"></span>
                            <span class="text-xs font-semibold text-blue-600">{{ __('Editing Mode') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <form id="quotation-edit-form" method="POST" action="{{ route('admin.quotations.update', $quotation) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                    <!-- Left Column: Sourcing Request Details -->
                    <div class="lg:col-span-5 space-y-6">
                        <!-- Product Specs -->
                        <div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden">
                            <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                                <h3 class="text-sm font-semibold text-slate-900">{{ __('Product Specifications') }}</h3>
                            </div>
                            <div class="p-6 space-y-4 text-sm">
                                <div>
                                    <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-0.5">{{ __('Product Name') }}</label>
                                    <div class="font-semibold text-slate-800">{{ $quotation->sourcingRequest->product_name }}</div>
                                </div>
                                @if($quotation->sourcingRequest->product_url)
                                <div>
                                    <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-0.5">{{ __('Reference Link') }}</label>
                                    <a href="{{ $quotation->sourcingRequest->product_url }}" target="_blank" class="text-blue-600 hover:underline truncate block">
                                        {{ $quotation->sourcingRequest->product_url }}
                                    </a>
                                </div>
                                @endif
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-0.5">{{ __('Category') }}</label>
                                        <div>{{ $quotation->sourcingRequest->category?->name ?? __('Unclassified') }}</div>
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-0.5">{{ __('Requested Location') }}</label>
                                        <div class="capitalize">{{ $quotation->sourcingRequest->sourcing_location }}</div>
                                    </div>
                                </div>
                                @if($quotation->sourcingRequest->note)
                                <div>
                                    <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-0.5">{{ __('Client Notes') }}</label>
                                    <div class="p-2.5 bg-slate-50 rounded border border-slate-150 text-slate-700 italic">
                                        {{ $quotation->sourcingRequest->note }}
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>

                        <!-- Product Image -->
                        <div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden p-4">
                            <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-2">{{ __('Requested Product Image') }}</label>
                            @if($quotation->sourcingRequest->product_image)
                                <img src="{{ media_url($quotation->sourcingRequest->product_image) }}" class="w-full h-auto rounded border border-slate-200 object-cover max-h-64">
                            @else
                                <div class="h-32 bg-slate-50 border border-dashed border-slate-200 rounded flex items-center justify-center text-slate-400">
                                    {{ __('No image uploaded') }}
                                </div>
                            @endif
                        </div>

                        <!-- Destinations -->
                        <div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden">
                            <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                                <h3 class="text-sm font-semibold text-slate-900">{{ __('Destinations & Quantities') }}</h3>
                            </div>
                            <table class="min-w-full divide-y divide-slate-100">
                                <thead class="bg-slate-50">
                                    <tr>
                                        <th class="px-6 py-2 text-left text-[10px] font-bold text-slate-500 uppercase">{{ __('Country') }}</th>
                                        <th class="px-6 py-2 text-left text-[10px] font-bold text-slate-500 uppercase">{{ __('Service') }}</th>
                                        <th class="px-6 py-2 text-right text-[10px] font-bold text-slate-500 uppercase">{{ __('Qty') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 bg-white">
                                    @foreach($quotation->sourcingRequest->destinations as $dest)
                                    <tr>
                                        <td class="px-6 py-2 text-sm text-slate-800">
                                            <span class="fi fi-{{ strtolower($dest->country->code) }} mr-1"></span>
                                            {{ $dest->country->name }}
                                        </td>
                                        <td class="px-6 py-2 text-sm text-slate-600">{{ $dest->service->name }}</td>
                                        <td class="px-6 py-2 text-sm text-right font-mono text-slate-800">{{ $dest->quantity }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Client Profile -->
                        <div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden p-5">
                            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-3">{{ __('Client Profile') }}</h3>
                            <div class="flex items-center gap-3 mb-4">
                                <div class="h-10 w-10 rounded-full bg-orange-100 text-orange-600 flex items-center justify-center font-bold border border-orange-200">
                                    {{ substr($quotation->sourcingRequest->user->name, 0, 1) }}
                                </div>
                                <div class="overflow-hidden">
                                    <h4 class="text-sm font-bold text-slate-900 truncate">{{ $quotation->sourcingRequest->user->name }}</h4>
                                    <p class="text-xs text-slate-500 truncate">{{ $quotation->sourcingRequest->user->email }}</p>
                                </div>
                            </div>
                            @if($quotation->sourcingRequest->destinations->isNotEmpty())
                                <div class="pt-3 border-t border-slate-100">
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">{{ __('Delivery Addresses') }}</p>
                                    <div class="space-y-1.5">
                                        @foreach($quotation->sourcingRequest->destinations as $dest)
                                            <div class="flex items-start gap-2 p-1.5 bg-slate-50 rounded-lg">
                                                <div class="h-5 w-5 rounded bg-slate-100 flex items-center justify-center text-slate-400 mt-0.5 shrink-0">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                                </div>
                                                <div class="min-w-0">
                                                    <p class="text-[11px] font-bold text-slate-700">{{ $dest->country->name }}</p>
                                                    <p class="text-[10px] text-slate-500 leading-tight">{{ $dest->label_address ?: $dest->address }}</p>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Right Column: Sourcing Quotation Form -->
                    <div class="lg:col-span-7 space-y-6">
                        <div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden">
                            <div class="px-6 py-4 border-b border-slate-200 bg-slate-50/50">
                                <h3 class="text-sm font-semibold text-slate-900">{{ __('Quotation Details') }}</h3>
                        <p class="text-xs text-slate-500 mt-0.5">{{ __('Update the financial and logistical data as requested.') }}</p>
                    </div>

                    <div class="p-6 space-y-8">
                        
                        <!-- Subsection: Basic & Currency -->
                        <div>
                            <div class="flex items-center gap-2 mb-4 pb-2 border-b border-slate-100">
                                <svg class="w-4 h-4 text-slate-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wide">{{ __('Currency Settings') }}</h4>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="currency" class="block text-[10px] font-bold text-slate-500 uppercase mb-1">{{ __('Currency') }} <span class="text-red-500">*</span></label>
                                    <select id="currency" name="currency" required
                                        class="block w-full px-3 py-2 text-sm bg-slate-50 border border-slate-300 text-slate-900 rounded focus:ring-1 focus:ring-orange-500 focus:border-orange-500 transition-colors">
                                        <option value="">{{ __('Select currency') }}</option>
                                        @foreach(['USD', 'EUR', 'GBP', 'MAD', 'JPY', 'CNY', 'CAD', 'AUD', 'AED'] as $curr)
                                            <option value="{{ $curr }}" {{ old('currency', $quotation->currency) == $curr ? 'selected' : '' }}>{{ $curr }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label for="actual_sourcing_location" class="block text-[10px] font-bold text-slate-500 uppercase mb-1">{{ __('Actual Sourcing Location') }} <span class="text-red-500">*</span></label>
                                    <select id="actual_sourcing_location" name="actual_sourcing_location" required
                                        class="block w-full px-3 py-2 text-sm bg-slate-50 border border-slate-300 text-slate-900 rounded focus:ring-1 focus:ring-orange-500 focus:border-orange-500 transition-colors capitalize">
                                        <option value="china" {{ old('actual_sourcing_location', $quotation->actual_sourcing_location) == 'china' ? 'selected' : '' }}>{{ __('China') }}</option>
                                        <option value="dubai" {{ old('actual_sourcing_location', $quotation->actual_sourcing_location) == 'dubai' ? 'selected' : '' }}>{{ __('Dubai') }}</option>
                                    </select>
                                    <p class="mt-1 text-[10px] text-slate-400">{{ __('The location where the product will actually be sourced from.') }}</p>
                                </div>
                            </div>

                            <!-- Sourcing Note (Shown only when alternative location selected) -->
                            <div id="sourcing_note_container" class="{{ old('actual_sourcing_location', $quotation->actual_sourcing_location) != $quotation->sourcingRequest->sourcing_location ? '' : 'hidden' }} mt-6 p-4 bg-orange-50 border border-orange-100 rounded-lg">
                                <label for="sourcing_note" class="block text-[10px] font-bold text-orange-600 uppercase mb-2">
                                    {{ __('Note about Alternative Sourcing') }}
                                </label>
                                <textarea id="sourcing_note" name="sourcing_note" rows="3"
                                    class="block w-full px-3 py-2 text-sm bg-white border border-orange-200 text-slate-900 rounded focus:ring-1 focus:ring-orange-500 focus:border-orange-500 transition-colors"
                                    placeholder="{{ __('Explain why this location was chosen and any impact on delivery...') }}">{{ old('sourcing_note', $quotation->sourcing_note) }}</textarea>
                                <p class="mt-2 text-[10px] text-orange-500/80 italic">
                                    <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    {{ __('This note will be visible to the client to help them understand the change.') }}
                                </p>
                            </div>

                            <div class="mt-6">
                                <label for="comments" class="block text-[10px] font-bold text-slate-500 uppercase mb-1">{{ __('Comments') }}</label>
                                <textarea id="comments" name="comments" rows="3"
                                    class="block w-full px-3 py-2 text-sm bg-slate-50 border border-slate-300 text-slate-900 rounded focus:ring-1 focus:ring-orange-500 focus:border-orange-500 transition-colors"
                                    placeholder="{{ __('Add any internal notes or clarifications for this quotation...') }}">{{ old('comments', $quotation->comments) }}</textarea>
                                <p class="mt-1 text-[10px] text-slate-400">{{ __('Visible to the client in quotation details.') }}</p>
                            </div>

                            @if($quotation->negotiation_notes)
                                <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                                    <label for="admin_negotiation_reply" class="block text-[10px] font-bold text-blue-700 uppercase mb-2">
                                        {{ __('Admin Reply To Client Negotiation') }}
                                    </label>
                                    <p class="mb-2 text-xs text-blue-700 italic">
                                        "{{ $quotation->negotiation_notes }}"
                                    </p>
                                    <textarea id="admin_negotiation_reply" name="admin_negotiation_reply" rows="3"
                                        class="block w-full px-3 py-2 text-sm bg-white border border-blue-200 text-slate-900 rounded focus:ring-1 focus:ring-orange-500 focus:border-orange-500 transition-colors"
                                        placeholder="{{ __('Write a clear response to the client note...') }}">{{ old('admin_negotiation_reply', $quotation->admin_negotiation_reply) }}</textarea>
                                    <p class="mt-1 text-[10px] text-blue-600/80">{{ __('Visible to the client in negotiation details.') }}</p>
                                </div>
                            @endif
                        </div>

                        <!-- Subsection: Real Quality Image -->
                        <div>
                            <div class="flex items-center gap-2 mb-4 pb-2 border-b border-slate-100">
                                <svg class="w-4 h-4 text-red-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wide">{{ __('Real Product Quality Image') }}</h4>
                                <span class="ml-2 px-2 py-0.5 bg-red-100 text-red-700 text-[10px] font-bold rounded animate-pulse">{{ __('Action Required') }}</span>
                            </div>

                            <div class="p-4 bg-red-50/50 border border-red-100 rounded-lg">
                                <div class="flex flex-col md:flex-row items-center gap-6">
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                            <div class="space-y-2">
                                                <label for="real_product_image" class="block text-[10px] font-bold text-red-600 uppercase">
                                                    {{ __('Featured Product Photo') }} <span class="text-red-400 font-normal">({{ __('Shows on dashboard/refunds') }})</span>
                                                </label>
                                                <div class="relative group">
                                                    <input type="file" name="real_product_image" id="real_product_image" accept="image/*"
                                                        class="block w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-xs file:font-semibold file:bg-slate-900 file:text-white hover:file:bg-slate-800 transition-all cursor-pointer bg-white border border-slate-200 p-2 rounded-md">
                                                </div>
                                            </div>
                                            <div class="space-y-2">
                                                <label for="media_files" class="block text-[10px] font-bold text-slate-600 uppercase">
                                                    {{ __('Update Additional Media') }}
                                                </label>
                                                <div class="relative group">
                                                    <input type="file" name="media_files[]" id="media_files" accept="image/*,video/*" multiple
                                                        class="block w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-xs file:font-semibold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200 transition-all cursor-pointer bg-white border border-slate-200 p-2 rounded-md">
                                                </div>
                                            </div>
                                            <div class="space-y-2 col-span-1 md:col-span-2">
                                                <label for="supplier_url" class="block text-[10px] font-bold text-slate-600 uppercase">
                                                    {{ __('Supplier Product Link') }} <span class="text-slate-400 font-normal">({{ __('Internal only') }})</span>
                                                </label>
                                                <div class="relative group">
                                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.828a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                                                    </div>
                                                    <input type="url" name="supplier_url" id="supplier_url" value="{{ old('supplier_url', $quotation->supplier_url) }}" placeholder="https://item.taobao.com/..."
                                                        class="w-full pl-9 pr-3 py-2 text-sm bg-white border border-slate-300 text-slate-900 rounded focus:ring-1 focus:ring-orange-500 focus:border-orange-500 block transition-colors placeholder:text-slate-400">
                                                </div>
                                                @error('supplier_url')
                                                    <p class="text-[10px] text-red-600 mt-1 font-medium">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        </div>
                                        <p class="mt-2 text-[10px] text-red-500/80 font-medium italic">
                                            <svg class="w-3.5 h-3.5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            {{ __('Téléchargez des photos et vidéos supplémentaires. Les médias existants peuvent être gérés ci-dessous. Taille maximale : 10 MB par fichier. Glisser-déposer supporté.') }}
                                        </p>
                                        <div id="quotation-file-size-error" class="mt-3 p-3 rounded-lg bg-red-100 border border-red-300 text-red-800 text-sm font-medium {{ $errors->has('real_product_image') || $errors->has('media_files') ? '' : 'hidden' }}" role="alert">
                                            <div class="flex items-start gap-2">
                                                <svg class="w-5 h-5 text-red-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                <div>
                                                    @if($errors->has('real_product_image'))
                                                        <strong>{{ __('Erreur :') }}</strong> {{ $errors->first('real_product_image') }}
                                                    @elseif($errors->has('media_files'))
                                                        <strong>{{ __('Erreur :') }}</strong> {{ $errors->first('media_files') }}
                                                    @else
                                                        <strong>{{ __('Erreur :') }}</strong> {{ __('Un ou plusieurs fichiers dépassent 10 MB. Veuillez choisir des fichiers plus petits.') }}
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="image-preview-container" class="{{ $quotation->real_product_image ? '' : 'hidden' }}">
                                        <p class="text-[10px] font-bold text-slate-400 uppercase mb-1">{{ __('Preview') }}</p>
                                        <div class="flex items-start gap-3">
                                            <x-photo-viewer src="{{ media_url($quotation->real_product_image) }}" alt="Preview">
                                                <div class="h-24 w-24 rounded-lg border-2 border-red-200 border-dashed overflow-hidden bg-white shadow-sm cursor-pointer hover:opacity-90 transition-opacity">
                                                    <img id="image-preview" src="{{ $quotation->real_product_image ? media_url($quotation->real_product_image) : '#' }}" alt="Preview" class="h-full w-full object-cover">
                                                </div>
                                            </x-photo-viewer>
                                            @if($quotation->real_product_image)
                                                <button type="button"
                                                    class="mt-1 inline-flex items-center gap-1 px-2 py-1 text-[10px] font-bold text-red-600 bg-red-50 border border-red-200 rounded-lg hover:bg-red-100 transition-colors"
                                                    onclick="if(confirm('{{ __('Delete this featured photo?') }}')) { fetch('{{ route('admin.quotations.featured-photo.destroy', $quotation) }}', { method: 'DELETE', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } }).then(r => { if(r.ok) location.reload(); }); }">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                    {{ __('Delete') }}
                                                </button>
                                            @endif
                                        </div>
                                    </div>

                                    @if($quotation->media->isNotEmpty())
                                        <div class="mt-6 border-t border-slate-100 pt-4">
                                            <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">{{ __('Existing Media') }}</label>
                                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                                                @foreach($quotation->media as $media)
                                                    <div class="relative group aspect-square rounded-lg border border-slate-200 overflow-hidden bg-slate-50">
                                                        @if($media->file_type === 'video')
                                                            <video src="{{ media_url($media->file_path) }}" class="w-full h-full object-cover" muted controls></video>
                                                            <div class="absolute inset-0 flex items-center justify-center bg-black/30 pointer-events-none">
                                                                <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                                            </div>
                                                        @else
                                                            <x-photo-viewer src="{{ $media->url }}" alt="{{ __('Real product photo') }}">
                                                                <img src="{{ media_url($media->file_path) }}" class="w-full h-full object-cover cursor-pointer hover:opacity-90 transition-opacity">
                                                            </x-photo-viewer>
                                                        @endif
                                                        <button type="button"
                                                            class="absolute top-1 right-1 z-10 p-1 rounded bg-white/90 hover:bg-red-500 hover:text-white text-red-500 shadow transition-all opacity-0 group-hover:opacity-100"
                                                            title="{{ __('Delete') }}"
                                                            onclick="if(confirm('{{ __('Delete this media?') }}')) { fetch('{{ route('admin.quotation-media.destroy', $media) }}', { method: 'DELETE', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' } }).then(r => { if(r.ok) this.closest('.aspect-square').remove(); }); }">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                        </button>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Subsection: Quality Pricing Options (Faible, Moyen, Bon) -->
                        <div>
                            <div class="flex items-center gap-2 mb-4 pb-2 border-b border-slate-100">
                                <svg class="w-4 h-4 text-orange-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                                </svg>
                                <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wide">{{ __('Quality Pricing Options') }}</h4>
                                <span class="ml-2 px-2 py-0.5 bg-orange-100 text-orange-700 text-[10px] font-bold rounded">{{ __('Optional') }}</span>
                            </div>

                            <p class="text-xs text-slate-500 mb-4">{{ __('Define alternative pricing based on product quality. Client can choose one of these levels.') }}</p>

                            <div class="space-y-4 mb-6">
                                @foreach(['low' => __('Low Quality (Qualité Faible)'), 'medium' => __('Medium Quality (Qualité Moyenne)'), 'good' => __('Good Quality (Qualité Bonne)')] as $key => $label)
                                    <div class="border border-slate-150 rounded-lg p-4 bg-slate-50/50 space-y-3">
                                        <h5 class="text-xs font-bold text-slate-800 uppercase tracking-wide flex items-center gap-2">
                                            <span class="w-2.5 h-2.5 rounded-full {{ $key === 'low' ? 'bg-amber-400' : ($key === 'medium' ? 'bg-blue-400' : 'bg-emerald-400') }}"></span>
                                            {{ $label }}
                                        </h5>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">{{ __('Unit Price') }}</label>
                                                <div class="relative rounded-md shadow-sm">
                                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                        <span class="text-slate-400 text-xs currency-symbol">$</span>
                                                    </div>
                                                    <input type="number" step="0.01" name="quality_options[{{ $key }}][price]" 
                                                        value="{{ old('quality_options.'.$key.'.price', $quotation->quality_options[$key]['price'] ?? '') }}" 
                                                        placeholder="0.00" 
                                                        class="pl-8 block w-full px-3 py-1.5 text-sm bg-white border border-slate-300 rounded focus:ring-1 focus:ring-orange-500">
                                                </div>
                                            </div>
                                            <div>
                                                <label class="block text-[10px] font-bold text-slate-500 uppercase mb-1">{{ __('Photo') }}</label>
                                                <input type="file" name="quality_options_images[{{ $key }}]" accept="image/*" 
                                                    class="block w-full text-xs text-slate-500 file:mr-4 file:py-1 file:px-3 file:rounded file:border-0 file:text-xs file:font-semibold file:bg-slate-900 file:text-white hover:file:bg-slate-800 bg-white border border-slate-200 p-1 rounded-md">
                                                @if(isset($quotation->quality_options[$key]['image_path']))
                                                    <div class="mt-2 flex items-center gap-2">
                                                        <img src="{{ media_url($quotation->quality_options[$key]['image_path']) }}" class="w-12 h-12 object-cover rounded border border-slate-200">
                                                        <a href="{{ media_url($quotation->quality_options[$key]['image_path']) }}" target="_blank" class="text-xs text-blue-600 hover:underline">{{ __('View') }}</a>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Subsection: Pricing -->
                        <div>
                            <div class="flex items-center gap-2 mb-4 pb-2 border-b border-slate-100">
                                <svg class="w-4 h-4 text-slate-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wide">{{ __('Financial Details') }}</h4>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="unit_price" class="block text-[10px] font-bold text-slate-500 uppercase mb-1">{{ __('Unit Price') }} <span class="text-red-500">*</span></label>
                                    <div class="relative rounded-md shadow-sm">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-slate-400 sm:text-sm font-serif italic currency-symbol">$</span>
                                        </div>
                                        <input type="number" step="0.0001" name="unit_price" id="unit_price" required placeholder="0.00" value="{{ old('unit_price', $quotation->unit_price) }}"
                                            class="pl-12 block w-full px-3 py-2 text-sm bg-slate-50 border border-slate-300 text-slate-900 rounded focus:ring-1 focus:ring-orange-500 focus:border-orange-500 transition-colors font-medium">
                                    </div>
                                    <p class="mt-1 text-[10px] text-slate-400">{{ __('Price per unit excluding fees') }}</p>
                                </div>

                                <div>
                                    <label for="commission_service" class="block text-[10px] font-bold text-slate-500 uppercase mb-1">{{ __('Service Commission') }} <span class="text-red-500">*</span></label>
                                    <div class="relative rounded-md shadow-sm">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-slate-400 sm:text-sm font-serif italic currency-symbol">$</span>
                                        </div>
                                        <input type="number" step="0.01" name="commission_service" id="commission_service" required placeholder="0.00" value="{{ old('commission_service', $quotation->commission_service) }}"
                                            class="pl-12 block w-full px-3 py-2 text-sm bg-slate-50 border border-slate-300 text-slate-900 rounded focus:ring-1 focus:ring-orange-500 focus:border-orange-500 transition-colors font-medium">
                                    </div>
                                    <p class="mt-1 text-[10px] text-slate-400">{{ __('Commission amount per unit') }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Subsection: Logistics -->
                        <div>
                            <div class="flex items-center gap-2 mb-4 pb-2 border-b border-slate-100">
                                <svg class="w-4 h-4 text-slate-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wide">{{ __('Logistics') }}</h4>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="unit_weight" class="block text-[10px] font-bold text-slate-500 uppercase mb-1">{{ __('Unit Weight') }} <span class="text-red-500">*</span></label>
                                    <div class="relative rounded-md shadow-sm">
                                        <input type="number" step="0.01" name="unit_weight" id="unit_weight" required placeholder="0.00" value="{{ old('unit_weight', $quotation->unit_weight) }}"
                                            class="block w-full px-3 py-2 text-sm bg-slate-50 border border-slate-300 text-slate-900 rounded focus:ring-1 focus:ring-orange-500 focus:border-orange-500 transition-colors font-medium">
                                        <div class="absolute inset-y-0 right-0 flex items-center">
                                            <select name="weight_unit" class="h-full py-0 pl-2 pr-7 border-transparent bg-transparent text-slate-500 sm:text-xs font-bold rounded-md focus:ring-0 focus:border-transparent">
                                                <option value="g" {{ $quotation->weight_unit == 'g' ? 'selected' : '' }}>g</option>
                                                <option value="kg" {{ $quotation->weight_unit == 'kg' ? 'selected' : '' }}>kg</option>
                                                <option value="colis" {{ $quotation->weight_unit == 'colis' ? 'selected' : '' }}>{{ __('package') }}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <p class="mt-1 text-[10px] text-slate-400">{{ __('Weight in grams (g)') }}</p>
                                </div>

                                <div>
                                    <label for="delivery_cost_china" class="block text-[10px] font-bold text-slate-500 uppercase mb-1">{{ __('Shipping Fees') }} <span class="text-red-500">*</span></label>
                                    <div class="relative rounded-md shadow-sm">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-slate-400 sm:text-sm font-serif italic currency-symbol">$</span>
                                        </div>
                                        <input type="number" step="0.01" name="delivery_cost_china" id="delivery_cost_china" required placeholder="0.00" value="{{ old('delivery_cost_china', $quotation->delivery_cost_china) }}"
                                            class="pl-12 block w-full px-3 py-2 text-sm bg-slate-50 border border-slate-300 text-slate-900 rounded focus:ring-1 focus:ring-orange-500 focus:border-orange-500 transition-colors font-medium">
                                    </div>
                                    <p class="mt-1 text-[10px] text-slate-400">{{ __('Domestic delivery cost') }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Subsection: Financial Estimation (Optional) -->
                        <div>
                            <div class="flex items-center justify-between mb-4 pb-2 border-b border-slate-100">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-slate-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wide">{{ __('Cost Estimation') }}</h4>
                                    <span class="ml-2 px-2 py-0.5 bg-blue-50 text-blue-600 text-[10px] font-semibold rounded">{{ __('Optional') }}</span>
                                </div>
                                <button type="button" id="toggle-estimates" class="text-xs text-slate-500 hover:text-slate-700 flex items-center gap-1">
                                    <span id="toggle-text">{{ __('Show') }}</span>
                                    <svg id="toggle-icon" class="w-3 h-3 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>
                            </div>

                            <div id="estimates-section" class="hidden space-y-4">
                                <div class="p-3 bg-blue-50 border border-blue-100 rounded-lg">
                                    <p class="text-xs text-blue-800">
                                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        {{ __('Estimate costs to analyze profitability before sending quotation to client') }}
                                    </p>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <!-- Estimated Product Cost (UNIT) -->
                                    <div>
                                        <label for="estimated_product_cost" class="block text-[10px] font-bold text-slate-500 uppercase mb-1">
                                            {{ __('Estimated Unit Product Cost') }}
                                        </label>
                                        <div class="relative rounded-md shadow-sm">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <span class="text-slate-400 sm:text-sm font-serif italic currency-symbol">$</span>
                                            </div>
                                            @php $totalQuantity = $quotation->sourcingRequest->destinations->sum('quantity'); @endphp
                                            <input type="number" step="0.01" name="estimated_product_cost" id="estimated_product_cost" placeholder="0.00" 
                                                value="{{ old('estimated_product_cost', $quotation->estimated_product_cost ? $quotation->estimated_product_cost / max(1, $totalQuantity) : null) }}"
                                                class="pl-12 block w-full px-3 py-2 text-sm bg-slate-50 border border-slate-300 text-slate-900 rounded focus:ring-1 focus:ring-orange-500 focus:border-orange-500 transition-colors font-medium border-l-4 border-l-orange-400"
                                                oninput="calculateEstimatedProfit()">
                                        </div>
                                        <p class="mt-1 text-[10px] text-slate-400">
                                            {{ __('Purchase cost per unit for') }} <strong class="text-slate-600">{{ $totalQuantity }}</strong> {{ __('units') }}
                                        </p>
                                        <div id="est-total-cost-preview" class="text-[10px] text-orange-600 font-bold ml-1"></div>
                                    </div>

                                    <!-- Estimated Shipping Cost (TOTAL) -->
                                    <div>
                                        <label for="estimated_shipping_cost" class="block text-[10px] font-bold text-slate-500 uppercase mb-1">
                                            {{ __('Total Estimated Shipping') }}
                                        </label>
                                        <div class="relative rounded-md shadow-sm">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <span class="text-slate-400 sm:text-sm font-serif italic currency-symbol">$</span>
                                            </div>
                                            <input type="number" step="0.01" name="estimated_shipping_cost" id="estimated_shipping_cost" placeholder="0.00" value="{{ old('estimated_shipping_cost', $quotation->estimated_shipping_cost) }}"
                                                class="pl-12 block w-full px-3 py-2 text-sm bg-slate-50 border border-slate-300 text-slate-900 rounded focus:ring-1 focus:ring-orange-500 focus:border-orange-500 transition-colors font-medium"
                                                oninput="calculateEstimatedProfit()">
                                        </div>
                                        <p class="mt-1 text-[10px] text-slate-400">{{ __('Total logistics costs') }}</p>
                                    </div>

                                    <!-- Estimated Other Costs (TOTAL) -->
                                    <div>
                                        <label for="estimated_other_costs" class="block text-[10px] font-bold text-slate-500 uppercase mb-1">
                                            {{ __('Total Other Costs') }}
                                        </label>
                                        <div class="relative rounded-md shadow-sm">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <span class="text-slate-400 sm:text-sm font-serif italic currency-symbol">$</span>
                                            </div>
                                            <input type="number" step="0.01" name="estimated_other_costs" id="estimated_other_costs" placeholder="0.00" value="{{ old('estimated_other_costs', $quotation->estimated_other_costs) }}"
                                                class="pl-12 block w-full px-3 py-2 text-sm bg-slate-50 border border-slate-300 text-slate-900 rounded focus:ring-1 focus:ring-orange-500 focus:border-orange-500 transition-colors font-medium"
                                                oninput="calculateEstimatedProfit()">
                                        </div>
                                        <p class="mt-1 text-[10px] text-slate-400">{{ __('Total customs, taxes, etc.') }}</p>
                                    </div>
                                </div>

                                <!-- Estimated Profit Display -->
                                <div id="profit-preview" class="hidden mt-4 p-4 bg-gradient-to-r from-slate-50 to-slate-100 rounded-lg border border-slate-200">
                                    <div class="flex justify-between items-center mb-2">
                                        <span class="text-xs font-bold text-slate-600 uppercase">{{ __('Estimated Net Profit (Total)') }}</span>
                                        <span id="estimated-profit-amount" class="text-xl font-bold text-slate-900">$0.00</span>
                                    </div>
                                    <div class="flex justify-between items-center text-xs text-slate-500 mb-2">
                                        <span>{{ __('Profit Margin') }}</span>
                                        <span id="estimated-profit-margin" class="font-semibold">0%</span>
                                    </div>
                                    <div class="h-2 bg-slate-200 rounded-full overflow-hidden">
                                        <div id="profit-margin-bar" class="h-full transition-all duration-300" style="width: 0%"></div>
                                    </div>
                                    <p id="margin-warning" class="mt-2 text-xs hidden"></p>
                                </div>
                            </div>
                        </div>

                    </div>
                    
                    <!-- Footer Actions -->
                    <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 flex items-center justify-end gap-3">
                        <a href="{{ route('admin.sourcing-requests.show', $quotation->sourcing_request_id) }}" 
                           class="px-4 py-2 bg-white border border-slate-300 text-slate-700 hover:bg-slate-50 text-sm font-medium rounded transition-colors shadow-sm">
                            {{ __('Cancel') }}
                        </a>
                        <button type="submit" 
                            class="px-6 py-2 bg-slate-900 hover:bg-slate-800 text-white text-sm font-medium rounded transition-colors shadow-sm flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                            {{ __('Update Quotation') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>

            <!-- Info Box -->
            <div class="rounded-lg border border-blue-200 bg-blue-50 p-4">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                         <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-blue-800">{{ __('Important Information') }}</h3>
                        <div class="mt-2 text-sm text-blue-700">
                            <ul class="list-disc pl-5 space-y-1">
                                <li>{{ __('Updating this quotation will notify the client.') }}</li>
                                <li>{{ __('The status will be reset to "Quoted".') }}</li>
                                <li>{{ __('Negotiation notes will be preserved for history.') }}</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    @push('scripts')
    <script>
        // Currency signs map
        const currencySymbols = {
            'USD': '$',
            'EUR': '€',
            'GBP': '£',
            'MAD': 'MAD',
            'JPY': '¥',
            'CNY': '¥',
            'CAD': 'CA$',
            'AUD': 'A$',
            'AED': 'AED'
        };

        function getSelectedCurrencySymbol() {
            const currency = document.getElementById('currency').value;
            return currencySymbols[currency] || '$';
        }

        // Toggle estimates section
        document.getElementById('toggle-estimates').addEventListener('click', function() {
            const section = document.getElementById('estimates-section');
            const icon = document.getElementById('toggle-icon');
            const text = document.getElementById('toggle-text');
            
            section.classList.toggle('hidden');
            icon.classList.toggle('rotate-180');
            text.textContent = section.classList.contains('hidden') ? '{{ __("Show") }}' : '{{ __("Hide") }}';
        });

        // Update currency symbols across the page
        document.getElementById('currency').addEventListener('change', function() {
            const symbol = getSelectedCurrencySymbol();
            const elements = document.querySelectorAll('.currency-symbol');
            elements.forEach(el => {
                el.textContent = symbol;
            });
            calculateEstimatedProfit();
        });

        // Calculate estimated profit in real-time
        function calculateEstimatedProfit() {
            const totalQuantity = {{ $quotation->sourcingRequest->destinations->sum('quantity') }};
            const unitPrice = parseFloat(document.querySelector('[name="unit_price"]')?.value || 0);
            const commission = parseFloat(document.querySelector('[name="commission_service"]')?.value || 0);
            const deliveryCost = parseFloat(document.querySelector('[name="delivery_cost_china"]')?.value || 0);
            
            const unitProductCost = parseFloat(document.getElementById('estimated_product_cost')?.value || 0);
            const shippingCost = parseFloat(document.getElementById('estimated_shipping_cost')?.value || 0);
            const otherCosts = parseFloat(document.getElementById('estimated_other_costs')?.value || 0);

            const symbol = getSelectedCurrencySymbol();

            // Calculate Total Cost Preview (for user feedback)
            const totalCostPreview = document.getElementById('est-total-cost-preview');
            const estimatedTotalProductCost = unitProductCost * totalQuantity;
            
            if (unitProductCost > 0 && totalQuantity > 0) {
                totalCostPreview.textContent = `{{ __('Scale Total') }}: ~${symbol}${estimatedTotalProductCost.toFixed(2)}`;
            } else {
                totalCostPreview.textContent = '';
            }

            // Calculate TOTALS
            // Revenue = Total
            const totalRevenue = (unitPrice * totalQuantity) + commission + deliveryCost;
            
            // Costs = (Unit Cost * Qty) + Shipping + Others
            const totalCosts = estimatedTotalProductCost + shippingCost + otherCosts;
            
            const profit = totalRevenue - totalCosts;
            const margin = totalRevenue > 0 ? (profit / totalRevenue) * 100 : 0;

            // Show/hide profit preview
            const profitPreview = document.getElementById('profit-preview');
            if (unitProductCost > 0 || shippingCost > 0 || otherCosts > 0) {
                profitPreview.classList.remove('hidden');
                
                // Update values
                document.getElementById('estimated-profit-amount').textContent = 
                    symbol + profit.toFixed(2);
                document.getElementById('estimated-profit-margin').textContent = 
                    margin.toFixed(1) + '%';
                
                // Update progress bar
                const bar = document.getElementById('profit-margin-bar');
                bar.style.width = Math.max(0, Math.min(100, margin)) + '%';
                
                // Color coding and warnings
                const warningEl = document.getElementById('margin-warning');
                if (margin < 10) {
                    bar.className = 'h-full bg-red-500 transition-all duration-300';
                    warningEl.textContent = '⚠️ {{ __("Low margin! Consider adjusting prices.") }}';
                    warningEl.className = 'mt-2 text-xs text-red-700 font-semibold';
                    warningEl.classList.remove('hidden');
                } else if (margin < 15) {
                    bar.className = 'h-full bg-amber-500 transition-all duration-300';
                    warningEl.textContent = '⚡ {{ __("Acceptable margin. Review if possible.") }}';
                    warningEl.className = 'mt-2 text-xs text-amber-700 font-semibold';
                    warningEl.classList.remove('hidden');
                } else {
                    bar.className = 'h-full bg-emerald-500 transition-all duration-300';
                    warningEl.textContent = '✓ {{ __("Good profit margin!") }}';
                    warningEl.className = 'mt-2 text-xs text-emerald-700 font-semibold';
                    warningEl.classList.remove('hidden');
                }
            } else {
                profitPreview.classList.add('hidden');
            }
        }

        // Attach listeners to pricing fields
        ['unit_price', 'commission_service', 'delivery_cost_china'].forEach(name => {
            const field = document.querySelector(`[name="${name}"]`);
            if (field) {
                field.addEventListener('input', calculateEstimatedProfit);
            }
        });

        // Toggle sourcing note visibility
        document.getElementById('actual_sourcing_location').addEventListener('change', function() {
            const container = document.getElementById('sourcing_note_container');
            const requestedLocation = "{{ $quotation->sourcingRequest->sourcing_location }}";
            
            if (this.value !== requestedLocation) {
                container.classList.remove('hidden');
            } else {
                container.classList.add('hidden');
            }
        });

        // Image Preview Logic & Size Validation
        const imageInput = document.getElementById('real_product_image');
        const mediaInput = document.getElementById('media_files');
        const previewContainer = document.getElementById('image-preview-container');
        const previewImage = document.getElementById('image-preview');
        const fileSizeErrorEl = document.getElementById('quotation-file-size-error');
        const maxFileSize = 10485760; // 10MB (10 * 1024 * 1024)
        const fileSizeErrorMsg = '{{ __("Un ou plusieurs fichiers dépassent 10 MB. Veuillez choisir des fichiers plus petits.") }}';

        if (mediaInput) {
            mediaInput.addEventListener('change', function() {
                const files = this.files;
                if (files.length > 0) {
                    for (let i = 0; i < files.length; i++) {
                        if (files[i].size > maxFileSize) { // 10MB
                            const fileSizeMB = (files[i].size / 1048576).toFixed(2);
                            if (fileSizeErrorEl) {
                                fileSizeErrorEl.classList.remove('hidden');
                                fileSizeErrorEl.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                            }
                            window.dispatchEvent(new CustomEvent('show-error-toast', { 
                                detail: `{{ __('Le fichier') }} "${files[i].name}" {{ __('est trop volumineux') }} (${fileSizeMB} MB). {{ __('Taille maximale : 10 MB.') }}` 
                            }));
                            this.value = '';
                            if (previewContainer && !previewImage.src.includes('storage/')) {
                                previewContainer.classList.add('hidden');
                            }
                            return;
                        }
                    }

                    // Optional: Preview the first image if it's an image
                    const firstFile = files[0];
                    if (firstFile && firstFile.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            previewImage.src = e.target.result;
                            previewContainer.classList.remove('hidden');
                        }
                        reader.readAsDataURL(firstFile);
                    }
                }
            });
        }

        // Prevent submit if any file exceeds 10MB (avoids 413 Entity Too Large)
        const quotationForm = document.getElementById('quotation-edit-form');
        if (quotationForm) {
            quotationForm.addEventListener('submit', function(e) {
                if (fileSizeErrorEl) fileSizeErrorEl.classList.add('hidden');
                if (imageInput && imageInput.files.length && imageInput.files[0].size > maxFileSize) {
                    e.preventDefault();
                    const fileSizeMB = (imageInput.files[0].size / 1048576).toFixed(2);
                    if (fileSizeErrorEl) { 
                        fileSizeErrorEl.classList.remove('hidden'); 
                        fileSizeErrorEl.scrollIntoView({ behavior: 'smooth', block: 'nearest' }); 
                    }
                    window.dispatchEvent(new CustomEvent('show-error-toast', { 
                        detail: `{{ __('Le fichier image principal dépasse 10 MB') }} (${fileSizeMB} MB). {{ __('Veuillez choisir un fichier plus petit.') }}` 
                    }));
                    return false;
                }
                if (mediaInput && mediaInput.files.length) {
                    for (let i = 0; i < mediaInput.files.length; i++) {
                        if (mediaInput.files[i].size > maxFileSize) {
                            e.preventDefault();
                            const fileSizeMB = (mediaInput.files[i].size / 1048576).toFixed(2);
                            if (fileSizeErrorEl) { 
                                fileSizeErrorEl.classList.remove('hidden'); 
                                fileSizeErrorEl.scrollIntoView({ behavior: 'smooth', block: 'nearest' }); 
                            }
                            window.dispatchEvent(new CustomEvent('show-error-toast', { 
                                detail: `{{ __('Le fichier') }} "${mediaInput.files[i].name}" {{ __('dépasse 10 MB') }} (${fileSizeMB} MB). {{ __('Veuillez choisir un fichier plus petit.') }}` 
                            }));
                            return false;
                        }
                    }
                }
            });
        }

        // Trigger initial calculation
        window.addEventListener('load', () => {
             calculateEstimatedProfit();
             const symbol = getSelectedCurrencySymbol();
             document.querySelectorAll('.currency-symbol').forEach(el => el.textContent = symbol);
        });
    </script>
    @endpush
</x-app-layout>
