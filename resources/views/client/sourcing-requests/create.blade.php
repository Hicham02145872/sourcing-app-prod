<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-4xl font-extrabold text-gray-900 tracking-tight">
                    {{ __('Create Sourcing Request') }}
                </h2>
                <p class="mt-2 text-sm text-gray-600">{{ __('Fill in the details below to submit a new sourcing request') }}</p>
            </div>
            <a href="{{ route('client.dashboard') }}" 
               class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-violet-500 transition-all duration-200 shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                {{ __('Back to Dashboard') }}
            </a>
        </div>
    </x-slot>

    <div class="py-8 bg-gradient-to-br from-gray-50 to-gray-100/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('client.sourcing-requests.store') }}" enctype="multipart/form-data"
                  x-data="sourcingRequestForm" @submit.prevent="submitForm"
                  data-translation-destination-required="{{ __('At least one destination is required!') }}"
                  data-translation-getting-location="{{ __('Getting your location...') }}"
                  data-translation-location-captured="{{ __('Location captured successfully!') }}"
                  data-translation-location-error="{{ __('Unable to retrieve your location.') }}"
                  data-translation-geolocation-unsupported="{{ __('Geolocation is not supported by your browser.') }}">

                @csrf

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Left Column - Product Information -->
                    <div class="lg:col-span-2 space-y-6">
                        <!-- Product Details Section -->
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                            <div class="px-6 py-5 bg-gradient-to-r from-violet-50 to-purple-50 border-b border-gray-200">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-violet-100 rounded-xl flex items-center justify-center">
                                        <svg class="w-5 h-5 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-bold text-gray-900">{{ __('Product Details') }}</h3>
                                        <p class="text-xs text-gray-600">{{ __('Basic information about the product') }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="p-6 space-y-6">
                                <!-- Product Name -->
                                <div>
                                    <x-input-label for="product_name" class="text-sm font-semibold text-gray-900 mb-2 flex items-center gap-2">
                                        <svg class="w-4 h-4 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                        </svg>
                                        {{ __('Product Name') }} <span class="text-red-500">*</span>
                                    </x-input-label>
                                    <x-text-input id="product_name" 
                                                  class="block mt-2 w-full rounded-xl border-gray-300 focus:border-violet-500 focus:ring-violet-500 shadow-sm" 
                                                  type="text" 
                                                  name="product_name" 
                                                  :value="old('product_name')" 
                                                  required 
                                                  autofocus 
                                                  placeholder="{{ __('e.g., Wireless Bluetooth Headphones') }}" />
                                    <x-input-error :messages="$errors->get('product_name')" class="mt-2" />
                                </div>

                                <!-- Product URL -->
                                <div>
                                    <x-input-label for="product_url" class="text-sm font-semibold text-gray-900 mb-2 flex items-center gap-2">
                                        <svg class="w-4 h-4 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                                        </svg>
                                        {{ __('Product URL') }}
                                    </x-input-label>
                                    <div class="relative mt-2">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                                            </svg>
                                        </div>
                                        <x-text-input id="product_url" 
                                                      class="block w-full pl-10 rounded-xl border-gray-300 focus:border-violet-500 focus:ring-violet-500 shadow-sm" 
                                                      type="url" 
                                                      name="product_url" 
                                                      :value="old('product_url')" 
                                                      placeholder="{{ __('https://example.com/product') }}" />
                                    </div>
                                    <p class="mt-2 text-xs text-gray-500 flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        {{ __('Optional: Link to the product reference page') }}
                                    </p>
                                    <x-input-error :messages="$errors->get('product_url')" class="mt-2" />
                                </div>

                                <!-- Category -->
                                <div>
                                    <x-input-label for="category_id" class="text-sm font-semibold text-gray-900 mb-2 flex items-center gap-2">
                                        <svg class="w-4 h-4 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                        </svg>
                                        {{ __('Category') }} <span class="text-red-500">*</span>
                                    </x-input-label>
                                    <select id="category_id" 
                                            name="category_id" 
                                            class="block mt-2 w-full rounded-xl border-gray-300 focus:border-violet-500 focus:ring-violet-500 text-gray-900 shadow-sm" 
                                            required>
                                        <option value="">{{ __('Select a Category') }}</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                    <x-input-error :messages="$errors->get('category_id')" class="mt-2" />
                                </div>

                                <!-- Note -->
                                <div>
                                    <x-input-label for="note" class="text-sm font-semibold text-gray-900 mb-2 flex items-center gap-2">
                                        <svg class="w-4 h-4 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                        {{ __('Additional Notes') }}
                                    </x-input-label>
                                    <textarea id="note" 
                                              rows="4" 
                                              class="block mt-2 w-full rounded-xl border-gray-300 focus:border-violet-500 focus:ring-violet-500 resize-none shadow-sm" 
                                              name="note" 
                                              placeholder="{{ __('Specify colors, sizes, materials, or any other requirements...') }}">{{ old('note') }}</textarea>
                                    <p class="mt-2 text-xs text-gray-500 flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        {{ __('Optional: Add specifications or special requirements') }}
                                    </p>
                                    <x-input-error :messages="$errors->get('note')" class="mt-2" />
                                </div>

                                <!-- Shipping Method -->
                                <div>
                                    <x-input-label class="text-sm font-semibold text-gray-900 mb-3 flex items-center gap-2">
                                        <svg class="w-4 h-4 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                                        </svg>
                                        {{ __('Shipping Method') }}
                                    </x-input-label>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <label class="relative flex items-center p-5 border-2 rounded-xl cursor-pointer transition-all duration-200 hover:border-violet-300 hover:shadow-sm has-[:checked]:border-violet-500 has-[:checked]:bg-violet-50 has-[:checked]:shadow-md group">
                                            <input type="radio" id="shipping_method_air" name="shipping_method" value="air" class="peer sr-only" {{ old('shipping_method') == 'air' ? 'checked' : '' }}>
                                            <div class="flex items-center gap-4 w-full">
                                                <div class="w-12 h-12 bg-gradient-to-br from-blue-100 to-blue-200 rounded-xl flex items-center justify-center flex-shrink-0 group-hover:scale-105 transition-transform">
                                                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                                    </svg>
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <p class="font-bold text-gray-900 text-sm mb-0.5">{{ __('Air Freight') }}</p>
                                                    <p class="text-xs text-gray-600">{{ __('Faster delivery') }}</p>
                                                </div>
                                                <svg class="w-5 h-5 text-violet-600 opacity-0 peer-checked:opacity-100 transition-opacity" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                </svg>
                                            </div>
                                        </label>

                                        <label class="relative flex items-center p-5 border-2 rounded-xl cursor-pointer transition-all duration-200 hover:border-violet-300 hover:shadow-sm has-[:checked]:border-violet-500 has-[:checked]:bg-violet-50 has-[:checked]:shadow-md group">
                                            <input type="radio" id="shipping_method_sea" name="shipping_method" value="sea" class="peer sr-only" {{ old('shipping_method') == 'sea' ? 'checked' : '' }}>
                                            <div class="flex items-center gap-4 w-full">
                                                <div class="w-12 h-12 bg-gradient-to-br from-cyan-100 to-cyan-200 rounded-xl flex items-center justify-center flex-shrink-0 group-hover:scale-105 transition-transform">
                                                    <svg class="w-6 h-6 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                                    </svg>
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <p class="font-bold text-gray-900 text-sm mb-0.5">{{ __('Sea Freight') }}</p>
                                                    <p class="text-xs text-gray-600">{{ __('Cost-effective') }}</p>
                                                </div>
                                                <svg class="w-5 h-5 text-violet-600 opacity-0 peer-checked:opacity-100 transition-opacity" fill="currentColor" viewBox="0 0 20 20">
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
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                            <div class="px-6 py-5 bg-gradient-to-r from-blue-50 to-indigo-50 border-b border-gray-200">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center">
                                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-bold text-gray-900">{{ __('Contact Information') }}</h3>
                                        <p class="text-xs text-gray-600">{{ __('Provide your contact details') }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="p-6 space-y-6">
                                <!-- Phone Number -->
                                <div>
                                    <x-input-label for="phone_number" class="text-sm font-semibold text-gray-900 mb-2 flex items-center gap-2">
                                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                        </svg>
                                        {{ __('Phone Number') }}
                                    </x-input-label>
                                    <x-text-input id="phone_number" 
                                                  class="block mt-2 w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm" 
                                                  type="text" 
                                                  name="phone_number" 
                                                  :value="old('phone_number')" 
                                                  placeholder="{{ __('e.g., +1234567890') }}" />
                                    <x-input-error :messages="$errors->get('phone_number')" class="mt-2" />
                                </div>

                                <!-- Address Options -->
                                <div>
                                    <x-input-label class="text-sm font-semibold text-gray-900 mb-3 flex items-center gap-2">
                                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                        {{ __('Address') }}
                                    </x-input-label>
                                    <div class="flex items-center gap-6 p-4 bg-gray-50 rounded-xl border border-gray-200">
                                        <label class="flex items-center cursor-pointer">
                                            <input type="radio" name="address_option" value="manual" class="form-radio h-4 w-4 text-violet-600 focus:ring-violet-500" checked>
                                            <span class="ml-2.5 text-sm font-medium text-gray-700">{{ __('Enter manually') }}</span>
                                        </label>
                                        <label class="flex items-center cursor-pointer">
                                            <input type="radio" name="address_option" value="geolocation" class="form-radio h-4 w-4 text-violet-600 focus:ring-violet-500">
                                            <span class="ml-2.5 text-sm font-medium text-gray-700">{{ __('Use my location') }}</span>
                                        </label>
                                    </div>
                                </div>

                                <!-- Manual Address -->
                                <div id="manual-address-container">
                                    <x-input-label for="address" class="text-sm font-semibold text-gray-900 mb-2">
                                        {{ __('Full Address') }}
                                    </x-input-label>
                                    <textarea id="address" 
                                              rows="3" 
                                              class="block mt-2 w-full rounded-xl border-gray-300 focus:border-blue-500 focus:ring-blue-500 resize-none shadow-sm" 
                                              name="address" 
                                              placeholder="{{ __('Enter your full address') }}">{{ old('address') }}</textarea>
                                    <x-input-error :messages="$errors->get('address')" class="mt-2" />
                                </div>

                                <!-- Geolocation -->
                                <div id="geolocation-container" style="display: none;">
                                    <button type="button" 
                                            @click="getGeolocation"
                                            id="get-location-btn" 
                                            class="inline-flex items-center gap-2 px-5 py-3 bg-gradient-to-r from-gray-800 to-gray-900 hover:from-gray-700 hover:to-gray-800 text-white rounded-xl text-sm font-semibold transition-all duration-200 shadow-md hover:shadow-lg">
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

                        <!-- Destination Details Section -->
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                            <div class="px-6 py-5 bg-gradient-to-r from-green-50 to-emerald-50 border-b border-gray-200">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center">
                                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-bold text-gray-900">{{ __('Destination Details') }}</h3>
                                        <p class="text-xs text-gray-600">{{ __('Specify quantities and destinations') }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="p-6">
                                <div id="destination-fields-container" class="space-y-4">
                                    <div class="destination-block p-5 bg-gradient-to-br from-gray-50 to-gray-100/50 rounded-xl border-2 border-gray-200 hover:border-violet-300 transition-colors">
                                        <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                                            <!-- Quantity -->
                                            <div class="md:col-span-3">
                                                <label class="block text-xs font-bold text-gray-700 mb-2 uppercase tracking-wide">{{ __('Quantity') }}</label>
                                                <x-text-input type="number" 
                                                              name="destinations[0][quantity]" 
                                                              value="{{ old('destinations.0.quantity') }}" 
                                                              required 
                                                              min="1" 
                                                              class="w-full rounded-xl text-sm shadow-sm" 
                                                              placeholder="{{ __('e.g., 100') }}" />
                                            </div>

                                            <!-- Country -->
                                            <div class="md:col-span-4">
                                                <label class="block text-xs font-bold text-gray-700 mb-2 uppercase tracking-wide">{{ __('Country') }}</label>
                                                <select name="destinations[0][country_id]" 
                                                        required 
                                                        class="block w-full rounded-xl border-gray-300 focus:border-violet-500 focus:ring-violet-500 text-sm tom-select-country shadow-sm">
                                                    <option value="">{{ __('Select Country') }}</option>
                                                    @foreach($countries as $country)
                                                        <option value="{{ $country->id }}" 
                                                                data-flag="{{ strtolower($country->code) }}" 
                                                                {{ old('destinations.0.country_id') == $country->id ? 'selected' : '' }}>
                                                            {{ $country->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <!-- Service -->
                                            <div class="md:col-span-4">
                                                <label class="block text-xs font-bold text-gray-700 mb-2 uppercase tracking-wide">{{ __('Service') }}</label>
                                                <select name="destinations[0][service_id]" 
                                                        required 
                                                        class="block w-full rounded-xl border-gray-300 focus:border-violet-500 focus:ring-violet-500 text-sm shadow-sm">
                                                    <option value="">{{ __('Select Service') }}</option>
                                                    @foreach($services as $service)
                                                        <option value="{{ $service->id }}" {{ old('destinations.0.service_id') == $service->id ? 'selected' : '' }}>
                                                            {{ $service->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <!-- Remove Button -->
                                            <div class="md:col-span-1 flex items-end">
                                                <button type="button" 
                                                        @click="removeDestination($event)"
                                                        class="remove-destination w-full p-2.5 text-red-600 hover:text-white hover:bg-red-600 border-2 border-red-300 hover:border-red-600 rounded-xl transition-all duration-200 flex items-center justify-center shadow-sm hover:shadow-md">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <button type="button" 
                                        id="add-destination" 
                                        class="mt-5 inline-flex items-center gap-2 px-5 py-3 bg-white hover:bg-violet-50 text-gray-700 hover:text-violet-700 border-2 border-gray-300 hover:border-violet-400 rounded-xl text-sm font-semibold transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-violet-500 shadow-sm hover:shadow-md">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                    {{ __('Add Another Destination') }}
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column - Product Image -->
                    <div class="lg:col-span-1">
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden sticky top-6">
                            <div class="px-6 py-5 bg-gradient-to-r from-purple-50 to-pink-50 border-b border-gray-200">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-purple-100 rounded-xl flex items-center justify-center">
                                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-bold text-gray-900">{{ __('Product Image') }}</h3>
                                        <p class="text-xs text-gray-600">{{ __('Upload product photo') }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="p-6">
                                <div class="space-y-4">
                                    <!-- Image Preview -->
                                    <div class="relative aspect-square bg-gradient-to-br from-gray-100 to-gray-50 rounded-2xl overflow-hidden border-2 border-dashed border-gray-300 group hover:border-violet-400 transition-all duration-200 shadow-inner">
                                        <img id="product_image_preview" 
                                             class="w-full h-full object-cover opacity-0 transition-opacity duration-300" 
                                             src="" 
                                             alt="Product preview" />
                                        <div id="placeholder" 
                                             class="absolute inset-0 flex flex-col items-center justify-center text-gray-400 group-hover:text-violet-500 transition-colors duration-200">
                                            <div class="w-20 h-20 bg-white rounded-2xl shadow-sm flex items-center justify-center mb-4 group-hover:scale-105 transition-transform">
                                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                            </div>
                                            <p class="text-sm font-semibold">{{ __('No image selected') }}</p>
                                            <p class="text-xs mt-1">{{ __('Click below to upload') }}</p>
                                        </div>
                                    </div>

                                    <!-- Upload Button -->
                                    <label for="product_image" class="block cursor-pointer">
                                        <div class="flex items-center justify-center gap-2 px-5 py-3.5 bg-gradient-to-r from-violet-600 to-purple-600 hover:from-violet-700 hover:to-purple-700 text-white rounded-xl text-sm font-bold transition-all duration-200 shadow-md hover:shadow-lg">
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

                                    <div class="p-3 bg-blue-50 border border-blue-200 rounded-xl">
                                        <p class="text-xs text-blue-700 font-medium flex items-start gap-2">
                                            <svg class="w-4 h-4 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            <span>{{ __('Accepted formats: JPG, PNG, WebP (Max: 5MB)') }}</span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="mt-8 flex flex-col sm:flex-row items-center justify-between gap-4 bg-white rounded-2xl shadow-sm border border-gray-200 px-6 py-5">
                    <a href="{{ route('client.dashboard') }}" 
                       class="text-sm font-semibold text-gray-700 hover:text-gray-900 transition-colors duration-200 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        {{ __('Cancel') }}
                    </a>
                    <button type="submit" 
                            class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-8 py-3.5 bg-gradient-to-r from-violet-600 to-purple-600 hover:from-violet-700 hover:to-purple-700 text-white text-sm font-bold rounded-xl shadow-lg transition-all duration-200 hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-violet-500">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        {{ __('Submit Request') }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            console.log('DOMContentLoaded fired.');



            // Address options toggle
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

            // Image Preview
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

            // Alpine.js data for form and pagination
            Alpine.data('sourcingRequestForm', () => ({
                currentPage: 1,
                itemsPerPage: 3, // Display 3 destination blocks per page
                destinationIndex: 0, // Use 0-based index for new blocks
                totalDestinations: 0,

                init() {
                    // Initialize TomSelect for existing elements
                    this.$nextTick(() => {
                        document.querySelectorAll('.tom-select-country').forEach(el => {
                            this.initializeTomSelect(el);
                        });
                        this.totalDestinations = document.querySelectorAll('.destination-block').length;
                        this.updateDestinationVisibility();
                    });
                },

                initializeTomSelect(element) {
                    if (element.tomselect) {
                        element.tomselect.destroy();
                    }
                    new TomSelect(element, {
                        render: {
                            option: function(data, escape) {
                                return '<div class="flex items-center gap-2 py-1"><span class="fi fi-' + data.flag + '"></span><span>' + escape(data.text) + '</span></div>';
                            },
                            item: function(data, escape) {
                                return '<div class="flex items-center gap-2"><span class="fi fi-' + data.flag + '"></span><span>' + escape(data.text) + '</span></div>';
                            }
                        }
                    });
                },

                updateDestinationVisibility() {
                    const blocks = document.querySelectorAll('.destination-block');
                    blocks.forEach((block, index) => {
                        const startIndex = (this.currentPage - 1) * this.itemsPerPage;
                        const endIndex = startIndex + this.itemsPerPage;
                        if (index >= startIndex && index < endIndex) {
                            block.style.display = 'block';
                        } else {
                            block.style.display = 'none';
                        }
                    });
                },

                addDestination() {
                    const container = document.getElementById('destination-fields-container');
                    const blockTemplate = container.querySelector('.destination-block');
                    const newBlock = blockTemplate.cloneNode(true);

                    newBlock.querySelectorAll('input, select').forEach(el => {
                        const name = el.getAttribute('name').replace(/destinations\[\d+\]/, `destinations[${this.totalDestinations}]`);
                        el.setAttribute('name', name);
                        if (el.tagName === 'INPUT') el.value = '';
                        if (el.tagName === 'SELECT') {
                            el.selectedIndex = 0;
                        }
                    });

                    // Re-initialize TomSelect for the new block
                    const oldSelect = newBlock.querySelector('.tom-select-country');
                    const newSelect = oldSelect.cloneNode(true);
                    oldSelect.parentNode.replaceChild(newSelect, oldSelect);

                    container.appendChild(newBlock);
                    this.initializeTomSelect(newBlock.querySelector('.tom-select-country'));
                    this.totalDestinations++;
                    this.currentPage = Math.ceil(this.totalDestinations / this.itemsPerPage); // Go to the last page
                    this.updateDestinationVisibility();
                    this.bindRemoveButtons();
                },

                removeDestination(event) {
                    const block = event.target.closest('.destination-block');
                    if (this.totalDestinations > 1) {
                        const select = block.querySelector('.tom-select-country');
                        if (select.tomselect) {
                            select.tomselect.destroy();
                        }
                        block.remove();
                        this.totalDestinations--;
                        // Re-index remaining destinations
                        document.querySelectorAll('.destination-block').forEach((b, idx) => {
                            b.querySelectorAll('input, select').forEach(el => {
                                const name = el.getAttribute('name').replace(/destinations\[\d+\]/, `destinations[${idx}]`);
                                el.setAttribute('name', name);
                            });
                        });
                        // Adjust current page if necessary
                        if (this.currentPage > Math.ceil(this.totalDestinations / this.itemsPerPage)) {
                            this.currentPage = Math.max(1, Math.ceil(this.totalDestinations / this.itemsPerPage));
                        }
                        this.updateDestinationVisibility();
                    } else {
                        alert(document.querySelector('form').dataset.translationDestinationRequired);
                    }
                },

                nextPage() {
                    if (this.currentPage < this.totalPages) {
                        this.currentPage++;
                        this.updateDestinationVisibility();
                    }
                },

                prevPage() {
                    if (this.currentPage > 1) {
                        this.currentPage--;
                        this.updateDestinationVisibility();
                    }
                },

                get totalPages() {
                    return Math.ceil(this.totalDestinations / this.itemsPerPage);
                },

                // AJAX Form Submission
                async submitForm(event) {
                    console.log('Submit event listener triggered.');
                    event.preventDefault();
                    console.log('event.preventDefault() called.');
                    window.dispatchEvent(new CustomEvent('loading-start', { detail: { message: 'Creating your sourcing request...' } }));

                    // Clear previous errors
                    document.querySelectorAll('.js-error-message').forEach(el => el.remove());

                    const form = event.target;
                    const formData = new FormData(form);
                    const action = form.getAttribute('action');

                    try {
                        const response = await fetch(action, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                        });

                        const data = await response.json();

                        if (response.ok) {
                            window.dispatchEvent(new CustomEvent('show-success-toast', { detail: data.message || 'Request submitted successfully!' }));
                            setTimeout(() => {
                                window.location.href = data.redirect_url || '{{ route("client.dashboard") }}';
                            }, 1500);
                        } else if (response.status === 422) {
                            window.dispatchEvent(new CustomEvent('show-error-toast', { detail: data.message || 'Please check the form for errors.' }));
                            
                            if (data.errors) {
                                for (const field in data.errors) {
                                    const sanitizedField = field.replace(/\./g, '[').replace(/\[(\d+)]/, '[$1]');
                                    const input = document.querySelector(`[name="${sanitizedField}"]`);
                                    if (input) {
                                        let errorContainer = input.closest('.destination-block') || input.parentNode;
                                        let errorEl = errorContainer.querySelector(`.js-error-${field.replace(/\./g, '-')}`);
                                        if (!errorEl) {
                                            errorEl = document.createElement('div');
                                            errorEl.className = `js-error-message js-error-${field.replace(/\./g, '-')} text-red-600 text-sm mt-1 font-semibold`;
                                            input.parentNode.insertBefore(errorEl, input.nextSibling);
                                        }
                                        errorEl.textContent = data.errors[field][0];
                                    }
                                }
                            }
                        } else {
                            window.dispatchEvent(new CustomEvent('show-error-toast', { detail: data.message || 'An unexpected error occurred. Please try again.' }));
                        }

                    } catch (error) {
                        window.dispatchEvent(new CustomEvent('show-error-toast', { detail: 'A network error occurred. Please check your connection.' }));
                    } finally {
                        window.dispatchEvent(new CustomEvent('loading-stop'));
                    }
                },

                // Geolocation logic (integrated into Alpine.js)
                getGeolocation() {
                    const form = document.querySelector('form');
                    const locationFeedback = document.getElementById('location-feedback');
                    const latitudeInput = document.getElementById('latitude');
                    const longitudeInput = document.getElementById('longitude');

                    locationFeedback.textContent = form.dataset.translationGettingLocation;
                    locationFeedback.className = 'mt-3 text-sm font-medium text-blue-600';

                    if (navigator.geolocation) {
                        navigator.geolocation.getCurrentPosition(
                            (position) => {
                                latitudeInput.value = position.coords.latitude;
                                longitudeInput.value = position.coords.longitude;
                                locationFeedback.textContent = form.dataset.translationLocationCaptured;
                                locationFeedback.className = 'mt-3 text-sm font-medium text-green-600';
                            },
                            (error) => {
                                console.error('Geolocation error:', error);
                                locationFeedback.textContent = form.dataset.translationLocationError;
                                locationFeedback.className = 'mt-3 text-sm font-medium text-red-600';
                            }
                        );
                    } else {
                        locationFeedback.textContent = form.dataset.translationGeolocationUnsupported;
                        locationFeedback.className = 'mt-3 text-sm font-medium text-red-600';
                    }
                }
            }));
        });
    </script>
    @endpush
</x-app-layout>