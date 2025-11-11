<x-app-layout :breadcrumb="[
    ['label' => __('Dashboard'), 'url' => route('admin.dashboard')],
    ['label' => __('Payment Methods'), 'url' => route('admin.payment-methods.index')],
    ['label' => __('Edit')]
]">
    <x-slot name="header">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6">
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-amber-600 dark:bg-amber-700 rounded-lg flex items-center justify-center shadow-lg">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-3xl font-bold text-slate-900 dark:text-white tracking-tight">
                                {{ __('Edit Payment Method') }}
                            </h2>
                            <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">{{ __('Update payment method information and settings') }}</p>
                        </div>
                    </div>
                </div>
                <a href="{{ route('admin.payment-methods.index') }}" 
                   class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-semibold text-slate-700 dark:text-slate-300 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-700 transition-all duration-200 shadow-sm hover:shadow w-full sm:w-auto justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    {{ __('Back to List') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-25 bg-slate-50 dark:bg-slate-900 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('admin.payment-methods.update', $paymentMethod) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    
                    {{-- Left Column - Main Content --}}
                    <div class="lg:col-span-2 space-y-6">
                        
                        {{-- Basic Information Card --}}
                        <div class="bg-white dark:bg-slate-800 shadow-sm rounded-lg border border-slate-200 dark:border-slate-700">
                            <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/50">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
                                        <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-base font-bold text-slate-900 dark:text-white">{{ __('Basic Information') }}</h3>
                                        <p class="text-xs text-slate-600 dark:text-slate-400 mt-0.5">{{ __('Update the payment method name and logo') }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="p-6 space-y-5">
                                {{-- Name --}}
                                <div>
                                    <label for="name" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">
                                        {{ __('Payment Method Name') }}
                                        <span class="text-red-500">*</span>
                                    </label>
                                    <input id="name" 
                                           type="text" 
                                           name="name" 
                                           value="{{ old('name', $paymentMethod->name) }}" 
                                           required 
                                           autofocus
                                           placeholder="{{ __('e.g., CIH Bank, Wise Transfer, PayPal') }}"
                                           class="block w-full px-4 py-3 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 dark:bg-slate-700 dark:text-white shadow-sm">
                                    @error('name')
                                        <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                {{-- Logo Upload --}}
                                <div>
                                    <label for="logo" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">
                                        {{ __('Payment Method Logo') }}
                                    </label>
                                    <div class="flex items-start gap-4">
                                        <div class="flex-shrink-0">
                                            <div id="logo-preview" class="w-20 h-20 bg-slate-100 dark:bg-slate-700 rounded-lg border-2 border-dashed border-slate-300 dark:border-slate-600 flex items-center justify-center overflow-hidden transition-all duration-200 hover:border-amber-400 dark:hover:border-amber-500">
                                                @if($paymentMethod->logo_path)
                                                    <img src="{{ asset('storage/' . $paymentMethod->logo_path) }}" alt="{{ $paymentMethod->name }}" class="w-full h-full object-contain p-1">
                                                @else
                                                    <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                    </svg>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="flex-1">
                                            <input type="file" 
                                                   name="logo" 
                                                   id="logo" 
                                                   accept="image/*"
                                                   onchange="previewLogo(event)"
                                                   class="block w-full text-sm text-slate-500 dark:text-slate-400 file:mr-4 file:py-2.5 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-amber-50 dark:file:bg-amber-900/30 file:text-amber-700 dark:file:text-amber-300 hover:file:bg-amber-100 dark:hover:file:bg-amber-900/50 cursor-pointer transition-all duration-200">
                                            <p class="mt-2 text-xs text-slate-500 dark:text-slate-400 flex items-center gap-1">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                {{ __('PNG, JPG or SVG (Max. 2MB) - Leave empty to keep current logo') }}
                                            </p>
                                        </div>
                                    </div>
                                    @error('logo')
                                        <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Account Details Card --}}
                        <div class="bg-white dark:bg-slate-800 shadow-sm rounded-lg border border-slate-200 dark:border-slate-700">
                            <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/50">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-purple-100 dark:bg-purple-900/30 rounded-lg flex items-center justify-center">
                                        <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-base font-bold text-slate-900 dark:text-white">{{ __('Account Details') }}</h3>
                                        <p class="text-xs text-slate-600 dark:text-slate-400 mt-0.5">{{ __('Update the payment account information') }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="p-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    {{-- Account Type --}}
                                    <div>
                                        <label for="account_type" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                            {{ __('Account Type') }}
                                        </label>
                                        <input id="account_type" 
                                               type="text" 
                                               name="account_type" 
                                               value="{{ old('account_type', $paymentMethod->details['account_type'] ?? '') }}"
                                               placeholder="{{ __('e.g., Checking, Business, Savings') }}"
                                               class="block w-full px-4 py-2.5 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 dark:bg-slate-700 dark:text-white shadow-sm">
                                    </div>

                                    {{-- Account Holder --}}
                                    <div>
                                        <label for="account_holder" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                            {{ __('Account Holder') }}
                                        </label>
                                        <input id="account_holder" 
                                               type="text" 
                                               name="account_holder" 
                                               value="{{ old('account_holder', $paymentMethod->details['account_holder'] ?? '') }}"
                                               placeholder="{{ __('Full legal name') }}"
                                               class="block w-full px-4 py-2.5 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 dark:bg-slate-700 dark:text-white shadow-sm">
                                    </div>

                                    {{-- Account Number --}}
                                    <div>
                                        <label for="account_number" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                            {{ __('Account Number') }}
                                        </label>
                                        <input id="account_number" 
                                               type="text" 
                                               name="account_number" 
                                               value="{{ old('account_number', $paymentMethod->details['account_number'] ?? '') }}"
                                               placeholder="{{ __('Enter account number') }}"
                                               class="block w-full px-4 py-2.5 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 dark:bg-slate-700 dark:text-white shadow-sm">
                                    </div>

                                    {{-- Routing Number --}}
                                    <div>
                                        <label for="routing_number" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                            {{ __('Routing Number') }}
                                        </label>
                                        <input id="routing_number" 
                                               type="text" 
                                               name="routing_number" 
                                               value="{{ old('routing_number', $paymentMethod->details['routing_number'] ?? '') }}"
                                               placeholder="{{ __('Enter routing number') }}"
                                               class="block w-full px-4 py-2.5 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 dark:bg-slate-700 dark:text-white shadow-sm">
                                    </div>

                                    {{-- Bank Name --}}
                                    <div>
                                        <label for="bank_name" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                            {{ __('Bank Name') }}
                                        </label>
                                        <input id="bank_name" 
                                               type="text" 
                                               name="bank_name" 
                                               value="{{ old('bank_name', $paymentMethod->details['bank_name'] ?? '') }}"
                                               placeholder="{{ __('e.g., CIH Bank, Bank of America') }}"
                                               class="block w-full px-4 py-2.5 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 dark:bg-slate-700 dark:text-white shadow-sm">
                                    </div>

                                    {{-- SWIFT Code --}}
                                    <div>
                                        <label for="swift_code" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                            {{ __('SWIFT/BIC Code') }}
                                        </label>
                                        <input id="swift_code" 
                                               type="text" 
                                               name="swift_code" 
                                               value="{{ old('swift_code', $paymentMethod->details['swift_code'] ?? '') }}"
                                               placeholder="{{ __('e.g., CIHBMAMA') }}"
                                               class="block w-full px-4 py-2.5 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 dark:bg-slate-700 dark:text-white shadow-sm">
                                    </div>

                                    {{-- IBAN --}}
                                    <div>
                                        <label for="iban" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                            {{ __('IBAN') }}
                                        </label>
                                        <input id="iban" 
                                               type="text" 
                                               name="iban" 
                                               value="{{ old('iban', $paymentMethod->details['iban'] ?? '') }}"
                                               placeholder="{{ __('e.g., MA64011519000001205000534921') }}"
                                               class="block w-full px-4 py-2.5 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 dark:bg-slate-700 dark:text-white shadow-sm">
                                    </div>

                                    {{-- Country --}}
                                    <div>
                                        <label for="country" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                            {{ __('Country') }}
                                        </label>
                                        <input id="country" 
                                               type="text" 
                                               name="country" 
                                               value="{{ old('country', $paymentMethod->details['country'] ?? '') }}"
                                               placeholder="{{ __('e.g., Morocco, United States') }}"
                                               class="block w-full px-4 py-2.5 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 dark:bg-slate-700 dark:text-white shadow-sm">
                                    </div>

                                    {{-- Email --}}
                                    <div>
                                        <label for="email" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                            {{ __('Email') }}
                                        </label>
                                        <input id="email" 
                                               type="email" 
                                               name="email" 
                                               value="{{ old('email', $paymentMethod->details['email'] ?? '') }}"
                                               placeholder="{{ __('account@example.com') }}"
                                               class="block w-full px-4 py-2.5 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 dark:bg-slate-700 dark:text-white shadow-sm">
                                    </div>

                                    {{-- Address --}}
                                    <div>
                                        <label for="address" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                            {{ __('Address') }}
                                        </label>
                                        <input id="address" 
                                               type="text" 
                                               name="address" 
                                               value="{{ old('address', $paymentMethod->details['address'] ?? '') }}"
                                               placeholder="{{ __('Full address') }}"
                                               class="block w-full px-4 py-2.5 border border-slate-300 dark:border-slate-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 dark:bg-slate-700 dark:text-white shadow-sm">
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    {{-- Right Column - Sidebar --}}
                    <div class="lg:col-span-1 space-y-6">
                        
                        {{-- Current Logo Display --}}
                        @if($paymentMethod->logo_path)
                        <div class="bg-white dark:bg-slate-800 shadow-sm rounded-lg border border-slate-200 dark:border-slate-700">
                            <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/50">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-indigo-100 dark:bg-indigo-900/30 rounded-lg flex items-center justify-center">
                                        <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="text-base font-bold text-slate-900 dark:text-white">{{ __('Current Logo') }}</h4>
                                        <p class="text-xs text-slate-600 dark:text-slate-400 mt-0.5">{{ __('Active logo') }}</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="p-6">
                                <div class="flex items-center justify-center p-6 bg-slate-50 dark:bg-slate-900/50 rounded-lg border border-slate-200 dark:border-slate-700">
                                    <img src="{{ asset('storage/' . $paymentMethod->logo_path) }}" alt="{{ $paymentMethod->name }}" class="max-w-full h-24 object-contain">
                                </div>
                            </div>
                        </div>
                        @endif

                        {{-- Status Card --}}
                        <div class="bg-white dark:bg-slate-800 shadow-sm rounded-lg border border-slate-200 dark:border-slate-700">
                            <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/50">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-emerald-100 dark:bg-emerald-900/30 rounded-lg flex items-center justify-center">
                                        <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="text-base font-bold text-slate-900 dark:text-white">{{ __('Status') }}</h4>
                                        <p class="text-xs text-slate-600 dark:text-slate-400 mt-0.5">{{ __('Control visibility') }}</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="p-6">
                                <div class="p-4 bg-slate-50 dark:bg-slate-900/50 rounded-lg border border-slate-200 dark:border-slate-700">
                                    <label for="is_active" class="flex items-center cursor-pointer group">
                                        <div class="relative">
                                            <input id="is_active" 
                                                   type="checkbox" 
                                                   name="is_active" 
                                                   value="1" 
                                                   {{ old('is_active', $paymentMethod->is_active) ? 'checked' : '' }}
                                                   class="sr-only peer">
                                            <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800 rounded-full peer dark:bg-slate-600 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-slate-500 peer-checked:bg-emerald-600"></div>
                                        </div>
                                        <span class="ml-3 text-sm font-semibold text-slate-900 dark:text-slate-300 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">{{ __('Active') }}</span>
                                    </label>
                                    <p class="mt-3 text-xs text-slate-500 dark:text-slate-400 flex items-start gap-1.5">
                                        <svg class="w-3.5 h-3.5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <span>{{ __('Enable this payment method to make it available for clients to use') }}</span>
                                    </p>
                                </div>
                            </div>
                        </div>

                        {{-- Actions Card --}}
                        <div class="bg-white dark:bg-slate-800 shadow-sm rounded-lg border border-slate-200 dark:border-slate-700">
                            <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/50">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-amber-100 dark:bg-amber-900/30 rounded-lg flex items-center justify-center">
                                        <svg class="w-5 h-5 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h4 class="text-base font-bold text-slate-900 dark:text-white">{{ __('Actions') }}</h4>
                                        <p class="text-xs text-slate-600 dark:text-slate-400 mt-0.5">{{ __('Save or cancel') }}</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="p-6 space-y-3">
                                <button type="submit" 
                                        class="w-full py-3 px-4 bg-amber-600 hover:bg-amber-700 dark:bg-amber-700 dark:hover:bg-amber-600 text-white font-semibold rounded-lg transition-all duration-200 flex items-center justify-center gap-2 shadow-sm hover:shadow">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    {{ __('Update Payment Method') }}
                                </button>

                                <a href="{{ route('admin.payment-methods.index') }}" 
                                   class="w-full py-3 px-4 bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-300 font-medium rounded-lg transition-all duration-200 flex items-center justify-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                    {{ __('Cancel') }}
                                </a>
                            </div>
                        </div>

                        {{-- Help Card --}}
                        <div class="bg-amber-50 dark:bg-amber-900/20 rounded-lg border border-amber-200 dark:border-amber-800 p-6">
                            <div class="flex items-start gap-3">
                                <div class="flex-shrink-0 w-10 h-10 bg-amber-100 dark:bg-amber-900/30 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h5 class="text-sm font-bold text-amber-900 dark:text-amber-300 mb-2">{{ __('Update Tips') }}</h5>
                                    <ul class="text-xs text-amber-700 dark:text-amber-400 space-y-2">
                                        <li class="flex items-start gap-2">
                                            <svg class="w-3 h-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                            </svg>
                                            <span>{{ __('Review all details carefully') }}</span>
                                        </li>
                                        <li class="flex items-start gap-2">
                                            <svg class="w-3 h-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                            </svg>
                                            <span>{{ __('Upload new logo if needed') }}</span>
                                        </li>
                                        <li class="flex items-start gap-2">
                                            <svg class="w-3 h-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                            </svg>
                                            <span>{{ __('Verify account information') }}</span>
                                        </li>
                                        <li class="flex items-start gap-2">
                                            <svg class="w-3 h-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                            </svg>
                                            <span>{{ __('Check active status') }}</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </form>
        </div>
    </div>

    <style>
        /* Smooth transitions */
        * {
            transition-property: background-color, border-color, color, fill, stroke;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
            transition-duration: 150ms;
        }
    </style>

    <script>
        function previewLogo(event) {
            const preview = document.getElementById('logo-preview');
            const file = event.target.files[0];
            
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.innerHTML = `<img src="${e.target.result}" alt="Logo Preview" class="w-full h-full object-contain p-1">`;
                }
                reader.readAsDataURL(file);
            }
        }
    </script>
</x-app-layout>