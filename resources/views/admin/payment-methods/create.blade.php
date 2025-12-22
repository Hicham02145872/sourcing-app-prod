<x-app-layout>
    <!-- Main Container: Enterprise Slate Background -->
    <div class="min-h-screen bg-slate-50/80 font-sans text-slate-900 pb-12">
        
        <!-- Top Navigation / Breadcrumb Area (Sticky) -->
        <div class="bg-white border-b border-slate-200 shadow-sm sticky top-0 z-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between h-auto md:h-16 py-4 md:py-0 gap-4">
                    <div class="flex items-center gap-2">
                        <!-- Back Button -->
                        <a href="{{ route('admin.payment-methods.index') }}" class="group inline-flex items-center justify-center h-8 w-8 rounded-full bg-slate-50 border border-slate-200 text-slate-500 hover:text-orange-600 hover:border-orange-200 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="group-hover:-translate-x-0.5 transition-transform"><path d="m15 18-6-6 6-6"/></svg>
                        </a>
                        
                        <div class="h-6 w-px bg-slate-200 mx-1"></div>

                        <div>
                            <h1 class="text-lg font-bold text-slate-900 leading-tight flex items-center gap-2">
                                {{ __('Create Payment Method') }}
                            </h1>
                            <nav class="flex text-xs text-slate-500" aria-label="Breadcrumb">
                                <a href="{{ route('admin.dashboard') }}" class="hover:text-slate-700 transition-colors">{{ __('Dashboard') }}</a>
                                <span class="mx-1.5">/</span>
                                <a href="{{ route('admin.payment-methods.index') }}" class="hover:text-slate-700 transition-colors">{{ __('Payment Methods') }}</a>
                                <span class="mx-1.5">/</span>
                                <span class="font-medium text-slate-700">{{ __('Create') }}</span>
                            </nav>
                        </div>
                    </div>
                    
                    <!-- Global Actions -->
                    <div class="flex items-center gap-2">
                        <!-- Add any global actions here if needed, or leave empty to maintain layout -->
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <form method="POST" action="{{ route('admin.payment-methods.store') }}" enctype="multipart/form-data">
                @csrf

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    
                    {{-- LEFT COLUMN --}}
                    <div class="lg:col-span-2 space-y-6">
                        
                        <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-slate-200 dark:border-slate-700">
                            <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-[#EF7722]/10 rounded-lg flex items-center justify-center">
                                        <svg class="w-5 h-5 text-[#EF7722]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-base font-bold text-slate-900 dark:text-white">{{ __('Basic Information') }}</h3>
                                        <p class="text-xs text-slate-600 dark:text-slate-400 mt-0.5">{{ __('Enter the payment method name and logo') }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="p-6 space-y-5">
                                {{-- NAME --}}
                                <div>
                                    <label for="name" class="block text-sm font-bold text-slate-900 dark:text-white mb-2">
                                        {{ __('Payment Method Name') }}
                                        <span class="text-red-600">*</span>
                                    </label>
                                    <input id="name" 
                                           type="text" 
                                           name="name" 
                                           value="{{ old('name') }}" 
                                           required 
                                           autofocus
                                           placeholder="{{ __('e.g., CIH Bank, Wise Transfer, PayPal') }}"
                                           class="block w-full px-4 py-2.5 border border-slate-300 dark:border-slate-700 rounded-lg focus:ring-2 focus:ring-[#EF7722] focus:border-transparent bg-white dark:bg-slate-700 shadow-sm text-slate-900 dark:text-white">
                                    @error('name')
                                        <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                            </svg>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>

                                {{-- LOGO UPLOAD --}}
                                <div>
                                    <label for="logo" class="block text-sm font-bold text-slate-900 dark:text-white mb-2">
                                        {{ __('Payment Method Logo') }}
                                    </label>
                                    <div class="flex items-start gap-4">
                                        <div class="flex-shrink-0">
                                            <div id="logo-preview" class="w-20 h-20 bg-slate-100 dark:bg-slate-900/50 rounded-lg border-2 border-dashed border-slate-300 dark:border-slate-700 flex items-center justify-center overflow-hidden transition-all hover:border-[#EF7722]">
                                                <svg class="w-8 h-8 text-slate-400 dark:text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                                                   class="block w-full text-sm text-slate-600 dark:text-slate-400 file:mr-4 file:py-2.5 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-[#EF7722]/10 file:text-[#EF7722] hover:file:bg-[#EF7722]/20 cursor-pointer transition-all">
                                            <p class="mt-2 text-xs text-slate-600 dark:text-slate-400 flex items-center gap-1">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                {{ __('PNG, JPG or SVG (Max. 2MB, Recommended: 200x200px)') }}
                                            </p>
                                        </div>
                                    </div>
                                    @error('logo')
                                        <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                            </svg>
                                            {{ $message }}
                                        </p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-slate-200 dark:border-slate-700">
                            <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-base font-bold text-slate-900 dark:text-white">{{ __('Account Details') }}</h3>
                                        <p class="text-xs text-slate-600 dark:text-slate-400 mt-0.5">{{ __('Fill in the payment account information') }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="p-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    {{-- ACCOUNT TYPE --}}
                                    <div>
                                        <label for="account_type" class="block text-sm font-semibold text-slate-900 dark:text-white mb-2">
                                            {{ __('Account Type') }}
                                        </label>
                                        <input id="account_type" 
                                               type="text" 
                                               name="account_type" 
                                               value="{{ old('account_type') }}"
                                               placeholder="{{ __('e.g., Checking, Business, Savings') }}"
                                               class="block w-full px-4 py-2.5 border border-slate-300 dark:border-slate-700 rounded-lg focus:ring-2 focus:ring-[#EF7722] focus:border-transparent bg-white dark:bg-slate-700 shadow-sm text-slate-900 dark:text-white">
                                    </div>

                                    {{-- ACCOUNT HOLDER --}}
                                    <div>
                                        <label for="account_holder" class="block text-sm font-semibold text-slate-900 dark:text-white mb-2">
                                            {{ __('Account Holder') }}
                                        </label>
                                        <input id="account_holder" 
                                               type="text" 
                                               name="account_holder" 
                                               value="{{ old('account_holder') }}"
                                               placeholder="{{ __('Full legal name') }}"
                                               class="block w-full px-4 py-2.5 border border-slate-300 dark:border-slate-700 rounded-lg focus:ring-2 focus:ring-[#EF7722] focus:border-transparent bg-white dark:bg-slate-700 shadow-sm text-slate-900 dark:text-white">
                                    </div>

                                    {{-- ACCOUNT NUMBER --}}
                                    <div>
                                        <label for="account_number" class="block text-sm font-semibold text-slate-900 dark:text-white mb-2">
                                            {{ __('Account Number') }}
                                        </label>
                                        <input id="account_number" 
                                               type="text" 
                                               name="account_number" 
                                               value="{{ old('account_number') }}"
                                               placeholder="{{ __('Enter account number') }}"
                                               class="block w-full px-4 py-2.5 border border-slate-300 dark:border-slate-700 rounded-lg focus:ring-2 focus:ring-[#EF7722] focus:border-transparent bg-white dark:bg-slate-700 shadow-sm text-slate-900 dark:text-white">
                                    </div>

                                    {{-- ROUTING NUMBER --}}
                                    <div>
                                        <label for="routing_number" class="block text-sm font-semibold text-slate-900 dark:text-white mb-2">
                                            {{ __('Routing Number') }}
                                        </label>
                                        <input id="routing_number" 
                                               type="text" 
                                               name="routing_number" 
                                               value="{{ old('routing_number') }}"
                                               placeholder="{{ __('Enter routing number') }}"
                                               class="block w-full px-4 py-2.5 border border-slate-300 dark:border-slate-700 rounded-lg focus:ring-2 focus:ring-[#EF7722] focus:border-transparent bg-white dark:bg-slate-700 shadow-sm text-slate-900 dark:text-white">
                                    </div>

                                    {{-- BANK NAME --}}
                                    <div>
                                        <label for="bank_name" class="block text-sm font-semibold text-slate-900 dark:text-white mb-2">
                                            {{ __('Bank Name') }}
                                        </label>
                                        <input id="bank_name" 
                                               type="text" 
                                               name="bank_name" 
                                               value="{{ old('bank_name') }}"
                                               placeholder="{{ __('e.g., CIH Bank, Bank of America') }}"
                                               class="block w-full px-4 py-2.5 border border-slate-300 dark:border-slate-700 rounded-lg focus:ring-2 focus:ring-[#EF7722] focus:border-transparent bg-white dark:bg-slate-700 shadow-sm text-slate-900 dark:text-white">
                                    </div>

                                    {{-- SWIFT CODE --}}
                                    <div>
                                        <label for="swift_code" class="block text-sm font-semibold text-slate-900 dark:text-white mb-2">
                                            {{ __('SWIFT/BIC Code') }}
                                        </label>
                                        <input id="swift_code" 
                                               type="text" 
                                               name="swift_code" 
                                               value="{{ old('swift_code') }}"
                                               placeholder="{{ __('e.g., CIHBMAMA') }}"
                                               class="block w-full px-4 py-2.5 border border-slate-300 dark:border-slate-700 rounded-lg focus:ring-2 focus:ring-[#EF7722] focus:border-transparent bg-white dark:bg-slate-700 shadow-sm text-slate-900 dark:text-white">
                                    </div>

                                    {{-- IBAN --}}
                                    <div>
                                        <label for="iban" class="block text-sm font-semibold text-slate-900 dark:text-white mb-2">
                                            {{ __('IBAN') }}
                                        </label>
                                        <input id="iban" 
                                               type="text" 
                                               name="iban" 
                                               value="{{ old('iban') }}"
                                               placeholder="{{ __('e.g., MA64011519000001205000534921') }}"
                                               class="block w-full px-4 py-2.5 border border-slate-300 dark:border-slate-700 rounded-lg focus:ring-2 focus:ring-[#EF7722] focus:border-transparent bg-white dark:bg-slate-700 shadow-sm text-slate-900 dark:text-white">
                                    </div>

                                    {{-- COUNTRY --}}
                                    <div>
                                        <label for="country" class="block text-sm font-semibold text-slate-900 dark:text-white mb-2">
                                            {{ __('Country') }}
                                        </label>
                                        <input id="country" 
                                               type="text" 
                                               name="country" 
                                               value="{{ old('country') }}"
                                               placeholder="{{ __('e.g., Morocco, United States') }}"
                                               class="block w-full px-4 py-2.5 border border-slate-300 dark:border-slate-700 rounded-lg focus:ring-2 focus:ring-[#EF7722] focus:border-transparent bg-white dark:bg-slate-700 shadow-sm text-slate-900 dark:text-white">
                                    </div>

                                    {{-- EMAIL --}}
                                    <div>
                                        <label for="email" class="block text-sm font-semibold text-slate-900 dark:text-white mb-2">
                                            {{ __('Email') }}
                                        </label>
                                        <input id="email" 
                                               type="email" 
                                               name="email" 
                                               value="{{ old('email') }}"
                                               placeholder="{{ __('account@example.com') }}"
                                               class="block w-full px-4 py-2.5 border border-slate-300 dark:border-slate-700 rounded-lg focus:ring-2 focus:ring-[#EF7722] focus:border-transparent bg-white dark:bg-slate-700 shadow-sm text-slate-900 dark:text-white">
                                    </div>

                                    {{-- ADDRESS --}}
                                    <div>
                                        <label for="address" class="block text-sm font-semibold text-slate-900 dark:text-white mb-2">
                                            {{ __('Address') }}
                                        </label>
                                        <input id="address" 
                                               type="text" 
                                               name="address" 
                                               value="{{ old('address') }}"
                                               placeholder="{{ __('Full address') }}"
                                               class="block w-full px-4 py-2.5 border border-slate-300 dark:border-slate-700 rounded-lg focus:ring-2 focus:ring-[#EF7722] focus:border-transparent bg-white dark:bg-slate-700 shadow-sm text-slate-900 dark:text-white">
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    {{-- RIGHT COLUMN - SIDEBAR --}}
                    <div class="lg:col-span-1 space-y-6">
                        
                        <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-slate-200 dark:border-slate-700">
                            <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center">
                                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                                                   checked
                                                   class="sr-only peer">
                                            <div class="w-11 h-6 bg-slate-200 dark:bg-slate-700 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-[#EF7722]/30 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#EF7722]"></div>
                                        </div>
                                        <span class="ml-3 text-sm font-semibold text-slate-900 dark:text-white group-hover:text-[#EF7722] transition-colors">{{ __('Active') }}</span>
                                    </label>
                                    <p class="mt-3 text-xs text-slate-600 dark:text-slate-400 flex items-start gap-1.5">
                                        <svg class="w-3.5 h-3.5 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <span>{{ __('Enable this payment method to make it available for clients to use') }}</span>
                                    </p>
                                </div>
                            </div>
                        </div>

                        {{-- ACTIONS CARD --}}
                        <div class="bg-white dark:bg-slate-800 rounded-lg shadow-sm border border-slate-200 dark:border-slate-700">
                            <div class="px-6 py-4 border-b border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-[#EF7722]/10 rounded-lg flex items-center justify-center">
                                        <svg class="w-5 h-5 text-[#EF7722]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                                <button type="submit" class="w-full py-3 px-4 bg-gradient-to-r from-[#EF7722] to-[#FAA533] hover:from-[#FAA533] hover:to-[#EF7722] text-white font-bold rounded-lg transition-all shadow-md hover:shadow-lg flex items-center justify-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    {{ __('Create Payment Method') }}
                                </button>

                                <a href="{{ route('admin.payment-methods.index') }}" class="w-full py-3 px-4 bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-300 font-semibold rounded-lg transition-all flex items-center justify-center gap-2 border border-slate-300 dark:border-slate-600">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                    {{ __('Cancel') }}
                                </a>
                            </div>
                        </div>

                        {{-- HELP CARD --}}
                        <div class="bg-[#EF7722]/5 dark:bg-[#EF7722]/10 rounded-lg border border-[#EF7722]/20 dark:border-[#EF7722]/30 p-5">
                            <div class="flex items-start gap-3">
                                <div class="flex-shrink-0 w-10 h-10 bg-[#EF7722]/10 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-[#EF7722]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h5 class="text-sm font-bold text-slate-900 dark:text-white mb-2">{{ __('Quick Tips') }}</h5>
                                    <ul class="text-xs text-slate-700 dark:text-slate-300 space-y-2">
                                        <li class="flex items-start gap-2">
                                            <svg class="w-3.5 h-3.5 flex-shrink-0 text-[#EF7722] mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                            <span>{{ __('Use clear, descriptive names') }}</span>
                                        </li>
                                        <li class="flex items-start gap-2">
                                            <svg class="w-3.5 h-3.5 flex-shrink-0 text-[#EF7722] mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                            <span>{{ __('Upload high-quality logos') }}</span>
                                        </li>
                                        <li class="flex items-start gap-2">
                                            <svg class="w-3.5 h-3.5 flex-shrink-0 text-[#EF7722] mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                            <span>{{ __('Verify account details') }}</span>
                                        </li>
                                        <li class="flex items-start gap-2">
                                            <svg class="w-3.5 h-3.5 flex-shrink-0 text-[#EF7722] mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                            <span>{{ __('Double-check SWIFT/IBAN codes') }}</span>
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