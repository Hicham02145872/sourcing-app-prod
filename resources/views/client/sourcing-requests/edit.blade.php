<x-app-layout>
    <x-slot name="header">
        <div class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 -m-6 p-6 mb-0">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-violet-600 dark:bg-violet-700 rounded-lg shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </div>
                        <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">
                            {{ __('Edit Sourcing Request') }}
                        </h2>
                    </div>
                    <a href="{{ route('client.sourcing-requests.show', $sourcingRequest) }}" 
                       class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-semibold text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        {{ __('Back to Request') }}
                    </a>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-10 bg-gray-50 dark:bg-gray-900 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('client.sourcing-requests.update', $sourcingRequest) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- Bento Grid Layout (Enterprise Cards) --}}
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

                    {{-- Product Details Card - Large --}}
                    <div class="lg:col-span-7 bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 p-8">
                        <div class="flex items-center gap-3 mb-6 border-b border-gray-100 dark:border-gray-700 pb-4">
                            <div class="p-2 bg-violet-100 dark:bg-violet-900/30 rounded-lg">
                                <svg class="w-5 h-5 text-violet-600 dark:text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white">{{ __('Product & Core Details') }}</h3>
                        </div>

                        <div class="space-y-6">
                            {{-- Product Name --}}
                            <div>
                                <x-input-label for="product_name" class="flex items-center gap-2 text-gray-700 dark:text-gray-300 font-semibold mb-2">
                                    <svg class="w-4 h-4 text-violet-600 dark:text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                    </svg>
                                    {{ __('Product Name') }}
                                </x-input-label>
                                <x-text-input id="product_name" 
                                    class="block w-full border-gray-300 dark:border-gray-600 focus:border-violet-500 focus:ring-violet-500 rounded-lg dark:bg-gray-700 dark:text-white" 
                                    type="text" 
                                    name="product_name" 
                                    :value="old('product_name', $sourcingRequest->product_name)" 
                                    required 
                                    autofocus />
                                <x-input-error :messages="$errors->get('product_name')" class="mt-2" />
                            </div>

                            {{-- Product URL --}}
                            <div>
                                <x-input-label for="product_url" class="flex items-center gap-2 text-gray-700 dark:text-gray-300 font-semibold mb-2">
                                    <svg class="w-4 h-4 text-violet-600 dark:text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                                    </svg>
                                    {{ __('Product URL (Optional)') }}
                                </x-input-label>
                                <x-text-input id="product_url" 
                                    class="block w-full border-gray-300 dark:border-gray-600 focus:border-violet-500 focus:ring-violet-500 rounded-lg dark:bg-gray-700 dark:text-white" 
                                    type="url" 
                                    name="product_url" 
                                    :value="old('product_url', $sourcingRequest->product_url)" />
                                <x-input-error :messages="$errors->get('product_url')" class="mt-2" />
                            </div>

                            {{-- Category --}}
                            <div>
                                <x-input-label for="category_id" class="flex items-center gap-2 text-gray-700 dark:text-gray-300 font-semibold mb-2">
                                    <svg class="w-4 h-4 text-violet-600 dark:text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                    </svg>
                                    {{ __('Category') }}
                                </x-input-label>
                                <select id="category_id" 
                                    name="category_id" 
                                    class="block w-full border-gray-300 dark:border-gray-600 focus:border-violet-500 focus:ring-violet-500 rounded-lg shadow-sm dark:bg-gray-700 dark:text-white" 
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

                            {{-- Sourcing Location --}}
                            <div>
                                <x-input-label for="sourcing_location" class="flex items-center gap-2 text-gray-700 dark:text-gray-300 font-semibold mb-2">
                                    <svg class="w-4 h-4 text-violet-600 dark:text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    {{ __('Sourcing Location') }}
                                </x-input-label>
                                <select id="sourcing_location" 
                                        name="sourcing_location" 
                                        class="block w-full border-gray-300 dark:border-gray-600 focus:border-violet-500 focus:ring-violet-500 rounded-lg shadow-sm dark:bg-gray-700 dark:text-white" 
                                        required>
                                    <option value="china" {{ old('sourcing_location', $sourcingRequest->sourcing_location) == 'china' ? 'selected' : '' }}>{{ __('China') }}</option>
                                    <option value="dubai" {{ old('sourcing_location', $sourcingRequest->sourcing_location) == 'dubai' ? 'selected' : '' }}>{{ __('Dubai') }}</option>
                                </select>
                                <x-input-error :messages="$errors->get('sourcing_location')" class="mt-2" />
                            </div>

                            {{-- Note --}}
                            <div>
                                <x-input-label for="note" class="flex items-center gap-2 text-gray-700 dark:text-gray-300 font-semibold mb-2">
                                    <svg class="w-4 h-4 text-violet-600 dark:text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                    {{ __('Additional Notes / Specific Requirements') }}
                                </x-input-label>
                                <textarea id="note" 
                                    class="block w-full border-gray-300 dark:border-gray-600 focus:border-violet-500 focus:ring-violet-500 rounded-lg shadow-sm dark:bg-gray-700 dark:text-white" 
                                    name="note" 
                                    rows="4" 
                                    placeholder="{{ __('e.g., Target price, preferred material, packaging requirements...') }}">{{ old('note', $sourcingRequest->note) }}</textarea>
                                <x-input-error :messages="$errors->get('note')" class="mt-2" />
                            </div>

                            {{-- Shipping Method --}}
                            <div>
                                <x-input-label class="flex items-center gap-2 text-gray-700 dark:text-gray-300 font-semibold mb-3">
                                    <svg class="w-4 h-4 text-violet-600 dark:text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                                    </svg>
                                    {{ __('Shipping Method') }}
                                </x-input-label>
                                <div class="grid grid-cols-2 gap-4">
                                    <label class="relative flex items-center p-4 bg-gray-50 dark:bg-gray-700 border-2 border-gray-200 dark:border-gray-600 rounded-xl cursor-pointer hover:border-violet-400 transition-all duration-200 has-[:checked]:border-violet-600 has-[:checked]:bg-violet-50 dark:has-[:checked]:bg-violet-900/30 has-[:checked]:shadow-md">
                                        <input type="radio" 
                                            name="shipping_method" 
                                            value="air" 
                                            class="sr-only peer" 
                                            {{ old('shipping_method', $sourcingRequest->shipping_method) == 'air' ? 'checked' : '' }}>
                                        <div class="flex items-center gap-3">
                                            <svg class="w-6 h-6 text-violet-600 dark:text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                            </svg>
                                            <span class="font-semibold text-gray-900 dark:text-white">{{ __('Air freight') }}</span>
                                        </div>
                                        <div class="absolute top-3 right-3 w-5 h-5 rounded-full border-2 border-gray-300 dark:border-gray-600 peer-checked:bg-violet-600 peer-checked:border-violet-600 dark:peer-checked:border-violet-500 flex items-center justify-center">
                                            <svg class="w-3 h-3 text-white hidden peer-checked:block" fill="none" stroke="currentColor" viewBox="0 0 12 12">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6.5l2 2 4-4"/>
                                            </svg>
                                        </div>
                                    </label>
                                    
                                    <label class="relative flex items-center p-4 bg-gray-50 dark:bg-gray-700 border-2 border-gray-200 dark:border-gray-600 rounded-xl cursor-pointer hover:border-violet-400 transition-all duration-200 has-[:checked]:border-violet-600 has-[:checked]:bg-violet-50 dark:has-[:checked]:bg-violet-900/30 has-[:checked]:shadow-md">
                                        <input type="radio" 
                                            name="shipping_method" 
                                            value="sea" 
                                            class="sr-only peer" 
                                            {{ old('shipping_method', $sourcingRequest->shipping_method) == 'sea' ? 'checked' : '' }}>
                                        <div class="flex items-center gap-3">
                                            <svg class="w-6 h-6 text-violet-600 dark:text-violet-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15l5.12-5.12A3 3 0 0110.24 9H13a2 2 0 012 2v1a2 2 0 002 2h3.28a1 1 0 01.948 1.316l-1.4 4.2A2 2 0 0118.36 21H5.64a2 2 0 01-1.946-1.484l-1.4-4.2A1 1 0 013.28 14H6a2 2 0 002-2v-1a2 2 0 00-2-2H4.76a3 3 0 01-2.12-.879L3 15z"/>
                                            </svg>
                                            <span class="font-semibold text-gray-900 dark:text-white">{{ __('Sea freight') }}</span>
                                        </div>
                                        <div class="absolute top-3 right-3 w-5 h-5 rounded-full border-2 border-gray-300 dark:border-gray-600 peer-checked:bg-violet-600 peer-checked:border-violet-600 dark:peer-checked:border-violet-500 flex items-center justify-center">
                                            <svg class="w-3 h-3 text-white hidden peer-checked:block" fill="none" stroke="currentColor" viewBox="0 0 12 12">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6.5l2 2 4-4"/>
                                            </svg>
                                        </div>
                                    </label>
                                </div>
                                <x-input-error :messages="$errors->get('shipping_method')" class="mt-2" />
                            </div>
                        </div>
                    </div>

                    {{-- Image Upload Card --}}
                    <div class="lg:col-span-5 bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 p-8">
                        <div class="flex items-center gap-3 mb-6 border-b border-gray-100 dark:border-gray-700 pb-4">
                            <div class="p-2 bg-purple-100 dark:bg-purple-900/30 rounded-lg">
                                <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white">{{ __('Product Image') }}</h3>
                        </div>

                        <div class="space-y-6">
                            <div class="flex flex-col items-center">
                                <div class="relative group">
                                    <img id="product_image_preview" 
                                        class="w-56 h-56 rounded-xl object-cover shadow-xl border-4 border-violet-100 dark:border-violet-900/50 group-hover:border-violet-400 transition-all duration-300" 
                                        src="{{ $sourcingRequest->product_image ? asset('storage/' . $sourcingRequest->product_image) : 'https://via.placeholder.com/224x224?text=No+Image' }}" 
                                        alt="Product preview" />
                                    <div class="absolute inset-0 bg-black/40 rounded-xl opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                                        <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                    </div>
                                </div>
                                
                                <label class="mt-6 cursor-pointer inline-flex items-center gap-2 px-6 py-3 bg-violet-600 hover:bg-violet-700 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transition-all duration-200">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                                    </svg>
                                    <span>{{ __('Upload New Image') }}</span>
                                    <input id="product_image" type="file" class="sr-only" name="product_image" accept="image/*" />
                                </label>
                                
                                <p class="mt-3 text-xs text-gray-500 dark:text-gray-400 text-center">
                                    {{ __('PNG, JPG, JPEG up to 5MB. Leave blank to keep current image.') }}
                                </p>
                            </div>

                            <div class="p-4 bg-violet-50 dark:bg-gray-700 rounded-lg border border-violet-200 dark:border-gray-600">
                                <div class="flex items-start gap-3">
                                    <svg class="w-5 h-5 text-violet-600 dark:text-violet-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <div>
                                        <p class="text-sm font-semibold text-violet-900 dark:text-violet-300 mb-1">{{ __('Image Tips') }}</p>
                                        <ul class="text-xs text-violet-700 dark:text-violet-400 space-y-1">
                                            <li>• {{ __('High-quality product images recommended (2:1 or 1:1 aspect ratio).') }}</li>
                                            <li>• {{ __('Max file size: 5MB.') }}</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <x-input-error :messages="$errors->get('product_image')" class="mt-2" />
                    </div>

                    {{-- Destinations Section - Full Width --}}
                    <div class="lg:col-span-12 bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 p-8">
                        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-6 border-b border-gray-100 dark:border-gray-700 pb-4">
                            <div class="flex items-center gap-3 mb-4 sm:mb-0">
                                <div class="p-2 bg-green-100 dark:bg-green-900/30 rounded-lg">
                                    <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                </div>
                                <h3 class="text-xl font-bold text-gray-900 dark:text-white">{{ __('Shipping Destinations & Quantities') }}</h3>
                            </div>
                            
                            <button type="button" 
                                id="add-destination" 
                                class="inline-flex items-center gap-2 px-5 py-2 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg shadow-md hover:shadow-lg transition-all duration-200">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                                {{ __('Add Destination') }}
                            </button>
                        </div>

                        <div id="destination-fields-container" class="space-y-4">
                            @foreach ($sourcingRequest->destinations as $index => $destination)
                                <div class="destination-block group bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600 p-4 hover:border-violet-300 dark:hover:border-violet-600 hover:shadow-sm transition-all duration-300">
                                    <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-center">
                                        {{-- Quantity --}}
                                        <div class="md:col-span-2">
                                            <label class="text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1 block">{{ __('Quantity') }}</label>
                                            <div class="relative">
                                                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                                </svg>
                                                <x-text-input type="number" 
                                                    name="destinations[{{ $index }}][quantity]" 
                                                    value="{{ old('destinations.' . $index . '.quantity', $destination->quantity) }}" 
                                                    required 
                                                    min="1" 
                                                    class="w-full pl-10 border-gray-300 dark:border-gray-600 focus:border-violet-500 focus:ring-violet-500 rounded-lg dark:bg-gray-800 dark:text-white" 
                                                    placeholder="0" />
                                            </div>
                                        </div>

                                        {{-- Country --}}
                                        <div class="md:col-span-4">
                                            <label class="text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1 block">{{ __('Country') }}</label>
                                            <select name="destinations[{{ $index }}][country_id]" 
                                                required 
                                                class="w-full border-gray-300 dark:border-gray-600 focus:border-violet-500 focus:ring-violet-500 rounded-lg shadow-sm dark:bg-gray-800 dark:text-white">
                                                <option value="">{{ __('Select Country') }}</option>
                                                @foreach($countries as $country)
                                                    <option value="{{ $country->id }}" {{ old('destinations.' . $index . '.country_id', $destination->country_id) == $country->id ? 'selected' : '' }}>
                                                        {{ $country->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        {{-- Service --}}
                                        <div class="md:col-span-5">
                                            <label class="text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1 block">{{ __('Shipping Service Type') }}</label>
                                            <select name="destinations[{{ $index }}][service_id]" 
                                                required 
                                                class="w-full border-gray-300 dark:border-gray-600 focus:border-violet-500 focus:ring-violet-500 rounded-lg shadow-sm dark:bg-gray-800 dark:text-white">
                                                <option value="">{{ __('Select Service') }}</option>
                                                @foreach($services as $service)
                                                    <option value="{{ $service->id }}" {{ old('destinations.' . $index . '.service_id', $destination->service_id) == $service->id ? 'selected' : '' }}>
                                                        {{ $service->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        {{-- Remove Button --}}
                                        <div class="md:col-span-1 flex items-end justify-center">
                                            <button type="button" 
                                                class="remove-destination w-full md:w-auto p-2.5 text-red-600 dark:text-red-400 hover:text-white hover:bg-red-600 border border-red-300 dark:border-red-700 hover:border-red-600 dark:hover:border-red-600 rounded-lg transition-all duration-200 shadow-sm">
                                                <svg class="w-5 h-5 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                    <x-input-error :messages="$errors->get('destinations.' . $index . '.quantity')" class="mt-2" />
                                    <x-input-error :messages="$errors->get('destinations.' . $index . '.country_id')" class="mt-2" />
                                    <x-input-error :messages="$errors->get('destinations.' . $index . '.service_id')" class="mt-2" />
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Submit Button --}}
                    <div class="lg:col-span-12 flex justify-end mt-4">
                        <button type="submit" 
                            class="inline-flex items-center gap-3 px-8 py-3 bg-gradient-to-r from-violet-600 to-purple-600 hover:from-violet-700 hover:to-purple-700 text-white text-lg font-bold rounded-lg shadow-xl hover:shadow-2xl transition-all duration-200 transform hover:scale-[1.01] focus:outline-none focus:ring-4 focus:ring-violet-300 dark:focus:ring-violet-700/50">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            {{ __('Update Request') }}
                        </button>
                    </div>
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

            // --- Helper: Destination Block Template (Using original structure for select inputs) ---
            const getDestinationTemplate = (index) => {
                const template = document.createElement('div');
                template.classList.add('destination-block', 'group', 'bg-gray-50', 'dark:bg-gray-700', 'rounded-lg', 'border', 'border-gray-200', 'dark:border-gray-600', 'p-4', 'hover:border-violet-300', 'dark:hover:border-violet-600', 'hover:shadow-sm', 'transition-all', 'duration-300');
                template.innerHTML = `
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-center">
                        {{-- Quantity --}}
                        <div class="md:col-span-2">
                            <label class="text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1 block">{{ __('Quantity') }}</label>
                            <div class="relative">
                                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                </svg>
                                <input type="number" 
                                    name="destinations[${index}][quantity]" 
                                    value=""
                                    required 
                                    min="1" 
                                    class="w-full pl-10 border-gray-300 dark:border-gray-600 focus:border-violet-500 focus:ring-violet-500 rounded-lg dark:bg-gray-800 dark:text-white" 
                                    placeholder="0" />
                            </div>
                        </div>

                        {{-- Country --}}
                        <div class="md:col-span-4">
                            <label class="text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1 block">{{ __('Country') }}</label>
                            <select name="destinations[${index}][country_id]" 
                                required 
                                class="w-full border-gray-300 dark:border-gray-600 focus:border-violet-500 focus:ring-violet-500 rounded-lg shadow-sm dark:bg-gray-800 dark:text-white">
                                <option value="">{{ __('Select Country') }}</option>
                                @foreach($countries as $country)
                                    <option value="{{ $country->id }}">{{ $country->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Service --}}
                        <div class="md:col-span-5">
                            <label class="text-xs font-semibold text-gray-600 dark:text-gray-400 mb-1 block">{{ __('Shipping Service Type') }}</label>
                            <select name="destinations[${index}][service_id]" 
                                required 
                                class="w-full border-gray-300 dark:border-gray-600 focus:border-violet-500 focus:ring-violet-500 rounded-lg shadow-sm dark:bg-gray-800 dark:text-white">
                                <option value="">{{ __('Select Service') }}</option>
                                @foreach($services as $service)
                                    <option value="{{ $service->id }}">{{ $service->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Remove Button --}}
                        <div class="md:col-span-1 flex items-end justify-center">
                            <button type="button" 
                                class="remove-destination w-full md:w-auto p-2.5 text-red-600 dark:text-red-400 hover:text-white hover:bg-red-600 border border-red-300 dark:border-red-700 hover:border-red-600 dark:hover:border-red-600 rounded-lg transition-all duration-200 shadow-sm">
                                <svg class="w-5 h-5 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                    <x-input-error messages="" class="mt-2 validation-error-quantity" />
                    <x-input-error messages="" class="mt-2 validation-error-country" />
                    <x-input-error messages="" class="mt-2 validation-error-service" />
                `;
                return template;
            };

            // --- Event: Remove Destination ---
            container.addEventListener('click', e => {
                if (e.target.closest('.remove-destination')) {
                    const block = e.target.closest('.destination-block');
                    if (container.children.length > 1) {
                        // Animation de sortie
                        block.style.opacity = '0';
                        block.style.transform = 'translateY(-20px)';
                        block.style.height = '0';
                        block.style.padding = '0';
                        setTimeout(() => block.remove(), 300); // Supprime après l'animation
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

                // Animation d'entrée
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
                        preview.classList.add('ring-4', 'ring-violet-500');
                        setTimeout(() => {
                            preview.classList.remove('ring-4', 'ring-violet-500');
                        }, 1000);
                    };
                    reader.readAsDataURL(file);
                } else if (!preview.src || preview.src.includes('No+Image')) {
                    // Reset to placeholder if no image was previously set and is now cleared
                    preview.src = 'https://via.placeholder.com/224x224?text=No+Image'; 
                }
            });
            
            // Initial placeholder fix (in case the image is null)
            if (!preview.src || preview.src.includes('via.placeholder.com/200')) {
                preview.src = 'https://via.placeholder.com/224x224?text=No+Image';
            }
        });
    </script>
    @endpush
</x-app-layout>