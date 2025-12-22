<x-app-layout>
    <!-- Main Container: Slate background for enterprise feel -->
    <div class="min-h-screen bg-slate-50/80 font-sans text-slate-900 pb-12 -mt-4">
        
        <!-- Top Navigation / Breadcrumb Area -->
        <div class="bg-white border-b border-slate-200 shadow-sm sticky top-0 z-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between h-auto md:h-16 py-4 md:py-0 gap-4">
                    <div class="flex items-center gap-2">
                        <span class="inline-flex items-center justify-center h-8 w-8 rounded bg-orange-100 text-orange-600">
                            <!-- Google Sheet Icon -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M19,3H5C3.9,3,3,3.9,3,5v14c0,1.1,0.9,2,2,2h14c1.1,0,2-0.9,2-2V5C21,3.9,20.1,3,19,3z M19,19H5V5h14V19z"/>
                                <rect x="7" y="7" width="10" height="2"/>
                                <rect x="7" y="11" width="10" height="2"/>
                                <rect x="7" y="15" width="10" height="2"/>
                            </svg>
                        </span>
                        <div>
                            <h1 class="text-lg font-bold text-slate-900 leading-tight">{{ __('Google Sheet Integration') }}</h1>
                            <nav class="flex text-xs text-slate-500" aria-label="Breadcrumb">
                                <span class="hover:text-slate-700">{{ __('Settings') }}</span>
                                <span class="mx-1.5">/</span>
                                <span class="font-medium text-slate-700">{{ __('Integrations') }}</span>
                            </nav>
                        </div>
                    </div>
                    
                    <!-- Global Actions -->
                    <div class="flex items-center gap-3">
                        <div class="hidden sm:flex items-center gap-2 px-3 py-1.5 bg-slate-50 rounded border border-slate-200">
                            <span class="inline-flex items-center justify-center w-2 h-2 rounded-full bg-emerald-500"></span>
                            <span class="text-xs font-semibold text-slate-600">{{ __('API Active') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

            <!-- 1. Important: Service Account Email (WARNING) -->
            <div class="rounded-lg border border-red-300 bg-red-50 p-4">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-500" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3 flex-1">
                        <h3 class="text-sm font-bold text-red-800">⚠️ {{ __('Important: Share your Google Sheet') }}</h3>
                        <p class="mt-2 text-sm text-red-700">
                            {!! __('Invite this email address as an <strong>Editor</strong> in your Google Sheet:') !!}
                        </p>
                        <div class="mt-3 flex items-center gap-2">
                            <code id="serviceAccountEmail" class="flex-1 px-3 py-2 bg-white border border-red-200 rounded text-sm font-mono text-red-800 select-all">laravel-sheets-sync@sourcing-app-1a786.iam.gserviceaccount.com</code>
                            <button type="button" onclick="copyServiceEmail()" id="copyEmailBtn" class="px-3 py-2 bg-red-600 hover:bg-red-700 text-white text-xs font-medium rounded transition-colors flex items-center gap-1.5">
                                <svg id="copyIcon" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/>
                                </svg>
                                <span id="copyText">{{ __('Copy') }}</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 2. KPIs / Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Total Success -->
                <div class="bg-emerald-50 rounded-lg border border-emerald-200 p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-[10px] font-bold uppercase tracking-wider text-emerald-600">{{ __('Successful Syncs') }}</p>
                            <p class="text-2xl font-bold text-emerald-700 mt-1">{{ $syncStats['total_success'] }}</p>
                        </div>
                        <div class="h-10 w-10 rounded-full bg-emerald-100 flex items-center justify-center">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Total Errors -->
                <div class="bg-red-50 rounded-lg border border-red-200 p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-[10px] font-bold uppercase tracking-wider text-red-600">{{ __('Errors') }}</p>
                            <p class="text-2xl font-bold text-red-700 mt-1">{{ $syncStats['total_errors'] }}</p>
                        </div>
                        <div class="h-10 w-10 rounded-full bg-red-100 flex items-center justify-center">
                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Last Sync -->
                <div class="bg-blue-50 rounded-lg border border-blue-200 p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-[10px] font-bold uppercase tracking-wider text-blue-600">{{ __('Last Sync') }}</p>
                            @if($syncStats['last_success_at'])
                                <p class="text-sm font-bold text-blue-700 mt-1">{{ $syncStats['last_success_at']->diffForHumans() }}</p>
                                <p class="text-[10px] text-blue-500">{{ __('Order') }} #{{ $syncStats['last_success_order_id'] }}</p>
                            @else
                                <p class="text-sm font-medium text-blue-700 mt-1 italic">{{ __('None') }}</p>
                            @endif
                        </div>
                        <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 3. Configuration Form -->
            <div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-200 bg-slate-50/50 flex justify-between items-center">
                    <div>
                        <h3 class="text-sm font-semibold text-slate-900">{{ __('Configuration') }}</h3>
                        <p class="text-xs text-slate-500 mt-0.5">{{ __('Connect your application to Google Sheets for data synchronization.') }}</p>
                    </div>
                </div>

                <div class="p-6">
                    <!-- SUCCESS MESSAGE -->
                    @if (session('success'))
                        <div class="mb-6 rounded-md bg-green-50 p-4 border border-green-200">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div class="ml-3"><p class="text-sm font-medium text-green-800">{{ session('success') }}</p></div>
                            </div>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.google-sheet-settings.update') }}">
                        @csrf
                        @method('PUT')

                        <div class="space-y-6">
                            
                            <!-- Sheet ID -->
                            <div>
                                <label for="sheet_id" class="block text-[10px] font-bold text-slate-500 uppercase mb-1">{{ __('Sheet ID') }}</label>
                                <div class="relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                                        </svg>
                                    </div>
                                    <input type="text" name="sheet_id" id="sheet_id" value="{{ old('sheet_id', $setting->sheet_id) }}" placeholder="1BxiMVs0XRA5nFMdKvBdBZjgmUUqptlbs74OgvE2upms"
                                        class="pl-10 block w-full px-3 py-2 text-sm bg-slate-50 border border-slate-300 text-slate-900 rounded focus:ring-1 focus:ring-orange-500 focus:border-orange-500 transition-colors font-medium">
                                </div>
                                <p class="mt-2 text-[10px] text-slate-500 flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    {{ __('Found in the URL: docs.google.com/spreadsheets/d/') }}<span class="font-bold text-slate-700">{{ __('SHEET_ID') }}</span>{{ __('/edit') }}
                                </p>
                                @error('sheet_id')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Sheet Name -->
                            <div>
                                <label for="sheet_name" class="block text-[10px] font-bold text-slate-500 uppercase mb-1">{{ __('Tab / Sheet Name') }}</label>
                                <div class="relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <input type="text" name="sheet_name" id="sheet_name" value="{{ old('sheet_name', $setting->sheet_name) }}" placeholder="Sheet1"
                                        class="pl-10 block w-full px-3 py-2 text-sm bg-slate-50 border border-slate-300 text-slate-900 rounded focus:ring-1 focus:ring-orange-500 focus:border-orange-500 transition-colors font-medium">
                                </div>
                                <p class="mt-1 text-[10px] text-slate-500">{{ __('The specific tab name at the bottom of your spreadsheet.') }}</p>
                                @error('sheet_name')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                        </div>

                        <!-- Actions -->
                        <div class="mt-8 pt-6 border-t border-slate-100 flex flex-col gap-4">
                            <div class="flex items-center justify-between gap-3 flex-wrap">
                                <div class="flex items-center gap-2">
                                    <button type="button" id="testConnectionBtn" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded transition-colors shadow-sm flex items-center gap-2">
                                        <svg id="testBtnIcon" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                        </svg>
                                        <span id="testBtnText">{{ __('Test connection') }}</span>
                                    </button>
                                    
                                    <button type="button" id="installHeadersBtn" class="px-4 py-2 bg-orange-500 hover:bg-orange-600 text-white text-sm font-medium rounded transition-colors shadow-sm flex items-center gap-2">
                                        <svg id="installBtnIcon" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                        </svg>
                                        <span id="installBtnText">{{ __('Install headers') }}</span>
                                    </button>

                                    <div class="h-6 w-px bg-slate-300 mx-2 hidden sm:block"></div>

                                    <button type="button" id="syncAllBtn" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded transition-colors shadow-sm flex items-center gap-2">
                                        <svg id="syncAllBtnIcon" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                        </svg>
                                        <span id="syncAllBtnText">{{ __('Sync All History') }}</span>
                                    </button>
                                </div>
                                
                                <div class="flex items-center gap-3">
                                    <button type="button" onclick="window.history.back()" class="px-4 py-2 bg-white border border-slate-300 text-slate-700 hover:bg-slate-50 text-sm font-medium rounded transition-colors">
                                        {{ __('Cancel') }}
                                    </button>
                                    <button type="submit" class="px-6 py-2 bg-slate-900 hover:bg-slate-800 text-white text-sm font-medium rounded transition-colors shadow-sm flex items-center gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                                        {{ __('Save Changes') }}
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Test Result Container -->
                            <div id="testResultContainer" class="hidden">
                                <!-- Will be populated by JavaScript -->
                            </div>
                            
                            <!-- Install Headers Result Container -->
                            <div id="installResultContainer" class="hidden">
                                <!-- Will be populated by JavaScript -->
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- 4. Error Logs Table -->
            <div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-200 bg-slate-50/50 flex justify-between items-center">
                    <div>
                        <h3 class="text-sm font-semibold text-slate-900 flex items-center gap-2">
                            <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            {{ __('Sync Logs') }}
                        </h3>
                        <p class="text-xs text-slate-500 mt-0.5">{{ __('Recent error history') }}</p>
                    </div>
                </div>

                <div class="p-6">
                    @if($syncStats['recent_errors']->isNotEmpty())
                        <div class="space-y-3">
                            @foreach($syncStats['recent_errors'] as $log)
                                <div class="bg-white rounded-lg border border-red-200">
                                    <div class="p-4">
                                        <div class="flex items-start gap-3">
                                            <!-- Icon -->
                                            <div class="flex-shrink-0 mt-0.5">
                                                <span class="text-xl">{{ $log->getStatusIcon() }}</span>
                                            </div>

                                            <!-- Content -->
                                            <div class="flex-1 min-w-0">
                                                <!-- Header -->
                                                <div class="flex items-center justify-between mb-2">
                                                    <div class="flex items-center gap-2">
                                                        @if($log->sourcing_order_id)
                                                            <a href="{{ route('admin.sourcing-orders.show', $log->sourcing_order_id) }}" class="text-sm text-blue-600 hover:text-blue-900 hover:underline font-medium">
                                                                {{ __('Order') }} #{{ $log->sourcing_order_id }}
                                                            </a>
                                                        @endif
                                                    </div>
                                                    <span class="text-xs text-slate-500" title="{{ $log->getFullTimestamp() }}">
                                                        {{ $log->getFormattedTime() }}
                                                    </span>
                                                </div>

                                                <!-- User-Friendly Error -->
                                                <div class="bg-red-50 border border-red-200 rounded-md p-3 mb-2">
                                                    <p class="text-sm font-medium text-red-800">
                                                        {{ $log->getFriendlyErrorMessage() }}
                                                    </p>
                                                </div>

                                                <!-- Suggestion -->
                                                @if($log->getSuggestion())
                                                    <div class="bg-blue-50 border border-blue-200 rounded-md p-3 mb-2">
                                                        <p class="text-sm text-blue-800">
                                                            <span class="font-medium">💡 {{ __('Suggestion:') }}</span>
                                                            {!! $log->getSuggestion() !!}
                                                        </p>
                                                    </div>
                                                @endif

                                                <!-- Technical Details (Collapsible) -->
                                                <details class="group">
                                                    <summary class="cursor-pointer text-xs text-slate-500 hover:text-slate-700 select-none">
                                                        <span class="inline-flex items-center gap-1">
                                                            <svg class="w-3 h-3 transition-transform group-open:rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                                            </svg>
                                                            {{ __('Show technical details') }}
                                                        </span>
                                                    </summary>
                                                    <div class="mt-2 ml-4 p-2 bg-slate-50 rounded text-xs font-mono text-slate-700 overflow-x-auto">
                                                        <div><strong>{{ __('Error Message:') }}</strong> {{ $log->error_message }}</div>
                                                        @if($log->error_code)
                                                            <div class="mt-1"><strong>{{ __('Error Code:') }}</strong> {{ $log->error_code }}</div>
                                                        @endif
                                                    </div>
                                                </details>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8 bg-slate-50 rounded-lg border border-dashed border-slate-200">
                            <svg class="w-12 h-12 text-slate-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="text-sm font-medium text-slate-600">{{ __('No recent errors') }}</p>
                            <p class="text-xs text-slate-400 mt-1">{{ __('All synchronizations are working correctly') }}</p>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>

    <script>
        document.getElementById('testConnectionBtn').addEventListener('click', async function() {
            const btn = this;
            const btnText = document.getElementById('testBtnText');
            const btnIcon = document.getElementById('testBtnIcon');
            const resultContainer = document.getElementById('testResultContainer');
            
            // Show loading state
            btn.disabled = true;
            btn.classList.add('opacity-75', 'cursor-wait');
            btnText.textContent = '{{ __("Test in progress...") }}';
            btnIcon.innerHTML = '<svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';
            
            try {
                const response = await fetch('{{ route("admin.google-sheet-settings.test-connection") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                });
                
                const data = await response.json();
                
                // Build result HTML
                let html = '';
                if (data.success) {
                    html = `
                        <div class="rounded-md bg-green-50 p-4 border border-green-200">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-green-500" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div class="ml-3 flex-1">
                                    <h3 class="text-sm font-bold text-green-800">${data.message}</h3>
                                    ${data.details ? `
                                        <div class="mt-3 text-sm text-green-700 space-y-2">
                                            <div class="flex items-center gap-2">
                                                <span class="font-medium">{{ __("Spreadsheet:") }}</span>
                                                <span class="bg-green-100 px-2 py-0.5 rounded text-xs font-mono">${data.details.spreadsheet_title}</span>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <span class="font-medium">{{ __("Configured tab:") }}</span>
                                                <span class="bg-green-100 px-2 py-0.5 rounded text-xs font-mono">${data.details.configured_sheet}</span>
                                                ${data.details.sheet_exists 
                                                    ? '<span class="text-green-600 text-xs font-bold">✓ {{ __("Found") }}</span>' 
                                                    : '<span class="text-amber-600 text-xs font-bold">⚠ {{ __("Not found") }}</span>'}
                                            </div>
                                            <div class="flex items-start gap-2">
                                                <span class="font-medium">{{ __("Available tabs:") }}</span>
                                                <div class="flex flex-wrap gap-1">
                                                    ${data.details.available_sheets.map(s => `<span class="bg-slate-100 px-2 py-0.5 rounded text-xs">${s}</span>`).join('')}
                                                </div>
                                            </div>

                                            ${!data.details.sheet_exists ? `
                                                <div class="mt-2 pt-2 border-t border-green-200">
                                                    <button type="button" id="createSheetBtn" class="px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white text-xs font-bold rounded shadow-sm inline-flex items-center gap-1">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                                                        {{ __("Create this sheet automatically") }}
                                                    </button>
                                                </div>
                                            ` : ''}
                                        </div>
                                    ` : ''}
                                </div>
                            </div>
                        </div>
                    `;

                    // Attach Listener for Create Sheet Button
                    setTimeout(() => {
                        const createBtn = document.getElementById('createSheetBtn');
                        if(createBtn) {
                            createBtn.addEventListener('click', createSheetAction);
                        }
                    }, 100);
                } else {
                    html = `
                        <div class="rounded-md bg-red-50 p-4 border border-red-200">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-red-500" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-bold text-red-800">{{ __("Connection failed") }}</h3>
                                    <p class="mt-1 text-sm text-red-700">${data.message}</p>
                                    ${data.details?.code ? `<p class="mt-1 text-xs text-red-500">{{ __("Error code:") }} ${data.details.code}</p>` : ''}
                                </div>
                            </div>
                        </div>
                    `;
                }
                
                resultContainer.innerHTML = html;
                resultContainer.classList.remove('hidden');
                
            } catch (error) {
                resultContainer.innerHTML = `
                    <div class="rounded-md bg-red-50 p-4 border border-red-200">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-500" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-bold text-red-800">{{ __("Network error") }}</h3>
                                <p class="mt-1 text-sm text-red-700">${error.message}</p>
                            </div>
                        </div>
                    </div>
                `;
                resultContainer.classList.remove('hidden');
            } finally {
                // Reset button state
                btn.disabled = false;
                btn.classList.remove('opacity-75', 'cursor-wait');
                btnText.textContent = '{{ __("Test connection") }}';
                btnIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>';
            }
        });

        document.getElementById('installHeadersBtn').addEventListener('click', async function() {
            const btn = this;
            const btnText = document.getElementById('installBtnText');
            const btnIcon = document.getElementById('installBtnIcon');
            const resultContainer = document.getElementById('installResultContainer');
            // Hide other result container if visible
            document.getElementById('testResultContainer').classList.add('hidden');
            
            // Show loading state
            btn.disabled = true;
            btn.classList.add('opacity-75', 'cursor-wait');
            const originalText = btnText.textContent;
            btnText.textContent = '{{ __("Installing...") }}';
            const originalIcon = btnIcon.innerHTML;
            btnIcon.innerHTML = '<svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';
            
            try {
                const response = await fetch('{{ route("admin.google-sheet-settings.install-headers") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                });
                
                const data = await response.json();
                
                // Build result HTML
                let html = '';
                if (data.success) {
                    html = `
                        <div class="rounded-md bg-green-50 p-4 border border-green-200 mt-4">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-green-500" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div class="ml-3 flex-1">
                                    <h3 class="text-sm font-bold text-green-800">${data.message}</h3>
                                    ${data.details ? `
                                        <div class="mt-2 text-sm text-green-700">
                                            <p>{{ __("Headers created:") }} <span class="font-mono text-xs bg-green-100 px-1 rounded">${data.details.headers.join(', ')}</span></p>
                                        </div>
                                    ` : ''}
                                </div>
                            </div>
                        </div>
                    `;
                } else {
                    html = `
                        <div class="rounded-md bg-red-50 p-4 border border-red-200 mt-4">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-red-500" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-bold text-red-800">{{ __("Installation failed") }}</h3>
                                    <p class="mt-1 text-sm text-red-700">${data.message}</p>
                                </div>
                            </div>
                        </div>
                    `;
                }
                
                resultContainer.innerHTML = html;
                resultContainer.classList.remove('hidden');
                
            } catch (error) {
                resultContainer.innerHTML = `
                    <div class="rounded-md bg-red-50 p-4 border border-red-200 mt-4">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-500" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-bold text-red-800">{{ __("Network error") }}</h3>
                                <p class="mt-1 text-sm text-red-700">${error.message}</p>
                            </div>
                        </div>
                    </div>
                `;
                resultContainer.classList.remove('hidden');
            } finally {
                // Reset button state
                btn.disabled = false;
                btn.classList.remove('opacity-75', 'cursor-wait');
                btnText.textContent = originalText;
                btnIcon.innerHTML = originalIcon;
            }
        });

        // Copy service account email to clipboard
        function copyServiceEmail() {
            const email = document.getElementById('serviceAccountEmail').textContent;
            const copyText = document.getElementById('copyText');
            const copyBtn = document.getElementById('copyEmailBtn');
            
            navigator.clipboard.writeText(email).then(() => {
                // Visual feedback
                copyText.textContent = '{{ __("Copied!") }}';
                copyBtn.classList.remove('bg-red-600', 'hover:bg-red-700');
                copyBtn.classList.add('bg-green-600', 'hover:bg-green-700');
                
                // Reset after 2 seconds
                setTimeout(() => {
                    copyText.textContent = '{{ __("Copy") }}';
                    copyBtn.classList.remove('bg-green-600', 'hover:bg-green-700');
                    copyBtn.classList.add('bg-red-600', 'hover:bg-red-700');
                }, 2000);
            }).catch(err => {
                // Fallback for older browsers
                const range = document.createRange();
                range.selectNode(document.getElementById('serviceAccountEmail'));
                window.getSelection().removeAllRanges();
                window.getSelection().addRange(range);
                document.execCommand('copy');
                window.getSelection().removeAllRanges();
                
                copyText.textContent = '{{ __("Copied!") }}';
                setTimeout(() => {
                    copyText.textContent = '{{ __("Copy") }}';
                }, 2000);
            });
        }
        async function createSheetAction() {
            const btn = document.getElementById('createSheetBtn');
            const resultContainer = document.getElementById('testResultContainer');
            
            // Show loading
            const originalText = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<svg class="animate-spin w-3 h-3 mr-1" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> {{ __("Creating...") }}';

            try {
                const response = await fetch('{{ route("admin.google-sheets.create-sheet") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                });
                
                const data = await response.json();

                if (data.success) {
                    // Success feedback
                    btn.classList.remove('bg-green-600', 'hover:bg-green-700');
                    btn.classList.add('bg-emerald-600', 'hover:bg-emerald-700');
                    btn.innerHTML = '✓ {{ __("Created!") }}';
                    
                    // Reload test after short delay to show green state
                    setTimeout(() => {
                        document.getElementById('testConnectionBtn').click();
                    }, 1500);
                } else {
                    alert(data.message || '{{ __("Failed to create sheet") }}');
                    btn.disabled = false;
                    btn.innerHTML = originalText;
                }
            } catch (error) {
                console.error(error);
                alert('{{ __("Network error occurred") }}');
                btn.disabled = false;
                btn.innerHTML = originalText;
            }
        }

        document.getElementById('syncAllBtn').addEventListener('click', async function() {
            if (!confirm('{{ __("This will sync ALL historical orders to the Google Sheet. This process runs in the background. Continue?") }}')) {
                return;
            }

            const btn = this;
            const btnText = document.getElementById('syncAllBtnText');
            const btnIcon = document.getElementById('syncAllBtnIcon');
            
            // Show loading
            const originalText = btnText.textContent;
            btn.disabled = true;
            btn.classList.add('opacity-75', 'cursor-wait');
            btnText.textContent = '{{ __("Dispatching...") }}';
            
            try {
                const response = await fetch('{{ route("admin.google-sheets.sync-all") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                });
                
                const data = await response.json();
                
                if (data.success) {
                    alert(data.message);
                } else {
                    alert('Error: ' + data.message);
                }
            } catch (error) {
                alert('{{ __("Network error occurred") }}');
            } finally {
                btn.disabled = false;
                btn.classList.remove('opacity-75', 'cursor-wait');
                btnText.textContent = originalText;
            }
        });
    </script>
</x-app-layout>