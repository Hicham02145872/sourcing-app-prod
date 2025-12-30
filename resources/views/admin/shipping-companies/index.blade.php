<x-app-layout>
    <!-- Main Container: Enterprise Slate Background -->
    <div class="min-h-screen bg-slate-50/80 font-sans text-slate-900 pb-12">
        
        <!-- Top Navigation / Breadcrumb Area (Sticky) -->
        <div class="bg-white border-b border-slate-200 shadow-sm sticky top-0 z-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between h-auto md:h-16 py-4 md:py-0 gap-4">
                    <div class="flex items-center gap-2">
                        <!-- Branding Icon -->
                        <span class="inline-flex items-center justify-center h-8 w-8 rounded bg-blue-100 text-blue-600">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2L2 7l10 5 10-5-10-5z"/><path d="M2 17l10 5 10-5"/><path d="M2 12l10 5 10-5"/></svg>
                        </span>
                        <div>
                            <h1 class="text-lg font-bold text-slate-900 leading-tight">{{ __('Shipping Companies') }}</h1>
                            <p class="text-xs text-slate-500 hidden sm:block">{{ __('Partners & Logistics Sync') }}</p>
                        </div>
                    </div>
                    
                    <!-- Top Actions -->
                    <div class="flex items-center gap-3">
                        <div class="hidden md:flex flex-col items-end mr-2">
                            <span class="text-xs font-bold text-slate-700">{{ now()->format('l, d M Y') }}</span>
                            <span class="text-[10px] text-slate-400 uppercase tracking-wide">{{ __('Casablanca (GMT+1)') }}</span>
                        </div>
                        <div class="h-8 w-px bg-slate-200 hidden md:block"></div>
                        
                        <button onclick="window.location.reload()" class="p-2 text-slate-400 hover:text-blue-600 transition-colors" title="{{ __('Refresh') }}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <livewire:admin.shipping-company-manager />
        </div>
    </div>
</x-app-layout>
