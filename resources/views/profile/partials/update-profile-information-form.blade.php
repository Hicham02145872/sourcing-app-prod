<!-- resources/views/profile/partials/update-profile-information-form.blade.php -->
<section class="bg-white/70 dark:bg-slate-800/70 backdrop-blur-md border border-white/20 dark:border-slate-700/30 rounded-2xl shadow-xl p-8 w-full group transition-all duration-300 hover:shadow-2xl">
    <header class="mb-8 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-orange-100 dark:bg-orange-900/40 text-orange-600 flex items-center justify-center shadow-inner">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
        </div>
        <div>
            <h2 class="text-xl font-bold text-slate-900 dark:text-slate-100 tracking-tight">
                {{ __('Profile Information') }}
            </h2>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                {{ __("Update your account's profile information and email address.") }}
            </p>
        </div>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="space-y-6" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <!-- Profile Photo -->
        <div class="flex items-center gap-6 mb-6">
            <div class="shrink-0 relative group">
                @if($user->profile_photo_path)
                    <img class="h-20 w-20 object-cover rounded-full border-2 border-slate-200 dark:border-slate-700 shadow-sm" 
                         src="{{ media_url($user->profile_photo_path) }}" 
                         alt="{{ $user->name }}" />
                @else
                    <div class="h-20 w-20 rounded-full bg-orange-100 dark:bg-orange-900/50 flex items-center justify-center text-2xl font-bold text-orange-600 border-2 border-orange-200 dark:border-orange-800 shadow-sm">
                        {{ substr($user->name, 0, 1) }}
                    </div>
                @endif
                <div class="absolute inset-0 bg-black/40 rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer" onclick="document.getElementById('photo').click()">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                </div>
            </div>
            
            <div class="flex-1">
                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">
                    {{ __('Profile Photo') }}
                </label>
                <div class="text-xs text-slate-500 dark:text-slate-400 mb-3">
                    {{ __('Update your profile picture. Max size 5MB.') }}
                </div>
                <input class="block w-full text-sm text-slate-500
                    file:mr-4 file:py-2 file:px-4
                    file:rounded-full file:border-0
                    file:text-xs file:font-semibold
                    file:bg-orange-50 file:text-orange-700
                    hover:file:bg-orange-100
                    cursor-pointer" 
                    id="photo" name="photo" type="file" accept="image/*">
                @error('photo')
                    <p class="mt-2 text-xs text-red-600 font-medium">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="w-full">
                <label for="name" class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-2">{{ __('Full Name') }}</label>
                <div class="relative group/input">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-slate-400 group-focus-within/input:text-orange-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                    </div>
                    <input id="name" 
                           name="name" 
                           type="text" 
                           class="block w-full pl-11 rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-900/50 dark:text-white shadow-sm focus:border-[#EF7722] focus:ring-[#EF7722] text-sm py-3 transition-all" 
                           value="{{ old('name', $user->name) }}" 
                           required 
                           autofocus 
                           autocomplete="name" />
                </div>
                @error('name')
                    <p class="mt-2 text-xs text-red-600 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <div class="w-full">
                <label for="email" class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-2">{{ __('Email Address') }}</label>
                <div class="relative group/input">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-slate-400 group-focus-within/input:text-orange-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                    </div>
                    <input id="email" 
                           name="email" 
                           type="email" 
                           class="block w-full pl-11 rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-900/50 dark:text-white shadow-sm focus:border-[#EF7722] focus:ring-[#EF7722] text-sm py-3 transition-all" 
                           value="{{ old('email', $user->email) }}" 
                           required 
                           autocomplete="username" />
                </div>
                @error('email')
                    <p class="mt-2 text-xs text-red-600 font-medium">{{ $message }}</p>
                @enderror

                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                    <div class="mt-4 p-4 bg-orange-50 dark:bg-orange-900/20 border border-orange-200 dark:border-orange-800 rounded-xl w-full">
                        <p class="text-sm text-slate-800 dark:text-slate-200 flex items-center gap-2">
                            <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                            {{ __('Your email address is unverified.') }}
                            <button form="send-verification" class="text-[#EF7722] hover:text-[#FAA533] hover:underline font-bold transition-colors">
                                {{ __('Resend Verification email.') }}
                            </button>
                        </p>

                        @if (session('status') === 'verification-link-sent')
                            <p class="mt-3 text-sm text-green-600 dark:text-green-400 font-bold">
                                {{ __('A new verification link has been sent to your email address.') }}
                            </p>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        <div class="flex items-center gap-4 w-full pt-4">
            <button class="bg-gradient-to-r from-[#EF7722] to-[#FAA533] hover:shadow-lg hover:shadow-orange-500/30 text-white px-8 py-3 rounded-xl font-bold text-sm focus:outline-none focus:ring-2 focus:ring-[#EF7722] focus:ring-offset-2 transition-all transform hover:-translate-y-0.5 active:scale-95">
                {{ __('Save Changes') }}
            </button>

            @if (session('status') === 'profile-updated')
                <div x-data="{ show: true }" 
                     x-show="show" 
                     x-transition 
                     x-init="setTimeout(() => show = false, 3000)" 
                     class="flex items-center gap-2 text-emerald-600 font-bold text-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" /></svg>
                    {{ __('Saved successfully.') }}
                </div>
            @endif
        </div>
    </form>
</section>