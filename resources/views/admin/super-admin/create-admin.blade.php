<x-app-layout>
    <!-- Main Container: Enterprise Slate Background -->
    <div class="min-h-screen bg-slate-50/80 font-sans text-slate-900 pb-12">
        
        <!-- Top Navigation / Breadcrumb Area (Sticky) -->
        <div class="bg-white border-b border-slate-200 shadow-sm sticky top-0 z-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between h-auto md:h-16 py-4 md:py-0 gap-4">
                    <div class="flex items-center gap-2">
                        <!-- Branding Icon -->
                        <span class="inline-flex items-center justify-center h-8 w-8 rounded bg-orange-100 text-orange-600">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="8.5" cy="7" r="4"/><line x1="20" y1="8" x2="20" y2="14"/><line x1="23" y1="11" x2="17" y2="11"/></svg>
                        </span>
                        <div>
                            <h1 class="text-lg font-bold text-slate-900 leading-tight">{{ __('Create Admin') }}</h1>
                            <nav class="flex text-xs text-slate-500" aria-label="Breadcrumb">
                                <a href="{{ route('admin.super-admin.list-admins') }}" class="hover:text-slate-700 transition-colors">{{ __('Administrators') }}</a>
                                <span class="mx-1.5">/</span>
                                <span class="font-medium text-slate-700">{{ __('New Account') }}</span>
                            </nav>
                        </div>
                    </div>
                    
                    <!-- Back Action -->
                    <div>
                        <a href="{{ route('admin.super-admin.list-admins') }}" class="inline-flex items-center gap-2 px-3 py-1.5 bg-white border border-slate-300 text-slate-700 hover:bg-slate-50 text-xs font-medium rounded transition-colors shadow-sm">
                            <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                            {{ __('Back to List') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                <!-- LEFT COLUMN: Form (2/3) -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden">
                        <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                            <h3 class="text-sm font-semibold text-slate-900">{{ __('Account Details') }}</h3>
                            <p class="text-xs text-slate-500 mt-0.5">{{ __('Enter the personal information for the new administrator.') }}</p>
                        </div>
                        
                        <div class="p-6">
                            <form method="POST" action="{{ route('admin.super-admin.store-admin') }}" class="space-y-6">
                                @csrf

                                <!-- Identity Section -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    {{-- NAME --}}
                                    <div class="col-span-1">
                                        <label for="name" class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1">
                                            {{ __('Full Name') }} <span class="text-red-500">*</span>
                                        </label>
                                        <input 
                                            id="name" 
                                            type="text" 
                                            name="name" 
                                            value="{{ old('name') }}"
                                            required 
                                            autofocus 
                                            autocomplete="name"
                                            class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-md text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition-all"
                                            placeholder="{{ __('Ex: John Doe') }}"
                                        />
                                        @error('name')
                                            <p class="mt-1 text-xs text-red-600 flex items-center gap-1">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                {{ $message }}
                                            </p>
                                        @enderror
                                    </div>

                                    {{-- EMAIL --}}
                                    <div class="col-span-1">
                                        <label for="email" class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1">
                                            {{ __('Email Address') }} <span class="text-red-500">*</span>
                                        </label>
                                        <input 
                                            id="email" 
                                            type="email" 
                                            name="email" 
                                            value="{{ old('email') }}"
                                            required 
                                            autocomplete="username"
                                            class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-md text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition-all"
                                            placeholder="{{ __('admin@company.com') }}"
                                        />
                                        @error('email')
                                            <p class="mt-1 text-xs text-red-600 flex items-center gap-1">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                {{ $message }}
                                            </p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="border-t border-slate-100 my-4"></div>

                                <!-- Security Section -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    {{-- PASSWORD --}}
                                    <div class="col-span-1">
                                        <label for="password" class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1">
                                            {{ __('Password') }} <span class="text-red-500">*</span>
                                        </label>
                                        <div class="relative">
                                            <input 
                                                id="password" 
                                                type="password" 
                                                name="password" 
                                                required 
                                                autocomplete="new-password"
                                                class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-md text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition-all pr-10"
                                                placeholder="••••••••"
                                            />
                                            <button type="button" onclick="togglePassword('password')" class="absolute right-3 top-2.5 text-slate-400 hover:text-slate-600 transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                            </button>
                                        </div>
                                        @error('password')
                                            <p class="mt-1 text-xs text-red-600 flex items-center gap-1">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                {{ $message }}
                                            </p>
                                        @enderror
                                    </div>

                                    {{-- CONFIRM PASSWORD --}}
                                    <div class="col-span-1">
                                        <label for="password_confirmation" class="block text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-1">
                                            {{ __('Confirm Password') }} <span class="text-red-500">*</span>
                                        </label>
                                        <div class="relative">
                                            <input 
                                                id="password_confirmation" 
                                                type="password" 
                                                name="password_confirmation" 
                                                required 
                                                autocomplete="new-password"
                                                class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-md text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition-all pr-10"
                                                placeholder="••••••••"
                                            />
                                            <button type="button" onclick="togglePassword('password_confirmation')" class="absolute right-3 top-2.5 text-slate-400 hover:text-slate-600 transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div class="border-t border-slate-100 my-4"></div>

                                {{-- PERMISSIONS --}}
                                <div class="space-y-4">
                                    <h4 class="text-xs font-bold text-slate-700 underline uppercase tracking-wider">{{ __('Permissions Settings') }}</h4>
                                    
                                    <div class="flex items-start">
                                        <div class="flex items-center h-5">
                                            <input id="can_delete_clients" name="can_delete_clients" type="checkbox" class="focus:ring-orange-500 h-4 w-4 text-orange-600 border-slate-300 rounded shadow-sm">
                                        </div>
                                        <div class="ml-3 text-sm">
                                            <label for="can_delete_clients" class="font-bold text-red-600 flex items-center gap-2">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                {{ __('Permission: Delete Clients') }}
                                            </label>
                                            <p class="text-slate-500 text-xs">{{ __('Allow this administrator to permanently delete client accounts.') }}</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Actions -->
                                <div class="pt-6 mt-6 border-t border-slate-100 flex items-center justify-end gap-3">
                                    <a href="{{ route('admin.super-admin.list-admins') }}" class="px-4 py-2 text-xs font-semibold text-slate-600 bg-white border border-slate-300 rounded hover:bg-slate-50 transition-colors">
                                        {{ __('Cancel') }}
                                    </a>
                                    <button type="submit" class="px-6 py-2 bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold uppercase tracking-wider rounded transition-colors shadow-sm flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        {{ __('Create Account') }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- RIGHT COLUMN: Info (1/3) -->
                <div class="lg:col-span-1 space-y-6">
                    <!-- Security Info -->
                    <div class="bg-white rounded-lg border border-slate-200 shadow-sm p-5">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="p-2 bg-orange-50 text-orange-600 rounded">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            </div>
                            <h4 class="font-semibold text-slate-900 text-sm">{{ __('Security & Access') }}</h4>
                        </div>
                        <ul class="space-y-3 text-xs text-slate-600">
                            <li class="flex gap-2">
                                <svg class="w-4 h-4 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span>{{ __('Use a strong password (min. 8 characters, uppercase, numbers).') }}</span>
                            </li>
                            <li class="flex gap-2">
                                <svg class="w-4 h-4 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span>{{ __('The email will be used for account recovery.') }}</span>
                            </li>
                            <li class="flex gap-2">
                                <svg class="w-4 h-4 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span>{{ __('This account will have "Standard Admin" access by default.') }}</span>
                            </li>
                        </ul>
                    </div>

                    <!-- Role Info -->
                    <div class="bg-blue-50/50 rounded-lg border border-blue-100 p-5">
                        <h4 class="font-semibold text-blue-900 text-sm mb-2">{{ __('Note on roles') }}</h4>
                        <p class="text-xs text-blue-800 leading-relaxed">
                            {{ __('Administrators can manage orders, products, and users. Only Super Admins can delete other administrators.') }}
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const button = field.nextElementSibling;
            
            if (field.type === 'password') {
                field.type = 'text';
                // Eye Off Icon
                button.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>';
            } else {
                field.type = 'password';
                // Eye Icon
                button.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>';
            }
        }
    </script>
</x-app-layout>