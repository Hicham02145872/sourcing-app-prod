{{-- components/layout/notification-loading.blade.php --}}
<div x-show="loading" class="p-8">
    <div class="flex flex-col items-center justify-center gap-4">
        <div class="relative">
            <div class="w-12 h-12 border-4 border-[#EF7722]/20 dark:border-[#EF7722]/30 rounded-full"></div>
            <div class="absolute top-0 left-0 w-12 h-12 border-4 border-[#EF7722] dark:border-[#FAA533] rounded-full border-t-transparent animate-spin"></div>
        </div>
        <div class="text-center">
            <p class="text-sm font-semibold text-slate-900 dark:text-white mb-1">{{ __('Loading notifications') }}</p>
            <p class="text-xs text-slate-600 dark:text-slate-400">{{ __('Please wait while we fetch your updates') }}</p>
        </div>
    </div>
</div>