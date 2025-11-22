<!-- resources/views/profile.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <div class="w-full px-4 sm:px-6 lg:px-8">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 bg-orange-600 rounded-lg flex items-center justify-center">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-3xl font-bold text-gray-900 dark:text-white">
                        {{ __('My Profile') }}
                    </h2>
                    <p class="mt-2 text-base text-gray-600 dark:text-gray-400">{{ __('Manage your personal and security settings') }}</p>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50 dark:bg-gray-900 min-h-screen w-full">
        <div class="w-full px-4 sm:px-6 lg:px-8 space-y-8">
            {{-- Update Profile Information --}}
            <div class="w-full">
                @include('profile.partials.update-profile-information-form')
            </div>

            {{-- Update Password --}}
            <div class="w-full">
                @include('profile.partials.update-password-form')
            </div>

            {{-- Delete Account --}}
            <div class="w-full">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</x-app-layout>