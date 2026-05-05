<x-app-layout>
    <div class="min-h-screen bg-slate-50/80 font-sans text-slate-900 pb-12" x-data="googleSheetSettings">
        
        {{-- Page Header --}}
        <div class="bg-white border-b border-slate-200 shadow-sm sticky top-0 z-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16">
                    <div class="flex items-center gap-3">
                        <div class="h-10 w-10 rounded-full bg-emerald-100 flex items-center justify-center">
                            <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                        <div>
                            <h1 class="text-lg font-bold text-slate-900">{{ __('Google Sheets Integration') }}</h1>
                            <p class="text-xs text-slate-500">{{ __('Configure and manage Google Sheets sync') }}</p>
                        </div>
                    </div>
                    <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 border border-slate-200 text-xs font-bold text-slate-700 bg-white hover:bg-slate-50 rounded transition-colors shadow-sm">
                        {{ __('Back to Dashboard') }}
                    </a>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            
            {{-- KPI Summary Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Total Logged -->
                <div class="bg-white rounded-lg border border-slate-200 p-4 shadow-sm group hover:border-emerald-500/50 transition-colors">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-500">{{ __('Total Logs') }}</p>
                            <p class="text-2xl font-black text-slate-900 mt-1">{{ $syncStats['total_count'] ?? 0 }}</p>
                        </div>
                        <div class="h-10 w-10 rounded-full bg-slate-100 flex items-center justify-center group-hover:bg-emerald-50 text-slate-400 group-hover:text-emerald-500 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Success Rate -->
                <div class="bg-white rounded-lg border border-slate-200 p-4 shadow-sm group hover:border-emerald-500/50 transition-colors">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-500">{{ __('Success Rate') }}</p>
                            <div class="flex items-baseline gap-2 mt-1">
                                <p class="text-2xl font-black text-slate-900">{{ $syncStats['success_rate'] ?? 0 }}%</p>
                                <p class="text-[10px] font-bold text-emerald-600">({{ $syncStats['success_count'] ?? 0 }} {{ __('success') }})</p>
                            </div>
                        </div>
                        <div class="h-10 w-10 rounded-full bg-emerald-100 flex items-center justify-center">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Last Sync -->
                <div class="bg-blue-50 rounded-lg border border-blue-200 p-4 shadow-sm">
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

            @php $hardcodedEmail = 'laravel-sheets-sync@sourcing-app-1a786.iam.gserviceaccount.com'; @endphp
            {{-- SHARE ALERT --}}
            <div class="mb-8 p-0 bg-white rounded-xl border-2 border-amber-200 shadow-xl shadow-amber-500/5 overflow-hidden ring-4 ring-amber-500/5 transition-all hover:scale-[1.01]">
                <div class="flex flex-col md:flex-row">
                    <div class="bg-amber-400 p-4 flex items-center justify-center md:w-20">
                        <svg class="w-10 h-10 text-amber-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div class="flex-1 p-6 flex flex-col md:flex-row items-center justify-between gap-6">
                        <div class="text-center md:text-left">
                            <h4 class="text-lg font-black text-slate-900 mb-1 uppercase tracking-tight">{{ __('Action Required: Share your Sheet') }}</h4>
                            <p class="text-xs text-slate-600 font-medium leading-relaxed">
                                {{ __('Google requires you to share your spreadsheet with the service account below to allow the application to synchronize orders.') }}
                            </p>
                        </div>
                        <div class="bg-slate-900 rounded-xl p-4 border border-slate-700 w-full md:w-auto min-w-[300px] group">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-[9px] font-black uppercase text-slate-500 tracking-widest">{{ __('Service Account Email') }}</span>
                                <div class="flex items-center gap-1.5">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                    <span class="text-[9px] font-bold text-emerald-500/90 uppercase">{{ __('Identity System OK') }}</span>
                                </div>
                            </div>
                            <div class="flex items-center justify-between gap-4">
                                <code class="text-[10px] font-mono text-emerald-400 break-all select-all">{{ $hardcodedEmail }}</code>
                                <button @click="copyToClipboard('{{ $hardcodedEmail }}', $el)" class="flex-shrink-0 p-2 bg-emerald-500/10 hover:bg-emerald-500/20 text-emerald-400 rounded-lg border border-emerald-500/30 transition-all active:scale-90">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/></svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Connection Status Card --}}
            @if($credentialsExist && $connectionStatus)
                <div class="bg-white rounded-lg border {{ $connectionStatus['success'] ? 'border-emerald-200' : 'border-red-200' }} shadow-sm p-6 mb-8">
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0">
                            @if($connectionStatus['success'])
                                <div class="h-12 w-12 rounded-full bg-emerald-100 flex items-center justify-center">
                                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>
                            @else
                                <div class="h-12 w-12 rounded-full bg-red-100 flex items-center justify-center">
                                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>
                            @endif
                        </div>
                        <div class="flex-1">
                            <h3 class="text-sm font-bold {{ $connectionStatus['success'] ? 'text-emerald-900' : 'text-red-900' }} mb-1">
                                {{ $connectionStatus['success'] ? __('Connected') : __('Connection Failed') }}
                            </h3>
                            <p class="text-sm text-slate-600 mb-3">{{ $connectionStatus['message'] }}</p>
                            
                            @if($connectionStatus['success'] && isset($connectionStatus['details']))
                                <div class="space-y-2 text-xs">
                                    <div class="flex items-center gap-2 text-slate-600">
                                        <span class="font-semibold text-slate-700 min-w-32">{{ __('Spreadsheet:') }}</span>
                                        <span class="px-2 py-0.5 bg-slate-100 rounded font-mono">{{ $connectionStatus['details']['spreadsheet_title'] ?? 'N/A' }}</span>
                                    </div>
                                    <div class="flex items-center gap-2 text-slate-600">
                                        <span class="font-semibold text-slate-700 min-w-32">{{ __('Configured Sheet:') }}</span>
                                        <span class="px-2 py-0.5 bg-slate-100 rounded font-mono">{{ $connectionStatus['details']['configured_sheet'] ?? 'N/A' }}</span>
                                        @if(isset($connectionStatus['details']['sheet_exists']))
                                            @if($connectionStatus['details']['sheet_exists'])
                                                <span class="px-2 py-0.5 bg-emerald-100 text-emerald-700 rounded text-[10px] font-bold">{{ __('EXISTS') }}</span>
                                            @else
                                                <span class="px-2 py-0.5 bg-red-100 text-red-700 rounded text-[10px] font-bold">{{ __('NOT FOUND') }}</span>
                                                <button @click="createSheet()" class="ml-2 text-[10px] font-bold text-emerald-600 hover:text-emerald-700 underline">{{ __('Create Automatically') }}</button>
                                            @endif
                                        @endif
                                    </div>
                                    @if(isset($connectionStatus['details']['available_sheets']))
                                        <div class="flex items-start gap-2 text-slate-600">
                                            <span class="font-semibold text-slate-700 min-w-32">{{ __('Available Sheets:') }}</span>
                                            <div class="flex flex-wrap gap-1">
                                                @foreach($connectionStatus['details']['available_sheets'] as $sheet)
                                                    <span class="px-2 py-0.5 bg-slate-50 border border-slate-200 rounded text-[10px]">{{ $sheet }}</span>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            {{-- Main Config Grid --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                
                {{-- JSON Credentials Upload Card --}}
                <div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden flex flex-col">
                    <div @click="uploadVisible = !uploadVisible" 
                         class="px-6 py-4 bg-slate-50 border-b border-slate-200 flex items-center justify-between cursor-pointer hover:bg-slate-100 transition-colors group">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-slate-400 group-hover:text-emerald-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                            </svg>
                            <h3 class="text-sm font-bold text-slate-900">{{ __('Google Service Account JSON Credentials') }}</h3>
                        </div>
                        <svg class="w-4 h-4 text-slate-400 transition-transform duration-300" :class="uploadVisible ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>

                    <div x-show="uploadVisible" 
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 -translate-y-4"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         class="p-6">
                        
                        @if($credentialsExist)
                            <div class="mb-4 space-y-3">
                                <div class="p-3 bg-emerald-50 border border-emerald-200 rounded-lg flex items-start gap-3">
                                    <svg class="w-5 h-5 text-emerald-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    <div>
                                        <p class="text-xs font-bold text-emerald-900">{{ __('Credentials configured') }}</p>
                                        <p class="text-[10px] text-emerald-700">{{ __('The application is using a valid service account file.') }}</p>
                                    </div>
                                </div>

                                @php $hardcodedEmail = 'laravel-sheets-sync@sourcing-app-1a786.iam.gserviceaccount.com'; @endphp
                                <div class="p-3 bg-slate-900 rounded-lg border border-slate-700 shadow-inner group">
                                    <div class="flex items-center justify-between mb-1">
                                        <p class="text-[9px] font-black uppercase tracking-widest text-slate-500">{{ __('Service Account Identity') }}</p>
                                        <button @click="copyToClipboard('{{ $hardcodedEmail }}', $el)" class="text-[9px] font-bold text-emerald-500 hover:text-emerald-400 flex items-center gap-1 transition-colors uppercase tracking-tight">
                                            <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/></svg>
                                            <span>{{ __('Copy Email') }}</span>
                                        </button>
                                    </div>
                                    <p class="text-xs font-mono text-emerald-400 break-all select-all">{{ $hardcodedEmail }}</p>
                                    <div class="mt-2 flex items-center gap-1.5">
                                        <span class="inline-block w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                        <p class="text-[9px] text-slate-400 italic">{{ __('Share your spreadsheet with this email to grant access.') }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <form action="{{ route('admin.google-sheets.upload-credentials') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="relative group"
                                 @dragover.prevent="dragOver = true" 
                                 @dragleave.prevent="dragOver = false" 
                                 @drop.prevent="dragOver = false; $refs.fileInput.files = $event.dataTransfer.files; $refs.uploadForm.submit()">
                                
                                <input type="file" 
                                       name="credentials_file" 
                                       id="credentials_file" 
                                       x-ref="fileInput"
                                       accept=".json"
                                       required
                                       class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                                       @change="$refs.uploadForm.submit()">
                                
                                <div class="border-2 border-dashed rounded-lg p-10 text-center transition-all duration-200"
                                     :class="dragOver ? 'border-emerald-500 bg-emerald-50 ring-4 ring-emerald-500/10' : 'border-slate-200 bg-slate-50 group-hover:bg-slate-100 group-hover:border-slate-300'">
                                    
                                    <div class="h-16 w-16 bg-white rounded-full shadow-sm flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform">
                                        <svg class="w-8 h-8 text-slate-400 group-hover:text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                        </svg>
                                    </div>
                                    
                                    <p class="text-sm font-bold text-slate-700 mb-1 group-hover:text-slate-900">{{ __('Click to upload or drag and drop') }}</p>
                                    <p class="text-xs text-slate-500">{{ __('Only JSON credential files from Google Cloud Console') }}</p>
                                </div>
                            </div>
                        </form>

                        <div class="mt-4 bg-blue-50 border border-blue-100 rounded-lg p-3">
                            <h4 class="text-[10px] font-bold text-blue-900 uppercase tracking-wider mb-2">{{ __('Quick Guide') }}</h4>
                            <ol class="text-[10px] text-blue-700 space-y-1 ml-4 list-decimal">
                                <li>{{ __('Go to Google Cloud Console > APIs & Services > Credentials') }}</li>
                                <li>{{ __('Create a Service Account and download its JSON key') }}</li>
                                <li>{{ __('Share your target spreadsheet with the email in that JSON file') }}</li>
                            </ol>
                        </div>
                    </div>
                </div>

                {{-- Spreadsheet Configuration Card --}}
                <div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden">
                    <div class="px-6 py-4 bg-slate-50 border-b border-slate-200">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <h3 class="text-sm font-bold text-slate-900">{{ __('Spreadsheet Configuration') }}</h3>
                        </div>
                    </div>
                    <div class="p-6">
                        <form action="{{ route('admin.google-sheets.update-settings') }}" method="POST" class="space-y-6">
                            @csrf
                            
                            <div>
                                <label for="sheet_id" class="block text-[10px] font-bold text-slate-500 uppercase mb-1">{{ __('Spreadsheet ID') }} <span class="text-red-500">*</span></label>
                                <div class="relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                                        </svg>
                                    </div>
                                    <input type="text" 
                                           id="sheet_id" 
                                           name="sheet_id" 
                                           value="{{ old('sheet_id', $setting->sheet_id ?? '') }}"
                                           required
                                           placeholder="1BxiMVs0XRA5nFMdKvBdBZjgmUUqptlbs74OgvE2upms"
                                           class="pl-10 block w-full px-3 py-2 text-sm bg-slate-50 border border-slate-200 text-slate-900 rounded focus:ring-emerald-500 focus:border-emerald-500 transition-colors font-medium">
                                </div>
                                <p class="mt-2 text-[10px] text-slate-500 flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    {{ __('Found in URL: docs.google.com/spreadsheets/d/') }}<span class="font-bold text-slate-700">{{ __('ID') }}</span>{{ __('/edit') }}
                                </p>
                                @error('sheet_id')
                                    <p class="mt-1 text-xs text-red-600 font-bold uppercase tracking-tight">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="sheet_name" class="block text-[10px] font-bold text-slate-500 uppercase mb-1">{{ __('Tab Name (Sheet)') }} <span class="text-red-500">*</span></label>
                                <div class="relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <input type="text" 
                                           id="sheet_name" 
                                           name="sheet_name" 
                                           value="{{ old('sheet_name', $setting->sheet_name ?? 'sourcing') }}"
                                           required
                                           placeholder="sourcing"
                                           class="pl-10 block w-full px-3 py-2 text-sm bg-slate-50 border border-slate-200 text-slate-900 rounded focus:ring-emerald-500 focus:border-emerald-500 transition-colors font-medium">
                                </div>
                                <p class="mt-1 text-[10px] text-slate-500">{{ __('The tab name at the bottom of your spreadsheet (e.g., "Sheet1").') }}</p>
                                @error('sheet_name')
                                    <p class="mt-1 text-xs text-red-600 font-bold uppercase tracking-tight">{{ $message }}</p>
                                @enderror
                            </div>

                            <button type="submit" class="w-full py-2.5 bg-slate-900 hover:bg-black text-white text-xs font-bold rounded transition-all shadow hover:shadow-lg flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                {{ __('Save Spreadsheet Settings') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Fields & Management Section --}}
            <div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden mb-8">
                <div class="px-6 py-4 border-b border-slate-200 bg-slate-50/50 flex flex-wrap items-center justify-between gap-4">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                        </svg>
                        <h3 class="text-sm font-bold text-slate-900">{{ __('Management & Column Preferences') }}</h3>
                    </div>

                    <div class="flex items-center gap-2">
                        <button @click="testConnection()" :disabled="testing" 
                                class="px-3 py-1.5 bg-blue-50 hover:bg-blue-100 text-blue-700 text-[10px] font-bold rounded border border-blue-200 transition-colors flex items-center gap-1.5 disabled:opacity-50">
                            <svg class="w-3.5 h-3.5" :class="testing ? 'animate-spin' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                            <span x-text="testing ? '{{ __('Testing...') }}' : '{{ __('Test Connection') }}'"></span>
                        </button>

                        <button @click="installHeaders()" :disabled="installing" 
                                class="px-3 py-1.5 bg-orange-50 hover:bg-orange-100 text-orange-700 text-[10px] font-bold rounded border border-orange-200 transition-colors flex items-center gap-1.5 disabled:opacity-50">
                            <svg class="w-3.5 h-3.5" :class="installing ? 'animate-spin' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                            </svg>
                            <span x-text="installing ? '{{ __('Installing...') }}' : '{{ __('Install Headers') }}'"></span>
                        </button>

                        <button @click="syncAll()" :disabled="syncing" 
                                class="px-3 py-1.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 text-[10px] font-bold rounded border border-emerald-200 transition-colors flex items-center gap-1.5 disabled:opacity-50">
                            <svg class="w-3.5 h-3.5" :class="syncing ? 'animate-spin' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            <span x-text="syncing ? '{{ __('Syncing...') }}' : '{{ __('Sync All History') }}'"></span>
                        </button>
                    </div>
                </div>

                <div class="p-6">
                    <form action="{{ route('admin.google-sheets.update-settings') }}" method="POST">
                        @csrf
                        <input type="hidden" name="sheet_id" value="{{ $setting->sheet_id ?? '' }}">
                        <input type="hidden" name="sheet_name" value="{{ $setting->sheet_name ?? '' }}">
                        
                        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 mb-8">
                            @php
                                $availableFields = [
                                    'id' => __('Order ID'),
                                    'created_at' => __('Date Création'),
                                    'status' => __('Statut'),
                                    'client_name' => __('Nom Client'),
                                    'client_email' => __('Email Client'),
                                    'product_name' => __('Nom Produit'),
                                    'quantity' => __('Quantité'),
                                    'total_amount' => __('Montant Total'),
                                    'currency' => __('Devise'),
                                    'shipping_method' => __('Méthode Livraison'),
                                    'tracking_number' => __('Numéro Suivi'),
                                    'admin_assigned' => __('Admin Assigné')
                                ];
                                $syncedFields = $setting->synced_fields ?? array_keys($availableFields);
                            @endphp

                            @foreach ($availableFields as $key => $label)
                                <label class="flex items-center gap-3 p-3 bg-slate-50 border border-slate-200 rounded-lg hover:bg-white hover:border-emerald-500/50 hover:shadow-sm transition-all cursor-pointer group">
                                    <input type="checkbox" name="synced_fields[]" value="{{ $key }}" 
                                        {{ in_array($key, $syncedFields) ? 'checked' : '' }}
                                        {{ $key === 'id' ? 'disabled' : '' }}
                                        class="h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-slate-300 rounded transition-colors cursor-pointer">
                                    <div class="flex flex-col">
                                        <span class="text-[11px] font-bold text-slate-700 group-hover:text-slate-900 transition-colors uppercase tracking-tight">{{ $label }}</span>
                                        @if($key === 'id')
                                            <input type="hidden" name="synced_fields[]" value="id">
                                            <span class="text-[9px] text-slate-400 font-medium">{{ __('MANDATORY') }}</span>
                                        @else
                                            <span class="text-[9px] text-slate-400 font-medium truncate">{{ $key }}</span>
                                        @endif
                                    </div>
                                </label>
                            @endforeach
                        </div>

                        <div class="flex justify-end pt-6 border-t border-slate-100">
                            <button type="submit" class="px-6 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-lg shadow-sm transition-all flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                {{ __('Save Preference & Re-Sync Headers') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Recent Sync Logs --}}
            <div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden flex flex-col">
                <div class="px-6 py-4 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <h3 class="text-sm font-bold text-slate-900">{{ __('Recent Connection & Sync Logs') }}</h3>
                    </div>
                    <button @click="clearLogs()" class="text-[10px] font-bold text-red-500 hover:text-red-700 uppercase tracking-wider flex items-center gap-1 transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                        {{ __('Clear All Logs') }}
                    </button>
                </div>

                <div class="p-6">
                    @if($syncStats['recent_errors']->isNotEmpty())
                        <div class="space-y-4">
                            @foreach($syncStats['recent_errors'] as $log)
                                <div class="bg-white border rounded-lg overflow-hidden transition-all duration-200 border-red-200">
                                    <div class="p-4 flex gap-4">
                                        <div class="flex-shrink-0">
                                            <div class="h-10 w-10 rounded-full bg-red-50 flex items-center justify-center">
                                                <span class="text-lg">{{ $log->getStatusIcon() }}</span>
                                            </div>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center justify-between mb-2">
                                                <div class="flex items-center gap-2">
                                                    @if($log->sourcing_order_id)
                                                        <a href="{{ route('admin.sourcing-orders.show', $log->sourcing_order_id) }}" class="text-xs font-bold text-blue-600 hover:text-blue-800 uppercase">
                                                            {{ __('Order') }} #{{ $log->sourcing_order_id }}
                                                        </a>
                                                    @endif
                                                    <span class="text-[10px] text-slate-400 font-medium">{{ $log->getStatusLabel() }}</span>
                                                </div>
                                                <span class="text-[10px] text-slate-400 font-bold" title="{{ $log->getFullTimestamp() }}">
                                                    {{ $log->getFormattedTime() }}
                                                </span>
                                            </div>
                                            
                                            <div class="bg-red-50 border border-red-100 rounded p-3 mb-2">
                                                <p class="text-xs font-bold text-red-900 leading-relaxed">{{ $log->getFriendlyErrorMessage() }}</p>
                                            </div>

                                            @if($log->getSuggestion())
                                                <div class="bg-emerald-50 border border-emerald-100 rounded p-3 mb-2">
                                                    <p class="text-[11px] text-emerald-900 leading-relaxed">
                                                        <span class="font-black uppercase text-[9px] tracking-widest mr-1 underline">{{ __('Strategy:') }}</span>
                                                        {!! $log->getSuggestion() !!}
                                                    </p>
                                                </div>
                                            @endif

                                            <details class="group">
                                                <summary class="cursor-pointer text-[10px] uppercase font-black tracking-widest text-slate-500 hover:text-slate-800 flex items-center gap-1 select-none transition-colors">
                                                    <svg class="w-3 h-3 group-open:rotate-180 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                                    {{ __('Raw Technical Trace') }}
                                                </summary>
                                                <div class="mt-3 p-3 bg-slate-900 rounded font-mono text-[10px] text-emerald-400 overflow-x-auto whitespace-pre-wrap leading-relaxed border border-slate-700 shadow-inner">
                                                    <span class="text-slate-500">{{ __('// ') }}{{ $log->error_code ?? 'NO_CODE' }}</span>
                                                    {{ "\n" }}{{ $log->error_message }}
                                                </div>
                                            </details>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-16 bg-slate-50 rounded-lg border border-dashed border-slate-200">
                            <div class="h-16 w-16 bg-white rounded-full shadow-sm flex items-center justify-center mx-auto mb-4 border border-slate-100">
                                <svg class="w-8 h-8 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <h4 class="text-sm font-bold text-slate-800 mb-1">{{ __('System Integrity Check: OK') }}</h4>
                            <p class="text-xs text-slate-500 max-w-xs mx-auto">{{ __('No recent errors or sync failures detected. All systems are operating normally.') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('googleSheetSettings', () => ({
                uploadVisible: false, 
                dragOver: false,
                syncing: false,
                testing: false,
                installing: false,
                
                async testConnection() {
                    this.testing = true;
                    try {
                        const response = await fetch('{{ route("admin.google-sheets.test-connection") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            }
                        });
                        
                        const data = await response.json();
                        this.showToast(data.message, data.success ? 'success' : 'error');
                        if(data.success) setTimeout(() => window.location.reload(), 1500);
                    } catch (error) {
                        this.showToast('{{ __("Connection test failed due to network error") }}', 'error');
                    } finally {
                        this.testing = false;
                    }
                },

                async createSheet() {
                    if(!confirm('{{ __("Create the missing tab in the spreadsheet?") }}')) return;
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
                        this.showToast(data.message, data.success ? 'success' : 'error');
                        if(data.success) setTimeout(() => window.location.reload(), 1500);
                    } catch (error) {
                        this.showToast('{{ __("Failed to create sheet due to network error") }}', 'error');
                    }
                },

                async installHeaders() {
                    this.installing = true;
                    try {
                        const response = await fetch('{{ route("admin.google-sheets.install-headers") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            }
                        });
                        
                        const data = await response.json();
                        this.showToast(data.message, data.success ? 'success' : 'error');
                    } catch (error) {
                        this.showToast('{{ __("Failed to install headers due to network error") }}', 'error');
                    } finally {
                        this.installing = false;
                    }
                },

                async syncAll() {
                    if(!confirm('{{ __("Dispatch full historical sync? This runs in background.") }}')) return;
                    this.syncing = true;
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
                        this.showToast(data.message, data.success ? 'success' : 'error');
                    } catch (error) {
                        this.showToast('{{ __("Sync dispatch failed due to network error") }}', 'error');
                    } finally {
                        this.syncing = false;
                    }
                },

                async clearLogs() {
                    if(!confirm('{{ __("Permanently delete all sync logs?") }}')) return;
                    try {
                        const response = await fetch('{{ route("admin.google-sheets.clear-logs") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            }
                        });
                        
                        const data = await response.json();
                        this.showToast(data.message, data.success ? 'success' : 'error');
                        if(data.success) setTimeout(() => window.location.reload(), 1000);
                    } catch (error) {
                        this.showToast('{{ __("Action failed due to network error") }}', 'error');
                    }
                },

                copyToClipboard(text, el) {
                    navigator.clipboard.writeText(text).then(() => {
                        const originalContent = el.innerHTML;
                        el.innerHTML = '✓ {{ __("Copied!") }}';
                        el.classList.add('text-white');
                        setTimeout(() => {
                            el.innerHTML = originalContent;
                            el.classList.remove('text-white');
                        }, 2000);
                    });
                },

                showToast(message, type = 'success') {
                    const toast = document.createElement('div');
                    let borderClass, iconColor, icon;
                    
                    if (type === 'success') {
                        borderClass = 'border-emerald-500';
                        iconColor = 'text-emerald-500';
                        icon = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>';
                    } else {
                        borderClass = 'border-red-500';
                        iconColor = 'text-red-500';
                        icon = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>';
                    }
                    
                    toast.className = `fixed top-20 right-4 bg-white border-l-4 ${borderClass} px-6 py-4 rounded shadow-2xl z-[100] flex items-center gap-3 animate-slide-in transform transition-all duration-300 max-w-sm`;
                    toast.innerHTML = `
                        <svg class="w-5 h-5 ${iconColor}" fill="none" stroke="currentColor" viewBox="0 0 24 24">${icon}</svg>
                        <p class="font-bold text-slate-800 text-xs">${message}</p>
                    `;
                    
                    document.body.appendChild(toast);
                    
                    setTimeout(() => {
                        toast.classList.add('opacity-0', 'translate-x-full');
                        setTimeout(() => toast.remove(), 300);
                    }, 4000);
                }
            }));
        });
    </script>
    <style>
        @keyframes slide-in {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        .animate-slide-in {
            animation: slide-in 0.3s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
    </style>
    @endpush
</x-app-layout>
