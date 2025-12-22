<x-app-layout>
    <div class="min-h-screen bg-slate-50/80 font-sans text-slate-900 pb-12">
        
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
            
            {{-- Connection Status Card --}}
            @if($credentialsExist && $connectionStatus)
                <div class="bg-white rounded-lg border {{ $connectionStatus['success'] ? 'border-emerald-200' : 'border-red-200' }} shadow-sm p-6 mb-6">
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
                                    <div class="flex items-center gap-2">
                                        <span class="font-semibold text-slate-700">{{ __('Spreadsheet:') }}</span>
                                        <span class="text-slate-600">{{ $connectionStatus['details']['spreadsheet_title'] ?? 'N/A' }}</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span class="font-semibold text-slate-700">{{ __('Configured Sheet:') }}</span>
                                        <span class="text-slate-600">{{ $connectionStatus['details']['configured_sheet'] ?? 'N/A' }}</span>
                                        @if(isset($connectionStatus['details']['sheet_exists']))
                                            @if($connectionStatus['details']['sheet_exists'])
                                                <span class="px-2 py-0.5 bg-emerald-100 text-emerald-700 rounded text-[10px] font-bold">{{ __('EXISTS') }}</span>
                                            @else
                                                <span class="px-2 py-0.5 bg-red-100 text-red-700 rounded text-[10px] font-bold">{{ __('NOT FOUND') }}</span>
                                            @endif
                                        @endif
                                    </div>
                                    @if(isset($connectionStatus['details']['available_sheets']))
                                        <div class="flex items-start gap-2">
                                            <span class="font-semibold text-slate-700">{{ __('Available Sheets:') }}</span>
                                            <span class="text-slate-600">{{ implode(', ', $connectionStatus['details']['available_sheets']) }}</span>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                
                {{-- Credentials Upload Card --}}
                <div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden">
                    <div class="px-6 py-4 bg-slate-50 border-b border-slate-200">
                        <h3 class="text-sm font-bold text-slate-900">{{ __('Service Account Credentials') }}</h3>
                    </div>
                    <div class="p-6">
                        @if($credentialsExist)
                            <div class="mb-4 p-3 bg-emerald-50 border border-emerald-200 rounded-lg flex items-center gap-3">
                                <svg class="w-5 h-5 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <div>
                                    <p class="text-xs font-bold text-emerald-900">{{ __('Credentials file is configured') }}</p>
                                    <p class="text-[10px] text-emerald-700">{{ __('Upload a new file to replace existing credentials') }}</p>
                                </div>
                            </div>
                        @else
                            <div class="mb-4 p-3 bg-amber-50 border border-amber-200 rounded-lg flex items-center gap-3">
                                <svg class="w-5 h-5 text-amber-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                <div>
                                    <p class="text-xs font-bold text-amber-900">{{ __('No credentials configured') }}</p>
                                    <p class="text-[10px] text-amber-700">{{ __('Upload your Google Service Account JSON file') }}</p>
                                </div>
                            </div>
                        @endif

                        <form action="{{ route('admin.google-sheets.upload-credentials') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                            @csrf
                            
                            <div>
                                <label for="credentials_file" class="block text-xs font-bold text-slate-700 mb-2">
                                    {{ __('Select Credentials File') }} <span class="text-red-500">*</span>
                                </label>
                                <input type="file" 
                                       id="credentials_file" 
                                       name="credentials_file" 
                                       accept=".json"
                                       required
                                       class="block w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-xs file:font-bold file:bg-slate-900 file:text-white hover:file:bg-black cursor-pointer border border-slate-200 rounded bg-slate-50">
                                @error('credentials_file')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                                <p class="text-[10px] font-bold text-blue-900 mb-1">{{ __('Instructions:') }}</p>
                                <ul class="text-[10px] text-blue-700 space-y-1 list-disc list-inside">
                                    <li>{{ __('Download JSON credentials from Google Cloud Console') }}</li>
                                    <li>{{ __('File must be from a Service Account (not OAuth)') }}</li>
                                    <li>{{ __('Ensure the service account has access to your spreadsheet') }}</li>
                                </ul>
                            </div>

                            <button type="submit" class="w-full py-2.5 bg-slate-900 hover:bg-black text-white text-xs font-bold rounded transition-all shadow hover:shadow-lg">
                                <span class="flex items-center justify-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                                    {{ __('Upload & Validate') }}
                                </span>
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Sheet Configuration Card --}}
                <div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden">
                    <div class="px-6 py-4 bg-slate-50 border-b border-slate-200">
                        <h3 class="text-sm font-bold text-slate-900">{{ __('Sheet Configuration') }}</h3>
                    </div>
                    <div class="p-6">
                        <form action="{{ route('admin.google-sheets.update-settings') }}" method="POST" class="space-y-4">
                            @csrf
                            
                            <div>
                                <label for="sheet_id" class="block text-xs font-bold text-slate-700 mb-2">
                                    {{ __('Spreadsheet ID') }} <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       id="sheet_id" 
                                       name="sheet_id" 
                                       value="{{ old('sheet_id', $setting->sheet_id ?? '') }}"
                                       required
                                       placeholder="1BxiMVs0XRA5nFMdKvBdBZjgmUUqptlbs74OgvE2upms"
                                       class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded text-xs focus:ring-emerald-500 focus:border-emerald-500">
                                <p class="mt-1 text-[10px] text-slate-500">{{ __('Found in the spreadsheet URL') }}</p>
                                @error('sheet_id')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="sheet_name" class="block text-xs font-bold text-slate-700 mb-2">
                                    {{ __('Sheet Name') }} <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       id="sheet_name" 
                                       name="sheet_name" 
                                       value="{{ old('sheet_name', $setting->sheet_name ?? 'sourcing') }}"
                                       required
                                       placeholder="sourcing"
                                       class="w-full px-4 py-2 bg-slate-50 border border-slate-200 rounded text-xs focus:ring-emerald-500 focus:border-emerald-500">
                                <p class="mt-1 text-[10px] text-slate-500">{{ __('The tab name within the spreadsheet') }}</p>
                                @error('sheet_name')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <button type="submit" class="w-full py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded transition-all shadow hover:shadow-lg">
                                <span class="flex items-center justify-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    {{ __('Save Settings') }}
                                </span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Field Configuration Card -->
            <div class="mt-6 bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                    <h3 class="text-lg font-medium text-slate-900 flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        {{ __('Fields to Sync') }}
                    </h3>
                </div>
                <div class="p-6">
                    <form action="{{ route('admin.google-sheets.update-settings') }}" method="POST">
                        @csrf
                        <input type="hidden" name="sheet_id" value="{{ $setting->sheet_id ?? '' }}">
                        <input type="hidden" name="sheet_name" value="{{ $setting->sheet_name ?? '' }}">
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
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
                                <label class="flex items-center space-x-3 p-3 border border-slate-200 rounded-lg hover:bg-slate-50 transition cursor-pointer">
                                    <input type="checkbox" name="synced_fields[]" value="{{ $key }}" 
                                        {{ in_array($key, $syncedFields) ? 'checked' : '' }}
                                        {{ $key === 'id' ? 'disabled' : '' }}
                                        class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                    <span class="text-sm font-medium text-slate-700">{{ $label }}</span>
                                    @if($key === 'id')
                                        <input type="hidden" name="synced_fields[]" value="id">
                                        <span class="ml-auto text-xs text-slate-400 font-normal">{{ __('(Required)') }}</span>
                                    @endif
                                </label>
                            @endforeach
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg shadow-sm transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                {{ __('Save Preferences') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Management Actions --}}
            <div class="mt-6 bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-6 py-4 bg-slate-50 border-b border-slate-200">
                    <h3 class="text-sm font-bold text-slate-900">{{ __('Management Actions') }}</h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <form action="{{ route('admin.google-sheets.test-connection') }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full py-3 px-4 bg-blue-50 hover:bg-blue-100 border border-blue-200 text-blue-700 text-xs font-bold rounded transition-all">
                                <span class="flex items-center justify-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                    {{ __('Test Connection') }}
                                </span>
                            </button>
                        </form>

                        <form action="{{ route('admin.google-sheets.install-headers') }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full py-3 px-4 bg-purple-50 hover:bg-purple-100 border border-purple-200 text-purple-700 text-xs font-bold rounded transition-all">
                                <span class="flex items-center justify-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                                    {{ __('Install Headers') }}
                                </span>
                            </button>
                        </form>

                        <a href="{{ route('admin.google-sheets.logs') }}" class="w-full py-3 px-4 bg-slate-50 hover:bg-slate-100 border border-slate-200 text-slate-700 text-xs font-bold rounded transition-all flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            {{ __('View Sync Logs') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Toast Notifications --}}
    @push('scripts')
    <script>
        // Auto-show toast from session
        document.addEventListener('DOMContentLoaded', function() {
            @if(session('success'))
                showToast("{{ session('success') }}", 'success');
            @endif
            @if(session('error'))
                showToast("{{ session('error') }}", 'error');
            @endif
            @if(session('warning'))
                showToast("{{ session('warning') }}", 'warning');
            @endif
            @if($errors->any())
                showToast("{{ __('Error:') }} {{ $errors->first() }}", 'error');
            @endif
        });
        
        function showToast(message, type = 'success') {
            const toast = document.createElement('div');
            let borderClass, iconColor, icon;
            
            if (type === 'success') {
                borderClass = 'border-emerald-500';
                iconColor = 'text-emerald-500';
                icon = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>';
            } else if (type === 'warning') {
                borderClass = 'border-amber-500';
                iconColor = 'text-amber-500';
                icon = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>';
            } else {
                borderClass = 'border-red-500';
                iconColor = 'text-red-500';
                icon = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>';
            }
            
            toast.className = `fixed top-20 right-4 bg-white border-l-4 ${borderClass} px-6 py-4 rounded shadow-lg z-50 flex items-center gap-3 animate-slide-in transform transition-all duration-300 max-w-sm`;
            toast.innerHTML = `
                <svg class="w-5 h-5 ${iconColor}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    ${icon}
                </svg>
                <p class="font-medium text-slate-800 text-sm">${message}</p>
            `;
            
            document.body.appendChild(toast);
            
            setTimeout(() => {
                toast.classList.add('opacity-0', 'translate-x-full');
                setTimeout(() => toast.remove(), 300);
            }, 4000);
        }
    </script>
    <style>
        @keyframes slide-in {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        .animate-slide-in {
            animation: slide-in 0.3s ease-out forwards;
        }
    </style>
    @endpush
</x-app-layout>
