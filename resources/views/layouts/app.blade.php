<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($title) ? $title . ' - ' . config('app.name', 'Laravel') : config('app.name', 'Laravel') }}</title>

    <link rel="icon" href="{{ asset('favicon.ico') }}">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="font-sans antialiased bg-gray-50" x-data="{ sidebarOpen: false }" x-cloak>

    {{-- Sidebar --}}
    <x-sidebar :role="auth()->user()->role ?? 'client'" />

    {{-- Main content --}}
    <div class="lg:pl-64 min-h-screen flex flex-col">
        {{-- Header --}}
        <div class="sticky top-0 z-40 bg-white/80 backdrop-blur-md border-b border-gray-200 shadow-sm">
            <div class="px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-20"> {{-- h-20 = hauteur augmentée --}}
                    {{-- Mobile menu --}}
                    <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden p-2 -ml-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>

                    {{-- Header title --}}
                    <div class="flex-1 min-w-0">
                        @if (isset($header))
                            <div class="py-2">
                                {{ $header }}
                            </div>
                        @endif

                        {{-- ✅ Breadcrumb --}}
                        @if (isset($breadcrumb))
                            <nav class="flex items-center text-sm text-gray-500 mt-1" aria-label="Breadcrumb">
                                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                                    {{-- Icône Accueil --}}
                                    <li class="inline-flex items-center">
                                        <a href="{{ url('/') }}" class="inline-flex items-center text-gray-600 hover:text-indigo-600">
                                            <svg class="w-4 h-4 mr-1 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M10.707 1.293a1 1 0 00-1.414 0L2 8.586V18a1 1 0 001 1h5a1 1 0 001-1v-4h2v4a1 1 0 001 1h5a1 1 0 001-1V8.586l-7.293-7.293z"/>
                                            </svg>
                                            Accueil
                                        </a>
                                    </li>
                                    @foreach ($breadcrumb as $item)
                                        <li class="inline-flex items-center">
                                            <svg class="w-4 h-4 text-gray-400 mx-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                            </svg>
                                            @if (!$loop->last)
                                                <a href="{{ $item['url'] ?? '#' }}" class="text-gray-600 hover:text-indigo-600">
                                                    {{ $item['label'] }}
                                                </a>
                                            @else
                                                <span class="text-gray-400">{{ $item['label'] }}</span>
                                            @endif
                                        </li>
                                    @endforeach
                                </ol>
                            </nav>
                        @endif
                    </div>

                    {{-- Right side (user menu) --}}
                    <div class="flex items-center gap-2 sm:gap-3">
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center gap-2 p-1.5 pr-3 text-sm font-medium text-gray-700 hover:bg-gray-100 rounded-lg">
                                <div class="w-8 h-8 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center text-white font-bold">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </div>
                                <span class="hidden sm:inline-block">{{ auth()->user()->name }}</span>
                                <svg class="w-4 h-4" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>

                            {{-- Dropdown --}}
                            <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-xl border border-gray-200 py-1" style="display:none;">
                                <div class="px-4 py-3 border-b border-gray-100">
                                    <p class="text-sm font-medium text-gray-900">{{ auth()->user()->name }}</p>
                                    <p class="text-xs text-gray-500">{{ auth()->user()->email }}</p>
                                </div>
                                <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-700">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    Profil
                                </a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="flex items-center gap-3 w-full px-4 py-2.5 text-sm text-red-600 hover:bg-red-50">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                        </svg>
                                        Déconnexion
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Contenu principal --}}
        <main class="flex-1 px-4 sm:px-6 lg:px-8 py-6">
            {{ $slot }}
        </main>

        {{-- Footer --}}
        <footer class="mt-auto border-t border-gray-200 bg-white">
            <div class="px-4 sm:px-6 lg:px-8 py-4 text-sm text-gray-500 flex justify-between">
                <p>&copy; {{ date('Y') }} {{ config('app.name') }}. Tous droits réservés.</p>
                <div class="flex gap-4">
                    <a href="#" class="hover:text-gray-700">Confidentialité</a>
                    <a href="#" class="hover:text-gray-700">Conditions</a>
                    <a href="#" class="hover:text-gray-700">Support</a>
                </div>
            </div>
        </footer>
    </div>
</body>
</html>
