<x-app-layout>
    <!-- Main Container: Enterprise Slate Background -->
    <div class="min-h-screen bg-slate-50/80 font-sans text-slate-900 pb-12">
        
        <!-- Top Navigation / Breadcrumb Area (Sticky) -->
        <div class="bg-white border-b border-slate-200 shadow-sm sticky top-0 z-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between h-auto md:h-16 py-4 md:py-0 gap-4">
                    <div class="flex items-center gap-2">
                        <!-- Branding Icon -->
                        <span class="inline-flex items-center justify-center h-8 w-8 rounded bg-orange-100 text-orange-600">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.24 12.24a6 6 0 0 0-8.49-8.49L5 10.5V19h8.5z"/><line x1="16" y1="8" x2="2" y2="22"/><line x1="17.5" y1="15" x2="9" y2="15"/></svg>
                        </span>
                        <div>
                            <h1 class="text-lg font-bold text-slate-900 leading-tight">{{ __('New Sourcing Request') }}</h1>
                            <nav class="flex text-[10px] text-slate-400 uppercase tracking-widest font-bold" aria-label="Breadcrumb">
                                <a href="{{ route('admin.dashboard') }}" class="hover:text-slate-700 transition-colors uppercase">{{ __('Dashboard') }}</a>
                                <span class="mx-1.5 text-slate-300">/</span>
                                <a href="{{ route('admin.sourcing-requests.index') }}" class="hover:text-slate-700 transition-colors uppercase">{{ __('Sourcing') }}</a>
                                <span class="mx-1.5 text-slate-300">/</span>
                                <span class="text-orange-600 uppercase">{{ __('Creation') }}</span>
                            </nav>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center gap-3">
                        <a href="{{ route('admin.sourcing-requests.index') }}" class="px-4 py-2 border border-slate-200 rounded text-slate-500 hover:bg-slate-50 text-xs font-bold transition-colors">
                            {{ __('Cancel') }}
                        </a>
                        <button type="button" onclick="window.dispatchEvent(new CustomEvent('trigger-save'))" class="px-5 py-2 bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold rounded transition-colors shadow-sm">
                            {{ __('Finalize & Create') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Livewire Component -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <livewire:admin.sourcing-request-create />
        </div>
    </div>

    @push('styles')
    <style>
        /* Smooth interactions for enterprise feel */
        input:focus, select:focus, textarea:focus {
            box-shadow: 0 0 0 4px rgba(15, 23, 42, 0.1) !important;
        }
        
        /* Custom scrollbar for destinations */
        .overflow-y-auto::-webkit-scrollbar {
            width: 5px;
        }
        .overflow-y-auto::-webkit-scrollbar-track {
            background: transparent;
        }
        .overflow-y-auto::-webkit-scrollbar-thumb {
            background: #e2e8f0;
            border-radius: 10px;
        }
        .overflow-y-auto::-webkit-scrollbar-thumb:hover {
            background: #cbd5e1;
        }
    </style>
    @endpush
</x-app-layout>
