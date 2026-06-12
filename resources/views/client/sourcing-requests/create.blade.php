<x-app-layout>
    

    <div class="py-12 bg-slate-50 dark:bg-slate-900 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-4">
            {{-- Progress Steps --}}
            <div class="bg-white dark:bg-slate-800 shadow-sm rounded-lg border border-[#EBEBEB] dark:border-slate-700 p-6 mb-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 bg-[#EF7722] text-white rounded-full flex items-center justify-center text-sm font-bold">1</div>
                        <span class="text-sm font-semibold text-slate-900 dark:text-white">{{ __('Product Details') }}</span>
                    </div>
                    <div class="h-0.5 flex-1 mx-4 bg-[#EBEBEB] dark:bg-slate-700"></div>
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 bg-[#EBEBEB] dark:bg-slate-700 text-slate-500 rounded-full flex items-center justify-center text-sm font-bold">2</div>
                        <span class="text-sm font-semibold text-slate-500 dark:text-slate-400">{{ __('Destinations') }}</span>
                    </div>
                    <div class="h-0.5 flex-1 mx-4 bg-[#EBEBEB] dark:bg-slate-700"></div>
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 bg-[#EBEBEB] dark:bg-slate-700 text-slate-500 rounded-full flex items-center justify-center text-sm font-bold">2</div>
                        <span class="text-sm font-semibold text-slate-500 dark:text-slate-400">{{ __('Destinations') }}</span>
                    </div>
                </div>
            </div>

            <form method="POST" action="{{ route('client.sourcing-requests.store') }}" enctype="multipart/form-data"
                  x-data="sourcingRequestForm" @submit.prevent="submitForm"
                  data-translation-destination-required="{{ __('At least one destination is required!') }}"
                  data-translation-getting-location="{{ __('Getting your location...') }}"
                  data-translation-location-captured="{{ __('Location captured successfully!') }}"
                  data-translation-location-error="{{ __('Unable to retrieve your location.') }}"
                  data-translation-geolocation-unsupported="{{ __('Geolocation is not supported by your browser.') }}">
                @csrf

                <!-- Global Error Alert -->
                <div id="global-errors" class="hidden mb-6 p-4 rounded-xl bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800/30">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-red-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <div>
                            <h4 class="text-sm font-bold text-red-800 dark:text-red-300">{{ __('Validation Errors') }}</h4>
                            <ul id="global-errors-list" class="mt-1 text-sm text-red-700 dark:text-red-400 list-disc list-inside"></ul>
                        </div>
                    </div>
                </div>

                <!-- Step 1: Product Details -->
                <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-[#EBEBEB] dark:border-slate-700 p-6 sm:p-8 mb-6">
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-6 flex items-center gap-3">
                        <span class="w-8 h-8 bg-[#EF7722] text-white rounded-full flex items-center justify-center text-sm font-bold">1</span>
                        {{ __('Product Details') }}
                    </h3>

                    <div class="space-y-5">
                        <!-- Product Name & Image Row -->
                        <div class="grid grid-cols-1 sm:grid-cols-4 gap-6">
                            <!-- Left: Form Fields (3 columns) -->
                            <div class="sm:col-span-3 space-y-5">
                                <!-- Product Name -->
                                <div>
                                    <label for="product_name" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">
                                        {{ __('What product are you looking for?') }} <span class="text-red-500">*</span>
                                    </label>
                                    <x-text-input id="product_name" 
                                                  class="block w-full rounded-lg border-[#EBEBEB] dark:border-slate-600 focus:border-[#EF7722] focus:ring-[#EF7722] shadow-sm dark:bg-slate-700 dark:text-white text-sm" 
                                                  type="text" 
                                                  name="product_name" 
                                                  :value="old('product_name')" 
                                                  required 
                                                  autofocus 
                                                  placeholder="{{ __('E.g., Wireless Headphones, LED Bulbs...') }}" />
                                    <x-input-error :messages="$errors->get('product_name')" class="mt-2" />
                                </div>

                                <!-- Product URL -->
                                <div>
                                    <label for="product_url" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">
                                        {{ __('Product Link (Alibaba/Amazon/etc)') }} <span class="text-red-500">*</span>
                                    </label>
                                    <x-text-input id="product_url" 
                                                  class="block w-full rounded-lg border-[#EBEBEB] dark:border-slate-600 focus:border-[#EF7722] focus:ring-[#EF7722] shadow-sm dark:bg-slate-700 dark:text-white text-sm" 
                                                  type="text" 
                                                  name="product_url" 
                                                  :value="old('product_url')" 
                                                  required 
                                                  placeholder="{{ __('Product link placeholder') }}" />
                                    <x-input-error :messages="$errors->get('product_url')" class="mt-2" />
                                </div>

                                <!-- Category & Location Grid -->
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label for="category_id" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">
                                            {{ __('Category') }} <span class="text-red-500">*</span>
                                        </label>
                                        <select id="category_id"
                                                name="category_id"
                                                class="block w-full rounded-lg border-[#EBEBEB] dark:border-slate-600 focus:border-[#EF7722] focus:ring-[#EF7722] text-slate-900 dark:text-white shadow-sm dark:bg-slate-700 text-sm tom-select-category" 
                                                required>
                                            <option value="">{{ __('Select a category') }}</option>
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                        <x-input-error :messages="$errors->get('category_id')" class="mt-2" />
                                    </div>

                                    <div>
                                        <label for="sourcing_location" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">
                                            {{ __('Source from') }} <span class="text-red-500">*</span>
                                        </label>
                                        <select id="sourcing_location" 
                                                name="sourcing_location" 
                                                class="block w-full rounded-lg border-[#EBEBEB] dark:border-slate-600 focus:border-[#EF7722] focus:ring-[#EF7722] text-slate-900 dark:text-white shadow-sm dark:bg-slate-700 text-sm tom-select-location" 
                                                required>
                                            <option value="">{{ __('Select location') }}</option>
                                            <option value="china" {{ old('sourcing_location') == 'china' ? 'selected' : '' }}>{{ __('China') }}</option>
                                            <option value="dubai" {{ old('sourcing_location') == 'dubai' ? 'selected' : '' }}>{{ __('Dubai') }}</option>
                                        </select>
                                        <x-input-error :messages="$errors->get('sourcing_location')" class="mt-2" />
                                    </div>
                                </div>
                            </div>

                            <!-- Right: Product Image (1 column) -->
                            <div class="sm:col-span-1 flex flex-col items-center">
                                <label for="product_image" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2 text-center w-full">
                                    {{ __('Photo') }}
                                </label>
                                <div class="relative w-32 h-32 bg-slate-50 dark:bg-slate-700 rounded-lg overflow-hidden border-2 border-dashed border-[#EBEBEB] dark:border-slate-600 group hover:border-[#EF7722] transition-all cursor-pointer shadow-sm">
                                    <img id="product_image_preview" 
                                         class="w-full h-full object-cover opacity-0 transition-opacity duration-300" 
                                         src="" 
                                         alt="{{ __('Product preview') }}" />
                                    <div id="placeholder" 
                                         class="absolute inset-0 flex flex-col items-center justify-center text-slate-400 dark:text-slate-500 group-hover:text-[#EF7722] transition-colors">
                                        <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        <span class="text-xs font-medium text-center">{{ __('Upload') }}</span>
                                    </div>
                                    <label for="product_image" class="absolute inset-0 cursor-pointer"></label>
                                    <input id="product_image" type="file" class="sr-only" name="product_image" accept="image/jpeg,image/png,image/jpg,image/gif,image/svg+xml,image/webp" required />
                                </div>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-2 text-center">{{ __('Max 15MB') }} <span class="text-red-500">*</span></p>
                            </div>
                        </div>

                        <!-- Shipping Method -->
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-3">
                                {{ __('Preferred Shipping') }} <span class="text-red-500">*</span>
                            </label>
                            <div class="grid grid-cols-2 gap-3">
                                <label class="relative flex items-center p-3 border-2 border-[#EBEBEB] dark:border-slate-600 rounded-lg cursor-pointer hover:border-[#EF7722] dark:hover:border-[#EF7722] transition-all has-[:checked]:border-[#EF7722] has-[:checked]:bg-[#EF7722]/5 has-[:checked]:shadow-sm">
                                    <input type="radio" id="shipping_method_air" name="shipping_method" value="air" class="sr-only" {{ old('shipping_method') == 'air' ? 'checked' : '' }} required>
                                    <div class="flex items-center gap-2 w-full">
                                        <svg class="w-5 h-5 text-[#EF7722] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                        </svg>
                                        <span class="text-sm font-semibold text-slate-700 dark:text-slate-300">{{ __('Air (Fast)') }}</span>
                                    </div>
                                </label>

                                <label class="relative flex items-center p-3 border-2 border-[#EBEBEB] dark:border-slate-600 rounded-lg cursor-pointer hover:border-[#EF7722] dark:hover:border-[#EF7722] transition-all has-[:checked]:border-[#EF7722] has-[:checked]:bg-[#EF7722]/5 has-[:checked]:shadow-sm">
                                    <input type="radio" id="shipping_method_sea" name="shipping_method" value="sea" class="sr-only" {{ old('shipping_method') == 'sea' ? 'checked' : '' }} required>
                                    <div class="flex items-center gap-2 w-full">
                                        <svg class="w-5 h-5 text-[#EF7722] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8 4-8-4m16 0l-8-4m8 4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                        </svg>
                                        <span class="text-sm font-semibold text-slate-700 dark:text-slate-300">{{ __('Sea (Economy)') }}</span>
                                    </div>
                                </label>
                            </div>
                            <x-input-error :messages="$errors->get('shipping_method')" class="mt-2" />
                        </div>

                        <!-- Additional Notes -->
                        <div>
                            <label for="note" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">
                                {{ __('Any special requirements?') }} <span class="text-red-500">*</span>
                            </label>
                            <textarea id="note" 
                                      rows="2" 
                                      class="block w-full rounded-lg border-[#EBEBEB] dark:border-slate-600 focus:border-[#EF7722] focus:ring-[#EF7722] resize-none shadow-sm dark:bg-slate-700 dark:text-white text-sm" 
                                      name="note" 
                                      required
                                      placeholder="{{ __('Colors, sizes, materials, quality requirements...') }}">{{ old('note') }}</textarea>
                            <x-input-error :messages="$errors->get('note')" class="mt-2" />
                        </div>
                    </div>
                </div>

                <!-- Step 2: Shipping Destinations -->
                <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-[#EBEBEB] dark:border-slate-700 p-6 sm:p-8 mb-6">
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-6 flex items-center gap-3">
                        <span class="w-8 h-8 bg-[#EF7722] text-white rounded-full flex items-center justify-center text-sm font-bold">2</span>
                        {{ __('Shipping Destinations') }}
                    </h3>

                    <div id="destination-fields-container" class="space-y-4">
                        <div class="destination-block p-4 bg-[#EBEBEB] dark:bg-slate-700 rounded-lg border border-[#EBEBEB] dark:border-slate-600">
                            <div class="space-y-3">
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                    <div>
                                        <label for="destinations_0_quantity" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5 uppercase tracking-wide">
                                            {{ __('Quantity') }}
                                        </label>
                                        <x-text-input type="number" 
                                                      id="destinations_0_quantity"
                                                      name="destinations[0][quantity]" 
                                                      value="{{ old('destinations.0.quantity') }}" 
                                                      required 
                                                      min="1" 
                                                      class="w-full rounded-lg text-sm shadow-sm dark:bg-slate-800 dark:text-white border-[#EBEBEB] dark:border-slate-600 focus:border-[#EF7722] focus:ring-[#EF7722]" 
                                                      placeholder="{{ __('Quantity placeholder') }}" />
                                    </div>

                                    <div>
                                        <label for="destinations_0_country_id" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5 uppercase tracking-wide">
                                            {{ __('Country') }}
                                        </label>
                                        <select id="destinations_0_country_id"
                                                name="destinations[0][country_id]" 
                                                required 
                                                class="block w-full rounded-lg border-[#EBEBEB] dark:border-slate-600 focus:border-[#EF7722] focus:ring-[#EF7722] text-sm tom-select-country shadow-sm dark:bg-slate-800 dark:text-white">
                                            <option value="">{{ __('Select') }}</option>
                                            @foreach($countries as $country)
                                                <option value="{{ $country->id }}" data-flag="{{ strtolower($country->code) }}">
                                                    {{ $country->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div>
                                        <label for="destinations_0_service_id" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5 uppercase tracking-wide">
                                            {{ __('Service') }}
                                        </label>
                                        <select id="destinations_0_service_id"
                                                name="destinations[0][service_id]" 
                                                required 
                                                class="block w-full rounded-lg border-[#EBEBEB] dark:border-slate-600 focus:border-[#EF7722] focus:ring-[#EF7722] text-sm shadow-sm dark:bg-slate-800 dark:text-white tom-select-service">
                                            <option value="">{{ __('Select') }}</option>
                                            @foreach($services as $service)
                                                <option value="{{ $service->id }}">{{ $service->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="mt-3">
                                    <label for="destinations_0_address" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5 uppercase tracking-wide">
                                        {{ __('Delivery Address') }}
                                    </label>
                                    <textarea id="destinations_0_address" 
                                              name="destinations[0][address]" 
                                              rows="2" 
                                              required
                                              class="block w-full rounded-lg border-[#EBEBEB] dark:border-slate-600 focus:border-[#EF7722] focus:ring-[#EF7722] resize-none shadow-sm dark:bg-slate-800 dark:text-white text-sm" 
                                              placeholder="{{ __('Full address for this destination') }}">{{ old('destinations.0.address') }}</textarea>
                                </div>

                                <button type="button" 
                                        @click="removeDestination($event)"
                                        class="remove-destination w-full sm:w-auto px-3 py-2 text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 border border-red-300 dark:border-red-700/30 rounded-lg transition-all text-sm font-medium flex items-center justify-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                    {{ __('Remove') }}
                                </button>
                            </div>
                        </div>
                    </div>

                    <button type="button" 
                            @click="addDestination"
                            class="mt-4 w-full py-2.5 text-[#EF7722] dark:text-[#FAA533] border border-[#EF7722] dark:border-[#FAA533] hover:bg-[#EF7722]/5 dark:hover:bg-[#FAA533]/5 rounded-lg text-sm font-semibold transition-all flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        {{ __('Add Another Destination') }}
                    </button>
                </div>


                <!-- Submit Buttons -->
                <div class="flex flex-col-reverse sm:flex-row gap-3 justify-end">
                    <a href="{{ route('client.dashboard') }}" 
                       class="py-3 px-6 text-center text-slate-700 dark:text-slate-300 bg-white dark:bg-slate-800 border border-[#EBEBEB] dark:border-slate-600 rounded-lg font-semibold hover:bg-slate-50 dark:hover:bg-slate-700 transition-all">
                        {{ __('Cancel') }}
                    </a>
                    <button type="submit" 
                            class="py-3 px-8 bg-[#EF7722] hover:bg-[#FAA533] text-white rounded-lg font-bold transition-all flex items-center justify-center gap-2 shadow-md hover:shadow-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        {{ __('Submit Request') }}
                    </button>
                </div>

            <!-- Shipping Fee Confirmation Modal -->
            <div x-show="showFeeModal" x-cloak class="fee-modal-overlay" style="display: none;">
                <div class="fee-modal fee-modal-animate" @click.outside="cancelFeeModal">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-6">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-[#EF7722]/10 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-[#EF7722]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-slate-900 dark:text-white">{{ __('Shipping Fees Summary') }}</h3>
                                    <p class="text-sm text-slate-500 dark:text-slate-400">{{ __('Review estimated shipping costs before submitting') }}</p>
                                </div>
                            </div>
                            <button type="button" @click="cancelFeeModal" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>

                        <template x-if="loadingFees">
                            <div class="flex items-center justify-center py-16">
                                <svg class="w-8 h-8 text-[#EF7722] animate-spin" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                                </svg>
                                <span class="ml-3 text-sm text-slate-600 dark:text-slate-400">{{ __('Fetching shipping rates...') }}</span>
                            </div>
                        </template>

                        <template x-if="!loadingFees && feeDestinations.length > 0">
                            <div class="space-y-6">
                                <template x-for="(dest, dIdx) in feeDestinations" :key="dIdx">
                                    <div>
                                        <div class="flex items-center gap-3 mb-3">
                                            <span class="fi fi-" x-bind:class="'fi-' + (dest.country_code || '').toLowerCase() + ' text-xl rounded-md shadow-sm'"></span>
                                            <div>
                                                <h4 class="text-sm font-bold text-slate-900 dark:text-white" x-text="dest.country_name"></h4>
                                                <p class="text-xs text-slate-500 dark:text-slate-400">
                                                    <span x-text="'{{ __("Source") }}: ' + (dest.sourcing === 'china' ? '{{ __("China") }}' : '{{ __("Dubai") }}')"></span>
                                                    <span class="mx-2">·</span>
                                                    <span x-text="'{{ __("Qty") }}: ' + dest.quantity"></span>
                                                    <span class="mx-2">·</span>
                                                    <span x-text="dest.transport === 'air' ? '{{ __("Air") }}' : '{{ __("Sea") }}'"></span>
                                                    <template x-if="dest.arrival_time">
                                                        <span><span class="mx-2">·</span> <span x-text="'{{ __("Est") }}: ' + dest.arrival_time + ' {{ __("days") }}'"></span></span>
                                                    </template>
                                                </p>
                                            </div>
                                        </div>

                                        <template x-if="dest.items && dest.items.length > 0">
                                            <div class="overflow-hidden rounded-xl border border-slate-200 dark:border-slate-600">
                                                <table class="min-w-full text-sm">
                                                    <thead>
                                                        <tr class="bg-slate-50 dark:bg-slate-700/50 border-b border-slate-200 dark:border-slate-600">
                                                            <th class="px-4 py-2.5 text-left text-[10px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">{{ __('Item Style') }}</th>
                                                            <th class="px-4 py-2.5 text-right text-[10px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">{{ __('Price') }} / <span x-text="dest.unit"></span></th>
                                                            <th class="px-4 py-2.5 text-center text-[10px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">{{ __('Delay') }}</th>
                                                            <th class="px-4 py-2.5 text-right text-[10px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">{{ __('Est. Total') }}</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <template x-for="(item, iIdx) in dest.items" :key="item.id || iIdx">
                                                            <tr class="border-b border-slate-100 dark:border-slate-700 last:border-b-0">
                                                                <td class="px-4 py-2.5 text-sm font-medium text-slate-900 dark:text-white" x-text="item.item_style"></td>
                                                                <td class="px-4 py-2.5 text-right font-mono text-sm text-slate-900 dark:text-white">
                                                                    <span x-text="parseFloat(item.price_per_kg).toFixed(2)"></span>
                                                                    <span class="text-[10px] text-slate-400 ml-1" x-text="item.currency"></span>
                                                                </td>
                                                                <td class="px-4 py-2.5 text-center">
                                                                    <template x-if="item.estimation_days">
                                                                        <span class="inline-flex items-center rounded-full bg-orange-50 dark:bg-orange-950/40 px-2 py-0.5 text-[10px] font-bold text-orange-700 dark:text-orange-300" x-text="item.estimation_days + ' ' + item.estimation_unit"></span>
                                                                    </template>
                                                                    <template x-if="!item.estimation_days">
                                                                        <span class="text-slate-300 dark:text-slate-600">—</span>
                                                                    </template>
                                                                </td>
                                                                <td class="px-4 py-2.5 text-right font-mono text-sm font-bold text-slate-900 dark:text-white">
                                                                    <span x-text="(parseFloat(item.price_per_kg) * parseInt(dest.quantity)).toFixed(2)"></span>
                                                                    <span class="text-[10px] text-slate-400 ml-1" x-text="item.currency"></span>
                                                                </td>
                                                            </tr>
                                                        </template>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </template>

                                        <template x-if="!dest.items || dest.items.length === 0">
                                            <div class="rounded-xl border border-dashed border-slate-200 dark:border-slate-600 p-6 text-center">
                                                <p class="text-sm text-slate-500 dark:text-slate-400">{{ __('No shipping rates available for this destination and transport method.') }}</p>
                                            </div>
                                        </template>
                                    </div>
                                </template>
                            </div>
                        </template>

                        <template x-if="!loadingFees">
                            <div class="flex items-center justify-end gap-3 mt-6 pt-4 border-t border-slate-200 dark:border-slate-700">
                                <button type="button" @click="cancelFeeModal"
                                        class="px-5 py-2.5 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 text-sm font-medium rounded-xl transition-colors">
                                    {{ __('Cancel') }}
                                </button>
                                <button type="button" @click="confirmSubmit"
                                        class="px-5 py-2.5 bg-[#EF7722] hover:bg-[#FAA533] text-white text-sm font-bold rounded-xl transition-colors shadow-sm inline-flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    {{ __('Confirm & Submit') }}
                                </button>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tom-select/2.2.2/css/tom-select.bootstrap5.min.css" rel="stylesheet"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tom-select/2.2.2/js/tom-select.complete.min.js"></script>
    <script>
        document.addEventListener('alpine:init', () => {

            const input = document.getElementById('product_image');
            const preview = document.getElementById('product_image_preview');
            const placeholder = document.getElementById('placeholder');

            if (input) {
                input.onchange = (e) => {
                    const file = e.target.files[0];
                    if (file) {
                        // 15MB = 15 * 1024 * 1024 bytes = 15728640 bytes
                        if (file.size > 15728640) {
                            window.dispatchEvent(new CustomEvent('show-error-toast', { 
                                detail: '{{ __("File size exceeds 15MB. Please choose a smaller file.") }}' 
                            }));
                            input.value = '';
                            preview.style.opacity = '0';
                            placeholder.style.display = 'flex';
                            return;
                        }

                        const reader = new FileReader();
                        reader.onload = ev => {
                            preview.src = ev.target.result;
                            preview.style.opacity = '1';
                            placeholder.style.display = 'none';
                        };
                        reader.readAsDataURL(file);
                    }
                };
            }

            const initializeCountryTomSelect = (element) => {
                if (element.tomselect) {
                    element.tomselect.destroy();
                }
                new TomSelect(element, {
                    searchField: 'text',
                    openOnFocus: true,
                    placeholder: '{{ __("Select Country") }}',
                    render: {
                        option: function(data) {
                            const flag = data.flag ? `<span class="fi fi-${data.flag} mr-2"></span>` : '';
                            return `<div class="flex items-center gap-2">${flag}<span>${data.text}</span></div>`;
                        },
                        item: function(data) {
                            const flag = data.flag ? `<span class="fi fi-${data.flag} mr-2"></span>` : '';
                            return `<div class="flex items-center gap-2">${flag}<span>${data.text}</span></div>`;
                        }
                    }
                });
            };

            const initializeGenericTomSelect = (element) => {
                if (element.tomselect) {
                    element.tomselect.destroy();
                }
                new TomSelect(element, {
                    searchField: 'text',
                    openOnFocus: true
                });
            };

            Alpine.data('sourcingRequestForm', () => ({
                showFeeModal: false,
                feeDestinations: [],
                loadingFees: false,
                selectedShippingMethod: '',
                selectedSourcingLocation: '',

                getNewIndex() {
                    const existingInputs = document.querySelectorAll('#destination-fields-container [name^="destinations"]');
                    if (existingInputs.length === 0) return 0;
                    const maxIndex = Array.from(existingInputs).reduce((max, input) => {
                        const match = input.name.match(/\[(\d+)\]/);
                        return match ? Math.max(max, parseInt(match[1])) : max;
                    }, 0);
                    return maxIndex + 1;
                },

                getDestinationTemplate(newIndex) {
                    const div = document.createElement('div');
                    div.classList.add('destination-block', 'p-4', 'bg-[#EBEBEB]', 'dark:bg-slate-700', 'rounded-lg', 'border', 'border-[#EBEBEB]', 'dark:border-slate-600');
                    div.innerHTML = `
                        <div class="space-y-3">
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                <div>
                                    <label for="destinations_${newIndex}_quantity" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5 uppercase tracking-wide">{{ __('Quantity') }}</label>
                                    <input type="number" id="destinations_${newIndex}_quantity" name="destinations[${newIndex}][quantity]" required min="1" class="w-full rounded-lg text-sm shadow-sm dark:bg-slate-800 dark:text-white border-[#EBEBEB] dark:border-slate-600 focus:border-[#EF7722] focus:ring-[#EF7722]" placeholder="{{ __('Quantity placeholder') }}" />
                                </div>
                                <div>
                                    <label for="destinations_${newIndex}_country_id" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5 uppercase tracking-wide">{{ __('Country') }}</label>
                                    <select id="destinations_${newIndex}_country_id" name="destinations[${newIndex}][country_id]" required class="block w-full rounded-lg border-[#EBEBEB] dark:border-slate-600 focus:border-[#EF7722] focus:ring-[#EF7722] text-sm tom-select-country shadow-sm dark:bg-slate-800 dark:text-white">
                                        <option value="">{{ __('Select') }}</option>
                                        @foreach($countries as $country)
                                            <option value="{{ $country->id }}" data-flag="{{ strtolower($country->code) }}">{{ $country->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label for="destinations_${newIndex}_service_id" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5 uppercase tracking-wide">{{ __('Service') }}</label>
                                    <select id="destinations_${newIndex}_service_id" name="destinations[${newIndex}][service_id]" required class="block w-full rounded-lg border-[#EBEBEB] dark:border-slate-600 focus:border-[#EF7722] focus:ring-[#EF7722] text-sm shadow-sm dark:bg-slate-800 dark:text-white tom-select-service">
                                        <option value="">{{ __('Select') }}</option>
                                        @foreach($services as $service)
                                            <option value="{{ $service->id }}">{{ $service->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="mt-3">
                                <label for="destinations_${newIndex}_address" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5 uppercase tracking-wide">{{ __('Delivery Address') }}</label>
                                <textarea id="destinations_${newIndex}_address" name="destinations[${newIndex}][address]" rows="2" required class="block w-full rounded-lg border-[#EBEBEB] dark:border-slate-600 focus:border-[#EF7722] focus:ring-[#EF7722] resize-none shadow-sm dark:bg-slate-800 dark:text-white text-sm" placeholder="{{ __('Full address for this destination') }}"></textarea>
                            </div>
                            <button type="button" @click="removeDestination($event)" class="remove-destination w-full sm:w-auto px-3 py-2 text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 border border-red-300 dark:border-red-700/30 rounded-lg transition-all text-sm font-medium flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                                {{ __('Remove') }}
                            </button>
                        </div>
                    `;
                    return div;
                },

                addDestination() {
                    const container = document.getElementById('destination-fields-container');
                    const newIndex = this.getNewIndex();
                    const newBlock = this.getDestinationTemplate(newIndex);
                    container.appendChild(newBlock);
                    this.$nextTick(() => {
                        initializeCountryTomSelect(newBlock.querySelector('.tom-select-country'));
                        initializeGenericTomSelect(newBlock.querySelector('.tom-select-service'));
                    });
                },

                removeDestination(event) {
                    const container = document.getElementById('destination-fields-container');
                    const blocks = container.querySelectorAll('.destination-block');
                    const block = event.target.closest('.destination-block');
                    
                    if (blocks.length > 1) {
                        const countrySelect = block.querySelector('.tom-select-country');
                        if (countrySelect && countrySelect.tomselect) {
                            countrySelect.tomselect.destroy();
                        }
                        const serviceSelect = block.querySelector('.tom-select-service');
                        if (serviceSelect && serviceSelect.tomselect) {
                            serviceSelect.tomselect.destroy();
                        }
                        
                        block.style.opacity = '0';
                        block.style.transform = 'scale(0.95)';
                        
                        setTimeout(() => {
                            block.remove();
                            document.querySelectorAll('.destination-block').forEach((b, idx) => {
                                b.querySelectorAll('input, select').forEach(el => {
                                    if (el.hasAttribute('name')) {
                                        const name = el.getAttribute('name').replace(/destinations\[\d+\]/, `destinations[${idx}]`);
                                        el.setAttribute('name', name);
                                    }
                                    if (el.id) {
                                        const id = el.getAttribute('id').replace(/destinations_\d+_/, `destinations_${idx}_`);
                                        el.setAttribute('id', id);
                                    }
                                });
                            });
                        }, 300);
                    } else {
                        alert(this.$el.dataset.translationDestinationRequired);
                    }
                },

                getFeeUrl(countryId, transport, sourcing) {
                    const actionUrl = this.$el.getAttribute('action');
                    const baseUrl = actionUrl.replace(/sourcing-requests(\/create)?$/, 'shipping-fees');
                    return `${baseUrl}/${countryId}?transport=${transport}&sourcing=${sourcing}`;
                },

                async submitForm(event) {
                    event.preventDefault();
                    const form = event.target;

                    // Collect form data
                    const shippingMethod = form.querySelector('[name="shipping_method"]:checked')?.value;
                    const sourcingLocation = form.querySelector('[name="sourcing_location"]')?.value;

                    if (!shippingMethod || !sourcingLocation) {
                        alert('{{ __("Please fill in all required fields.") }}');
                        return;
                    }

                    // Get destination blocks
                    const destBlocks = form.querySelectorAll('.destination-block');
                    const destInfos = [];
                    let valid = true;

                    destBlocks.forEach((block) => {
                        const countrySelect = block.querySelector('[name$="[country_id]"]');
                        const quantityInput = block.querySelector('[name$="[quantity]"]');
                        const countryId = countrySelect?.value;
                        const quantity = quantityInput?.value;

                        if (!countryId) {
                            valid = false;
                            return;
                        }
                        destInfos.push({ countryId, quantity, block });
                    });

                    if (!valid) {
                        alert('{{ __("Please select a country for all destinations.") }}');
                        return;
                    }

                    // Fetch shipping fees for all destinations
                    this.loadingFees = true;
                    this.showFeeModal = true;
                    this.selectedShippingMethod = shippingMethod;
                    this.selectedSourcingLocation = sourcingLocation;

                    try {
                        const feePromises = destInfos.map((d) =>
                            fetch(this.getFeeUrl(d.countryId, shippingMethod, sourcingLocation))
                                .then(async (r) => {
                                    if (!r.ok) {
                                        const text = await r.text();
                                        throw new Error(`HTTP ${r.status}: ${text.substring(0, 200)}`);
                                    }
                                    return r.json();
                                })
                                .then((data) => ({
                                    ...data,
                                    quantity: d.quantity,
                                }))
                        );

                        this.feeDestinations = await Promise.all(feePromises);

                        const hasAnyItems = this.feeDestinations.some((d) => d.items && d.items.length > 0);
                        if (!hasAnyItems) {
                            this.showFeeModal = false;
                            this.submitFormDirectly(form);
                        }
                    } catch (e) {
                        console.error('Failed to fetch shipping fees:', e);
                        this.showFeeModal = false;
                        window.dispatchEvent(new CustomEvent('show-error-toast', {
                            detail: '{{ __("Could not load shipping rates") }}: ' + e.message
                        }));
                        this.submitFormDirectly(form);
                    } finally {
                        this.loadingFees = false;
                    }
                },

                confirmSubmit() {
                    this.showFeeModal = false;
                    this.submitFormDirectly(this.$el);
                },

                cancelFeeModal() {
                    this.showFeeModal = false;
                    this.feeDestinations = [];
                },

                async submitFormDirectly(form) {
                    const formData = new FormData(form);
                    const action = form.getAttribute('action');

                    try {
                        const response = await fetch(action, {
                            method: 'POST',
                            body: formData,
                            headers: { 'Accept': 'application/json' },
                        });

                        const data = await response.json();

                        if (response.ok) {
                            window.dispatchEvent(new CustomEvent('show-success-toast', { detail: data.message || '{{ __("Request submitted successfully!") }}' }));
                            setTimeout(() => {
                                window.location.href = data.redirect_url || '{{ route("client.dashboard") }}';
                            }, 1500);
                        } else if (response.status === 422) {
                            window.dispatchEvent(new CustomEvent('show-error-toast', { detail: '{{ __("Please check the form for errors.") }}' }));

                            // Clear existing errors
                            document.querySelectorAll('.validation-error').forEach(el => el.remove());
                            document.querySelectorAll('.border-red-500').forEach(el => el.classList.remove('border-red-500'));
                            const globalErrors = document.getElementById('global-errors');
                            const globalErrorsList = document.getElementById('global-errors-list');
                            globalErrors.classList.add('hidden');
                            globalErrorsList.innerHTML = '';

                            if (data.errors) {
                                let hasGlobalErrors = false;

                                for (const field in data.errors) {
                                    let inputEl = document.querySelector(`[name="${field}"]`);

                                    if (!inputEl) {
                                        const parts = field.split('.');
                                        if (parts.length > 1) {
                                            const nameSelector = parts[0] + '[' + parts[1] + '][' + parts.slice(2).join('][') + ']';
                                            inputEl = document.querySelector(`[name="${nameSelector}"]`);
                                        }

                                        if (!inputEl) {
                                            const idSelector = field.replace(/\./g, '_');
                                            inputEl = document.getElementById(idSelector);
                                        }
                                    }

                                    if (inputEl) {
                                        inputEl.classList.add('border-red-500');

                                        let errorContainer = inputEl.parentNode;
                                        if (field === 'product_image') {
                                            errorContainer = document.getElementById('image-drop-zone').parentNode;
                                        } else if (field === 'sourcing_location' || field === 'shipping_method') {
                                            errorContainer = inputEl.closest('div').parentNode;
                                        }

                                        const errorMsg = document.createElement('p');
                                        errorMsg.className = 'text-xs text-red-500 mt-1 validation-error';
                                        errorMsg.textContent = data.errors[field][0];
                                        errorContainer.appendChild(errorMsg);
                                    } else {
                                        hasGlobalErrors = true;
                                        const li = document.createElement('li');
                                        li.textContent = `${field}: ${data.errors[field][0]}`;
                                        globalErrorsList.appendChild(li);
                                    }
                                }

                                if (hasGlobalErrors) {
                                    globalErrors.classList.remove('hidden');
                                    globalErrors.scrollIntoView({ behavior: 'smooth', block: 'center' });
                                }

                                console.log('Validation errors:', data.errors);
                            }
                        } else {
                            console.error('Server Error:', response.status, data);
                            let errorMessage = data.message || '{{ __("An unexpected error occurred. Please try again.") }}';
                            if (data.error) {
                                errorMessage += ' (' + data.error + ')';
                            }
                            window.dispatchEvent(new CustomEvent('show-error-toast', { detail: errorMessage }));
                        }
                    } catch (error) {
                        console.error('Form submission error:', error);
                        window.dispatchEvent(new CustomEvent('show-error-toast', { detail: '{{ __("A network error occurred.") }}' }));
                    }
                },

            }));

            document.querySelectorAll('.tom-select-country').forEach(el => {
                initializeCountryTomSelect(el);
            });
            document.querySelectorAll('.tom-select-category').forEach(el => {
                initializeGenericTomSelect(el);
            });
            document.querySelectorAll('.tom-select-location').forEach(el => {
                initializeGenericTomSelect(el);
            });
            document.querySelectorAll('.tom-select-service').forEach(el => {
                initializeGenericTomSelect(el);
            });
        });
    </script>

    <style>
        textarea::-webkit-scrollbar {
            width: 6px;
        }

        textarea::-webkit-scrollbar-track {
            background: transparent;
        }

        textarea::-webkit-scrollbar-thumb {
            background: #EF7722;
            border-radius: 3px;
        }

        .dark textarea::-webkit-scrollbar-thumb {
            background: #FAA533;
        }

        textarea::-webkit-scrollbar-thumb:hover {
            background: #FAA533;
        }

        .destination-block {
            transition: all 0.3s ease;
        }

        .destination-block:hover {
            box-shadow: 0 1px 3px 0 rgba(239, 119, 34, 0.1);
        }

        input[type="radio"] {
            accent-color: #EF7722;
        }

        input[type="number"]::-webkit-outer-spin-button,
        input[type="number"]::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        input[type="number"] {
            -moz-appearance: textfield;
        }

        /* Tom Select Custom Styling */
        .ts-wrapper.single .ts-control {
            border-color: #EBEBEB;
            background-color: white;
            border-radius: 0.5rem;
            min-height: 2.375rem;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        }

        .dark .ts-wrapper.single .ts-control {
            border-color: #475569;
            background-color: #1e293b;
            color: white;
        }

        .ts-wrapper.focus .ts-control {
            border-color: #EF7722;
            box-shadow: 0 0 0 3px rgba(239, 119, 34, 0.1);
        }

        .ts-dropdown {
            border-color: #EF7722;
            background-color: white;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .dark .ts-dropdown {
            background-color: #1e293b;
        }

        .ts-dropdown .ts-dropdown-content .option {
            padding: 0.5rem 0.75rem;
            color: #334155;
        }

        .dark .ts-dropdown .ts-dropdown-content .option {
            color: #e2e8f0;
        }

        .ts-dropdown .ts-dropdown-content .option.selected,
        .ts-dropdown .ts-dropdown-content .option:hover {
            background-color: #EF7722;
            color: white;
        }

        .ts-input {
            color: #334155;
        }

        .dark .ts-input {
            color: white;
        }

        .ts-dropdown .ts-dropdown-content .option.selected,
        .ts-dropdown .ts-dropdown-content .option:hover {
            background-color: #EF7722;
            color: white;
        }
        .dark .ts-dropdown .ts-dropdown-content .option.selected,
        .dark .ts-dropdown .ts-dropdown-content .option:hover {
            background-color: #FAA533;
            color: white;
        }
    </style>

    <style>
    /* Fee Modal Styles */
    .fee-modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(4px);
        z-index: 1000;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 1rem;
    }
    .fee-modal {
        background: white;
        border-radius: 1rem;
        max-width: 48rem;
        width: 100%;
        max-height: 90vh;
        overflow-y: auto;
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.25);
    }
    .dark .fee-modal {
        background: #1e293b;
        border: 1px solid #334155;
    }
    .fee-modal::-webkit-scrollbar {
        width: 6px;
    }
    .fee-modal::-webkit-scrollbar-thumb {
        background: #EF7722;
        border-radius: 3px;
    }
    @keyframes fee-fade-in {
        from { opacity: 0; transform: scale(0.95) translateY(10px); }
        to { opacity: 1; transform: scale(1) translateY(0); }
    }
    .fee-modal-animate {
        animation: fee-fade-in 0.2s ease-out;
    }
    </style>
    @endpush
</x-app-layout>
