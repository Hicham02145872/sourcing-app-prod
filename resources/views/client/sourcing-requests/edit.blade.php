<x-app-layout>
    <x-slot name="header">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6">
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-[#EF7722] rounded-lg flex items-center justify-center shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-3xl font-bold text-slate-900 dark:text-white tracking-tight">
                                {{ __('Edit Sourcing Request') }}
                            </h2>
                            <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">{{ __('Update your product details and requirements') }}</p>
                        </div>
                    </div>
                </div>
                <a href="{{ route('client.dashboard') }}" 
                   class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-semibold text-slate-700 dark:text-slate-300 bg-white dark:bg-slate-800 border border-[#EBEBEB] dark:border-slate-600 hover:bg-slate-50 dark:hover:bg-slate-700 rounded-lg transition-all duration-200 shadow-sm w-full sm:w-auto justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    {{ __('Cancel') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-25 bg-[#fffff] dark:bg-slate-900 min-h-screen">
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
                        <div class="w-8 h-8 bg-[#EBEBEB] dark:bg-slate-700 text-slate-500 rounded-full flex items-center justify-center text-sm font-bold">3</div>
                        <span class="text-sm font-semibold text-slate-500 dark:text-slate-400">{{ __('Contact Info') }}</span>
                    </div>
                </div>
            </div>

            <form method="POST" action="{{ route('client.sourcing-requests.update', $sourcingRequest) }}" enctype="multipart/form-data"
                  x-data="sourcingRequestForm" @submit.prevent="submitForm"
                  data-translation-destination-required="{{ __('At least one destination is required!') }}"
                  data-translation-getting-location="{{ __('Getting your location...') }}"
                  data-translation-location-captured="{{ __('Location captured successfully!') }}"
                  data-translation-location-error="{{ __('Unable to retrieve your location.') }}"
                  data-translation-geolocation-unsupported="{{ __('Geolocation is not supported by your browser.') }}">
                @csrf
                @method('PUT')

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
                                                  :value="old('product_name', $sourcingRequest->product_name)" 
                                                  required 
                                                  autofocus 
                                                  placeholder="{{ __('E.g., Wireless Headphones, LED Bulbs...') }}" />
                                    <x-input-error :messages="$errors->get('product_name')" class="mt-2" />
                                </div>

                                <!-- Category & Location Grid -->
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div>
                                        <label for="category_id" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">
                                            {{ __('Category') }} <span class="text-red-500">*</span>
                                        </label>
                                        <select id="category_id" 
                                                name="category_id" 
                                                class="block w-full rounded-lg border-[#EBEBEB] dark:border-slate-600 focus:border-[#EF7722] focus:ring-[#EF7722] text-slate-900 dark:text-white shadow-sm dark:bg-slate-700 text-sm" 
                                                required>
                                            <option value="">{{ __('Select a category') }}</option>
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}" {{ old('category_id', $sourcingRequest->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
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
                                                class="block w-full rounded-lg border-[#EBEBEB] dark:border-slate-600 focus:border-[#EF7722] focus:ring-[#EF7722] text-slate-900 dark:text-white shadow-sm dark:bg-slate-700 text-sm" 
                                                required>
                                            <option value="china" {{ old('sourcing_location', $sourcingRequest->sourcing_location) == 'china' ? 'selected' : '' }}>{{ __('China') }}</option>
                                            <option value="dubai" {{ old('sourcing_location', $sourcingRequest->sourcing_location) == 'dubai' ? 'selected' : '' }}>{{ __('Dubai') }}</option>
                                        </select>
                                        <x-input-error :messages="$errors->get('sourcing_location')" class="mt-2" />
                                    </div>
                                </div>
                            </div>

                            <!-- Right: Product Image (1 column) -->
                            <div class="sm:col-span-1 flex flex-col items-center">
                                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2 text-center w-full">
                                    {{ __('Photo') }}
                                </label>
                                <div class="relative w-32 h-32 bg-slate-50 dark:bg-slate-700 rounded-lg overflow-hidden border-2 border-dashed border-[#EBEBEB] dark:border-slate-600 group hover:border-[#EF7722] transition-all cursor-pointer shadow-sm">
                                    <img id="product_image_preview" 
                                         class="w-full h-full object-cover" 
                                         src="{{ $sourcingRequest->product_image ? asset('storage/' . $sourcingRequest->product_image) : '' }}" 
                                         alt="{{ __('Product preview') }}" />
                                    <div id="placeholder" 
                                         class="absolute inset-0 flex flex-col items-center justify-center text-slate-400 dark:text-slate-500 group-hover:text-[#EF7722] transition-colors {{ $sourcingRequest->product_image ? 'opacity-0' : '' }}">
                                        <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        <span class="text-xs font-medium text-center">{{ __('Upload') }}</span>
                                    </div>
                                    <label for="product_image" class="absolute inset-0 cursor-pointer"></label>
                                    <input id="product_image" type="file" class="sr-only" name="product_image" accept="image/*" />
                                </div>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-2 text-center">{{ __('Max 5MB') }}</p>
                            </div>
                        </div>

                        <!-- Shipping Method -->
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-3">
                                {{ __('Preferred Shipping') }} <span class="text-red-500">*</span>
                            </label>
                            <div class="grid grid-cols-2 gap-3">
                                <label class="relative flex items-center p-3 border-2 border-[#EBEBEB] dark:border-slate-600 rounded-lg cursor-pointer hover:border-[#EF7722] dark:hover:border-[#EF7722] transition-all has-[:checked]:border-[#EF7722] has-[:checked]:bg-[#EF7722]/5 has-[:checked]:shadow-sm">
                                    <input type="radio" id="shipping_method_air" name="shipping_method" value="air" class="sr-only" {{ old('shipping_method', $sourcingRequest->shipping_method) == 'air' ? 'checked' : '' }}>
                                    <div class="flex items-center gap-2 w-full">
                                        <svg class="w-5 h-5 text-[#EF7722] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                        </svg>
                                        <span class="text-sm font-semibold text-slate-700 dark:text-slate-300">{{ __('Air (Fast)') }}</span>
                                    </div>
                                </label>

                                <label class="relative flex items-center p-3 border-2 border-[#EBEBEB] dark:border-slate-600 rounded-lg cursor-pointer hover:border-[#EF7722] dark:hover:border-[#EF7722] transition-all has-[:checked]:border-[#EF7722] has-[:checked]:bg-[#EF7722]/5 has-[:checked]:shadow-sm">
                                    <input type="radio" id="shipping_method_sea" name="shipping_method" value="sea" class="sr-only" {{ old('shipping_method', $sourcingRequest->shipping_method) == 'sea' ? 'checked' : '' }}>
                                    <div class="flex items-center gap-2 w-full">
                                        <svg class="w-5 h-5 text-[#EF7722] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8 4-8-4m16 0l-8-4m8 4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                        </svg>
                                        <span class="text-sm font-semibold text-slate-700 dark:text-slate-300">{{ __('Sea (Economy)') }}</span>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Additional Notes -->
                        <div>
                            <label for="note" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">
                                {{ __('Any special requirements?') }}
                            </label>
                            <textarea id="note" 
                                      rows="2" 
                                      class="block w-full rounded-lg border-[#EBEBEB] dark:border-slate-600 focus:border-[#EF7722] focus:ring-[#EF7722] resize-none shadow-sm dark:bg-slate-700 dark:text-white text-sm" 
                                      name="note" 
                                      placeholder="{{ __('Colors, sizes, materials, quality requirements...') }}">{{ old('note', $sourcingRequest->note) }}</textarea>
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
                        @foreach ($sourcingRequest->destinations as $index => $destination)
                        <div class="destination-block p-4 bg-[#EBEBEB] dark:bg-slate-700 rounded-lg border border-[#EBEBEB] dark:border-slate-600">
                            <div class="space-y-3">
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                    <div>
                                        <label for="destinations_{{ $index }}_quantity" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5 uppercase tracking-wide">
                                            {{ __('Quantity') }}
                                        </label>
                                        <x-text-input type="number" 
                                                      id="destinations_{{ $index }}_quantity"
                                                      name="destinations[{{ $index }}][quantity]" 
                                                      value="{{ old('destinations.' . $index . '.quantity', $destination->quantity) }}" 
                                                      required 
                                                      min="1" 
                                                      class="w-full rounded-lg text-sm shadow-sm dark:bg-slate-800 dark:text-white border-[#EBEBEB] dark:border-slate-600 focus:border-[#EF7722] focus:ring-[#EF7722]" 
                                                      placeholder="100" />
                                    </div>

                                    <div>
                                        <label for="destinations_{{ $index }}_country_id" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5 uppercase tracking-wide">
                                            {{ __('Country') }}
                                        </label>
                                        <select id="destinations_{{ $index }}_country_id"
                                                name="destinations[{{ $index }}][country_id]" 
                                                required 
                                                class="block w-full rounded-lg border-[#EBEBEB] dark:border-slate-600 focus:border-[#EF7722] focus:ring-[#EF7722] text-sm tom-select-country shadow-sm dark:bg-slate-800 dark:text-white">
                                            <option value="">{{ __('Select') }}</option>
                                            @foreach($countries as $country)
                                                <option value="{{ $country->id }}" data-flag="{{ strtolower($country->code) }}" {{ old('destinations.' . $index . '.country_id', $destination->country_id) == $country->id ? 'selected' : '' }}>
                                                    {{ $country->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div>
                                        <label for="destinations_{{ $index }}_service_id" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5 uppercase tracking-wide">
                                            {{ __('Service') }}
                                        </label>
                                        <select id="destinations_{{ $index }}_service_id"
                                                name="destinations[{{ $index }}][service_id]" 
                                                required 
                                                class="block w-full rounded-lg border-[#EBEBEB] dark:border-slate-600 focus:border-[#EF7722] focus:ring-[#EF7722] text-sm shadow-sm dark:bg-slate-800 dark:text-white">
                                            <option value="">{{ __('Select') }}</option>
                                            @foreach($services as $service)
                                                <option value="{{ $service->id }}" {{ old('destinations.' . $index . '.service_id', $destination->service_id) == $service->id ? 'selected' : '' }}>{{ $service->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
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
                        @endforeach
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

                <!-- Step 3: Contact Info -->
                <div class="bg-white dark:bg-slate-800 rounded-xl shadow-sm border border-[#EBEBEB] dark:border-slate-700 p-6 sm:p-8 mb-6">
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-6 flex items-center gap-3">
                        <span class="w-8 h-8 bg-[#EF7722] text-white rounded-full flex items-center justify-center text-sm font-bold">3</span>
                        {{ __('Your Information') }}
                    </h3>

                    <input type="hidden" id="phone_number" name="phone_number" value="{{ old('phone_number', auth()->user()->phone) }}" />

                    <div class="space-y-4">
                        <!-- Address Section -->
                        <div>
                            <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-3">
                                {{ __('Delivery Address') }}
                            </label>
                            <div class="flex flex-col sm:flex-row gap-3 p-3 bg-[#EBEBEB] dark:bg-slate-700 rounded-lg border border-[#EBEBEB] dark:border-slate-600 mb-3">
                                <label class="flex items-center cursor-pointer">
                                    <input type="radio" id="address_manual" name="address_option" value="manual" class="form-radio h-4 w-4 text-[#EF7722] focus:ring-[#EF7722]" {{ old('address_option', $sourcingRequest->address ? 'manual' : 'geolocation') == 'manual' ? 'checked' : '' }}>
                                    <span class="ml-2 text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('Enter manually') }}</span>
                                </label>
                                <label class="flex items-center cursor-pointer">
                                    <input type="radio" id="address_geolocation" name="address_option" value="geolocation" class="form-radio h-4 w-4 text-[#EF7722] focus:ring-[#EF7722]" {{ old('address_option', $sourcingRequest->address ? 'manual' : 'geolocation') == 'geolocation' ? 'checked' : '' }}>
                                    <span class="ml-2 text-sm font-medium text-slate-700 dark:text-slate-300">{{ __('Use my location') }}</span>
                                </label>
                            </div>

                            <!-- Manual Address -->
                            <div id="manual-address-container" style="{{ old('address_option', $sourcingRequest->address ? 'manual' : 'geolocation') == 'manual' ? '' : 'display: none;' }}">
                                <textarea id="address" 
                                          rows="2" 
                                          class="block w-full rounded-lg border-[#EBEBEB] dark:border-slate-600 focus:border-[#EF7722] focus:ring-[#EF7722] resize-none shadow-sm dark:bg-slate-700 dark:text-white text-sm" 
                                          name="address" 
                                          placeholder="{{ __('Full address including street, city, and postal code') }}">{{ old('address', $sourcingRequest->address) }}</textarea>
                            </div>

                            <!-- Geolocation -->
                            <div id="geolocation-container" style="{{ old('address_option', $sourcingRequest->address ? 'manual' : 'geolocation') == 'geolocation' ? '' : 'display: none;' }}">
                                <button type="button" 
                                        @click="getGeolocation"
                                        class="w-full py-2.5 bg-[#EF7722] hover:bg-[#FAA533] text-white rounded-lg text-sm font-semibold transition-all flex items-center justify-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    </svg>
                                    {{ __('Get My Location') }}
                                </button>
                                <p id="location-feedback" class="mt-3 text-sm font-medium text-center"></p>
                                <input type="hidden" name="latitude" id="latitude" value="{{ old('latitude', $sourcingRequest->latitude) }}">
                                <input type="hidden" name="longitude" id="longitude" value="{{ old('longitude', $sourcingRequest->longitude) }}">
                            </div>
                        </div>
                    </div>
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
                        {{ __('Update Request') }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('alpine:init', () => {
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

            const input = document.getElementById('product_image');
            const preview = document.getElementById('product_image_preview');
            const placeholder = document.getElementById('placeholder');

            if (input) {
                input.onchange = (e) => {
                    const file = e.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = ev => {
                            preview.src = ev.target.result;
                            preview.style.opacity = '1';
                            if (placeholder) {
                                placeholder.style.display = 'none';
                            }
                        };
                        reader.readAsDataURL(file);
                    }
                };
            }

            const initializeTomSelect = (element) => {
                if (element.tomselect) {
                    element.tomselect.destroy();
                }
                new TomSelect(element, {
                    plugins: { 'dropdown_header': { title: '{{ __("Select Country") }}' } },
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

            Alpine.data('sourcingRequestForm', () => ({
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
                                    <input type="number" id="destinations_${newIndex}_quantity" name="destinations[${newIndex}][quantity]" required min="1" class="w-full rounded-lg text-sm shadow-sm dark:bg-slate-800 dark:text-white border-[#EBEBEB] dark:border-slate-600 focus:border-[#EF7722] focus:ring-[#EF7722]" placeholder="100" />
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
                                    <select id="destinations_${newIndex}_service_id" name="destinations[${newIndex}][service_id]" required class="block w-full rounded-lg border-[#EBEBEB] dark:border-slate-600 focus:border-[#EF7722] focus:ring-[#EF7722] text-sm shadow-sm dark:bg-slate-800 dark:text-white">
                                        <option value="">{{ __('Select') }}</option>
                                        @foreach($services as $service)
                                            <option value="{{ $service->id }}">{{ $service->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
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
                        alert(document.querySelector('form').dataset.translationDestinationRequired);
                    }
                },

                async submitForm(event) {
                    event.preventDefault();
                    const form = event.target;
                    const formData = new FormData(form);
                    const action = form.getAttribute('action');

                    try {
                        const response = await fetch(action, {
                            method: 'POST',
                            body: formData,
                            headers: { 
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                        });

                        const data = await response.json();

                        if (response.ok) {
                            window.dispatchEvent(new CustomEvent('show-success-toast', { detail: data.message || '{{ __("Request updated successfully!") }}' }));
                            setTimeout(() => {
                                window.location.href = data.redirect_url || '{{ route("client.dashboard") }}';
                            }, 1500);
                        } else if (response.status === 422) {
                            window.dispatchEvent(new CustomEvent('show-error-toast', { detail: '{{ __("Please check the form for errors.") }}' }));
                            if (data.errors) {
                                for (const field in data.errors) {
                                    const errorPath = field.replace(/\./g, '-');
                                    const inputEl = document.querySelector(`[name="${field}"]`);
                                    if (inputEl) {
                                        inputEl.classList.add('border-red-500');
                                    }
                                }
                            }
                        }
                    } catch (error) {
                        window.dispatchEvent(new CustomEvent('show-error-toast', { detail: '{{ __("A network error occurred.") }}' }));
                    }
                },

                getGeolocation() {
                    const form = document.querySelector('form');
                    const locationFeedback = document.getElementById('location-feedback');
                    const latitudeInput = document.getElementById('latitude');
                    const longitudeInput = document.getElementById('longitude');

                    locationFeedback.textContent = form.dataset.translationGettingLocation;
                    locationFeedback.className = 'mt-3 text-sm font-medium text-blue-600 dark:text-blue-400 text-center';

                    if (navigator.geolocation) {
                        navigator.geolocation.getCurrentPosition(
                            (position) => {
                                latitudeInput.value = position.coords.latitude;
                                longitudeInput.value = position.coords.longitude;
                                locationFeedback.textContent = form.dataset.translationLocationCaptured;
                                locationFeedback.className = 'mt-3 text-sm font-medium text-green-600 dark:text-green-400 text-center';
                            },
                            (error) => {
                                latitudeInput.value = '';
                                longitudeInput.value = '';
                                locationFeedback.textContent = form.dataset.translationLocationError;
                                locationFeedback.className = 'mt-3 text-sm font-medium text-red-600 dark:text-red-400 text-center';
                            }
                        );
                    } else {
                        locationFeedback.textContent = form.dataset.translationGeolocationUnsupported;
                        locationFeedback.className = 'mt-3 text-sm font-medium text-red-600 dark:text-red-400 text-center';
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
    </style>
</x-app-layout>