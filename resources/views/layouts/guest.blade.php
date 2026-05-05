<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'SmartSource') }} – {{ __('Enterprise Sourcing Platform') }}</title>
    <link rel="canonical" href="https://www.fastsourcingbrothers.com/">
    <link rel="icon" type="image/png" href="/favicon-96x96.png" sizes="96x96" />
    <link rel="icon" type="image/svg+xml" href="/favicon.svg" />
    <link rel="shortcut icon" href="/favicon.ico" />
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Facebook Pixel -->
    <x-facebook-pixel/>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script>
        if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark')
        }
    </script>
</head>

<body class="font-sans antialiased bg-white dark:bg-slate-900">
    <div class="min-h-screen flex">
        
        <!-- Left Panel - Brand Identity -->
        <div class="hidden lg:flex lg:w-2/5 xl:w-1/2 bg-gradient-to-br from-[#EF7722] via-[#FAA533] to-[#FF6B35] relative overflow-hidden">
            <!-- Subtle Pattern Overlay -->
            <div class="absolute inset-0 opacity-5">
                <svg class="w-full h-full" xmlns="http://www.w3.org/2000/svg">
                    <defs>
                        <pattern id="grid" width="32" height="32" patternUnits="userSpaceOnUse">
                            <path d="M 32 0 L 0 0 0 32" fill="none" stroke="white" stroke-width="0.5"/>
                        </pattern>
                    </defs>
                    <rect width="100%" height="100%" fill="url(#grid)" />
                </svg>
                <!-- Logo & Brand -->
                <div>
                    <a href="/" class="mb-16 text-center block">
                        <img class="h-auto max-w-[300px] mx-auto" src="{{ asset('images/logo0.png') }}" alt="{{ config('app.name') }}">
                    </a>

                    <!-- Main Message -->
                    <div class="max-w-md mx-auto text-center">
                        <h1 class="text-4xl xl:text-5xl font-light mb-6 leading-tight">
                            fastSourcingBrothers<br/>
                            <span class="font-semibold">{{ __('Made Simple') }}</span>
                        </h1>
                        <p class="text-lg text-orange-100 leading-relaxed font-light">
                            {{ __('Streamline your procurement process with intelligent automation and real-time supplier management.') }}
                        </p>
                    </div>
                </div>

                <!-- Trust Indicators -->
                <div class="space-y-6 flex flex-col items-center">
                    <div class="flex items-center justify-center gap-4 text-orange-100">
                        <div class="flex -space-x-2">
                            <div class="w-10 h-10 rounded-full bg-white/20 border-2 border-[#EF7722] flex items-center justify-center text-xs font-semibold">JD</div>
                            <div class="w-10 h-10 rounded-full bg-white/20 border-2 border-[#EF7722] flex items-center justify-center text-xs font-semibold">SM</div>
                            <div class="w-10 h-10 rounded-full bg-white/20 border-2 border-[#EF7722] flex items-center justify-center text-xs font-semibold">AK</div>
                            <div class="w-10 h-10 rounded-full bg-white/20 border-2 border-[#EF7722] flex items-center justify-center text-xs font-semibold">+99</div>
                        </div>
                        <span class="text-sm font-light">{{ __('Trusted by 500+ clients worldwide') }}</span>
                    </div>
                    
                    <div class="flex items-center justify-center gap-8 text-sm">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span>{{ __('Enterprise Security') }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span>{{ __('ISO Certified') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Panel - Authentication Form -->
        <div class="flex-1 flex items-center justify-center p-8 lg:p-12 bg-slate-50 dark:bg-slate-900">
            <div class="w-full max-w-md">
                
                <!-- Mobile Logo -->
                <div class="lg:hidden mb-12">
                    <a href="/">
                        <img class="block h-[350px] w-auto" src="{{ asset('images/logo1.png') }}" alt="{{ config('app.name') }}">
                    </a>
                </div>

                <!-- Form Card -->
                <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-200 dark:border-slate-700 p-8 lg:p-10">
                    {{ $slot }}
                </div>

                <!-- Footer Links -->
                <div class="mt-8 text-center">
                    <p class="text-xs text-slate-400 dark:text-slate-500">
                        {{ __('© 2026 Fast Sourcing Brothers LLC. Registered in Wyoming, USA.') }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>