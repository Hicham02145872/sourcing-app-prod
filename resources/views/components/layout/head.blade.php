{{-- components/layout/head.blade.php --}}
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#EF7722">
    <meta name="description" content="Sourcing App - Plateforme Professionnelle d'Entreprise">
    
    <title>{{ isset($title) ? $title . ' - ' . config('app.name', 'Laravel') : config('app.name', 'Laravel') }}</title>

    {{-- Favicon --}}
    <link rel="icon" href="{{ asset('favicon.ico') }}">
    
    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet">
    
    {{-- External Libraries --}}
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/flag-icons@6.6.2/css/flag-icons.min.css" rel="stylesheet">
    
    {{-- Vite Assets --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    {{-- Deferred Scripts --}}
    <script defer src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.csp.min.js"></script>

    {{-- Custom Styles --}}
    @include('components.layout.styles')
    @include('components.layout.alpine-notification-center')
    @include('components.layout.firebase-config')
    @include('components.layout.dark-mode-script')
    
    {{-- Enterprise Orange Theme Variables --}}
    <style>
        :root {
            /* Enterprise Orange Palette */
            --color-primary: #EF7722;
            --color-primary-dark: #D2691E;
            --color-primary-light: #FAA533;
            --color-primary-lighter: #FBBF66;
            --color-primary-lightest: #FEF3E6;
            
            /* Accent Colors */
            --color-accent: #0BA6DF;
            --color-accent-dark: #0987B8;
            
            /* Neutral Colors */
            --color-gray-50: #F9FAFB;
            --color-gray-100: #F3F4F6;
            --color-gray-200: #EBEBEB;
            --color-gray-300: #D1D5DB;
            --color-gray-400: #9CA3AF;
            --color-gray-500: #6B7280;
            --color-gray-600: #4B5563;
            --color-gray-700: #374151;
            --color-gray-800: #1F2937;
            --color-gray-900: #111827;
            
            /* Shadows */
            --shadow-sm: 0 1px 2px 0 rgba(239, 119, 34, 0.05);
            --shadow-md: 0 4px 6px -1px rgba(239, 119, 34, 0.1), 0 2px 4px -1px rgba(239, 119, 34, 0.06);
            --shadow-lg: 0 10px 15px -3px rgba(239, 119, 34, 0.1), 0 4px 6px -2px rgba(239, 119, 34, 0.05);
            --shadow-xl: 0 20px 25px -5px rgba(239, 119, 34, 0.1), 0 10px 10px -5px rgba(239, 119, 34, 0.04);
            
            /* Transitions */
            --transition-fast: 150ms cubic-bezier(0.4, 0, 0.2, 1);
            --transition-base: 200ms cubic-bezier(0.4, 0, 0.2, 1);
            --transition-slow: 300ms cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        /* Smooth Scrolling */
        html {
            scroll-behavior: smooth;
        }
        
        /* Custom Scrollbar - Enterprise Orange */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: var(--color-gray-100);
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb {
            background: var(--color-primary);
            border-radius: 4px;
            transition: background var(--transition-base);
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: var(--color-primary-dark);
        }
        
        /* Dark Mode Scrollbar */
        .dark ::-webkit-scrollbar-track {
            background: var(--color-gray-800);
        }
        
        .dark ::-webkit-scrollbar-thumb {
            background: var(--color-primary-light);
        }
        
        .dark ::-webkit-scrollbar-thumb:hover {
            background: var(--color-primary);
        }
        
        /* Focus Visible - Enterprise Orange */
        *:focus-visible {
            outline: 2px solid var(--color-primary);
            outline-offset: 2px;
            border-radius: 4px;
        }
        
        /* Selection - Enterprise Orange */
        ::selection {
            background-color: var(--color-primary-lightest);
            color: var(--color-primary-dark);
        }
        
        .dark ::selection {
            background-color: var(--color-primary-dark);
            color: var(--color-primary-lightest);
        }
        
        /* Smooth Font Rendering */
        body {
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            text-rendering: optimizeLegibility;
        }
        
        /* Loading Animation */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .animate-fade-in {
            animation: fadeIn var(--transition-slow) ease-out;
        }
        
        /* Pulse Animation - Orange */
        @keyframes pulse-orange {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: 0.5;
            }
        }
        
        .animate-pulse-orange {
            animation: pulse-orange 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
    </style>
</head>