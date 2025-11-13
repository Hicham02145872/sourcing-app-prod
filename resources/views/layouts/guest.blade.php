<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'SmartSource') }} - Enterprise Sourcing Platform</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700&display=swap" rel="stylesheet" />

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
        <div class="hidden lg:flex lg:w-2/5 xl:w-1/2 bg-gradient-to-br from-blue-600 via-blue-700 to-blue-800 relative overflow-hidden">
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
            </div>
            
            <!-- Content Container -->
            <div class="relative z-10 flex flex-col justify-between p-12 xl:p-16 text-white w-full">
                <!-- Logo & Brand -->
                <div>
                    <div class="inline-flex items-center gap-3 mb-16">
                        <div class="w-12 h-12 bg-white/10 backdrop-blur-xl rounded-xl flex items-center justify-center border border-white/20">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                      d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                        </div>
                        <span class="text-2xl font-semibold">fastSourcingBrothers</span>
                    </div>

                    <!-- Main Message -->
                    <div class="max-w-md">
                        <h1 class="text-4xl xl:text-5xl font-light mb-6 leading-tight">
                            fastSourcingBrothers<br/>
                            <span class="font-semibold">Made Simple</span>
                        </h1>
                        <p class="text-lg text-blue-100 leading-relaxed font-light">
                            Streamline your procurement process with intelligent automation and real-time supplier management.
                        </p>
                    </div>
                </div>

                <!-- Trust Indicators -->
                <div class="space-y-6">
                    <div class="flex items-center gap-4 text-blue-100">
                        <div class="flex -space-x-2">
                            <div class="w-10 h-10 rounded-full bg-white/20 border-2 border-blue-600 flex items-center justify-center text-xs font-semibold">JD</div>
                            <div class="w-10 h-10 rounded-full bg-white/20 border-2 border-blue-600 flex items-center justify-center text-xs font-semibold">SM</div>
                            <div class="w-10 h-10 rounded-full bg-white/20 border-2 border-blue-600 flex items-center justify-center text-xs font-semibold">AK</div>
                            <div class="w-10 h-10 rounded-full bg-white/20 border-2 border-blue-600 flex items-center justify-center text-xs font-semibold">+99</div>
                        </div>
                        <span class="text-sm font-light">Trusted by 500+ clients worldwide</span>
                    </div>
                    
                    <div class="flex items-center gap-8 text-sm">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span>Enterprise Security</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span>ISO Certified</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Panel - Authentication Form -->
        <div class="flex-1 flex items-center justify-center p-8 lg:p-12 bg-slate-50 dark:bg-slate-900">
            <div class="w-full max-w-md">
                
                <!-- Mobile Logo -->
                <div class="lg:hidden mb-12 text-center">
                    <div class="inline-flex items-center gap-3">
                        <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                      d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                        </div>
                        <span class="text-xl font-semibold text-slate-900 dark:text-white">fastSourcingBrothers</span>
                    </div>
                </div>

                <!-- Form Card -->
                <div class="bg-white dark:bg-slate-800 rounded-3xl shadow-sm border border-slate-200 dark:border-slate-700 p-8 lg:p-10">
                    {{ $slot }}
                </div>

                <!-- Footer Links -->
      
                    <p class="text-xs text-slate-400 dark:text-slate-500">
                        © {{ date('Y') }} SmartSource. All rights reserved.
                    </p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>