<!-- resources/views/profile/partials/update-password-form.blade.php -->
<section class="bg-white/70 dark:bg-slate-800/70 backdrop-blur-md border border-white/20 dark:border-slate-700/30 rounded-2xl shadow-xl p-8 w-full group transition-all duration-300 hover:shadow-2xl">
    <header class="mb-8 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-blue-100 dark:bg-blue-900/40 text-blue-600 flex items-center justify-center shadow-inner">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
        </div>
        <div>
            <h2 class="text-xl font-bold text-slate-900 dark:text-slate-100 tracking-tight">
                {{ __('Update Password') }}
            </h2>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                {{ __('Ensure your account is using a long, random password to stay secure.') }}
            </p>
        </div>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="space-y-6">
        @csrf
        @method('put')

        <div class="space-y-6">
            <div class="w-full">
                <label for="update_password_current_password" class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-2">{{ __('Current Password') }}</label>
                <div class="relative group/input">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-slate-400 group-focus-within/input:text-orange-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                    </div>
                    <input id="update_password_current_password" 
                           name="current_password" 
                           type="password" 
                           class="block w-full pl-11 rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-900/50 dark:text-white shadow-sm focus:border-[#EF7722] focus:ring-[#EF7722] text-sm py-3 transition-all" 
                           autocomplete="current-password" />
                </div>
                @error('current_password', 'updatePassword')
                    <p class="mt-2 text-xs text-red-600 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="w-full">
                    <label for="update_password_password" class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-2">{{ __('New Password') }}</label>
                    <div class="relative group/input">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-slate-400 group-focus-within/input:text-orange-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                        </div>
                        <input id="update_password_password" 
                               name="password" 
                               type="password" 
                               class="block w-full pl-11 rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-900/50 dark:text-white shadow-sm focus:border-[#EF7722] focus:ring-[#EF7722] text-sm py-3 transition-all" 
                               autocomplete="new-password" />
                    </div>
                    @error('password', 'updatePassword')
                        <p class="mt-2 text-xs text-red-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div class="w-full">
                    <label for="update_password_password_confirmation" class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-2">{{ __('Confirm Password') }}</label>
                    <div class="relative group/input">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-slate-400 group-focus-within/input:text-orange-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                        </div>
                        <input id="update_password_password_confirmation" 
                               name="password_confirmation" 
                               type="password" 
                               class="block w-full pl-11 rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-900/50 dark:text-white shadow-sm focus:border-[#EF7722] focus:ring-[#EF7722] text-sm py-3 transition-all" 
                               autocomplete="new-password" />
                    </div>
                    @error('password_confirmation', 'updatePassword')
                        <p class="mt-2 text-xs text-red-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <div class="flex items-center gap-4 w-full pt-4">
            <button class="bg-gradient-to-r from-blue-600 to-indigo-600 hover:shadow-lg hover:shadow-blue-500/30 text-white px-8 py-3 rounded-xl font-bold text-sm focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2 transition-all transform hover:-translate-y-0.5 active:scale-95">
                {{ __('Update Security') }}
            </button>

            @if (session('status') === 'password-updated')
                <div x-data="{ show: true }" 
                     x-show="show" 
                     x-transition 
                     x-init="setTimeout(() => show = false, 3000)" 
                     class="flex items-center gap-2 text-emerald-600 font-bold text-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" /></svg>
                    {{ __('Password updated.') }}
                </div>
            @endif
        </div>
    </form>
</section>