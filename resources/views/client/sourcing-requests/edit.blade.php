<x-app-layout>
    <x-slot name="header">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="flex-1 min-w-0">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight">
                        {{ __('Edit Sourcing Request') }}
                    </h2>
                    <p class="mt-1 text-base text-gray-600 dark:text-gray-400">{{ __('Update your product sourcing details and requirements') }}</p>
                </div>
                <a href="{{ route('client.sourcing-requests.show', $sourcingRequest) }}" 
                   class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors shadow-sm w-full sm:w-auto justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    {{ __('Back to Request') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6 bg-gray-50 dark:bg-gray-900 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('client.sourcing-requests.update', $sourcingRequest) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Progress Steps -->
                <div class="mb-8">
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                        <div class="flex items-center justify-between max-w-3xl mx-auto">
                            <!-- Step 1 -->
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-blue-600 text-white flex items-center justify-center text-sm font-bold">
                                    1
                                </div>
                                <div class="hidden sm:block">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ __('Product Details') }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('Basic information') }}</p>
                                </div>
                            </div>
                            
                            <!-- Connector -->
                            <div class="flex-1 h-0.5 bg-gray-200 dark:bg-gray-700 mx-4"></div>
                            
                            <!-- Step 2 -->
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-blue-600 text-white flex items-center justify-center text-sm font-bold">
                                    2
                                </div>
                                <div class="hidden sm:block">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ __('Image & Details') }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('Visual reference') }}</p>
                                </div>
                            </div>
                            
                            <!-- Connector -->
                            <div class="flex-1 h-0.5 bg-gray-200 dark:bg-gray-700 mx-4"></div>
                            
                            <!-- Step 3 -->
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-gray-300 dark:bg-gray-700 text-gray-600 dark:text-gray-400 flex items-center justify-center text-sm font-bold">
                                    3
                                </div>
                                <div class="hidden sm:block">
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Destinations') }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ __('Shipping details') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Left Column - Product Details -->
                    <div class="lg:col-span-2 space-y-6">
                        <!-- Product Details Section -->
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                            <div class="px-6 py-5 bg-gray-50 dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ __('Product & Core Details') }}</h3>
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ __('Essential product information and requirements') }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="p-6 space-y-6">
                                <!-- Product Name -->
                                <div>
                                    <x-input-label for="product_name" class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2 flex items-center gap-2">
                                        {{ __('Product Name') }} <span class="text-red-500">*</span>
                                    </x-input-label>
                                    <x-text-input id="product_name" 
                                                  class="block w-full rounded-lg border-gray-300 dark:border-gray-600 focus:border-blue-500 focus:ring-blue-500 shadow-sm dark:bg-gray-700 dark:text-white" 
                                                  type="text" 
                                                  name="product_name" 
                                                  :value="old('product_name', $sourcingRequest->product_name)" 
                                                  required 
                                                  autofocus />
                                    <x-input-error :messages="$errors->get('product_name')" class="mt-2" />
                                </div>

                                <!-- Product URL -->
                                <div>
                                    <x-input-label for="product_url" class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2 flex items-center gap-2">
                                        {{ __('Product URL (Optional)') }}
                                    </x-input-label>
                                    <x-text-input id="product_url" 
                                                  class="block w-full rounded-lg border-gray-300 dark:border-gray-600 focus:border-blue-500 focus:ring-blue-500 shadow-sm dark:bg-gray-700 dark:text-white" 
                                                  type="url" 
                                                  name="product_url" 
                                                  :value="old('product_url', $sourcingRequest->product_url)" />
                                    <x-input-error :messages="$errors->get('product_url')" class="mt-2" />
                                </div>

                                <!-- Category & Sourcing Location -->
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <!-- Category -->
                                    <div>
                                        <x-input-label for="category_id" class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2 flex items-center gap-2">
                                            {{ __('Category') }} <span class="text-red-500">*</span>
                                        </x-input-label>
                                        <select id="category_id" 
                                                name="category_id" 
                                                class="block w-full rounded-lg border-gray-300 dark:border-gray-600 focus:border-blue-500 focus:ring-blue-500 text-gray-900 dark:text-white shadow-sm dark:bg-gray-700" 
                                                required>
                                            <option value="">{{ __('Select a Category') }}</option>
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}" {{ old('category_id', $sourcingRequest->category_id) == $category->id ? 'selected' : '' }}>
                                                    {{ $category->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <x-input-error :messages="$errors->get('category_id')" class="mt-2" />
                                    </div>

                                    <!-- Sourcing Location -->
                                    <div>
                                        <x-input-label for="sourcing_location" class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2 flex items-center gap-2">
                                            {{ __('Sourcing Location') }} <span class="text-red-500">*</span>
                                        </x-input-label>
                                        <select id="sourcing_location" 
                                                name="sourcing_location" 
                                                class="block w-full rounded-lg border-gray-300 dark:border-gray-600 focus:border-blue-500 focus:ring-blue-500 text-gray-900 dark:text-white shadow-sm dark:bg-gray-700" 
                                                required>
                                            <option value="china" {{ old('sourcing_location', $sourcingRequest->sourcing_location) == 'china' ? 'selected' : '' }}>{{ __('China') }}</option>
                                            <option value="dubai" {{ old('sourcing_location', $sourcingRequest->sourcing_location) == 'dubai' ? 'selected' : '' }}>{{ __('Dubai') }}</option>
                                        </select>
                                        <x-input-error :messages="$errors->get('sourcing_location')" class="mt-2" />
                                    </div>
                                </div>

                                <!-- Note -->
                                <div>
                                    <x-input-label for="note" class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2 flex items-center gap-2">
                                        {{ __('Additional Notes / Specific Requirements') }}
                                    </x-input-label>
                                    <textarea id="note" 
                                              rows="4" 
                                              class="block w-full rounded-lg border-gray-300 dark:border-gray-600 focus:border-blue-500 focus:ring-blue-500 resize-none shadow-sm dark:bg-gray-700 dark:text-white" 
                                              name="note" 
                                              placeholder="{{ __('e.g., Target price, preferred material, packaging requirements...') }}">{{ old('note', $sourcingRequest->note) }}</textarea>
                                    <x-input-error :messages="$errors->get('note')" class="mt-2" />
                                </div>

                                <!-- Shipping Method -->
                                <div>
                                    <x-input-label class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3 flex items-center gap-2">
                                        {{ __('Shipping Method') }}
                                    </x-input-label>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <label class="relative flex items-center p-4 border-2 rounded-lg cursor-pointer transition-all duration-200 hover:border-blue-300 dark:hover:border-blue-600 has-[:checked]:border-blue-500 dark:has-[:checked]:border-blue-400 has-[:checked]:bg-blue-50 dark:has-[:checked]:bg-blue-900/20 has-[:checked]:shadow-sm group">
                                            <input type="radio" id="shipping_method_air" name="shipping_method" value="air" class="peer sr-only" {{ old('shipping_method', $sourcingRequest->shipping_method) == 'air' ? 'checked' : '' }}>
                                            <div class="flex items-center gap-4 w-full">
                                                <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center flex-shrink-0">
                                                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                                    </svg>
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <p class="font-bold text-gray-900 dark:text-white text-sm mb-1">{{ __('Air Freight') }}</p>
                                                    <p class="text-xs text-gray-600 dark:text-gray-400">{{ __('Faster delivery, suitable for small volumes') }}</p>
                                                </div>
                                                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 opacity-0 peer-checked:opacity-100 transition-opacity flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                </svg>
                                            </div>
                                        </label>

                                        <label class="relative flex items-center p-4 border-2 rounded-lg cursor-pointer transition-all duration-200 hover:border-blue-300 dark:hover:border-blue-600 has-[:checked]:border-blue-500 dark:has-[:checked]:border-blue-400 has-[:checked]:bg-blue-50 dark:has-[:checked]:bg-blue-900/20 has-[:checked]:shadow-sm group">
                                            <input type="radio" id="shipping_method_sea" name="shipping_method" value="sea" class="peer sr-only" {{ old('shipping_method', $sourcingRequest->shipping_method) == 'sea' ? 'checked' : '' }}>
                                            <div class="flex items-center gap-4 w-full">
                                                <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center flex-shrink-0">
                                                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15l5.12-5.12A3 3 0 0110.24 9H13a2 2 0 012 2v1a2 2 0 002 2h3.28a1 1 0 01.948 1.316l-1.4 4.2A2 2 0 0118.36 21H5.64a2 2 0 01-1.946-1.484l-1.4-4.2A1 1 0 013.28 14H6a2 2 0 002-2v-1a2 2 0 00-2-2H4.76a3 3 0 01-2.12-.879L3 15z"/>
                                                    </svg>
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <p class="font-bold text-gray-900 dark:text-white text-sm mb-1">{{ __('Sea Freight') }}</p>
                                                    <p class="text-xs text-gray-600 dark:text-gray-400">{{ __('Cost-effective, suitable for large volumes') }}</p>
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
                    </div>

                    <!-- Right Column - Product Image & Destinations -->
                    <div class="space-y-6">
                        <!-- Product Image Section -->
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                            <div class="px-6 py-5 bg-gray-50 dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ __('Product Image') }}</h3>
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ __('Visual reference for sourcing team') }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="p-6">
                                <div class="space-y-4">
                                    <!-- Image Preview -->
                                    <div class="relative aspect-square bg-gray-50 dark:bg-gray-700 rounded-lg overflow-hidden border-2 border-dashed border-gray-300 dark:border-gray-600 group hover:border-blue-500 transition-all duration-200 shadow-inner">
                                        <img id="product_image_preview" 
                                             class="w-full h-full object-cover" 
                                             src="{{ $sourcingRequest->product_image ? asset('storage/' . $sourcingRequest->product_image) : 'https://via.placeholder.com/400x400?text=No+Image' }}" 
                                             alt="Product preview" />
                                    </div>

                                    <!-- Upload Button -->
                                    <label for="product_image" class="block cursor-pointer">
                                        <div class="flex items-center justify-center gap-2 px-5 py-3.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-bold transition-all duration-200 shadow-sm hover:shadow-md">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                                            </svg>
                                            <span>{{ __('Upload New Image') }}</span>
                                        </div>
                                        <input id="product_image" 
                                               type="file" 
                                               class="sr-only" 
                                               name="product_image" 
                                               accept="image/*" />
                                    </label>

                                    <div class="p-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg">
                                        <p class="text-xs text-gray-600 dark:text-gray-400 font-medium flex items-start gap-2">
                                            <svg class="w-4 h-4 flex-shrink-0 mt-0.5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            <span>{{ __('PNG, JPG, JPEG up to 5MB. Leave blank to keep current image.') }}</span>
                                        </p>
                                    </div>
                                </div>
                                <x-input-error :messages="$errors->get('product_image')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Destination Details Section -->
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                            <div class="px-6 py-5 bg-gray-50 dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center flex-shrink-0">
                                            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ __('Shipping Destinations') }}</h3>
                                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ __('Where and how much to ship') }}</p>
                                        </div>
                                    </div>
                                    <button type="button" 
                                            id="add-destination" 
                                            class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg shadow-sm hover:shadow-md transition-all duration-200">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                        </svg>
                                        {{ __('Add Destination') }}
                                    </button>
                                </div>
                            </div>

                            <div class="p-6">
                                <div id="destination-fields-container" class="space-y-4">
                                    @foreach ($sourcingRequest->destinations as $index => $destination)
                                        <div class="destination-block p-4 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600 hover:border-blue-400 dark:hover:border-blue-600 transition-colors">
                                            <div class="space-y-4">
                                                <!-- Quantity -->
                                                <div>
                                                    <label for="destinations_{{ $index }}_quantity" class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-2 uppercase tracking-wide">{{ __('Quantity') }}</label>
                                                    <x-text-input type="number" 
                                                                  id="destinations_{{ $index }}_quantity"
                                                                  name="destinations[{{ $index }}][quantity]" 
                                                                  value="{{ old('destinations.' . $index . '.quantity', $destination->quantity) }}" 
                                                                  required 
                                                                  min="1" 
                                                                  class="w-full rounded-lg shadow-sm dark:bg-gray-800 dark:text-white border-gray-300 dark:border-gray-600 focus:border-blue-500 focus:ring-blue-500" 
                                                                  placeholder="{{ __('e.g., 100') }}" />
                                                    <x-input-error :messages="$errors->get('destinations.' . $index . '.quantity')" class="mt-2" />
                                                </div>

                                                <!-- Country -->
                                                <div>
                                                    <label for="destinations_{{ $index }}_country_id" class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-2 uppercase tracking-wide">{{ __('Country') }}</label>
                                                    <select id="destinations_{{ $index }}_country_id"
                                                            name="destinations[{{ $index }}][country_id]" 
                                                            required 
                                                            class="block w-full rounded-lg border-gray-300 dark:border-gray-600 focus:border-blue-500 focus:ring-blue-500 shadow-sm dark:bg-gray-800 dark:text-white">
                                                        <option value="">{{ __('Select Country') }}</option>
                                                        @foreach($countries as $country)
                                                            <option value="{{ $country->id }}" 
                                                                    {{ old('destinations.' . $index . '.country_id', $destination->country_id) == $country->id ? 'selected' : '' }}>
                                                                {{ $country->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <x-input-error :messages="$errors->get('destinations.' . $index . '.country_id')" class="mt-2" />
                                                </div>

                                                <!-- Service -->
                                                <div>
                                                    <label for="destinations_{{ $index }}_service_id" class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-2 uppercase tracking-wide">{{ __('Service') }}</label>
                                                    <select id="destinations_{{ $index }}_service_id"
                                                            name="destinations[{{ $index }}][service_id]" 
                                                            required 
                                                            class="block w-full rounded-lg border-gray-300 dark:border-gray-600 focus:border-blue-500 focus:ring-blue-500 shadow-sm dark:bg-gray-800 dark:text-white">
                                                        <option value="">{{ __('Select Service') }}</option>
                                                        @foreach($services as $service)
                                                            <option value="{{ $service->id }}" {{ old('destinations.' . $index . '.service_id', $destination->service_id) == $service->id ? 'selected' : '' }}>
                                                                {{ $service->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <x-input-error :messages="$errors->get('destinations.' . $index . '.service_id')" class="mt-2" />
                                                </div>

                                                <!-- Remove Button -->
                                                <div class="flex items-center justify-center">
                                                    <button type="button" 
                                                            class="remove-destination w-full sm:w-auto px-4 py-2.5 text-red-600 dark:text-red-400 hover:text-white hover:bg-red-600 border border-red-300 dark:border-red-700 rounded-lg transition-all duration-200 flex items-center justify-center gap-2 shadow-sm text-sm font-medium">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                        </svg>
                                                        <span>{{ __('Remove') }}</span>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="mt-8 flex flex-col sm:flex-row items-center justify-end gap-4 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 px-6 py-5">
                    <a href="{{ route('client.sourcing-requests.show', $sourcingRequest) }}" 
                       class="w-full sm:w-auto text-sm font-medium text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors duration-200 flex items-center justify-center gap-2 py-2.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        {{ __('Cancel') }}
                    </a>
                    <button type="submit" 
                            class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-8 py-3.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold rounded-lg shadow-sm transition-all duration-200 hover:shadow-md focus:outline-none focus:ring-4 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-blue-700/50">
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
        document.addEventListener('DOMContentLoaded', () => {
            const addBtn = document.getElementById('add-destination');
            const container = document.getElementById('destination-fields-container');
            const atLeastOneDestinationRequired = "{{ __('At least one destination is required!') }}";
            
            // --- Helper: Get New Index ---
            const getNewIndex = () => {
                const existingIndices = Array.from(container.querySelectorAll('[name^="destinations"]'))
                    .map(el => {
                        const match = el.name.match(/\[(\d+)\]/);
                        return match ? parseInt(match[1]) : 0;
                    });
                return existingIndices.length > 0 ? Math.max(...existingIndices) + 1 : 0;
            };

            // --- Helper: Destination Block Template ---
            const getDestinationTemplate = (index) => {
                const template = document.createElement('div');
                template.classList.add('destination-block', 'p-4', 'bg-gray-50', 'dark:bg-gray-700', 'rounded-lg', 'border', 'border-gray-200', 'dark:border-gray-600', 'hover:border-blue-400', 'dark:hover:border-blue-600', 'transition-colors');
                template.innerHTML = `
                    <div class="space-y-4">
                        <!-- Quantity -->
                        <div>
                            <label for="destinations_${index}_quantity" class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-2 uppercase tracking-wide">{{ __('Quantity') }}</label>
                            <input type="number" 
                                   id="destinations_${index}_quantity"
                                   name="destinations[${index}][quantity]" 
                                   value=""
                                   required 
                                   min="1" 
                                   class="w-full rounded-lg shadow-sm dark:bg-gray-800 dark:text-white border-gray-300 dark:border-gray-600 focus:border-blue-500 focus:ring-blue-500" 
                                   placeholder="{{ __('e.g., 100') }}" />
                        </div>

                        <!-- Country -->
                        <div>
                            <label for="destinations_${index}_country_id" class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-2 uppercase tracking-wide">{{ __('Country') }}</label>
                            <select id="destinations_${index}_country_id"
                                    name="destinations[${index}][country_id]" 
                                    required 
                                    class="block w-full rounded-lg border-gray-300 dark:border-gray-600 focus:border-blue-500 focus:ring-blue-500 shadow-sm dark:bg-gray-800 dark:text-white">
                                <option value="">{{ __('Select Country') }}</option>
                                @foreach($countries as $country)
                                    <option value="{{ $country->id }}">{{ $country->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Service -->
                        <div>
                            <label for="destinations_${index}_service_id" class="block text-xs font-bold text-gray-700 dark:text-gray-300 mb-2 uppercase tracking-wide">{{ __('Service') }}</label>
                            <select id="destinations_${index}_service_id"
                                    name="destinations[${index}][service_id]" 
                                    required 
                                    class="block w-full rounded-lg border-gray-300 dark:border-gray-600 focus:border-blue-500 focus:ring-blue-500 shadow-sm dark:bg-gray-800 dark:text-white">
                                <option value="">{{ __('Select Service') }}</option>
                                @foreach($services as $service)
                                    <option value="{{ $service->id }}">{{ $service->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Remove Button -->
                        <div class="flex items-center justify-center">
                            <button type="button" 
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
            };

            // --- Event: Remove Destination ---
            container.addEventListener('click', e => {
                if (e.target.closest('.remove-destination')) {
                    const block = e.target.closest('.destination-block');
                    if (container.children.length > 1) {
                        block.style.opacity = '0';
                        block.style.transform = 'translateY(-20px)';
                        block.style.height = '0';
                        block.style.padding = '0';
                        setTimeout(() => block.remove(), 300);
                    } else {
                        alert(atLeastOneDestinationRequired);
                    }
                }
            });

            // --- Event: Add Destination ---
            addBtn.addEventListener('click', () => {
                const newIndex = getNewIndex();
                const newBlock = getDestinationTemplate(newIndex);
                
                container.appendChild(newBlock);

                newBlock.style.opacity = '0';
                newBlock.style.transform = 'translateY(20px)';
                setTimeout(() => {
                    newBlock.style.transition = 'all 0.3s ease';
                    newBlock.style.opacity = '1';
                    newBlock.style.transform = 'translateY(0)';
                }, 10);
            });

            // --- Event: Image Preview ---
            const input = document.getElementById('product_image');
            const preview = document.getElementById('product_image_preview');
            input.addEventListener('change', e => {
                const file = e.target.files[0];
                if (file) {
                    if (file.size > 5 * 1024 * 1024) {
                        alert("{{ __('File size must be less than 5MB') }}");
                        input.value = '';
                        return;
                    }
                    
                    const reader = new FileReader();
                    reader.onload = ev => {
                        preview.src = ev.target.result;
                    };
                    reader.readAsDataURL(file);
                }
            });
        });
    </script>
    @endpush
</x-app-layout>