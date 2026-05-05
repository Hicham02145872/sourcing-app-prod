<x-app-layout>
    <!-- Main Container: Enterprise Slate Background -->
    <div class="min-h-screen bg-slate-50/80 font-sans text-slate-900 pb-12">
        
        <!-- Top Navigation / Breadcrumb Area (Sticky) -->
        <div class="bg-white border-b border-slate-200 shadow-sm sticky top-0 z-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between h-auto md:h-16 py-4 md:py-0 gap-4">
                    <div class="flex items-center gap-2">
                        <!-- Back Button -->
                        <a href="{{ route('admin.categories.index') }}" class="group inline-flex items-center justify-center h-8 w-8 rounded-full bg-slate-50 border border-slate-200 text-slate-500 hover:text-orange-600 hover:border-orange-200 transition-colors" title="{{ __('Back') }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="group-hover:-translate-x-0.5 transition-transform"><path d="m15 18-6-6 6-6"/></svg>
                        </a>
                        
                        <div class="h-6 w-px bg-slate-200 mx-1 hidden sm:block"></div>
 
                        <!-- Branding Icon & Title -->
                        <div class="flex items-center gap-3">
                            <span class="inline-flex items-center justify-center h-8 w-8 rounded bg-orange-100 text-orange-600 hidden sm:inline-flex">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 6h16M4 12h16M4 18h7"/></svg>
                            </span>
                            <div>
                                <h1 class="text-lg font-bold text-slate-900 leading-tight">
                                    {{ __('Create Category') }}
                                </h1>
                                <nav class="hidden sm:flex text-xs text-slate-500 mt-0.5" aria-label="Breadcrumb">
                                    <a href="{{ route('admin.dashboard') }}" class="hover:text-orange-600 transition-colors">{{ __('Dashboard') }}</a>
                                    <span class="mx-1.5 text-slate-300">/</span>
                                    <a href="{{ route('admin.categories.index') }}" class="hover:text-orange-600 transition-colors">{{ __('Categories') }}</a>
                                    <span class="mx-1.5 text-slate-300">/</span>
                                    <span class="font-medium text-slate-700">{{ __('New') }}</span>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
 
        <!-- Main Content -->
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">
            
            <!-- Form Card -->
            <div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden">
                <!-- Card Header -->
                <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                    <div>
                        <h3 class="text-sm font-bold text-slate-900">{{ __('Category Details') }}</h3>
                        <p class="text-xs text-slate-500 mt-0.5">{{ __('Define the information for the new category') }}</p>
                    </div>
                    <!-- Decorative Icon -->
                    <div class="text-slate-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                </div>
 
                <!-- Form Body -->
                <div class="p-6">
                    <form method="POST" action="{{ route('admin.categories.store') }}" class="space-y-6">
                        @csrf
 
                        <!-- Name Field -->
                        <div>
                            <label for="name" class="block text-xs font-bold text-slate-700 uppercase tracking-wide mb-2">
                                {{ __('Category Name') }} <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                                </div>
                                <input 
                                    id="name" 
                                    type="text" 
                                    name="name" 
                                    value="{{ old('name') }}" 
                                    required 
                                    autofocus 
                                    autocomplete="name"
                                    class="w-full pl-9 pr-3 py-2.5 border border-slate-300 rounded-md text-sm shadow-sm placeholder-slate-400 focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500 transition-all"
                                    placeholder="{{ __('Ex: International Logistics') }}"
                                />
                            </div>
                            @error('name')
                                <p class="mt-1.5 text-xs text-red-600 font-medium flex items-center">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
 
                        <div class="border-t border-slate-100 pt-6 flex items-center justify-end gap-3">
                            <a href="{{ route('admin.categories.index') }}" class="px-4 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-md hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-500 transition-all">
                                {{ __('Cancel') }}
                            </a>
                            <button type="submit" class="inline-flex items-center px-5 py-2 border border-transparent text-sm font-bold rounded-md shadow-sm text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition-all">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                </svg>
                                {{ __('Create Category') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
 
            <!-- Help / Info Section -->
            <div class="rounded-md bg-blue-50 p-4 border border-blue-100 flex items-start gap-3">
                <div class="flex-shrink-0 mt-0.5">
                    <svg class="h-5 w-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <h4 class="text-sm font-bold text-blue-800">{{ __('Catalog Organization') }}</h4>
                    <p class="mt-1 text-sm text-blue-700/80">
                        {{ __('Categories are used to group your services or products. Make sure to use clear and unique names to facilitate user navigation.') }}
                    </p>
                </div>
            </div>
 
        </div>
    </div>
</x-app-layout>