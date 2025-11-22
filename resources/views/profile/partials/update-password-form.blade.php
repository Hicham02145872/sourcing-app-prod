<!-- resources/views/profile/partials/update-password-form.blade.php -->
<section class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-orange-200 dark:border-orange-800 p-8 w-full">
    <header class="mb-6">
        <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">
            {{ __('Update Password') }}
        </h2>

        <p class="mt-2 text-base text-gray-600 dark:text-gray-400">
            {{ __('Ensure your account is using a long, random password to stay secure.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="space-y-8">
        @csrf
        @method('put')

        <div class="w-full">
            <label for="update_password_current_password" class="block text-base font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('Current Password') }}</label>
            <input id="update_password_current_password" 
                   name="current_password" 
                   type="password" 
                   class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-orange-500 focus:ring-orange-500 text-base p-3" 
                   autocomplete="current-password" />
            @error('current_password', 'updatePassword')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="w-full">
            <label for="update_password_password" class="block text-base font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('New Password') }}</label>
            <input id="update_password_password" 
                   name="password" 
                   type="password" 
                   class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-orange-500 focus:ring-orange-500 text-base p-3" 
                   autocomplete="new-password" />
            @error('password', 'updatePassword')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="w-full">
            <label for="update_password_password_confirmation" class="block text-base font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('Confirm Password') }}</label>
            <input id="update_password_password_confirmation" 
                   name="password_confirmation" 
                   type="password" 
                   class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-orange-500 focus:ring-orange-500 text-base p-3" 
                   autocomplete="new-password" />
            @error('password_confirmation', 'updatePassword')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center gap-4 w-full pt-4">
            <button class="bg-orange-600 hover:bg-orange-700 text-white px-6 py-3 rounded-md font-medium text-base focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 transition-colors">
                {{ __('Save') }}
            </button>

            @if (session('status') === 'password-updated')
                <p class="text-base text-green-600 dark:text-green-400 font-medium">
                    {{ __('Saved.') }}
                </p>
            @endif
        </div>
    </form>
</section>