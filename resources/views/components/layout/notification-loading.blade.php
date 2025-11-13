{{-- Loading State --}}
<div x-show="loading" class="p-8">
    <div class="flex flex-col items-center justify-center gap-4">
        <div class="relative">
            <div class="w-12 h-12 border-4 border-blue-200 dark:border-blue-800 rounded-full"></div>
            <div class="absolute top-0 left-0 w-12 h-12 border-4 border-blue-600 dark:border-blue-500 rounded-full border-t-transparent animate-spin"></div>
        </div>
        <p class="text-sm text-blue-600 dark:text-blue-400 font-medium">{{ __('Loading notifications...') }}</p>
    </div>
</div>