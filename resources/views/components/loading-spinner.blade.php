<div x-data="{ loading: false }"
    @loading-start.window="loading = true"
    @loading-stop.window="loading = false"
    x-show="loading"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 bg-white/75 dark:bg-gray-900/75 flex items-center justify-center z-[9999]"
    style="display: none;">
    <div class="animate-spin rounded-full h-16 w-16 border-t-4 border-b-4 border-orange-500"></div>
</div>