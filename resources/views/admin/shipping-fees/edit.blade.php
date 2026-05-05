<x-app-layout>
    <div class="min-h-screen bg-slate-50/80 font-sans text-slate-900 pb-12">
        
        <!-- Top Navigation / Breadcrumb Area (Sticky) -->
        <div class="bg-white border-b border-slate-200 shadow-sm sticky top-0 z-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between h-auto md:h-16 py-4 md:py-0 gap-4">
                    <div class="flex items-center gap-3">
                        <a href="{{ route('admin.shipping-fees.index') }}" class="p-2 text-slate-400 hover:text-orange-600 hover:bg-orange-50 rounded-lg transition-all" title="{{ __('Back to List') }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                        </a>
                        <div class="h-8 w-px bg-slate-200"></div>
                        <div class="flex items-center gap-2">
                            <span class="inline-flex items-center justify-center h-8 w-8 rounded bg-blue-100 text-blue-600 shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                            </span>
                            <div>
                                <h1 class="text-lg font-bold text-slate-900 leading-tight">{{ __('Configure Rates') }}: <span class="text-orange-600">{{ $country->name }}</span></h1>
                                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">{{ $country->code }} • {{ __('Logistics Hub') }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-3">
                        <div class="hidden md:flex flex-col items-end mr-2">
                            <span class="text-xs font-bold text-slate-700">{{ now()->format('l, d M Y') }}</span>
                            <span class="text-[10px] text-slate-400 uppercase tracking-wide">{{ __('Regional Pricing Engine') }}</span>
                        </div>
                        <div class="h-8 w-px bg-slate-200 hidden md:block"></div>
                        <div class="flex items-center gap-1.5 px-3 py-1 bg-green-50 text-green-700 rounded-full border border-green-100">
                            <div class="w-1.5 h-1.5 bg-green-500 rounded-full animate-pulse"></div>
                            <span class="text-[10px] font-bold uppercase tracking-wider">{{ __('Live Data') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <livewire:admin.shipping-fee-edit :country="$country" />
        </div>
    </div>
</x-app-layout>
