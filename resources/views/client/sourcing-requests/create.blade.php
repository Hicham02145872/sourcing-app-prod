<x-app-layout>
    <x-slot name="header">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6">
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-blue-600 dark:bg-blue-700 rounded-lg flex items-center justify-center shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-3xl font-bold text-slate-900 dark:text-white tracking-tight">
                                {{ __('Create New Request') }}
                            </h2>
                            <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">{{ __('Fill in the details below to submit a new sourcing request') }}</p>
                        </div>
                    </div>
                </div>
                <a href="{{ route('client.dashboard') }}" 
                   class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-semibold text-slate-700 dark:text-slate-300 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-700 transition-all duration-200 shadow-sm hover:shadow w-full sm:w-auto justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    {{ __('Back to Dashboard') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-25 bg-slate-50 dark:bg-slate-900 min-h-screen">
        <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-4">
            <form method="POST" action="{{ route('client.sourcing-requests.store') }}" enctype="multipart/form-data"
                  x-data="sourcingRequestForm" @submit.prevent="submitForm"
                  data-translation-destination-required="{{ __('At least one destination is required!') }}"
                  data-translation-getting-location="{{ __('Getting your location...') }}"
                  data-translation-location-captured="{{ __('Location captured successfully!') }}"
                  data-translation-location-error="{{ __('Unable to retrieve your location.') }}"
                  data-translation-geolocation-unsupported="{{ __('Geolocation is not supported by your browser.') }}">

                @csrf

                <!-- Progress Steps -->
                <div class="mb-8">
                    <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-slate-200 dark:border-slate-700 p-6">
                        <div class="flex items-center justify-between max-w-3xl mx-auto">
                            <!-- Step 1 -->
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-blue-600 text-white flex items-center justify-center text-sm font-bold shadow-sm">
                                    {{ __('1') }}
                                </div>
                                <div class="hidden sm:block">
                                    <p class="text-sm font-semibold text-slate-900 dark:text-white">{{ __('Product Details') }}</p>
                                    <p class="text-xs text-slate-500 dark:text-slate-400">{{ __('Basic information') }}</p>
                                </div>
                            </div>
                            
                            <!-- Connector -->
                            <div class="flex-1 h-0.5 bg-slate-200 dark:bg-slate-700 mx-4"></div>
                            
                            <!-- Step 2 -->
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-slate-300 dark:bg-slate-700 text-slate-600 dark:text-slate-400 flex items-center justify-center text-sm font-bold shadow-sm">
                                    {{ __('2') }}
                                </div>
                                <div class="hidden sm:block">
                                    <p class="text-sm font-semibold text-slate-500 dark:text-slate-400">{{ __('Destinations') }}</p>
                                    <p class="text-xs text-slate-500 dark:text-slate-400">{{ __('Shipping details') }}</p>
                                </div>
                            </div>
                            
                            <!-- Connector -->
                            <div class="flex-1 h-0.5 bg-slate-200 dark:bg-slate-700 mx-4"></div>
                            
                            <!-- Step 3 -->
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-slate-300 dark:bg-slate-700 text-slate-600 dark:text-slate-400 flex items-center justify-center text-sm font-bold shadow-sm">
                                    {{ __('3') }}
                                </div>
                                <div class="hidden sm:block">
                                    <p class="text-sm font-semibold text-slate-500 dark:text-slate-400">{{ __('Review') }}</p>
                                    <p class="text-xs text-slate-500 dark:text-slate-400">{{ __('Final check') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Left Column - Product & Contact Information -->
                    <div class="lg:col-span-2 space-y-6">
                        <!-- Product Details Section -->
                        <div class="bg-white dark:bg-slate-800 shadow-sm rounded-lg border border-slate-200 dark:border-slate-700 overflow-hidden">
                            <div class="px-6 py-4 bg-slate-50 dark:bg-slate-900/50 border-b border-slate-200 dark:border-slate-700">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8-4m8 4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-bold text-slate-900 dark:text-white">{{ __('Product & Core Details') }}</h3>
                                        <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">{{ __('Enter the basic information about your product') }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="p-6 space-y-5">
                                <!-- Product Name -->
                                <div>
                                    <x-input-label for="product_name" class="text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2 flex items-center gap-2">
                                        {{ __('Product Name') }} <span class="text-red-500">*</span>
                                    </x-input-label>
                                    <x-text-input id="product_name" 
                                                  class="block w-full rounded-lg border-slate-300 dark:border-slate-600 focus:border-blue-500 focus:ring-blue-500 shadow-sm dark:bg-slate-700 dark:text-white text-sm" 
                                                  type="text" 
                                                  name="product_name" 
                                                  :value="old('product_name')" 
                                                  required 
                                                  autofocus 
                                                  autocomplete="off"
                                                  placeholder="{{ __('e.g., Wireless Bluetooth Headphones') }}" />
                                    <x-input-error :messages="$errors->get('product_name')" class="mt-2" />
                                </div>

                                <!-- Product URL -->
                                <div>
                                    <x-input-label for="product_url" class="text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2 flex items-center gap-2">
                                        {{ __('Product URL (Optional)') }}
                                    </x-input-label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                                            </svg>
                                        </div>
                                        <x-text-input id="product_url" 
                                                      class="block w-full pl-10 rounded-lg border-slate-300 dark:border-slate-600 focus:border-blue-500 focus:ring-blue-500 shadow-sm dark:bg-slate-700 dark:text-white text-sm" 
                                                      type="url" 
                                                      name="product_url" 
                                                      :value="old('product_url')" 
                                                      autocomplete="off"
                                                      placeholder="{{ __('https://example.com/product') }}" />
                                    </div>
                                    <x-input-error :messages="$errors->get('product_url')" class="mt-2" />
                                </div>

                                <!-- Category & Sourcing Location -->
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <!-- Category -->
                                    <div>
                                        <x-input-label for="category_id" class="text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2 flex items-center gap-2">
                                            {{ __('Category') }} <span class="text-red-500">*</span>
                                        </x-input-label>
                                        <select id="category_id" 
                                                name="category_id" 
                                                class="block w-full rounded-lg border-slate-300 dark:border-slate-600 focus:border-blue-500 focus:ring-blue-500 text-slate-900 dark:text-white shadow-sm dark:bg-slate-700 text-sm" 
                                                required>
                                            <option value="">{{ __('Select a Category') }}</option>
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                        <x-input-error :messages="$errors->get('category_id')" class="mt-2" />
                                    </div>

                                    <!-- Sourcing Location -->
                                    <div>
                                        <x-input-label for="sourcing_location" class="text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2 flex items-center gap-2">
                                            {{ __('Sourcing Location') }} <span class="text-red-500">*</span>
                                        </x-input-label>
                                        <select id="sourcing_location" 
                                                name="sourcing_location" 
                                                class="block w-full rounded-lg border-slate-300 dark:border-slate-600 focus:border-blue-500 focus:ring-blue-500 text-slate-900 dark:text-white shadow-sm dark:bg-slate-700 text-sm" 
                                                required>
                                            <option value="china" {{ old('sourcing_location') == 'china' ? 'selected' : '' }}>{{ __('China') }}</option>
                                            <option value="dubai" {{ old('sourcing_location') == 'dubai' ? 'selected' : '' }}>{{ __('Dubai') }}</option>
                                        </select>
                                        <x-input-error :messages="$errors->get('sourcing_location')" class="mt-2" />
                                    </div>
                                </div>

                                <!-- Note -->
                                <div>
                                    <x-input-label for="note" class="text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2 flex items-center gap-2">
                                        {{ __('Additional Notes / Specific Requirements') }}
                                    </x-input-label>
                                    <textarea id="note" 
                                              rows="3" 
                                              class="block w-full rounded-lg border-slate-300 dark:border-slate-600 focus:border-blue-500 focus:ring-blue-500 resize-none shadow-sm dark:bg-slate-700 dark:text-white text-sm" 
                                              name="note" 
                                              placeholder="{{ __('Specify colors, sizes, materials, or any other requirements...') }}">{{ old('note') }}</textarea>
                                    <x-input-error :messages="$errors->get('note')" class="mt-2" />
                                </div>

                                <!-- Shipping Method -->
                                <div>
                                    <x-input-label class="text-sm font-semibold text-slate-700 dark:text-slate-300 mb-3 flex items-center gap-2">
                                        {{ __('Shipping Method') }}
                                    </x-input-label>
                                    <div class="grid grid-cols-1 gap-3">
                                        <label class="relative flex items-center p-4 border-2 border-slate-200 dark:border-slate-600 rounded-lg cursor-pointer transition-all duration-200 hover:border-blue-400 dark:hover:border-blue-600 has-[:checked]:border-blue-500 dark:has-[:checked]:border-blue-400 has-[:checked]:bg-blue-50 dark:has-[:checked]:bg-blue-900/20 has-[:checked]:shadow-sm group">
                                            <input type="radio" id="shipping_method_air" name="shipping_method" value="air" class="peer sr-only" {{ old('shipping_method') == 'air' ? 'checked' : '' }}>
                                            <div class="flex items-center gap-4 w-full">
                                                <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center flex-shrink-0">
                                                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                                    </svg>
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <p class="font-bold text-slate-900 dark:text-white text-sm mb-1">{{ __('Air Freight') }}</p>
                                                    <p class="text-xs text-slate-600 dark:text-slate-400">{{ __('Faster delivery, suitable for small volumes') }}</p>
                                                </div>
                                                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 opacity-0 peer-checked:opacity-100 transition-opacity flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                </svg>
                                            </div>
                                        </label>

                                        <label class="relative flex items-center p-4 border-2 border-slate-200 dark:border-slate-600 rounded-lg cursor-pointer transition-all duration-200 hover:border-blue-400 dark:hover:border-blue-600 has-[:checked]:border-blue-500 dark:has-[:checked]:border-blue-400 has-[:checked]:bg-blue-50 dark:has-[:checked]:bg-blue-900/20 has-[:checked]:shadow-sm group">
                                            <input type="radio" id="shipping_method_sea" name="shipping_method" value="sea" class="peer sr-only" {{ old('shipping_method') == 'sea' ? 'checked' : '' }}>
                                            <div class="flex items-center gap-4 w-full">
                                                <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center flex-shrink-0">
                                                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8 4-8-4m16 0l-8-4m8 4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                                    </svg>
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <p class="font-bold text-slate-900 dark:text-white text-sm mb-1">{{ __('Sea Freight') }}</p>
                                                    <p class="text-xs text-slate-600 dark:text-slate-400">{{ __('Cost-effective, suitable for large volumes') }}</p>
                                                </div>
                                                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 opacity-0 peer-checked:opacity-100 transition-opacity flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                </svg>
                                            </div>
                                        </label>
                                    </div>
                                    <x-input-error :messages="$errors->get('shipping_method')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <!-- Contact Information Section -->
                        <div class="bg-white dark:bg-slate-800 shadow-sm rounded-lg border border-slate-200 dark:border-slate-700 overflow-hidden">
                            <div class="px-6 py-4 bg-slate-50 dark:bg-slate-900/50 border-b border-slate-200 dark:border-slate-700">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-bold text-slate-900 dark:text-white">{{ __('Contact Information') }}</h3>
                                        <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">{{ __('How we can reach you for updates') }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="p-6 space-y-5">
                                <!-- Phone Number -->
                                <div>
                                    <x-input-label for="phone_number" class="text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2 flex items-center gap-2">
                                        {{ __('Phone Number') }}
                                    </x-input-label>
                                    <x-text-input id="phone_number" 
                                                  class="block w-full rounded-lg border-slate-300 dark:border-slate-600 focus:border-blue-500 focus:ring-blue-500 shadow-sm dark:bg-slate-700 dark:text-white text-sm" 
                                                  type="tel" 
                                                  name="phone_number" 
                                                  :value="old('phone_number')" 
                                                  autocomplete="tel"
                                                  placeholder="{{ __('e.g., +1234567890') }}" />
                                    <x-input-error :messages="$errors->get('phone_number')" class="mt-2" />
                                </div>

                                <!-- Address Options -->
                                <div>
                                    <x-input-label class="text-sm font-semibold text-slate-700 dark:text-slate-300 mb-3 flex items-center gap-2">
                                        {{ __('Address / Location') }}
                                    </x-input-label>
                                    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4 p-4 bg-slate-50 dark:bg-slate-700 rounded-lg border border-slate-200 dark:border-slate-600">
                                        <label class="flex items-center cursor-pointer">
                                            <input type="radio" id="address_manual" name="address_option" value="manual" class="form-radio h-4 w-4 text-blue-600 focus:ring-blue-500" checked>
                                            <span class="ml-2 text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('Enter manually') }}</span>
                                        </label>
                                        <label class="flex items-center cursor-pointer">
                                            <input type="radio" id="address_geolocation" name="address_option" value="geolocation" class="form-radio h-4 w-4 text-blue-600 focus:ring-blue-500">
                                            <span class="ml-2 text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('Use my location (GPS)') }}</span>
                                        </label>
                                    </div>
                                </div>

                                <!-- Manual Address -->
                                <div id="manual-address-container">
                                    <x-input-label for="address" class="text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">
                                        {{ __('Full Address') }}
                                    </x-input-label>
                                    <textarea id="address" 
                                              rows="3" 
                                              class="block w-full rounded-lg border-slate-300 dark:border-slate-600 focus:border-blue-500 focus:ring-blue-500 resize-none shadow-sm dark:bg-slate-700 dark:text-white text-sm" 
                                              name="address" 
                                              autocomplete="street-address"
                                              placeholder="{{ __('Enter your full address') }}">{{ old('address') }}</textarea>
                                    <x-input-error :messages="$errors->get('address')" class="mt-2" />
                                </div>

                                <!-- Geolocation -->
                                <div id="geolocation-container" style="display: none;">
                                    <button type="button" 
                                            @click="getGeolocation"
                                            id="get-location-btn" 
                                            class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 dark:bg-blue-700 dark:hover:bg-blue-600 text-white rounded-lg text-sm font-semibold transition-all duration-200 shadow-sm hover:shadow w-full sm:w-auto">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                        {{ __('Get My Location') }}
                                    </button>
                                    <p id="location-feedback" class="mt-3 text-sm font-medium"></p>
                                    <input type="hidden" name="latitude" id="latitude">
                                    <input type="hidden" name="longitude" id="longitude">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column - Product Image & Destinations -->
                    <div class="space-y-6">
                        <!-- Product Image Section -->
                        <div class="bg-white dark:bg-slate-800 shadow-sm rounded-lg border border-slate-200 dark:border-slate-700 overflow-hidden">
                            <div class="px-6 py-4 bg-slate-50 dark:bg-slate-900/50 border-b border-slate-200 dark:border-slate-700">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-bold text-slate-900 dark:text-white">{{ __('Product Image') }}</h3>
                                        <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">{{ __('Optional but recommended') }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="p-6">
                                <div class="space-y-4">
                                    <!-- Image Preview -->
                                    <div class="relative aspect-square bg-slate-50 dark:bg-slate-700 rounded-lg overflow-hidden border-2 border-dashed border-slate-300 dark:border-slate-600 group hover:border-blue-500 transition-all duration-200 shadow-inner">
                                        <img id="product_image_preview" 
                                             class="w-full h-full object-cover opacity-0 transition-opacity duration-300" 
                                             src="" 
                                             alt="{{ __('Product preview') }}" />
                                        <div id="placeholder" 
                                             class="absolute inset-0 flex flex-col items-center justify-center text-slate-400 dark:text-slate-500 group-hover:text-blue-500 transition-colors duration-200">
                                            <div class="w-16 h-16 bg-white dark:bg-slate-800 rounded-lg shadow-sm flex items-center justify-center mb-4 group-hover:scale-105 transition-transform">
                                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                            </div>
                                            <p class="text-sm font-semibold">{{ __('No image selected') }}</p>
                                        </div>
                                    </div>
                                    <x-input-error :messages="$errors->get('product_image')" class="mt-2" />

                                    <!-- Upload Button -->
                                    <label for="product_image" class="block cursor-pointer">
                                        <div class="flex items-center justify-center gap-2 px-5 py-3.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-bold transition-all duration-200 shadow-sm hover:shadow-md">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                            </svg>
                                            <span id="upload-text">{{ __('Choose Image') }}</span>
                                        </div>
                                        <input id="product_image" 
                                               type="file" 
                                               class="sr-only" 
                                               name="product_image" 
                                               accept="image/*" />
                                    </label>

                                    <div class="p-3 bg-slate-50 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-lg">
                                        <p class="text-xs text-slate-600 dark:text-slate-400 font-medium flex items-start gap-2">
                                            <svg class="w-4 h-4 flex-shrink-0 mt-0.5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            <span>{{ __('Max file size: 5MB. Clear product photo greatly assists our sourcing team.') }}</span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Destination Details Section -->
                        <div class="bg-white dark:bg-slate-800 shadow-sm rounded-lg border border-slate-200 dark:border-slate-700 overflow-hidden">
                            <div class="px-6 py-4 bg-slate-50 dark:bg-slate-900/50 border-b border-slate-200 dark:border-slate-700">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center flex-shrink-0">
                                            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <h3 class="text-lg font-bold text-slate-900 dark:text-white">{{ __('Destinations') }}</h3>
                                            <p class="text-sm text-slate-600 dark:text-slate-400 mt-1">{{ __('Where to ship your products') }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="p-6">
                                <div id="destination-fields-container" class="space-y-4">
                                    {{-- Initial Destination Block --}}
                                    <div class="destination-block p-4 bg-slate-50 dark:bg-slate-700 rounded-lg border border-slate-200 dark:border-slate-600 hover:border-blue-400 dark:hover:border-blue-600 transition-colors">
                                        <div class="space-y-4">
                                            <!-- Quantity -->
                                            <div>
                                                <label for="destinations_0_quantity" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-2 uppercase tracking-wide">{{ __('Quantity') }}</label>
                                                <x-text-input type="number" 
                                                              id="destinations_0_quantity"
                                                              name="destinations[0][quantity]" 
                                                              value="{{ old('destinations.0.quantity') }}" 
                                                              required 
                                                              min="1" 
                                                              class="w-full rounded-lg text-sm shadow-sm dark:bg-slate-800 dark:text-white border-slate-300 dark:border-slate-600 focus:border-blue-500 focus:ring-blue-500" 
                                                              placeholder="{{ __('e.g., 100') }}" />
                                                <x-input-error :messages="$errors->get('destinations.0.quantity')" class="mt-2 js-error-message js-error-destinations-0-quantity" />
                                            </div>

                                            <!-- Country -->
                                            <div>
                                                <label for="destinations_0_country_id" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-2 uppercase tracking-wide">{{ __('Country') }}</label>
                                                <select id="destinations_0_country_id"
                                                        name="destinations[0][country_id]" 
                                                        required 
                                                        class="block w-full rounded-lg border-slate-300 dark:border-slate-600 focus:border-blue-500 focus:ring-blue-500 text-sm tom-select-country shadow-sm dark:bg-slate-800 dark:text-white">
                                                    <option value="">{{ __('Select Country') }}</option>
                                                    @foreach($countries as $country)
                                                        <option value="{{ $country->id }}" 
                                                                data-flag="{{ strtolower($country->code) }}">
                                                            {{ $country->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <x-input-error :messages="$errors->get('destinations.0.country_id')" class="mt-2 js-error-message js-error-destinations-0-country_id" />
                                            </div>

                                            <!-- Service -->
                                            <div>
                                                <label for="destinations_0_service_id" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-2 uppercase tracking-wide">{{ __('Service') }}</label>
                                                <select id="destinations_0_service_id"
                                                        name="destinations[0][service_id]" 
                                                        required 
                                                        class="block w-full rounded-lg border-slate-300 dark:border-slate-600 focus:border-blue-500 focus:ring-blue-500 text-sm shadow-sm dark:bg-slate-800 dark:text-white">
                                                    <option value="">{{ __('Select Service') }}</option>
                                                    @foreach($services as $service)
                                                        <option value="{{ $service->id }}">
                                                            {{ $service->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <x-input-error :messages="$errors->get('destinations.0.service_id')" class="mt-2 js-error-message js-error-destinations-0-service_id" />
                                            </div>

                                            <!-- Remove Button -->
                                            <div class="flex items-center justify-center">
                                                <button type="button" 
                                                        @click="removeDestination($event)"
                                                        class="remove-destination w-full sm:w-auto px-4 py-2.5 text-red-600 dark:text-red-400 hover:text-white hover:bg-red-600 border border-red-300 dark:border-red-700 rounded-lg transition-all duration-200 flex items-center justify-center gap-2 shadow-sm text-sm font-medium">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                    <span>{{ __('Remove') }}</span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <button type="button" 
                                        @click="addDestination"
                                        id="add-destination" 
                                        class="mt-5 inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-slate-100 dark:bg-slate-700 hover:bg-blue-50 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-300 hover:text-blue-700 dark:hover:text-blue-400 border border-slate-300 dark:border-slate-600 hover:border-blue-400 rounded-lg text-sm font-medium transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 shadow-sm w-full">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                    {{ __('Add Another Destination') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="mt-8 flex flex-col sm:flex-row items-center justify-end gap-4 bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-slate-200 dark:border-slate-700 px-6 py-5">
                    <a href="{{ route('client.dashboard') }}" 
                       class="w-full sm:w-auto text-sm font-medium text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white transition-colors duration-200 flex items-center justify-center gap-2 py-2.5 sm:py-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        {{ __('Cancel Request') }}
                    </a>
                    <button type="submit" 
                            class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-8 py-3.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-lg shadow-sm transition-all duration-200 hover:shadow-md focus:outline-none focus:ring-4 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-blue-700/50">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        {{ __('Submit Sourcing Request') }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('alpine:init', () => {
            
            // --- Address options toggle ---
            const addressOptions = document.querySelectorAll('input[name="address_option"]');
            const manualAddressContainer = document.getElementById('manual-address-container');
            const geolocationContainer = document.getElementById('geolocation-container');

            addressOptions.forEach(option => {
                option.addEventListener('change', () => {
                    if (option.value === 'manual') {
                        manualAddressContainer.style.display = 'block';
                        geolocationContainer.style.display = 'none';
                    } else {
                        manualAddressContainer.style.display = 'none';
                        geolocationContainer.style.display = 'block';
                    }
                });
            });

            // --- Image Preview Logic ---
            const input = document.getElementById('product_image');
            const preview = document.getElementById('product_image_preview');
            const placeholder = document.getElementById('placeholder');
            const uploadText = document.getElementById('upload-text');

            if (input) {
                input.onchange = (e) => {
                    const file = e.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = ev => {
                            preview.src = ev.target.result;
                            preview.style.opacity = '1';
                            placeholder.style.display = 'none';
                            uploadText.textContent = '{{ __("Change Image") }}';
                        };
                        reader.readAsDataURL(file);
                    }
                };
            }

            // --- TomSelect Initialization for Country Flags ---
            const initializeTomSelect = (element) => {
                if (element.tomselect) {
                    element.tomselect.destroy();
                }
                new TomSelect(element, {
                    valueField: 'value',
                    labelField: 'text',
                    searchField: ['text'],
                    plugins: {
                        'dropdown_header': {
                            title: '{{ __("Select Country") }}'
                        }
                    },
                    render: {
                        option: function(data, escape) {
                            const flagHtml = data.flag ? `<span class="fi fi-${data.flag} mr-2"></span>` : '';
                            return `<div class="flex items-center gap-2 py-1">${flagHtml}<span>${escape(data.text)}</span></div>`;
                        },
                        item: function(data, escape) {
                            const flagHtml = data.flag ? `<span class="fi fi-${data.flag} mr-2"></span>` : '';
                            return `<div class="flex items-center gap-2">${flagHtml}<span>${escape(data.text)}</span></div>`;
                        }
                    }
                });
            };
            
            // --- Alpine Data ---
            Alpine.data('sourcingRequestForm', () => ({
                getNewIndex() {
                    const existingInputs = document.querySelectorAll('#destination-fields-container [name^="destinations"]');
                    if (existingInputs.length === 0) return 0;

                    const maxIndex = Array.from(existingInputs).reduce((max, input) => {
                        const match = input.name.match(/\[(\d+)\]/);
                        if (match) {
                            const index = parseInt(match[1]);
                            return index > max ? index : max;
                        }
                        return max;
                    }, 0);
                    return maxIndex + 1;
                },
                
                getDestinationTemplate(newIndex) {
                    const template = document.createElement('div');
                    template.classList.add('destination-block', 'p-4', 'bg-slate-50', 'dark:bg-slate-700', 'rounded-lg', 'border', 'border-slate-200', 'dark:border-slate-600', 'hover:border-blue-400', 'dark:hover:border-blue-600', 'transition-colors');
                    template.innerHTML = `
                        <div class="space-y-4">
                            <!-- Quantity -->
                            <div>
                                <label for="destinations_${newIndex}_quantity" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-2 uppercase tracking-wide">{{ __('Quantity') }}</label>
                                <input type="number" 
                                       id="destinations_${newIndex}_quantity"
                                       name="destinations[${newIndex}][quantity]" 
                                       required 
                                       min="1" 
                                       class="w-full rounded-lg text-sm shadow-sm dark:bg-slate-800 dark:text-white border-slate-300 dark:border-slate-600 focus:border-blue-500 focus:ring-blue-500" 
                                       placeholder="{{ __('e.g., 100') }}" />
                                <x-input-error messages="" class="mt-2 js-error-message js-error-destinations-${newIndex}-quantity" />
                            </div>

                            <!-- Country -->
                            <div>
                                <label for="destinations_${newIndex}_country_id" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-2 uppercase tracking-wide">{{ __('Country') }}</label>
                                <select id="destinations_${newIndex}_country_id"
                                        name="destinations[${newIndex}][country_id]" 
                                        required 
                                        class="block w-full rounded-lg border-slate-300 dark:border-slate-600 focus:border-blue-500 focus:ring-blue-500 text-sm tom-select-country shadow-sm dark:bg-slate-800 dark:text-white">
                                    <option value="">{{ __('Select Country') }}</option>
                                    @foreach($countries as $country)
                                        <option value="{{ $country->id }}" 
                                                data-flag="{{ strtolower($country->code) }}">
                                            {{ $country->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error messages="" class="mt-2 js-error-message js-error-destinations-${newIndex}-country_id" />
                            </div>

                            <!-- Service -->
                            <div>
                                <label for="destinations_${newIndex}_service_id" class="block text-xs font-bold text-slate-700 dark:text-slate-300 mb-2 uppercase tracking-wide">{{ __('Service') }}</label>
                                <select id="destinations_${newIndex}_service_id"
                                        name="destinations[${newIndex}][service_id]" 
                                        required 
                                        class="block w-full rounded-lg border-slate-300 dark:border-slate-600 focus:border-blue-500 focus:ring-blue-500 text-sm shadow-sm dark:bg-slate-800 dark:text-white">
                                    <option value="">{{ __('Select Service') }}</option>
                                    @foreach($services as $service)
                                        <option value="{{ $service->id }}">
                                            {{ $service->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error messages="" class="mt-2 js-error-message js-error-destinations-${newIndex}-service_id" />
                            </div>

                            <!-- Remove Button -->
                            <div class="flex items-center justify-center">
                                <button type="button" 
                                        @click="removeDestination($event)"
                                        class="remove-destination w-full sm:w-auto px-4 py-2.5 text-red-600 dark:text-red-400 hover:text-white hover:bg-red-600 border border-red-300 dark:border-red-700 rounded-lg transition-all duration-200 flex items-center justify-center gap-2 shadow-sm text-sm font-medium">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                    <span>{{ __('Remove') }}</span>
                                </button>
                            </div>
                        </div>
                    `;
                    return template;
                },
                
                addDestination() {
                    const container = document.getElementById('destination-fields-container');
                    const newIndex = this.getNewIndex();
                    const newBlock = this.getDestinationTemplate(newIndex);
                    
                    container.appendChild(newBlock);
                    
                    this.$nextTick(() => {
                        initializeTomSelect(newBlock.querySelector('.tom-select-country'));
                    });
                },

                removeDestination(event) {
                    const container = document.getElementById('destination-fields-container');
                    const blocks = container.querySelectorAll('.destination-block');
                    const block = event.target.closest('.destination-block');
                    
                    if (blocks.length > 1) {
                        const select = block.querySelector('.tom-select-country');
                        if (select && select.tomselect) {
                            select.tomselect.destroy();
                        }
                        
                        block.style.opacity = '0';
                        block.style.transform = 'translateY(-20px)';
                        block.style.height = '0';
                        block.style.padding = '0';
                        
                        setTimeout(() => {
                            block.remove();
                            document.querySelectorAll('.destination-block').forEach((b, idx) => {
                                b.querySelectorAll('input, select, .js-error-message').forEach(el => {
                                    if (el.hasAttribute('name')) {
                                        const name = el.getAttribute('name').replace(/destinations\[\d+\]/, `destinations[${idx}]`);
                                        el.setAttribute('name', name);
                                    }
                                    if (el.id) {
                                        const id = el.getAttribute('id').replace(/destinations_\d+_/, `destinations_${idx}_`);
                                        el.setAttribute('id', id);
                                    }
                                    if (el.classList.contains('js-error-message')) {
                                         const className = `js-error-destinations-${idx}-${el.classList[1].split('-').slice(-1)[0]}`;
                                         el.classList.remove(el.classList[1]);
                                         el.classList.add(className);
                                    }
                                });
                            });
                        }, 300);
                    } else {
                        alert(document.querySelector('form').dataset.translationDestinationRequired);
                    }
                },
                
                async submitForm(event) {
                    event.preventDefault();
                    window.dispatchEvent(new CustomEvent('loading-start', { detail: { message: '{{ __("Creating your sourcing request...") }}' } }));

                    document.querySelectorAll('.js-error-message').forEach(el => el.textContent = '');
                    document.querySelectorAll('.js-error-message').forEach(el => el.style.display = 'none');
                    document.querySelectorAll('.border-red-500').forEach(el => el.classList.remove('border-red-500'));

                    const form = event.target;
                    const formData = new FormData(form);
                    const action = form.getAttribute('action');

                    try {
                        const response = await fetch(action, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'Accept': 'application/json',
                            },
                        });

                        const data = await response.json();

                        if (response.ok) {
                            window.dispatchEvent(new CustomEvent('show-success-toast', { detail: data.message || '{{ __("Request submitted successfully!") }}' }));
                            setTimeout(() => {
                                window.location.href = data.redirect_url || '{{ route("client.dashboard") }}';
                            }, 1500);
                        } else if (response.status === 422) {
                            window.dispatchEvent(new CustomEvent('show-error-toast', { detail: data.message || '{{ __("Please check the form for errors.") }}' }));
                            
                            if (data.errors) {
                                for (const field in data.errors) {
                                    const errorPath = field.replace(/\./g, '-');
                                    const errorEl = document.querySelector(`.js-error-${errorPath}`);
                                    const inputEl = document.querySelector(`[name="${field}"]`);
                                    
                                    if (errorEl) {
                                        errorEl.textContent = data.errors[field][0];
                                        errorEl.style.display = 'block';
                                    }
                                    if (inputEl) {
                                        inputEl.classList.add('border-red-500');
                                    }
                                }
                            }
                        } else {
                            window.dispatchEvent(new CustomEvent('show-error-toast', { detail: data.message || '{{ __("An unexpected error occurred. Please try again.") }}' }));
                        }

                    } catch (error) {
                        window.dispatchEvent(new CustomEvent('show-error-toast', { detail: '{{ __("A network error occurred. Please check your connection.") }}' }));
                    } finally {
                        window.dispatchEvent(new CustomEvent('loading-stop'));
                    }
                },

                getGeolocation() {
                    const form = document.querySelector('form');
                    const locationFeedback = document.getElementById('location-feedback');
                    const latitudeInput = document.getElementById('latitude');
                    const longitudeInput = document.getElementById('longitude');

                    locationFeedback.textContent = form.dataset.translationGettingLocation;
                    locationFeedback.className = 'mt-3 text-sm font-medium text-blue-600 dark:text-blue-400';

                    if (navigator.geolocation) {
                        navigator.geolocation.getCurrentPosition(
                            (position) => {
                                latitudeInput.value = position.coords.latitude;
                                longitudeInput.value = position.coords.longitude;
                                locationFeedback.textContent = form.dataset.translationLocationCaptured;
                                locationFeedback.className = 'mt-3 text-sm font-medium text-green-600 dark:text-green-400';
                            },
                            (error) => {
                                console.error('Geolocation error:', error);
                                latitudeInput.value = '';
                                longitudeInput.value = '';
                                locationFeedback.textContent = form.dataset.translationLocationError;
                                locationFeedback.className = 'mt-3 text-sm font-medium text-red-600 dark:text-red-400';
                            }
                        );
                    } else {
                        locationFeedback.textContent = form.dataset.translationGeolocationUnsupported;
                        locationFeedback.className = 'mt-3 text-sm font-medium text-red-600 dark:text-red-400';
                    }
                }
            }));

            document.querySelectorAll('.tom-select-country').forEach(el => {
                initializeTomSelect(el);
            });
        });
    </script>
    @endpush

    <style>
        /* Enterprise form styling */
        input[type="text"],
        input[type="email"],
        input[type="tel"],
        input[type="url"],
        input[type="number"],
        textarea,
        select {
            border-collapse: separate;
        }

        /* Custom scrollbar for textareas */
        textarea::-webkit-scrollbar {
            width: 8px;
        }

        textarea::-webkit-scrollbar-track {
            background: transparent;
        }

        textarea::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }

        .dark textarea::-webkit-scrollbar-thumb {
            background: #475569;
        }

        textarea::-webkit-scrollbar-thumb:hover {
            background: #3b82f6;
        }

        .dark textarea::-webkit-scrollbar-thumb:hover {
            background: #2563eb;
        }

        /* Smooth transitions */
        * {
            transition-property: background-color, border-color, color, fill, stroke;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
            transition-duration: 150ms;
        }

        /* Progress steps styling */
        .destination-block {
            transition: all 0.3s ease;
        }

        .destination-block:hover {
            box-shadow: 0 1px 3px 0 rgba(59, 130, 246, 0.1);
        }
    </style>
</x-app-layout>
