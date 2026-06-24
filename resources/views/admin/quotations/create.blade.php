<x-app-layout>
    <!-- Main Container: Clean, premium dashboard gradient background -->
    <div class="min-h-screen bg-slate-50/80 font-sans text-slate-900 pb-12">
        
        <!-- Top Navigation / Breadcrumb Area with soft shadow and backdrop blur -->
        <div class="bg-white border-b border-slate-200 shadow-sm sticky top-0 z-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between h-auto md:h-20 py-4 md:py-0 gap-4">
                    <div class="flex items-center gap-3">
                        <span class="inline-flex items-center justify-center h-11 w-11 rounded-xl bg-gradient-to-br from-orange-500 to-orange-600 text-white shadow-md shadow-orange-500/20">
                            <!-- Pen/Document Icon -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                        </span>
                        <div>
                            <div class="flex items-center gap-2 flex-wrap">
                                <h1 class="text-xl font-extrabold text-slate-900 tracking-tight">{{ __('Create Quotation') }}</h1>
                                <span class="px-2 py-0.5 text-[10px] font-bold bg-slate-100 text-slate-600 rounded border border-slate-200 uppercase tracking-wider">
                                    {{ __('Request ID') }}: #{{ $sourcingRequest->id }}
                                </span>
                            </div>
                            <nav class="flex items-center text-xs text-slate-500 mt-1" aria-label="Breadcrumb">
                                <span class="hover:text-slate-700 transition-colors cursor-pointer">{{ __('Dashboard') }}</span>
                                <span class="mx-2 text-slate-300">/</span>
                                <span class="hover:text-slate-700 transition-colors cursor-pointer">{{ __('Requests') }}</span>
                                <span class="mx-2 text-slate-300">/</span>
                                <span class="font-semibold text-orange-600">{{ __('New Quote') }}</span>
                            </nav>
                        </div>
                    </div>
                    
                    <!-- Global Status Indicators -->
                    <div class="flex items-center gap-3">
                        <div class="flex items-center gap-2 px-3 py-1.5 bg-orange-50/50 border border-orange-100 rounded-lg text-orange-700 shadow-sm shadow-orange-500/5">
                            <span class="inline-flex items-center justify-center w-2.5 h-2.5 rounded-full bg-orange-500 animate-pulse"></span>
                            <span class="text-xs font-bold uppercase tracking-wider">{{ __('Draft Mode') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <form id="quotation-create-form" method="POST" action="{{ route('admin.quotations.store') }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="sourcing_request_id" value="{{ $sourcingRequest->id }}">

                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                    
                    <!-- Left Column: Sourcing Request Details (cols: 5) -->
                    <div class="lg:col-span-5 space-y-6">
                        
                        <!-- Product Specs Card -->
                        <div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden">
                            <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
                                <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">{{ __('Product Specifications') }}</h3>
                                <span class="h-2 w-2 rounded-full bg-orange-500"></span>
                            </div>
                            <div class="p-6 space-y-5 text-sm">
                                <div>
                                    <label class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-400 mb-1">{{ __('Product Name') }}</label>
                                    <div class="text-base font-bold text-slate-800 leading-snug">{{ $sourcingRequest->product_name }}</div>
                                </div>
                                
                                @if($sourcingRequest->product_url)
                                <div>
                                    <label class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-400 mb-1.5">{{ __('Reference Link') }}</label>
                                    <a href="{{ $sourcingRequest->product_url }}" target="_blank" 
                                       class="inline-flex items-center gap-2 px-3 py-1.5 bg-blue-50 hover:bg-blue-100/80 text-blue-600 hover:text-blue-700 font-medium rounded-lg text-xs transition-all border border-blue-100 max-w-full overflow-hidden truncate">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                        <span class="truncate block">{{ $sourcingRequest->product_url }}</span>
                                    </a>
                                </div>
                                @endif
                                
                                <div class="grid grid-cols-2 gap-6 pt-2 border-t border-slate-100">
                                    <div>
                                        <label class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-400 mb-1">{{ __('Category') }}</label>
                                        <div class="font-semibold text-slate-700 bg-slate-100/60 px-2.5 py-1 rounded-md inline-block text-xs">{{ $sourcingRequest->category?->name ?? __('Unclassified') }}</div>
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-400 mb-1">{{ __('Requested Location') }}</label>
                                        <div class="font-semibold text-slate-700 capitalize flex items-center gap-1.5 text-xs">
                                            <span class="inline-block w-2 h-2 rounded-full bg-slate-400"></span>
                                            {{ $sourcingRequest->sourcing_location }}
                                        </div>
                                    </div>
                                </div>
                                
                                @if($sourcingRequest->note)
                                <div class="pt-2 border-t border-slate-100">
                                    <label class="block text-[10px] font-extrabold uppercase tracking-wider text-slate-400 mb-2">{{ __('Client Notes') }}</label>
                                    <div class="p-4 bg-orange-50/30 rounded-xl border border-orange-100/60 text-slate-700 italic relative leading-relaxed text-xs">
                                        <span class="absolute right-3 bottom-1.5 text-orange-200 select-none text-2xl font-serif leading-none">”</span>
                                        {{ $sourcingRequest->note }}
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>

                        <!-- Product Image Card -->
                        <div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden p-5">
                            <label class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-3">{{ __('Requested Product Image') }}</label>
                            @if($sourcingRequest->product_image)
                                <div class="relative group rounded-xl overflow-hidden border border-slate-200 bg-slate-50 max-h-80 flex items-center justify-center shadow-inner">
                                    <img src="{{ asset('storage/' . $sourcingRequest->product_image) }}" 
                                         class="w-full h-auto object-cover max-h-80 transition-transform duration-500 group-hover:scale-105">
                                    <div class="absolute inset-0 bg-slate-900/10 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                                        <span class="px-3 py-1.5 bg-white/90 backdrop-blur text-xs font-semibold rounded-lg text-slate-700 shadow-sm">
                                            {{ __('Zoom View') }}
                                        </span>
                                    </div>
                                </div>
                            @else
                                <div class="h-36 bg-slate-50/50 border-2 border-dashed border-slate-200 rounded-xl flex flex-col items-center justify-center text-slate-400 gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    <span class="text-xs font-medium">{{ __('No image uploaded') }}</span>
                                </div>
                            @endif
                        </div>

                        <!-- Destinations Card -->
                        <div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden">
                            <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
                                <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">{{ __('Destinations & Quantities') }}</h3>
                                <span class="px-2 py-0.5 bg-orange-100 text-orange-700 text-[10px] font-extrabold rounded-md shadow-sm">
                                    {{ $sourcingRequest->destinations->count() }} {{ __('Routes') }}
                                </span>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-slate-100">
                                    <thead class="bg-slate-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-[10px] font-bold text-slate-400 uppercase tracking-wider">{{ __('Country') }}</th>
                                            <th class="px-6 py-3 text-left text-[10px] font-bold text-slate-400 uppercase tracking-wider">{{ __('Service') }}</th>
                                            <th class="px-6 py-3 text-right text-[10px] font-bold text-slate-400 uppercase tracking-wider">{{ __('Qty') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100 bg-white">
                                        @foreach($sourcingRequest->destinations as $dest)
                                        <tr class="hover:bg-slate-50/50 transition-colors">
                                            <td class="px-6 py-3.5 text-sm text-slate-800 font-semibold">
                                                <span class="fi fi-{{ strtolower($dest->country->code) }} w-5 h-4 inline-block align-middle rounded-sm shadow-sm mr-2 border border-slate-100"></span>
                                                <span class="align-middle">{{ $dest->country->name }}</span>
                                            </td>
                                            <td class="px-6 py-3.5 text-sm text-slate-500">
                                                <span class="px-2 py-1 bg-slate-100 text-slate-700 text-xs font-semibold rounded-md border border-slate-200/60">
                                                    {{ $dest->service->name }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-3.5 text-sm text-right font-mono font-bold text-slate-800">
                                                {{ number_format($dest->quantity) }}
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Client Profile Card -->
                        <div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden p-5">
                            <h3 class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400 mb-4">{{ __('Client Profile') }}</h3>
                            <div class="flex items-center gap-4 bg-slate-50 p-3 rounded-xl border border-slate-100">
                                <div class="h-11 w-11 rounded-full bg-gradient-to-br from-orange-100 to-orange-200 text-orange-700 flex items-center justify-center font-extrabold text-base border border-orange-200 shadow-sm">
                                    {{ substr($sourcingRequest->user->name, 0, 1) }}
                                </div>
                                <div class="overflow-hidden">
                                    <h4 class="text-sm font-bold text-slate-900 truncate">{{ $sourcingRequest->user->name }}</h4>
                                    <p class="text-xs text-slate-500 truncate flex items-center gap-1 mt-0.5">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                        {{ $sourcingRequest->user->email }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column: Sourcing Quotation Form (cols: 7) -->
                    <div class="lg:col-span-7 space-y-6">
                        <div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden">
                            <div class="px-6 py-5 border-b border-slate-200 bg-slate-50/50">
                                <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">{{ __('Quotation Details') }}</h3>
                                <p class="text-xs text-slate-500 mt-1 leading-relaxed">{{ __('Please fill in all financial and logistical data accurately to create the quote.') }}</p>
                            </div>

                            <div class="p-6 space-y-8">
                                
                                <!-- Subsection: Basic & Currency -->
                                <div class="space-y-4">
                                    <div class="flex items-center gap-2 pb-2 border-b border-slate-100">
                                        <span class="inline-flex items-center justify-center w-6 h-6 rounded-md bg-slate-100 text-slate-600">
                                            <svg class="w-3.5 h-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        </span>
                                        <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wide">{{ __('Currency & Location Settings') }}</h4>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                        <div>
                                            <label for="currency" class="block text-[10px] font-extrabold text-slate-500 uppercase tracking-wider mb-1.5">{{ __('Currency') }} <span class="text-red-500">*</span></label>
                                            <div class="relative">
                                                <select id="currency" name="currency" required
                                                    class="block w-full pl-3 pr-10 py-2.5 text-sm bg-slate-50 hover:bg-slate-100/50 border border-slate-200 text-slate-900 rounded-xl focus:bg-white focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition-all cursor-pointer">
                                                    <option value="">{{ __('Select currency') }}</option>
                                                    <option value="USD">{{ __('USD - US Dollar') }}</option>
                                                    <option value="EUR">{{ __('EUR - Euro') }}</option>
                                                    <option value="GBP">{{ __('GBP - British Pound') }}</option>
                                                    <option value="MAD">{{ __('MAD - Moroccan Dirham') }}</option>
                                                    <option value="JPY">{{ __('JPY - Japanese Yen') }}</option>
                                                    <option value="CNY">{{ __('CNY - Chinese Yuan') }}</option>
                                                    <option value="CAD">{{ __('CAD - Canadian Dollar') }}</option>
                                                    <option value="AUD">{{ __('AUD - Australian Dollar') }}</option>
                                                    <option value="AED">{{ __('AED - Dirham Imarati') }}</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div>
                                            <label for="actual_sourcing_location" class="block text-[10px] font-extrabold text-slate-500 uppercase tracking-wider mb-1.5">{{ __('Actual Sourcing Location') }} <span class="text-red-500">*</span></label>
                                            <select id="actual_sourcing_location" name="actual_sourcing_location" required
                                                class="block w-full px-3 py-2.5 text-sm bg-slate-50 hover:bg-slate-100/50 border border-slate-200 text-slate-900 rounded-xl focus:bg-white focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition-all cursor-pointer capitalize">
                                                <option value="china" {{ old('actual_sourcing_location', $sourcingRequest->sourcing_location) == 'china' ? 'selected' : '' }}>{{ __('China') }}</option>
                                                <option value="dubai" {{ old('actual_sourcing_location', $sourcingRequest->sourcing_location) == 'dubai' ? 'selected' : '' }}>{{ __('Dubai') }}</option>
                                            </select>
                                            <p class="mt-1 text-[10px] text-slate-400">{{ __('Defaults to the requested location.') }}</p>
                                        </div>
                                    </div>

                                    <!-- Sourcing Note (Warning Card displayed when alternative location selected) -->
                                    <div id="sourcing_note_container" class="{{ old('actual_sourcing_location', $sourcingRequest->sourcing_location) != $sourcingRequest->sourcing_location ? '' : 'hidden' }} p-4 bg-orange-50/50 border border-orange-100 rounded-xl space-y-3 shadow-inner shadow-orange-500/5">
                                        <div class="flex items-center gap-2">
                                            <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                            </svg>
                                            <label for="sourcing_note" class="text-[10px] font-bold text-orange-800 uppercase tracking-wider">
                                                {{ __('Note about Alternative Sourcing') }}
                                            </label>
                                        </div>
                                        <textarea id="sourcing_note" name="sourcing_note" rows="3"
                                            class="block w-full px-3 py-2 text-sm bg-white border border-orange-200 text-slate-900 rounded-xl focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition-all placeholder:text-slate-400"
                                            placeholder="{{ __('Explain why this location was chosen and any impact on delivery...') }}">{{ old('sourcing_note') }}</textarea>
                                        <p class="text-[10px] text-orange-600/90 italic flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            {{ __('This note will be visible to the client to help them understand the change.') }}
                                        </p>
                                    </div>

                                    <div class="space-y-4 pt-2">
                                        <div>
                                            <label for="supplier_url" class="block text-[10px] font-extrabold text-slate-500 uppercase tracking-wider mb-1.5">
                                                {{ __('Supplier Product Link') }} <span class="text-slate-400 font-normal lowercase">({{ __('internal administrative use only') }})</span>
                                            </label>
                                            <div class="relative rounded-xl shadow-sm">
                                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.828a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
                                                </div>
                                                <input type="url" name="supplier_url" id="supplier_url" value="{{ old('supplier_url') }}" placeholder="https://item.taobao.com/..."
                                                    class="w-full pl-10 pr-3 py-2.5 text-sm bg-slate-50 hover:bg-slate-100/50 border border-slate-200 text-slate-900 rounded-xl focus:bg-white focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 block transition-all placeholder:text-slate-400">
                                            </div>
                                            @error('supplier_url')
                                                <p class="text-[10px] text-red-600 mt-1 font-medium">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <div>
                                            <label for="comments" class="block text-[10px] font-extrabold text-slate-500 uppercase tracking-wider mb-1.5">{{ __('Comments') }}</label>
                                            <textarea id="comments" name="comments" rows="3"
                                                class="block w-full px-3.5 py-2.5 text-sm bg-slate-50 hover:bg-slate-100/50 border border-slate-200 text-slate-900 rounded-xl focus:bg-white focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition-all placeholder:text-slate-400"
                                                placeholder="{{ __('Add any internal notes or clarifications for this quotation...') }}">{{ old('comments') }}</textarea>
                                            <p class="mt-1 text-[10px] text-slate-400">{{ __('Visible to the client in quotation details.') }}</p>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Subsection: Quality Pricing Options (Faible, Moyen, Bon) -->
                                <div class="space-y-4">
                                    <div class="flex items-center gap-2 pb-2 border-b border-slate-100">
                                        <span class="inline-flex items-center justify-center w-6 h-6 rounded-md bg-orange-50 text-orange-600">
                                            <svg class="w-3.5 h-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                                            </svg>
                                        </span>
                                        <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wide">{{ __('Quality Pricing Options') }}</h4>
                                        <span class="px-2 py-0.5 bg-slate-100 text-slate-600 text-[9px] font-bold rounded-md uppercase tracking-wider">{{ __('Optional') }}</span>
                                    </div>

                                    <p class="text-xs text-slate-500 leading-relaxed">{{ __('Define alternative pricing based on product quality. Client can choose one of these levels.') }}</p>

                                    <div class="grid grid-cols-1 gap-4">
                                        @foreach(['low' => ['label' => __('Low Quality (Qualité Faible)'), 'color' => 'amber'], 'medium' => ['label' => __('Medium Quality (Qualité Moyenne)'), 'color' => 'blue'], 'good' => ['label' => __('Good Quality (Qualité Bonne)'), 'color' => 'emerald']] as $key => $info)
                                            @php 
                                                $color = $info['color'];
                                                $colorClass = $color === 'amber' ? 'bg-amber-500' : ($color === 'blue' ? 'bg-blue-500' : 'bg-emerald-500');
                                                $borderClass = $color === 'amber' ? 'border-amber-100 hover:border-amber-200 bg-amber-50/5' : ($color === 'blue' ? 'border-blue-100 hover:border-blue-200 bg-blue-50/5' : 'border-emerald-100 hover:border-emerald-200 bg-emerald-50/5');
                                            @endphp
                                            <div class="border rounded-lg p-4 transition-all {{ $borderClass }} space-y-3.5 shadow-sm">
                                                <h5 class="text-xs font-bold text-slate-800 uppercase tracking-wider flex items-center gap-2">
                                                    <span class="w-2.5 h-2.5 rounded-full {{ $colorClass }} shadow-sm"></span>
                                                    {{ $info['label'] }}
                                                </h5>
                                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                    <div>
                                                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">{{ __('Unit Price') }}</label>
                                                        <div class="relative rounded-xl shadow-sm">
                                                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                                                <span class="text-slate-400 text-xs font-semibold currency-symbol">$</span>
                                                            </div>
                                                            <input type="number" step="0.01" name="quality_options[{{ $key }}][price]" 
                                                                value="{{ old('quality_options.'.$key.'.price') }}" 
                                                                placeholder="0.00" 
                                                                class="pl-8 block w-full px-3 py-2 text-sm bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition-all font-semibold text-slate-800">
                                                        </div>
                                                    </div>
                                                    <div x-data="{ 
                                                            previews: [],
                                                            handleFiles(files) {
                                                                this.previews = [];
                                                                for (let i = 0; i < files.length; i++) {
                                                                    const file = files[i];
                                                                    if (file.size > 10485760) {
                                                                        window.dispatchEvent(new CustomEvent('show-error-toast', { 
                                                                            detail: `{{ __('Le fichier') }} '${file.name}' {{ __('dépasse 10 MB. Veuillez choisir des fichiers plus petits.') }}`
                                                                        }));
                                                                        continue;
                                                                    }
                                                                    const reader = new FileReader();
                                                                    reader.onload = (e) => {
                                                                        this.previews.push({
                                                                            name: file.name,
                                                                            src: e.target.result
                                                                        });
                                                                    };
                                                                    reader.readAsDataURL(file);
                                                                }
                                                            }
                                                         }" 
                                                         class="space-y-2">
                                                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">{{ __('Photos') }}</label>
                                                        <input type="file" name="quality_options_images[{{ $key }}][]" accept="image/*" multiple
                                                            @change="handleFiles($event.target.files)"
                                                            class="block w-full text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-[10px] file:font-bold file:uppercase file:tracking-wider file:bg-slate-900 file:text-white hover:file:bg-slate-850 bg-white border border-slate-200 p-1.5 rounded-xl transition-all cursor-pointer">
                                                        
                                                        <!-- Preview thumbnails -->
                                                        <template x-if="previews.length > 0">
                                                            <div class="flex flex-wrap gap-2 pt-1">
                                                                <template x-for="(preview, idx) in previews" :key="idx">
                                                                    <div class="relative h-10 w-10 rounded-lg overflow-hidden border border-slate-200 shadow-sm bg-slate-50 group">
                                                                        <img :src="preview.src" class="h-full w-full object-cover">
                                                                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center pointer-events-none">
                                                                            <span class="text-[6px] text-white font-bold truncate px-0.5" x-text="preview.name"></span>
                                                                        </div>
                                                                    </div>
                                                                </template>
                                                            </div>
                                                        </template>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Subsection: Financial Pricing -->
                                <div class="space-y-4">
                                    <div class="flex items-center gap-2 pb-2 border-b border-slate-100">
                                        <span class="inline-flex items-center justify-center w-6 h-6 rounded-md bg-emerald-50 text-emerald-600">
                                            <svg class="w-3.5 h-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        </span>
                                        <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wide">{{ __('Financial Pricing') }}</h4>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                        <div>
                                            <label for="unit_price" class="block text-[10px] font-extrabold text-slate-500 uppercase tracking-wider mb-1.5">{{ __('Unit Price') }} <span class="text-red-500">*</span></label>
                                            <div class="relative rounded-xl shadow-sm">
                                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                                    <span class="text-slate-400 text-sm font-bold currency-symbol">$</span>
                                                </div>
                                                <input type="number" step="0.01" name="unit_price" id="unit_price" required placeholder="0.00"
                                                    class="pl-10 block w-full px-4 py-2.5 text-sm bg-slate-50 hover:bg-slate-100/50 border border-slate-200 text-slate-900 rounded-xl focus:bg-white focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition-all font-bold">
                                            </div>
                                            <p class="mt-1 text-[10px] text-slate-400">{{ __('Price per unit excluding fees') }}</p>
                                        </div>

                                        <div>
                                            <label for="commission_service" class="block text-[10px] font-extrabold text-slate-500 uppercase tracking-wider mb-1.5">{{ __('Service Commission') }} <span class="text-red-500">*</span></label>
                                            <div class="relative rounded-xl shadow-sm">
                                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                                    <span class="text-slate-400 text-sm font-bold currency-symbol">$</span>
                                                </div>
                                                <input type="number" step="0.01" name="commission_service" id="commission_service" required placeholder="0.00"
                                                    class="pl-10 block w-full px-4 py-2.5 text-sm bg-slate-50 hover:bg-slate-100/50 border border-slate-200 text-slate-900 rounded-xl focus:bg-white focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition-all font-bold">
                                            </div>
                                            <p class="mt-1 text-[10px] text-slate-400">{{ __('Commission amount per unit') }}</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Subsection: Logistics -->
                                <div class="space-y-4">
                                    <div class="flex items-center gap-2 pb-2 border-b border-slate-100">
                                        <span class="inline-flex items-center justify-center w-6 h-6 rounded-md bg-blue-50 text-blue-600">
                                            <svg class="w-3.5 h-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                        </span>
                                        <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wide">{{ __('Logistics & Logistics Costs') }}</h4>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                        <div>
                                            <label for="unit_weight" class="block text-[10px] font-extrabold text-slate-500 uppercase tracking-wider mb-1.5">{{ __('Unit Weight') }} <span class="text-red-500">*</span></label>
                                            <div class="relative rounded-xl shadow-sm flex">
                                                <input type="number" step="0.01" name="unit_weight" id="unit_weight" required placeholder="0.00"
                                                    class="block w-full pl-4 pr-20 py-2.5 text-sm bg-slate-50 hover:bg-slate-100/50 border border-slate-200 text-slate-900 rounded-xl focus:bg-white focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition-all font-bold">
                                                <div class="absolute inset-y-0 right-0 flex items-center pr-1">
                                                    <select name="weight_unit" class="h-8 py-0 pl-2 pr-7 border-transparent bg-slate-100 text-slate-600 text-[10px] font-bold rounded-lg focus:ring-0 focus:border-transparent mr-1 cursor-pointer">
                                                        <option value="g">g</option>
                                                        <option value="kg">kg</option>
                                                        <option value="colis">{{ __('package') }}</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <p class="mt-1 text-[10px] text-slate-400">{{ __('Weight value for routing calculation') }}</p>
                                        </div>

                                        <div>
                                            <label for="delivery_cost_china" class="block text-[10px] font-extrabold text-slate-500 uppercase tracking-wider mb-1.5">{{ __('Shipping Fees') }} <span class="text-red-500">*</span></label>
                                            <div class="relative rounded-xl shadow-sm">
                                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                                    <span class="text-slate-400 text-sm font-bold currency-symbol">$</span>
                                                </div>
                                                <input type="number" step="0.01" name="delivery_cost_china" id="delivery_cost_china" required placeholder="0.00"
                                                    class="pl-10 block w-full px-4 py-2.5 text-sm bg-slate-50 hover:bg-slate-100/50 border border-slate-200 text-slate-900 rounded-xl focus:bg-white focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition-all font-bold">
                                            </div>
                                            <p class="mt-1 text-[10px] text-slate-400">{{ __('Domestic delivery cost') }}</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Subsection: Financial Estimation Dashboard Card -->
                                <div class="bg-gradient-to-tr from-slate-900 to-slate-950 text-white rounded-lg p-5 space-y-4 shadow-xl shadow-slate-900/15 relative overflow-hidden">
                                    
                                    
                                    <div class="flex items-center justify-between border-b border-slate-200 pb-3">
                                        <div class="flex items-center gap-2">
                                            <span class="inline-flex items-center justify-center w-6 h-6 rounded-md bg-orange-100 text-orange-600">
                                                <svg class="w-3.5 h-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                            </span>
                                            <h4 class="text-xs font-bold uppercase tracking-wider text-slate-900">{{ __('Live Cost & Profit Analyzer') }}</h4>
                                        </div>
                                        <button type="button" id="toggle-estimates" class="text-xs text-slate-400 hover:text-white flex items-center gap-1 bg-white px-2.5 py-1 rounded border border-slate-300 shadow-sm transition-all">
                                            <span id="toggle-text">{{ __('Show') }}</span>
                                            <svg id="toggle-icon" class="w-3 h-3 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                                            </svg>
                                        </button>
                                    </div>

                                    <div id="estimates-section" class="hidden space-y-4">
                                        <div class="p-3 bg-slate-100 border border-slate-200 rounded-lg">
                                            <p class="text-[10px] text-slate-600 flex items-start gap-1.5 leading-relaxed">
                                                <svg class="w-4 h-4 text-orange-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                <span>{{ __('Estimate costs to analyze profitability before sending quotation to client.') }}</span>
                                            </p>
                                        </div>

                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                            <!-- Estimated Product Cost (UNIT) -->
                                            <div class="space-y-1.5">
                                                <label for="estimated_product_cost" class="block text-[9px] font-bold text-slate-500 uppercase tracking-wider">
                                                    {{ __('Est. Unit Product Cost') }}
                                                </label>
                                                <div class="relative rounded-xl shadow-sm">
                                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                        <span class="text-slate-500 text-xs font-semibold currency-symbol">$</span>
                                                    </div>
                                                    <input type="number" step="0.01" name="estimated_product_cost" id="estimated_product_cost" placeholder="0.00"
                                                        class="pl-7 block w-full px-2.5 py-2 text-xs bg-white border border-slate-200 text-slate-900 rounded-xl focus:ring-2 focus:ring-orange-500/30 focus:border-orange-500 block transition-all font-semibold"
                                                        oninput="calculateEstimatedProfit()">
                                                </div>
                                                @php $totalQuantity = $sourcingRequest->destinations->sum('quantity'); @endphp
                                                <div class="flex items-center justify-between text-[9px] text-slate-500">
                                                    <span>{{ __('For') }} {{ $totalQuantity }} {{ __('units') }}</span>
                                                    <span id="est-total-cost-preview" class="text-orange-600 font-bold"></span>
                                                </div>
                                            </div>

                                            <!-- Estimated Shipping Cost (TOTAL) -->
                                            <div class="space-y-1.5">
                                                <label for="estimated_shipping_cost" class="block text-[9px] font-bold text-slate-500 uppercase tracking-wider">
                                                    {{ __('Total Est. Shipping') }}
                                                </label>
                                                <div class="relative rounded-xl shadow-sm">
                                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                        <span class="text-slate-500 text-xs font-semibold currency-symbol">$</span>
                                                    </div>
                                                    <input type="number" step="0.01" name="estimated_shipping_cost" id="estimated_shipping_cost" placeholder="0.00"
                                                        class="pl-7 block w-full px-2.5 py-2 text-xs bg-white border border-slate-200 text-slate-900 rounded-xl focus:ring-2 focus:ring-orange-500/30 focus:border-orange-500 block transition-all font-semibold"
                                                        oninput="calculateEstimatedProfit()">
                                                </div>
                                                <p class="text-[9px] text-slate-500">{{ __('Logistics sum total') }}</p>
                                            </div>

                                            <!-- Estimated Other Costs (TOTAL) -->
                                            <div class="space-y-1.5">
                                                <label for="estimated_other_costs" class="block text-[9px] font-bold text-slate-500 uppercase tracking-wider">
                                                    {{ __('Total Other Costs') }}
                                                </label>
                                                <div class="relative rounded-xl shadow-sm">
                                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                        <span class="text-slate-500 text-xs font-semibold currency-symbol">$</span>
                                                    </div>
                                                    <input type="number" step="0.01" name="estimated_other_costs" id="estimated_other_costs" placeholder="0.00"
                                                        class="pl-7 block w-full px-2.5 py-2 text-xs bg-white border border-slate-200 text-slate-900 rounded-xl focus:ring-2 focus:ring-orange-500/30 focus:border-orange-500 block transition-all font-semibold"
                                                        oninput="calculateEstimatedProfit()">
                                                </div>
                                                <p class="text-[9px] text-slate-500">{{ __('Customs, clearance, taxes') }}</p>
                                            </div>
                                        </div>

                                        <!-- Estimated Profit Display Widget -->
                                        <div id="profit-preview" class="hidden mt-4 p-4 bg-slate-50 border border-slate-200 rounded-xl space-y-3.5">
                                            <div class="flex justify-between items-center">
                                                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">{{ __('Estimated Net Profit') }}</span>
                                                <span id="estimated-profit-amount" class="text-2xl font-black text-emerald-600 tracking-tight">$0.00</span>
                                            </div>
                                            <div class="space-y-1.5">
                                                <div class="flex justify-between items-center text-xs font-semibold">
                                                    <span class="text-slate-500">{{ __('Profit Margin') }}</span>
                                                    <span id="estimated-profit-margin" class="text-slate-900">0%</span>
                                                </div>
                                                <div class="h-2.5 bg-slate-200 rounded-full overflow-hidden p-0.5">
                                                    <div id="profit-margin-bar" class="h-full rounded-full transition-all duration-500" style="width: 0%"></div>
                                                </div>
                                            </div>
                                            <div id="margin-warning" class="text-xs font-bold flex items-center gap-1.5"></div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            
                            <!-- Footer Actions with elegant border and spacing -->
                            <div class="px-6 py-5 bg-slate-50 border-t border-slate-200/80 flex items-center justify-end gap-3 rounded-b-2xl">
                                <a href="{{ route('admin.quotations.index') }}" 
                                   class="px-4 py-2.5 bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 text-xs font-bold uppercase tracking-wider rounded-xl transition-all shadow-sm">
                                    {{ __('Cancel') }}
                                </a>
                                <button type="submit" 
                                    class="px-6 py-2.5 bg-slate-900 hover:bg-slate-800 active:bg-slate-950 text-white text-xs font-bold uppercase tracking-wider rounded-xl transition-all shadow-md shadow-slate-900/10 flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                                    {{ __('Create Quotation') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <!-- Important Information footer banner -->
            <div class="mt-8 rounded-lg border border-blue-200 bg-blue-50/50 p-5 shadow-sm">
                <div class="flex items-start gap-3">
                    <div class="flex-shrink-0 p-1 bg-blue-100 rounded-lg text-blue-600">
                         <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-blue-900">{{ __('Important Information') }}</h3>
                        <div class="mt-2 text-xs text-blue-700/95 leading-relaxed">
                            <ul class="list-disc pl-5 space-y-1.5">
                                <li>{{ __('Fields marked with * are mandatory.') }}</li>
                                <li>{{ __('Verify pricing accuracy before submission. Quotations cannot be edited directly after submission without client reject.') }}</li>
                                <li>{{ __('Quotation will be automatically linked to the sourcing request and notify the user via web app notification.') }}</li>
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
            const currencyEl = document.getElementById('currency');
            const currency = currencyEl ? currencyEl.value : 'USD';
            return currencySymbols[currency] || '$';
        }

        // Calculate estimated profit in real-time
        function calculateEstimatedProfit() {
            const totalQuantity = {{ $sourcingRequest->destinations->sum('quantity') }};
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
            
            if (totalCostPreview) {
                if (unitProductCost > 0 && totalQuantity > 0) {
                    totalCostPreview.textContent = `~${symbol}${estimatedTotalProductCost.toFixed(2)}`;
                } else {
                    totalCostPreview.textContent = '';
                }
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
            if (profitPreview) {
                if (unitProductCost > 0 || shippingCost > 0 || otherCosts > 0) {
                    profitPreview.classList.remove('hidden');
                    
                    // Update values
                    const profitAmtEl = document.getElementById('estimated-profit-amount');
                    const profitMarginEl = document.getElementById('estimated-profit-margin');
                    if (profitAmtEl) profitAmtEl.textContent = symbol + profit.toFixed(2);
                    if (profitMarginEl) profitMarginEl.textContent = margin.toFixed(1) + '%';
                    
                    // Update progress bar
                    const bar = document.getElementById('profit-margin-bar');
                    if (bar) bar.style.width = Math.max(0, Math.min(100, margin)) + '%';
                    
                    // Color coding and warnings
                    const warningEl = document.getElementById('margin-warning');
                    if (warningEl) {
                        if (margin < 10) {
                            if (bar) bar.className = 'h-full rounded-full bg-gradient-to-r from-red-500 to-rose-600 transition-all duration-500';
                            warningEl.innerHTML = '⚠️ {{ __("Low margin! Consider adjusting prices.") }}';
                            warningEl.className = 'text-xs text-red-400 font-bold mt-2 flex items-center gap-1';
                            warningEl.classList.remove('hidden');
                        } else if (margin < 15) {
                            if (bar) bar.className = 'h-full rounded-full bg-gradient-to-r from-amber-500 to-orange-500 transition-all duration-500';
                            warningEl.innerHTML = '⚡ {{ __("Acceptable margin. Review if possible.") }}';
                            warningEl.className = 'text-xs text-amber-400 font-bold mt-2 flex items-center gap-1';
                            warningEl.classList.remove('hidden');
                        } else {
                            if (bar) bar.className = 'h-full rounded-full bg-gradient-to-r from-emerald-400 to-teal-500 transition-all duration-500';
                            warningEl.innerHTML = '✓ {{ __("Good profit margin!") }}';
                            warningEl.className = 'text-xs text-emerald-400 font-bold mt-2 flex items-center gap-1';
                            warningEl.classList.remove('hidden');
                        }
                    }
                } else {
                    profitPreview.classList.add('hidden');
                }
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Toggle estimates section
            const toggleEstimatesBtn = document.getElementById('toggle-estimates');
            if (toggleEstimatesBtn) {
                toggleEstimatesBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const section = document.getElementById('estimates-section');
                    const icon = document.getElementById('toggle-icon');
                    const text = document.getElementById('toggle-text');
                    
                    if (section) section.classList.toggle('hidden');
                    if (icon) icon.classList.toggle('rotate-180');
                    if (text) {
                        text.textContent = (section && section.classList.contains('hidden')) 
                            ? '{{ __("Show") }}' 
                            : '{{ __("Hide") }}';
                    }
                });
            }

            // Update currency symbols across the page
            const currencySelect = document.getElementById('currency');
            if (currencySelect) {
                currencySelect.addEventListener('change', function() {
                    const symbol = getSelectedCurrencySymbol();
                    const elements = document.querySelectorAll('.currency-symbol');
                    elements.forEach(el => {
                        el.textContent = symbol;
                    });
                    calculateEstimatedProfit();
                });
            }

            // Attach listeners to pricing fields
            ['unit_price', 'commission_service', 'delivery_cost_china'].forEach(name => {
                const field = document.querySelector(`[name="${name}"]`);
                if (field) {
                    field.addEventListener('input', calculateEstimatedProfit);
                }
            });

            // Toggle sourcing note visibility
            const actualSourcingLoc = document.getElementById('actual_sourcing_location');
            if (actualSourcingLoc) {
                actualSourcingLoc.addEventListener('change', function() {
                    const container = document.getElementById('sourcing_note_container');
                    const requestedLocation = "{{ strtolower($sourcingRequest->sourcing_location) }}";
                    const selectedLocation = this.value.toLowerCase();
                    
                    if (container) {
                        if (selectedLocation !== requestedLocation) {
                            container.classList.remove('hidden');
                        } else {
                            container.classList.add('hidden');
                        }
                    }
                });
            }

            // Prevent submit if any file exceeds 10MB (avoids 413 Entity Too Large)
            const quotationForm = document.getElementById('quotation-create-form');
            if (quotationForm) {
                quotationForm.addEventListener('submit', function(e) {
                    const maxFileSize = 10485760; // 10MB
                    const qualityKeys = ['low', 'medium', 'good'];
                    for (const key of qualityKeys) {
                        const fileInput = document.querySelector(`input[name="quality_options_images[${key}][]"]`);
                        if (fileInput && fileInput.files.length) {
                            for (let i = 0; i < fileInput.files.length; i++) {
                                const file = fileInput.files[i];
                                if (file.size > maxFileSize) {
                                    e.preventDefault();
                                    const fileSizeMB = (file.size / 1048576).toFixed(2);
                                    window.dispatchEvent(new CustomEvent('show-error-toast', { 
                                        detail: `{{ __('Le fichier') }} "${file.name}" ({{ __('Qualité') }} ${key}) {{ __('dépasse 10 MB') }} (${fileSizeMB} MB). {{ __('Veuillez choisir des fichiers plus petits.') }}` 
                                    }));
                                    return false;
                                }
                            }
                        }
                    }
                });
            }
        });
    </script>
    @endpush
</x-app-layout>
