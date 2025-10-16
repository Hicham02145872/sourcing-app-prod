<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-3xl font-bold text-gray-900 tracking-tight">
                    {{ __('Create Sourcing Request') }}
                </h2>
                <p class="mt-1 text-sm text-gray-600">{{ __('Fill in the details below to submit a new sourcing request') }}</p>
            </div>
            <a href="{{ route('client.dashboard') }}" 
               class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                {{ __('Back to Dashboard') }}
            </a>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50">
        <div class="max-w-8xl mx-auto px-4 sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('client.sourcing-requests.store') }}" enctype="multipart/form-data">
                @csrf

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Left Column - Product Information -->
                    <div class="lg:col-span-2 space-y-6">
                        <!-- Product Details Section -->
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-200">
                            <div class="px-6 py-5 border-b border-gray-200">
                                <h3 class="text-lg font-semibold text-gray-900">{{ __('Product Details') }}</h3>
                                <p class="mt-1 text-sm text-gray-600">{{ __('Basic information about the product') }}</p>
                            </div>

                            <div class="p-6 space-y-6">
                                <!-- Product Name -->
                                <div>
                                    <x-input-label for="product_name" :value="__('Product Name')" class="text-sm font-medium text-gray-900" />
                                    <x-text-input id="product_name" class="block mt-2 w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" type="text" name="product_name" :value="old('product_name')" required autofocus placeholder="e.g., Wireless Bluetooth Headphones" />
                                    <x-input-error :messages="$errors->get('product_name')" class="mt-2" />
                                </div>

                                <!-- Product URL -->
                                <div>
                                    <x-input-label for="product_url" :value="__('Product URL')" class="text-sm font-medium text-gray-900" />
                                    <div class="relative mt-2">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                                            </svg>
                                        </div>
                                        <x-text-input id="product_url" class="block w-full pl-10 rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" type="url" name="product_url" :value="old('product_url')" placeholder="https://example.com/product" />
                                    </div>
                                    <p class="mt-1.5 text-xs text-gray-500">{{ __('Optional: Link to the product reference page') }}</p>
                                    <x-input-error :messages="$errors->get('product_url')" class="mt-2" />
                                </div>

                                <!-- Category -->
                                <div>
                                    <x-input-label for="category_id" :value="__('Category')" class="text-sm font-medium text-gray-900" />
                                    <select id="category_id" name="category_id" class="block mt-2 w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-gray-900" required>
                                        <option value="">{{ __('Select a Category') }}</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                    <x-input-error :messages="$errors->get('category_id')" class="mt-2" />
                                </div>

                                <!-- Note -->
                                <div>
                                    <x-input-label for="note" :value="__('Additional Notes')" class="text-sm font-medium text-gray-900" />
                                    <textarea id="note" rows="4" class="block mt-2 w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 resize-none" name="note" placeholder="Specify colors, sizes, materials, or any other requirements...">{{ old('note') }}</textarea>
                                    <p class="mt-1.5 text-xs text-gray-500">{{ __('Optional: Add specifications or special requirements') }}</p>
                                    <x-input-error :messages="$errors->get('note')" class="mt-2" />
                                </div>

                                <!-- Shipping Method -->
                                <div>
                                    <x-input-label :value="__('Shipping Method')" class="text-sm font-medium text-gray-900 mb-3" />
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                        <label class="relative flex items-center p-4 border-2 rounded-xl cursor-pointer transition-all duration-200 hover:border-indigo-300 has-[:checked]:border-indigo-600 has-[:checked]:bg-indigo-50">
                                            <input type="radio" id="shipping_method_air" name="shipping_method" value="air" class="peer sr-only" {{ old('shipping_method') == 'air' ? 'checked' : '' }}>
                                            <div class="flex items-center gap-3 w-full">
                                                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                                    </svg>
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <p class="font-semibold text-gray-900 text-sm">{{ __('Air Freight') }}</p>
                                                    <p class="text-xs text-gray-600">{{ __('Faster delivery') }}</p>
                                                </div>
                                            </div>
                                        </label>

                                        <label class="relative flex items-center p-4 border-2 rounded-xl cursor-pointer transition-all duration-200 hover:border-indigo-300 has-[:checked]:border-indigo-600 has-[:checked]:bg-indigo-50">
                                            <input type="radio" id="shipping_method_sea" name="shipping_method" value="sea" class="peer sr-only" {{ old('shipping_method') == 'sea' ? 'checked' : '' }}>
                                            <div class="flex items-center gap-3 w-full">
                                                <div class="w-10 h-10 bg-cyan-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                                    <svg class="w-5 h-5 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                                    </svg>
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <p class="font-semibold text-gray-900 text-sm">{{ __('Sea Freight') }}</p>
                                                    <p class="text-xs text-gray-600">{{ __('Cost-effective') }}</p>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                    <x-input-error :messages="$errors->get('shipping_method')" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <!-- Destination Details Section -->
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-200">
                            <div class="px-6 py-5 border-b border-gray-200">
                                <h3 class="text-lg font-semibold text-gray-900">{{ __('Destination Details') }}</h3>
                                <p class="mt-1 text-sm text-gray-600">{{ __('Specify quantities and destinations') }}</p>
                            </div>

                            <div class="p-6">
                                <div id="destination-fields-container" class="space-y-4">
                                    <div class="destination-block p-5 bg-gradient-to-br from-gray-50 to-gray-100/50 rounded-xl border border-gray-200">
                                        <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                                            <!-- Quantity -->
                                            <div class="md:col-span-3">
                                                <label class="block text-xs font-medium text-gray-700 mb-2">{{ __('Quantity') }}</label>
                                                <x-text-input type="number" name="destinations[0][quantity]" value="{{ old('destinations.0.quantity') }}" required min="1" class="w-full rounded-lg text-sm" placeholder="100" />
                                            </div>

                                            <!-- Country -->
                                            <div class="md:col-span-4">
                                                <label class="block text-xs font-medium text-gray-700 mb-2">{{ __('Country') }}</label>
                                                <select name="destinations[0][country_id]" required class="block w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                                    <option value="">{{ __('Select Country') }}</option>
                                                    @foreach($countries as $country)
                                                        <option value="{{ $country->id }}" {{ old('destinations.0.country_id') == $country->id ? 'selected' : '' }}>{{ $country->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <!-- Service -->
                                            <div class="md:col-span-4">
                                                <label class="block text-xs font-medium text-gray-700 mb-2">{{ __('Service') }}</label>
                                                <select name="destinations[0][service_id]" required class="block w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                                    <option value="">{{ __('Select Service') }}</option>
                                                    @foreach($services as $service)
                                                        <option value="{{ $service->id }}" {{ old('destinations.0.service_id') == $service->id ? 'selected' : '' }}>{{ $service->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <!-- Remove Button -->
                                            <div class="md:col-span-1 flex items-end">
                                                <button type="button" class="remove-destination w-full p-2 text-red-600 hover:text-white hover:bg-red-600 border border-red-300 hover:border-red-600 rounded-lg transition-colors duration-200 flex items-center justify-center">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <button type="button" id="add-destination" class="mt-4 inline-flex items-center gap-2 px-4 py-2.5 bg-white hover:bg-gray-50 text-gray-700 border border-gray-300 rounded-lg text-sm font-medium transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
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
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 sticky top-6">
                            <div class="px-6 py-5 border-b border-gray-200">
                                <h3 class="text-lg font-semibold text-gray-900">{{ __('Product Image') }}</h3>
                                <p class="mt-1 text-sm text-gray-600">{{ __('Upload product photo') }}</p>
                            </div>

                            <div class="p-6">
                                <div class="space-y-4">
                                    <!-- Image Preview -->
                                    <div class="relative aspect-square bg-gradient-to-br from-gray-100 to-gray-50 rounded-2xl overflow-hidden border-2 border-dashed border-gray-300 group hover:border-indigo-400 transition-colors duration-200">
                                        <img id="product_image_preview" class="w-full h-full object-cover opacity-0 transition-opacity duration-300" src="" alt="Product preview" />
                                        <div id="placeholder" class="absolute inset-0 flex flex-col items-center justify-center text-gray-400 group-hover:text-indigo-500 transition-colors duration-200">
                                            <svg class="w-16 h-16 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                            <p class="text-sm font-medium">{{ __('No image selected') }}</p>
                                        </div>
                                    </div>

                                    <!-- Upload Button -->
                                    <label for="product_image" class="block cursor-pointer">
                                        <div class="flex items-center justify-center gap-2 px-4 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm font-medium transition-colors duration-200 shadow-sm hover:shadow-md">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                            </svg>
                                            <span id="upload-text">{{ __('Choose Image') }}</span>
                                        </div>
                                        <input id="product_image" type="file" class="sr-only" name="product_image" accept="image/*" />
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="mt-6 flex items-center justify-between bg-white rounded-2xl shadow-sm border border-gray-200 px-6 py-4">
                    <a href="{{ route('client.dashboard') }}" class="text-sm font-medium text-gray-700 hover:text-gray-900 transition-colors duration-200">
                        {{ __('Cancel') }}
                    </a>
                    <button type="submit" class="inline-flex items-center gap-2 px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg shadow-sm transition-all duration-200 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
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
            let destinationIndex = 1;
            const container = document.getElementById('destination-fields-container');
            const addBtn = document.getElementById('add-destination');

            function bindRemoveButtons() {
                document.querySelectorAll('.remove-destination').forEach(btn => {
                    btn.onclick = () => {
                        const block = btn.closest('.destination-block');
                        if (document.querySelectorAll('.destination-block').length > 1) {
                            block.remove();
                        } else {
                            alert("At least one destination is required!");
                        }
                    };
                });
            }

            addBtn.onclick = () => {
                const block = container.querySelector('.destination-block').cloneNode(true);
                block.querySelectorAll('input, select').forEach(el => {
                    const name = el.getAttribute('name').replace(/\d+/, destinationIndex);
                    el.setAttribute('name', name);
                    if (el.tagName === 'INPUT') el.value = '';
                    if (el.tagName === 'SELECT') el.selectedIndex = 0;
                });
                container.appendChild(block);
                destinationIndex++;
                bindRemoveButtons();
            };

            bindRemoveButtons();

            // Image Preview
            const input = document.getElementById('product_image');
            const preview = document.getElementById('product_image_preview');
            const placeholder = document.getElementById('placeholder');

            input.onchange = (e) => {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = ev => {
                        preview.src = ev.target.result;
                        preview.style.opacity = '1';
                        placeholder.style.display = 'none';
                    };
                    reader.readAsDataURL(file);
                }
            };
        });
    </script>
    @endpush
</x-app-layout>
