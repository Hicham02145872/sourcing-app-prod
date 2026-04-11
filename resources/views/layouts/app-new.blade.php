<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@include('components.layout.head')

<body class="font-sans antialiased bg-gray-50 dark:bg-gray-900" x-data="{ sidebarOpen: false }" x-cloak>
    {{-- Sidebar --}}
    <x-sidebar :role="auth()->user()?->role ?? 'client'" />

    {{-- Main Container --}}
    <div class="flex flex-col w-full lg:ml-64 min-h-screen">
        {{-- Header - Largeur complète --}}
        @include('components.layout.header')

        {{-- Main Content avec padding-top pour header sticky --}}
        <main class="flex-1 px-4 sm:px-6 lg:px-8 py-8 pt-20">
            {{ $slot }}
        </main>

        {{-- Footer --}}
        @include('components.layout.footer')
    </div>
    
    <x-loading-spinner />
    @stack('scripts')
    @if(auth()->user() && auth()->user()->role === 'client')
        <script src="//code.tidio.co/fcoeyvf3lyzubcu375ojfn87yy6zf6l1.js" async></script>
    @endif
    <x-cookie-consent />
</body>
</html>