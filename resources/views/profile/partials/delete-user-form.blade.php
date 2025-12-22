<!-- resources/views/profile/partials/delete-user-form.blade.php -->
<section class="group w-full">
    <header class="mb-6 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-red-50 dark:bg-red-900/40 text-red-600 flex items-center justify-center shadow-inner">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
        </div>
        <div>
            <h2 class="text-lg font-bold text-slate-900 dark:text-white tracking-tight">
                {{ __('Danger Zone') }}
            </h2>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                {{ __('Permanently delete your account and all associated data.') }}
            </p>
        </div>
    </header>

    <div class="p-6 bg-red-50/50 dark:bg-red-900/10 border border-red-100 dark:border-red-900/30 rounded-2xl flex flex-col md:flex-row md:items-center justify-between gap-6 transition-all duration-300 hover:bg-red-50 dark:hover:bg-red-900/20">
        <div class="flex-1">
            <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed italic">
                {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. This action cannot be undone.') }}
            </p>
        </div>
        
        <button
            x-data=""
            x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
            class="bg-red-600 hover:bg-red-700 text-white px-8 py-3 rounded-xl font-bold text-sm shadow-lg shadow-red-500/20 transition-all transform hover:-translate-y-0.5 active:scale-95 flex-shrink-0"
        >
            {{ __('Delete Account') }}
        </button>
    </div>

    <!-- Modal -->
    <div x-data="{ open: {{ $errors->userDeletion->isNotEmpty() ? 'true' : 'false' }} }" x-show="open" 
         x-on:open-modal.window="if ($event.detail === 'confirm-user-deletion') open = true"
         x-on:keydown.escape.window="open = false"
         class="fixed inset-0 z-[60] overflow-y-auto" style="display: none;">
        <div class="flex items-center justify-center min-h-full p-4 text-center">
            <div x-on:click="open = false" class="fixed inset-0 bg-slate-950/60 transition-opacity backdrop-blur-md"></div>
            
            <div x-show="open" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 class="relative bg-white dark:bg-slate-900 rounded-3xl p-8 w-full max-w-lg shadow-2xl border border-slate-200 dark:border-slate-800 text-left">
                
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-14 h-14 rounded-2xl bg-red-100 dark:bg-red-900/40 text-red-600 flex items-center justify-center">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                    </div>
                    <div>
                        <h2 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight">
                            {{ __('Final Warning') }}
                        </h2>
                        <p class="text-sm text-slate-500 font-medium">Cette action est irréversible.</p>
                    </div>
                </div>

                <p class="text-base text-slate-600 dark:text-slate-400 leading-relaxed">
                    {{ __('Please enter your password to confirm you would like to permanently delete your account.') }}
                </p>

                <form method="post" action="{{ route('profile.destroy') }}" class="mt-8">
                    @csrf
                    @method('delete')

                    <div class="w-full">
                        <label for="password" class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-2">{{ __('Current Password') }}</label>
                        <input
                            id="password"
                            name="password"
                            type="password"
                            class="block w-full rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-800 dark:text-white shadow-sm focus:border-red-500 focus:ring-red-500 py-3 px-4 transition-all"
                            placeholder="{{ __('Confirm with password') }}"
                        />
                        @error('password', 'userDeletion')
                            <p class="mt-2 text-xs text-red-600 font-bold">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mt-10 flex justify-end gap-4">
                        <button 
                            type="button" 
                            x-on:click="open = false"
                            class="px-6 py-3 text-sm font-bold text-slate-600 dark:text-slate-400 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 rounded-xl transition-all"
                        >
                            {{ __('Abort deletion') }}
                        </button>

                        <button 
                            type="submit"
                            class="px-8 py-3 text-sm font-black text-white bg-red-600 hover:bg-red-700 rounded-xl shadow-lg shadow-red-500/20 transition-all transform hover:-translate-y-0.5 active:scale-95"
                        >
                            {{ __('Yes, Delete Account') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>