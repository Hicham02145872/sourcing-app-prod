<x-app-layout>
    <div class="min-h-screen bg-slate-50/80 pb-12 font-sans text-slate-900 dark:bg-slate-900 dark:text-slate-100">
        <div class="border-b border-slate-200 bg-white shadow-sm dark:border-slate-700 dark:bg-slate-800">
            <div class="mx-auto flex max-w-7xl flex-col gap-4 px-4 py-6 sm:px-6 lg:px-8">
                <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h1 class="text-xl font-bold tracking-tight">{{ __('Admin email notifications') }}</h1>
                        <p class="text-sm text-slate-500 dark:text-slate-400">{{ __('Choose which system emails each administrator receives.') }}</p>
                    </div>
                    <a href="{{ route('admin.super-admin.list-admins') }}" class="text-sm font-semibold text-[#EF7722] hover:underline">
                        {{ __('← Back to admins') }}
                    </a>
                </div>
            </div>
        </div>

        <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
            @livewire(\App\Livewire\Admin\AdminMailNotificationPreferences::class)
        </div>
    </div>
</x-app-layout>
