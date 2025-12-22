<x-app-layout>
    <!-- Main Container: Enterprise Slate Background -->
    <div class="min-h-screen bg-slate-50/80 font-sans text-slate-900 pb-12">
        
        <!-- Top Navigation / Breadcrumb Area (Sticky) -->
        <div class="bg-white border-b border-slate-200 shadow-sm sticky top-0 z-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between h-auto md:h-16 py-4 md:py-0 gap-4">
                    <div class="flex items-center gap-2">
                        <!-- Back Button -->
                        <a href="{{ route('admin.countries.index') }}" class="group inline-flex items-center justify-center h-8 w-8 rounded-full bg-slate-50 border border-slate-200 text-slate-500 hover:text-orange-600 hover:border-orange-200 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="group-hover:-translate-x-0.5 transition-transform"><path d="m15 18-6-6 6-6"/></svg>
                        </a>
                        
                        <div class="h-6 w-px bg-slate-200 mx-1"></div>

                        <div>
                            <h1 class="text-lg font-bold text-slate-900 leading-tight flex items-center gap-2">
                                {{ __('Create Country') }}
                            </h1>
                            <nav class="flex text-xs text-slate-500" aria-label="Breadcrumb">
                                <a href="{{ route('admin.dashboard') }}" class="hover:text-slate-700 transition-colors">{{ __('Dashboard') }}</a>
                                <span class="mx-1.5">/</span>
                                <a href="{{ route('admin.countries.index') }}" class="hover:text-slate-700 transition-colors">{{ __('Countries') }}</a>
                                <span class="mx-1.5">/</span>
                                <span class="font-medium text-slate-700">{{ __('Create') }}</span>
                            </nav>
                        </div>
                    </div>
                    
                    <!-- Global Actions -->
                    <div>
                        <!-- Optional global actions -->
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-8">
                <form method="POST" action="{{ route('admin.countries.store') }}">
                    @csrf

                    <div class="space-y-6">
                        <!-- Country Dropdown -->
                        <div>
                            <x-input-label for="country_code" :value="__('Select Country')" />
                            <select id="country_code" name="code" class="block mt-1 w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-[#EF7722] focus:ring-[#EF7722] rounded-md shadow-sm" required>
                                <option value="">{{ __('Select a country') }}</option>
                                @foreach ($countries as $country)
                                    <option value="{{ $country->code }}" {{ old('code') == $country->code ? 'selected' : '' }}>
                                        <span class="fi fi-{{ strtolower($country->code) }} mr-2"></span>
                                        {{ $country->name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('code')" class="mt-2" />
                        </div>
                    </div>

                    <div class="flex items-center justify-end mt-6">
                        <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-[#EF7722] to-[#FAA533] hover:from-[#FAA533] hover:to-[#EF7722] text-white text-sm font-bold rounded-lg shadow-md hover:shadow-lg transition-all duration-200">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            {{ __('Create Country') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>