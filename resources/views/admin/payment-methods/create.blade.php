<x-app-layout :breadcrumb="[
    ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
    ['label' => 'Payment Methods', 'url' => route('admin.payment-methods.index')],
    ['label' => 'Create']
]">
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="p-3 bg-green-600 rounded-lg shadow-sm">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                        {{ __('Create Payment Method') }}
                    </h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ __('Add a new payment method for clients') }}</p>
                </div>
            </div>
            <a href="{{ route('admin.payment-methods.index') }}" 
               class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors duration-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                {{ __('Back') }}
            </a>
        </div>
    </x-slot>

    <div class="py-6 bg-gray-50 dark:bg-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('admin.payment-methods.store') }}" enctype="multipart/form-data">
                @csrf

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    
                    {{-- Left Column - Basic Info --}}
                    <div class="lg:col-span-2 space-y-6">
                        
                        {{-- Basic Information Card --}}
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                            <div class="p-6">
                                <div class="flex items-center gap-3 mb-6">
                                    <div class="p-2 bg-blue-50 dark:bg-blue-900/50 rounded-lg">
                                        <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ __('Basic Information') }}</h3>
                                </div>

                                <div class="space-y-4">
                                    {{-- Name --}}
                                    <div>
                                        <label for="name" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                            {{ __('Payment Method Name') }}
                                            <span class="text-red-500">*</span>
                                        </label>
                                        <input id="name" 
                                               type="text" 
                                               name="name" 
                                               value="{{ old('name') }}" 
                                               required 
                                               autofocus
                                               placeholder="e.g., CIH Bank, Wise Transfer"
                                               class="block w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors duration-200 dark:bg-gray-700 dark:text-white">
                                        @error('name')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    {{-- Logo Upload --}}
                                    <div>
                                        <label for="logo" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                            {{ __('Payment Method Logo') }}
                                        </label>
                                        <div class="flex items-center gap-4">
                                            <div class="flex-shrink-0">
                                                <div id="logo-preview" class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-lg border-2 border-dashed border-gray-300 dark:border-gray-600 flex items-center justify-center overflow-hidden">
                                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                    </svg>
                                                </div>
                                            </div>
                                            <div class="flex-1">
                                                <input type="file" 
                                                       name="logo" 
                                                       id="logo" 
                                                       accept="image/*"
                                                       onchange="previewLogo(event)"
                                                       class="block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 dark:file:bg-blue-900/50 file:text-blue-700 dark:file:text-blue-300 hover:file:bg-blue-100 dark:hover:file:bg-blue-900 cursor-pointer">
                                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">PNG, JPG or SVG (Max. 2MB)</p>
                                            </div>
                                        </div>
                                        @error('logo')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Account Details Card --}}
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                            <div class="p-6">
                                <div class="flex items-center gap-3 mb-6">
                                    <div class="p-2 bg-purple-50 dark:bg-purple-900/50 rounded-lg">
                                        <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                    </div>
                                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ __('Account Details') }}</h3>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    {{-- Account Type --}}
                                    <div>
                                        <label for="account_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            {{ __('Account Type') }}
                                        </label>
                                        <input id="account_type" 
                                               type="text" 
                                               name="account_type" 
                                               value="{{ old('account_type') }}"
                                               placeholder="e.g., Checking, Business"
                                               class="block w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors duration-200 dark:bg-gray-700 dark:text-white">
                                    </div>

                                    {{-- Account Holder --}}
                                    <div>
                                        <label for="account_holder" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            {{ __('Account Holder') }}
                                        </label>
                                        <input id="account_holder" 
                                               type="text" 
                                               name="account_holder" 
                                               value="{{ old('account_holder') }}"
                                               placeholder="Full name"
                                               class="block w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors duration-200 dark:bg-gray-700 dark:text-white">
                                    </div>

                                    {{-- Account Number --}}
                                    <div>
                                        <label for="account_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            {{ __('Account Number') }}
                                        </label>
                                        <input id="account_number" 
                                               type="text" 
                                               name="account_number" 
                                               value="{{ old('account_number') }}"
                                               placeholder="Enter account number"
                                               class="block w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors duration-200 dark:bg-gray-700 dark:text-white">
                                    </div>

                                    {{-- Routing Number --}}
                                    <div>
                                        <label for="routing_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            {{ __('Routing Number') }}
                                        </label>
                                        <input id="routing_number" 
                                               type="text" 
                                               name="routing_number" 
                                               value="{{ old('routing_number') }}"
                                               placeholder="Enter routing number"
                                               class="block w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors duration-200 dark:bg-gray-700 dark:text-white">
                                    </div>

                                    {{-- Bank Name --}}
                                    <div>
                                        <label for="bank_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            {{ __('Bank Name') }}
                                        </label>
                                        <input id="bank_name" 
                                               type="text" 
                                               name="bank_name" 
                                               value="{{ old('bank_name') }}"
                                               placeholder="e.g., CIH Bank"
                                               class="block w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors duration-200 dark:bg-gray-700 dark:text-white">
                                    </div>

                                    {{-- SWIFT Code --}}
                                    <div>
                                        <label for="swift_code" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            {{ __('SWIFT/BIC Code') }}
                                        </label>
                                        <input id="swift_code" 
                                               type="text" 
                                               name="swift_code" 
                                               value="{{ old('swift_code') }}"
                                               placeholder="e.g., CIHBMAMA"
                                               class="block w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors duration-200 dark:bg-gray-700 dark:text-white">
                                    </div>

                                    {{-- IBAN --}}
                                    <div>
                                        <label for="iban" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            {{ __('IBAN') }}
                                        </label>
                                        <input id="iban" 
                                               type="text" 
                                               name="iban" 
                                               value="{{ old('iban') }}"
                                               placeholder="e.g., MA64..."
                                               class="block w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors duration-200 dark:bg-gray-700 dark:text-white">
                                    </div>

                                    {{-- Country --}}
                                    <div>
                                        <label for="country" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            {{ __('Country') }}
                                        </label>
                                        <input id="country" 
                                               type="text" 
                                               name="country" 
                                               value="{{ old('country') }}"
                                               placeholder="e.g., Morocco"
                                               class="block w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors duration-200 dark:bg-gray-700 dark:text-white">
                                    </div>

                                    {{-- Email --}}
                                    <div>
                                        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            {{ __('Email') }}
                                        </label>
                                        <input id="email" 
                                               type="email" 
                                               name="email" 
                                               value="{{ old('email') }}"
                                               placeholder="account@example.com"
                                               class="block w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors duration-200 dark:bg-gray-700 dark:text-white">
                                    </div>

                                    {{-- Address --}}
                                    <div>
                                        <label for="address" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            {{ __('Address') }}
                                        </label>
                                        <input id="address" 
                                               type="text" 
                                               name="address" 
                                               value="{{ old('address') }}"
                                               placeholder="Full address"
                                               class="block w-full px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors duration-200 dark:bg-gray-700 dark:text-white">
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    {{-- Right Column - Status & Actions --}}
                    <div class="lg:col-span-1 space-y-6">
                        
                        {{-- Status Card --}}
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                            <div class="p-6">
                                <div class="flex items-center gap-3 mb-4">
                                    <div class="p-2 bg-green-50 dark:bg-green-900/50 rounded-lg">
                                        <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                    <h4 class="text-lg font-bold text-gray-900 dark:text-white">{{ __('Status') }}</h4>
                                </div>
                                
                                <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600">
                                    <label for="is_active" class="flex items-center cursor-pointer">
                                        <div class="relative">
                                            <input id="is_active" 
                                                   type="checkbox" 
                                                   name="is_active" 
                                                   value="1" 
                                                   checked
                                                   class="sr-only peer">
                                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-gray-600 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-500 peer-checked:bg-green-600"></div>
                                        </div>
                                        <span class="ml-3 text-sm font-medium text-gray-900 dark:text-gray-300">{{ __('Active') }}</span>
                                    </label>
                                    <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">{{ __('Enable this payment method for clients') }}</p>
                                </div>
                            </div>
                        </div>

                        {{-- Actions Card --}}
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                            <div class="p-6">
                                <div class="flex items-center gap-3 mb-4">
                                    <div class="p-2 bg-blue-50 dark:bg-blue-900/50 rounded-lg">
                                        <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                        </svg>
                                    </div>
                                    <h4 class="text-lg font-bold text-gray-900 dark:text-white">{{ __('Actions') }}</h4>
                                </div>
                                
                                <div class="space-y-3">
                                    <button type="submit" 
                                            class="w-full py-3 px-4 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg transition-colors duration-200 flex items-center justify-center gap-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        {{ __('Create Payment Method') }}
                                    </button>

                                    <a href="{{ route('admin.payment-methods.index') }}" 
                                       class="w-full py-3 px-4 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 font-medium rounded-lg transition-colors duration-200 flex items-center justify-center gap-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                        {{ __('Cancel') }}
                                    </a>
                                </div>
                            </div>
                        </div>

                        {{-- Help Card --}}
                        <div class="bg-blue-50 dark:bg-blue-900/50 rounded-lg border border-blue-100 dark:border-blue-900 p-6">
                            <div class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <div>
                                    <h5 class="text-sm font-semibold text-blue-900 dark:text-blue-300 mb-1">{{ __('Quick Tips') }}</h5>
                                    <ul class="text-xs text-blue-700 dark:text-blue-400 space-y-1">
                                        <li>• {{ __('Use clear, descriptive names') }}</li>
                                        <li>• {{ __('Upload high-quality logos') }}</li>
                                        <li>• {{ __('Verify account details') }}</li>
                                        <li>• {{ __('Double-check SWIFT/IBAN codes') }}</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        function previewLogo(event) {
            const preview = document.getElementById('logo-preview');
            const file = event.target.files[0];
            
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.innerHTML = `<img src="${e.target.result}" alt="Logo Preview" class="w-full h-full object-contain">`;
                }
                reader.readAsDataURL(file);
            }
        }
    </script>
</x-app-layout>