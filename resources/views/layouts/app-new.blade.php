<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@include('components.layout.head')

<body class="font-sans antialiased bg-gray-50 dark:bg-gray-900" x-data="{ sidebarOpen: false }" x-cloak>
    <x-sidebar :role="auth()->user()->role ?? 'client'" />

    <div class="lg:pl-64 min-h-screen flex flex-col">
        @include('components.layout.header')

        {{-- Main Content --}}
        <main class="flex-1 px-4 sm:px-6 lg:px-8 py-8">
            {{ $slot }}
        </main>

        @include('components.layout.footer')
    </div>
    
    <x-loading-spinner />
    @stack('scripts')
</body>
</html>