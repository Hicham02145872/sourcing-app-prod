<x-app-layout>
    <!-- Main Container: Slate background for enterprise feel -->
    <div class="min-h-screen bg-slate-50/80 font-sans text-slate-900 pb-12">
        
        <!-- Top Navigation / Breadcrumb Area -->
        <div class="bg-white border-b border-slate-200 shadow-sm sticky top-0 z-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between h-auto md:h-16 py-4 md:py-0 gap-4">
                    <div class="flex items-center gap-2">
                        <span class="inline-flex items-center justify-center h-8 w-8 rounded bg-orange-100 text-orange-600">
                            <!-- Share/Network Icon -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/><line x1="8.59" y1="13.51" x2="15.42" y2="17.49"/><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"/></svg>
                        </span>
                        <div>
                            <h1 class="text-lg font-bold text-slate-900 leading-tight">{{ __('Social Media Integration') }}</h1>
                            <nav class="flex text-xs text-slate-500" aria-label="Breadcrumb">
                                <span class="hover:text-slate-700">{{ __('Dashboard') }}</span>
                                <span class="mx-1.5">/</span>
                                <span class="font-medium text-slate-700">{{ __('Configuration') }}</span>
                            </nav>
                        </div>
                    </div>
                    
                    <!-- Global Actions -->
                    <div class="flex items-center gap-3">
                        <button type="button" onclick="window.location.reload()" class="text-xs font-medium text-slate-500 hover:text-slate-700 flex items-center gap-1 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21.5 2v6h-6M2.5 22v-6h6M2 11.5a10 10 0 0 1 18.8-4.3M22 12.5a10 10 0 0 1-18.8 4.2"/></svg>
                            {{ __('Refresh') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">

            <!-- Section 1: Social Status Cards (KPI Style) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6">
                
                <!-- Facebook -->
                <div class="bg-white rounded-lg border border-slate-200 shadow-sm p-5 flex flex-col justify-between hover:shadow-md transition-shadow duration-200">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-500">{{ __('Facebook') }}</p>
                            @if($socialMediaLinks->facebook_url)
                                <h3 class="mt-2 text-lg font-bold text-emerald-600 flex items-center gap-1">
                                    {{ __('Active') }}
                                    <span class="inline-flex h-2 w-2 rounded-full bg-emerald-500"></span>
                                </h3>
                            @else
                                <h3 class="mt-2 text-lg font-bold text-slate-400">{{ __('Inactive') }}</h3>
                            @endif
                        </div>
                        <div class="p-2 bg-blue-50 rounded-md text-blue-600">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center text-xs">
                        <span class="text-slate-400 truncate">{{ $socialMediaLinks->facebook_url ? __('Connected') : __('Not configured') }}</span>
                    </div>
                </div>

                <!-- Instagram -->
                <div class="bg-white rounded-lg border border-slate-200 shadow-sm p-5 flex flex-col justify-between hover:shadow-md transition-shadow duration-200">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-500">{{ __('Instagram') }}</p>
                            @if($socialMediaLinks->instagram_url)
                                <h3 class="mt-2 text-lg font-bold text-emerald-600 flex items-center gap-1">
                                    {{ __('Active') }}
                                    <span class="inline-flex h-2 w-2 rounded-full bg-emerald-500"></span>
                                </h3>
                            @else
                                <h3 class="mt-2 text-lg font-bold text-slate-400">{{ __('Inactive') }}</h3>
                            @endif
                        </div>
                        <div class="p-2 bg-purple-50 rounded-md text-purple-600">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center text-xs">
                         <span class="text-slate-400 truncate">{{ $socialMediaLinks->instagram_url ? __('Connected') : __('Not configured') }}</span>
                    </div>
                </div>

                <!-- LinkedIn -->
                <div class="bg-white rounded-lg border border-slate-200 shadow-sm p-5 flex flex-col justify-between hover:shadow-md transition-shadow duration-200">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-500">{{ __('LinkedIn') }}</p>
                             @if($socialMediaLinks->linkedin_url)
                                <h3 class="mt-2 text-lg font-bold text-emerald-600 flex items-center gap-1">
                                    {{ __('Active') }}
                                    <span class="inline-flex h-2 w-2 rounded-full bg-emerald-500"></span>
                                </h3>
                            @else
                                <h3 class="mt-2 text-lg font-bold text-slate-400">{{ __('Inactive') }}</h3>
                            @endif
                        </div>
                        <div class="p-2 bg-indigo-50 rounded-md text-indigo-600">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"/><rect x="2" y="9" width="4" height="12"/><circle cx="4" cy="4" r="2"/></svg>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center text-xs">
                        <span class="text-slate-400 truncate">{{ $socialMediaLinks->linkedin_url ? __('Connected') : __('Not configured') }}</span>
                    </div>
                </div>

                <!-- Twitter / X -->
                <div class="bg-white rounded-lg border border-slate-200 shadow-sm p-5 flex flex-col justify-between hover:shadow-md transition-shadow duration-200">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-500">{{ __('X (Twitter)') }}</p>
                             @if($socialMediaLinks->twitter_url)
                                <h3 class="mt-2 text-lg font-bold text-emerald-600 flex items-center gap-1">
                                    {{ __('Active') }}
                                    <span class="inline-flex h-2 w-2 rounded-full bg-emerald-500"></span>
                                </h3>
                            @else
                                <h3 class="mt-2 text-lg font-bold text-slate-400">{{ __('Inactive') }}</h3>
                            @endif
                        </div>
                        <div class="p-2 bg-slate-100 rounded-md text-slate-900">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4l11.733 16h4.267l-11.733 -16z"/><path d="M4 20l6.768 -6.768m2.46 -2.46l6.772 -6.772"/></svg>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center text-xs">
                        <span class="text-slate-400 truncate">{{ $socialMediaLinks->twitter_url ? __('Connected') : __('Not configured') }}</span>
                    </div>
                </div>

                <!-- WhatsApp -->
                <div class="bg-white rounded-lg border border-slate-200 shadow-sm p-5 flex flex-col justify-between hover:shadow-md transition-shadow duration-200">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-500">{{ __('WhatsApp') }}</p>
                             @if($socialMediaLinks->whatsapp_number)
                                <h3 class="mt-2 text-lg font-bold text-emerald-600 flex items-center gap-1">
                                    {{ __('Active') }}
                                    <span class="inline-flex h-2 w-2 rounded-full bg-emerald-500"></span>
                                </h3>
                            @else
                                <h3 class="mt-2 text-lg font-bold text-slate-400">{{ __('Inactive') }}</h3>
                            @endif
                        </div>
                        <div class="p-2 bg-green-50 rounded-md text-green-600">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center text-xs">
                        <span class="text-slate-400 truncate">{{ $socialMediaLinks->whatsapp_number ? __('Connected') : __('Not configured') }}</span>
                    </div>
                </div>
            </div>

            <!-- Section 2: Configuration Form -->
            <div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-200 bg-slate-50/50 flex justify-between items-center">
                    <div>
                        <h3 class="text-sm font-semibold text-slate-900">{{ __('Platform Configuration') }}</h3>
                        <p class="text-xs text-slate-500 mt-0.5">{{ __('Update your business links') }}</p>
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

                    <!-- ERROR MESSAGE -->
                    @if ($errors->any())
                        <div class="mb-6 rounded-md bg-red-50 p-4 border border-red-200">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-red-800">{{ __('Please correct the following errors:') }}</h3>
                                    <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.social-media-links.update') }}">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            <!-- Facebook -->
                            <div>
                                <label for="facebook_url" class="block text-[10px] font-bold text-slate-500 uppercase mb-1">{{ __('Facebook Business Page') }}</label>
                                <div class="relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-4 w-4 text-slate-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>
                                    </div>
                                    <input type="url" name="facebook_url" id="facebook_url" value="{{ old('facebook_url', $socialMediaLinks->facebook_url) }}" placeholder="https://facebook.com/your-page"
                                        class="pl-10 block w-full px-3 py-2 text-sm bg-slate-50 border border-slate-300 text-slate-900 rounded focus:ring-1 focus:ring-orange-500 focus:border-orange-500 transition-colors">
                                </div>
                                @error('facebook_url')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Instagram -->
                            <div>
                                <label for="instagram_url" class="block text-[10px] font-bold text-slate-500 uppercase mb-1">{{ __('Instagram Business Profile') }}</label>
                                <div class="relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-4 w-4 text-slate-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg>
                                    </div>
                                    <input type="url" name="instagram_url" id="instagram_url" value="{{ old('instagram_url', $socialMediaLinks->instagram_url) }}" placeholder="https://instagram.com/your-profile"
                                        class="pl-10 block w-full px-3 py-2 text-sm bg-slate-50 border border-slate-300 text-slate-900 rounded focus:ring-1 focus:ring-orange-500 focus:border-orange-500 transition-colors">
                                </div>
                                @error('instagram_url')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- LinkedIn -->
                            <div>
                                <label for="linkedin_url" class="block text-[10px] font-bold text-slate-500 uppercase mb-1">{{ __('LinkedIn Company Page') }}</label>
                                <div class="relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-4 w-4 text-slate-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"/><rect x="2" y="9" width="4" height="12"/><circle cx="4" cy="4" r="2"/></svg>
                                    </div>
                                    <input type="url" name="linkedin_url" id="linkedin_url" value="{{ old('linkedin_url', $socialMediaLinks->linkedin_url) }}" placeholder="https://linkedin.com/company/your-company"
                                        class="pl-10 block w-full px-3 py-2 text-sm bg-slate-50 border border-slate-300 text-slate-900 rounded focus:ring-1 focus:ring-orange-500 focus:border-orange-500 transition-colors">
                                </div>
                                @error('linkedin_url')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Twitter / X -->
                            <div>
                                <label for="twitter_url" class="block text-[10px] font-bold text-slate-500 uppercase mb-1">{{ __('X (Twitter) Business Account') }}</label>
                                <div class="relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-4 w-4 text-slate-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M4 4l11.733 16h4.267l-11.733 -16z"/><path d="M4 20l6.768 -6.768m2.46 -2.46l6.772 -6.772"/></svg>
                                    </div>
                                    <input type="url" name="twitter_url" id="twitter_url" value="{{ old('twitter_url', $socialMediaLinks->twitter_url) }}" placeholder="https://x.com/your-handle"
                                        class="pl-10 block w-full px-3 py-2 text-sm bg-slate-50 border border-slate-300 text-slate-900 rounded focus:ring-1 focus:ring-orange-500 focus:border-orange-500 transition-colors">
                                </div>
                                @error('twitter_url')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- WhatsApp -->
                            <div>
                                <label for="whatsapp_number" class="block text-[10px] font-bold text-slate-500 uppercase mb-1">{{ __('WhatsApp Business Number') }}</label>
                                <div class="relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-4 w-4 text-slate-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
                                    </div>
                                    <input type="tel" name="whatsapp_number" id="whatsapp_number" value="{{ old('whatsapp_number', $socialMediaLinks->whatsapp_number) }}" placeholder="+1234567890"
                                        class="pl-10 block w-full px-3 py-2 text-sm bg-slate-50 border border-slate-300 text-slate-900 rounded focus:ring-1 focus:ring-orange-500 focus:border-orange-500 transition-colors">
                                </div>
                                @error('whatsapp_number')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                             <!-- YouTube -->
                            <div>
                                <label for="youtube_url" class="block text-[10px] font-bold text-slate-500 uppercase mb-1">{{ __('YouTube Channel URL') }}</label>
                                <div class="relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-4 w-4 text-slate-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M22.54 6.42a2.78 2.78 0 0 0-1.94-2C18.88 4 12 4 12 4s-6.88 0-8.6.46a2.78 2.78 0 0 0-1.94 2A29 29 0 0 0 1 11.75a29 29 0 0 0 .46 5.33A2.78 2.78 0 0 0 3.4 19c1.72.46 8.6.46 8.6.46s6.88 0 8.6-.46a2.78 2.78 0 0 0 1.94-2 29 29 0 0 0 .46-5.33 29 29 0 0 0-.46-5.33z"/><polygon points="9.75 15.02 15.5 11.75 9.75 8.48 9.75 15.02"/></svg>
                                    </div>
                                    <input type="url" name="youtube_url" id="youtube_url" value="{{ old('youtube_url', $socialMediaLinks->youtube_url) }}" placeholder="https://youtube.com/your-channel"
                                        class="pl-10 block w-full px-3 py-2 text-sm bg-slate-50 border border-slate-300 text-slate-900 rounded focus:ring-1 focus:ring-orange-500 focus:border-orange-500 transition-colors">
                                </div>
                                @error('youtube_url')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="mt-8 pt-6 border-t border-slate-100 flex items-center justify-end gap-3">
                            <button type="button" onclick="window.location.reload()" class="px-4 py-2 bg-white border border-slate-300 text-slate-700 hover:bg-slate-50 text-sm font-medium rounded transition-colors">
                                {{ __('Cancel') }}
                            </button>
                            <button type="submit" class="px-6 py-2 bg-slate-900 hover:bg-slate-800 text-white text-sm font-medium rounded transition-colors shadow-sm flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                                {{ __('Save Changes') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Info Box -->
            <div class="rounded-lg border border-blue-200 bg-blue-50 p-4">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                         <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-blue-800">{{ __('Best Practices') }}</h3>
                        <div class="mt-2 text-sm text-blue-700">
                            <ul class="list-disc pl-5 space-y-1">
                                <li>Use official business accounts for better credibility.</li>
                                <li>Ensure all URLs are publicly accessible.</li>
                                <li>Verify your accounts on each platform for enhanced trust.</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>