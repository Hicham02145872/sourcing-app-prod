<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="https://public-frontend-cos.metadl.com/mgx/img/favicon.png" type="image/png">
    <title>SourceHub - Streamline Your Procurement Process</title>
    <meta name="description" content="Simplify your procurement process, save time, and gain transparency with our all-in-one sourcing platform. Connect with suppliers, manage quotes, and track orders with ease.">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            200: '#bfdbfe',
                            300: '#93c5fd',
                            400: '#60a5fa',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            800: '#1e40af',
                            900: '#1e3a8a',
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'system-ui', '-apple-system', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
        }

        /* Custom cursor */
        * {
            cursor: none !important;
        }
        
        #custom-cursor {
            position: fixed;
            width: 20px;
            height: 20px;
            border: 2px solid #2563eb;
            border-radius: 50%;
            pointer-events: none;
            z-index: 9999;
            transition: transform 0.15s ease, opacity 0.15s ease;
            mix-blend-mode: difference;
        }
        
        #custom-cursor-dot {
            position: fixed;
            width: 6px;
            height: 6px;
            background: #2563eb;
            border-radius: 50%;
            pointer-events: none;
            z-index: 10000;
            transition: transform 0.1s ease;
        }
        
        #custom-cursor.hover {
            transform: scale(1.8);
            background: rgba(37, 99, 235, 0.1);
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        .float-animation {
            animation: float 6s ease-in-out infinite;
        }
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .fade-in-up {
            opacity: 0;
            animation: fadeInUp 0.8s ease-out forwards;
        }
        @keyframes pulse-glow {
            0%, 100% {
                box-shadow: 0 0 20px rgba(37, 99, 235, 0.2);
            }
            50% {
                box-shadow: 0 0 40px rgba(37, 99, 235, 0.4);
            }
        }
        .pulse-glow {
            animation: pulse-glow 3s ease-in-out infinite;
        }
        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-50px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        .slide-in-left {
            opacity: 0;
            animation: slideInLeft 0.8s ease-out forwards;
        }
        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(50px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        .slide-in-right {
            opacity: 0;
            animation: slideInRight 0.8s ease-out forwards;
        }
        @keyframes scaleIn {
            from {
                opacity: 0;
                transform: scale(0.8);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }
        .scale-in {
            opacity: 0;
            animation: scaleIn 0.8s ease-out forwards;
        }
        body {
            -webkit-font-smoothing: antialiased;
            -webkit-tap-highlight-color: transparent;
            overflow-x: hidden;
        }
        button, a {
            -webkit-tap-highlight-color: transparent;
        }
        .mobile-nav-btn {
            padding: 12px 16px;
            min-height: 44px;
            min-width: 44px;
        }
        .touch-button {
            padding: 16px;
            min-height: 50px;
            font-size: 16px;
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 10px;
            height: 10px;
        }

        ::-webkit-scrollbar-track {
            background: #f8fafc;
        }

        ::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, #2563eb, #1e40af);
            border-radius: 5px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(180deg, #1d4ed8, #1e3a8a);
        }

        /* Firefox scrollbar */
        * {
            scrollbar-width: thin;
            scrollbar-color: #2563eb #f8fafc;
        }

        /* Smooth transitions */
        * {
            transition-property: background-color, border-color, color, fill, stroke, transform;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
            transition-duration: 200ms;
        }

        /* Three.js canvas */
        #three-canvas {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100vh;
            z-index: 0;
            pointer-events: none;
        }

        .content-wrapper {
            position: relative;
            z-index: 1;
        }

        /* Glass morphism effect */
        .glass {
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
        }

        /* Gradient text */
        .gradient-text {
            background: linear-gradient(135deg, #1e40af 0%, #2563eb 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Card hover effect */
        .card-hover {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .card-hover:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 40px rgba(37, 99, 235, 0.12);
        }

        /* Particle effect overlay */
        .particle-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            background: radial-gradient(circle at 50% 50%, rgba(37, 99, 235, 0.03) 0%, transparent 70%);
        }

        /* Enterprise badge */
        .enterprise-badge {
            position: relative;
            overflow: hidden;
        }
        .enterprise-badge::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(45deg, transparent, rgba(255,255,255,0.2), transparent);
            transform: rotate(45deg);
            animation: shine 3s infinite;
        }
        @keyframes shine {
            0% { transform: translateX(-100%) translateY(-100%) rotate(45deg); }
            100% { transform: translateX(100%) translateY(100%) rotate(45deg); }
        }

        /* Counter animation */
        .counter {
            font-variant-numeric: tabular-nums;
        }

        /* Parallax effect */
        .parallax {
            transform: translateY(var(--scroll-offset, 0));
        }

        /* Blue theme accent */
        .blue-accent {
            background: linear-gradient(135deg, #1e40af 0%, #2563eb 100%);
        }

        /* Enterprise styling */
        .enterprise-card {
            background: white;
            border: 1px solid #e2e8f0;
            box-shadow: 0 4px 12px rgba(15, 23, 42, 0.06);
        }

        .enterprise-card:hover {
            border-color: #cbd5e1;
            box-shadow: 0 12px 24px rgba(15, 23, 42, 0.1);
        }

        /* Professional spacing */
        .section-spacing {
            padding-top: 6rem;
            padding-bottom: 6rem;
        }

        @media (max-width: 768px) {
            .section-spacing {
                padding-top: 4rem;
                padding-bottom: 4rem;
            }
        }

        /* Process timeline */
        .process-step {
            position: relative;
        }
        .process-step::after {
            content: '';
            position: absolute;
            top: 50%;
            right: -50%;
            width: 100%;
            height: 2px;
            background: linear-gradient(90deg, #2563eb, transparent);
        }
        .process-step:last-child::after {
            display: none;
        }

        @media (max-width: 768px) {
            .process-step::after {
                display: none;
            }
        }
    </style>
</head>
<body class="bg-gradient-to-br from-slate-50 via-white to-blue-50 min-h-screen">
    <!-- Custom Cursor -->
    <div id="custom-cursor"></div>
    <div id="custom-cursor-dot"></div>

    <!-- Three.js Canvas -->
    <canvas id="three-canvas"></canvas>

    <!-- Content Wrapper -->
    <div class="content-wrapper">
        <!-- Navigation -->
        <nav class="fixed top-0 left-0 right-0 glass z-50 border-b border-slate-200/80 shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-20">
                    <!-- Logo -->
                    <div class="flex items-center space-x-3">
                        <div class="w-11 h-11 bg-gradient-to-br from-blue-700 to-blue-600 rounded-xl flex items-center justify-center shadow-md pulse-glow flex-shrink-0">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                            </svg>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-xl font-bold text-slate-900 leading-tight tracking-tight">
                                SourceHub
                            </span>
                            <span class="text-xs text-slate-600 font-semibold tracking-wide">Procurement Made Simple</span>
                        </div>
                    </div>

                    <!-- Mobile Menu Button -->
                    <button onclick="toggleMenu()" class="mobile-nav-btn lg:hidden hover:bg-slate-100 rounded-lg">
                        <svg class="w-6 h-6 text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>

                    <!-- Desktop Navigation -->
                    <div class="hidden lg:flex items-center space-x-8">
                        <a href="#features" class="text-slate-700 hover:text-blue-700 font-semibold text-sm tracking-wide">Features</a>
                        <a href="#how-it-works" class="text-slate-700 hover:text-blue-700 font-semibold text-sm tracking-wide">How It Works</a>
                        <a href="#benefits" class="text-slate-700 hover:text-blue-700 font-semibold text-sm tracking-wide">Benefits</a>
                        <a href="#testimonials" class="text-slate-700 hover:text-blue-700 font-semibold text-sm tracking-wide">Testimonials</a>
                    </div>

                    <!-- Auth Buttons Desktop -->
                    <div class="hidden lg:flex items-center space-x-3">
                        <a href="{{ route('login') }}" class="px-5 py-2.5 text-slate-700 hover:text-blue-700 font-semibold text-sm border-2 border-slate-300 hover:border-blue-600 rounded-lg transition-all">
                            Login
                        </a>
                        <a href="{{ route('register') }}" class="px-6 py-2.5 bg-gradient-to-r from-blue-700 to-blue-600 text-white rounded-lg hover:shadow-lg hover:scale-105 font-semibold text-sm tracking-wide">
                            Sign Up Free
                        </a>
                    </div>
                </div>
            </div>

            <!-- Mobile Menu -->
            <div id="mobileMenu" class="hidden lg:hidden glass border-t border-slate-200">
                <div class="px-4 py-4 space-y-3 max-w-7xl mx-auto">
                    <a href="#features" class="block px-4 py-3 text-slate-700 hover:bg-slate-100 rounded-lg font-semibold">Features</a>
                    <a href="#how-it-works" class="block px-4 py-3 text-slate-700 hover:bg-slate-100 rounded-lg font-semibold">How It Works</a>
                    <a href="#benefits" class="block px-4 py-3 text-slate-700 hover:bg-slate-100 rounded-lg font-semibold">Benefits</a>
                    <a href="#testimonials" class="block px-4 py-3 text-slate-700 hover:bg-slate-100 rounded-lg font-semibold">Testimonials</a>
                    <hr class="my-3 border-slate-200">
                    <a href="#" class="block px-4 py-3 text-slate-700 border-2 border-slate-300 rounded-lg font-semibold text-center">
                        Login
                    </a>
                    <a href="#cta" class="block px-4 py-3 bg-gradient-to-r from-blue-700 to-blue-600 text-white rounded-lg font-semibold text-center">
                        Sign Up Free
                    </a>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <section class="pt-32 sm:pt-40 pb-20 sm:pb-28 px-4 sm:px-6 lg:px-8 relative overflow-hidden">
            <div class="particle-overlay"></div>
            <div class="max-w-7xl mx-auto">
                <div class="grid lg:grid-cols-2 gap-12 lg:gap-16 items-center">
                    <!-- Left Content -->
                    <div class="fade-in-up space-y-8">
                        <!-- Badge -->
                        <div class="inline-flex items-center space-x-2 bg-gradient-to-r from-blue-700 to-blue-600 text-white px-5 py-2.5 rounded-full enterprise-badge shadow-md">
                            <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-sm font-bold tracking-wide">Trusted by 1000+ Businesses</span>
                        </div>

                        <!-- Heading -->
                        <h1 class="text-5xl sm:text-6xl lg:text-7xl font-bold text-slate-900 leading-tight tracking-tight">
                            Simplify Your
                            <span class="block gradient-text mt-2">
                                Procurement Process
                            </span>
                        </h1>

                        <!-- Description -->
                        <p class="text-lg sm:text-xl text-slate-600 leading-relaxed font-medium">
                            Save time and gain transparency with our all-in-one sourcing platform. Connect with suppliers, manage quotes, and track orders with ease.
                        </p>

                        <!-- Trust Badges -->
                        <div class="flex flex-wrap gap-3 items-center pt-2">
                            <div class="flex items-center space-x-2 bg-white px-5 py-3 rounded-xl border border-slate-200 shadow-sm enterprise-card">
                                <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-sm font-semibold text-slate-700">Secure Payment</span>
                            </div>
                            <div class="flex items-center space-x-2 bg-white px-5 py-3 rounded-xl border border-slate-200 shadow-sm enterprise-card">
                                <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-sm font-semibold text-slate-700">Real-time Updates</span>
                            </div>
                            <div class="flex items-center space-x-2 bg-white px-5 py-3 rounded-xl border border-slate-200 shadow-sm enterprise-card">
                                <svg class="w-5 h-5 text-blue-700" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/>
                                </svg>
                                <span class="text-sm font-semibold text-slate-700">24/7 Support</span>
                            </div>
                        </div>

                        <!-- Buttons -->
                        <div class="flex flex-col sm:flex-row gap-4 pt-6">
                            <a href="#cta" class="px-8 py-4 bg-gradient-to-r from-blue-700 to-blue-600 text-white rounded-xl hover:shadow-xl hover:scale-105 font-semibold text-center flex items-center justify-center space-x-2 active:scale-95 touch-button shadow-lg shadow-blue-600/25">
                                <span>Start Your Sourcing Request</span>
                                <svg class="w-5 h-5 hidden sm:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                </svg>
                            </a>
                            <a href="#how-it-works" class="px-8 py-4 glass text-slate-700 rounded-xl border-2 border-slate-300 hover:border-blue-600 hover:shadow-lg transition-all font-semibold text-center active:scale-95 touch-button">
                                Get a Free Quote
                            </a>
                        </div>

                        <!-- Stats -->
                        <div class="grid grid-cols-3 gap-4 pt-8">
                            <div class="enterprise-card p-6 rounded-xl text-center card-hover">
                                <div class="text-3xl sm:text-4xl font-bold gradient-text counter" data-target="1000">0</div>
                                <div class="text-sm text-slate-600 mt-2 font-semibold">Active Users</div>
                            </div>
                            <div class="enterprise-card p-6 rounded-xl text-center card-hover">
                                <div class="text-3xl sm:text-4xl font-bold gradient-text counter" data-target="5000">0</div>
                                <div class="text-sm text-slate-600 mt-2 font-semibold">Orders Processed</div>
                            </div>
                            <div class="enterprise-card p-6 rounded-xl text-center card-hover">
                                <div class="text-3xl sm:text-4xl font-bold gradient-text counter" data-target="99">0</div>
                                <div class="text-sm text-slate-600 mt-2 font-semibold">Satisfaction</div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Visual Element -->
                    <div class="hidden lg:block relative">
                        <div class="relative w-full h-[500px] flex items-center justify-center">
                            <div class="absolute inset-0 bg-gradient-to-br from-blue-600/10 to-blue-800/10 rounded-3xl blur-3xl"></div>
                            <div class="relative z-10 w-full h-full flex items-center justify-center">
                                <div class="w-64 h-64 bg-gradient-to-br from-blue-600 to-blue-800 rounded-full opacity-15 blur-3xl absolute"></div>
                                <svg class="w-96 h-96 text-blue-700 opacity-25 float-animation" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="0.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section id="features" class="section-spacing px-4 sm:px-6 lg:px-8 glass border-y border-slate-200/80">
            <div class="max-w-7xl mx-auto">
                <div class="text-center mb-16 scroll-reveal">
                    <div class="inline-flex items-center space-x-2 bg-blue-100 text-blue-800 px-5 py-2.5 rounded-full mb-6 border border-blue-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                        </svg>
                        <span class="text-sm font-bold tracking-wide">Platform Features</span>
                    </div>
                    <h2 class="text-4xl sm:text-5xl font-bold text-slate-900 mb-6 tracking-tight">
                        Everything You Need for Efficient Sourcing
                    </h2>
                    <p class="text-xl text-slate-600 max-w-3xl mx-auto font-medium">
                        Our comprehensive platform streamlines every aspect of your procurement process
                    </p>
                </div>

                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <!-- Feature 1 -->
                    <div class="group p-8 enterprise-card rounded-2xl card-hover scroll-reveal">
                        <div class="w-14 h-14 bg-gradient-to-br from-blue-700 to-blue-600 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform shadow-md shadow-blue-600/25">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-4 tracking-tight">Effortless Sourcing Requests</h3>
                        <p class="text-slate-600 leading-relaxed">Submit detailed sourcing requests for products or services with ease. Our intuitive interface guides you through every step.</p>
                    </div>

                    <!-- Feature 2 -->
                    <div class="group p-8 enterprise-card rounded-2xl card-hover scroll-reveal">
                        <div class="w-14 h-14 bg-gradient-to-br from-blue-700 to-blue-600 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform shadow-md shadow-blue-600/25">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-4 tracking-tight">Streamlined Quotation Management</h3>
                        <p class="text-slate-600 leading-relaxed">Receive and manage multiple quotations from suppliers with clear acceptance and rejection workflows all in one place.</p>
                    </div>

                    <!-- Feature 3 -->
                    <div class="group p-8 enterprise-card rounded-2xl card-hover scroll-reveal">
                        <div class="w-14 h-14 bg-gradient-to-br from-blue-700 to-blue-600 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform shadow-md shadow-blue-600/25">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-4 tracking-tight">Comprehensive Order Tracking</h3>
                        <p class="text-slate-600 leading-relaxed">Track the status of your sourcing orders from quotation acceptance to final delivery with real-time updates.</p>
                    </div>

                    <!-- Feature 4 -->
                    <div class="group p-8 enterprise-card rounded-2xl card-hover scroll-reveal">
                        <div class="w-14 h-14 bg-gradient-to-br from-blue-700 to-blue-600 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform shadow-md shadow-blue-600/25">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-4 tracking-tight">Secure Payment Processing</h3>
                        <p class="text-slate-600 leading-relaxed">Integration with various payment methods ensures smooth and secure transactions for all your sourcing needs.</p>
                    </div>

                    <!-- Feature 5 -->
                    <div class="group p-8 enterprise-card rounded-2xl card-hover scroll-reveal">
                        <div class="w-14 h-14 bg-gradient-to-br from-blue-700 to-blue-600 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform shadow-md shadow-blue-600/25">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-4 tracking-tight">Real-time Notifications</h3>
                        <p class="text-slate-600 leading-relaxed">Stay updated with push notifications via mobile/web and email alerts on request status, quotations, and order progress.</p>
                    </div>

                    <!-- Feature 6 -->
                    <div class="group p-8 enterprise-card rounded-2xl card-hover scroll-reveal">
                        <div class="w-14 h-14 bg-gradient-to-br from-blue-700 to-blue-600 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform shadow-md shadow-blue-600/25">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-4 tracking-tight">Dedicated Client Support</h3>
                        <p class="text-slate-600 leading-relaxed">Direct communication channels including WhatsApp support ensure you get assistance whenever you need it.</p>
                    </div>

                    <!-- Feature 7 -->
                    <div class="group p-8 enterprise-card rounded-2xl card-hover scroll-reveal md:col-span-2 lg:col-span-1 mx-auto md:max-w-md lg:max-w-none">
                        <div class="w-14 h-14 bg-gradient-to-br from-blue-700 to-blue-600 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform shadow-md shadow-blue-600/25">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-4 tracking-tight">Intuitive User Interface</h3>
                        <p class="text-slate-600 leading-relaxed">A modern, user-friendly dashboard designed for both clients and administrators to navigate with ease.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- How It Works Section -->
        <section id="how-it-works" class="section-spacing px-4 sm:px-6 lg:px-8 relative">
            <div class="max-w-7xl mx-auto">
                <div class="text-center mb-16 scroll-reveal">
                    <div class="inline-flex items-center space-x-2 bg-blue-100 text-blue-800 px-5 py-2.5 rounded-full mb-6 border border-blue-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                        <span class="text-sm font-bold tracking-wide">Simple Process</span>
                    </div>
                    <h2 class="text-4xl sm:text-5xl font-bold text-slate-900 mb-6 tracking-tight">
                        How It Works
                    </h2>
                    <p class="text-xl text-slate-600 max-w-3xl mx-auto font-medium">
                        Get started with our streamlined 6-step process
                    </p>
                </div>

                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
    <!-- Step 1 -->
    <div class="process-step enterprise-card p-8 rounded-2xl card-hover scroll-reveal group relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-blue-50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
        <div class="flex items-start gap-6 relative z-10">
            <div class="w-14 h-14 bg-gradient-to-br from-blue-700 to-blue-600 rounded-xl flex items-center justify-center flex-shrink-0 shadow-md group-hover:shadow-lg group-hover:scale-110 transition-all duration-300">
                <span class="text-white font-bold text-2xl">1</span>
            </div>
            <div class="flex-1">
                <h3 class="text-xl font-bold text-slate-900 mb-3 tracking-tight">Submit Request</h3>
                <p class="text-slate-600 leading-relaxed text-sm">Create a detailed sourcing request with your product specifications and requirements.</p>
                <div class="mt-4 flex items-center gap-2 text-blue-600 text-xs font-medium opacity-0 group-hover:opacity-100 transition-opacity">
                    <span>✓</span> Simple & Fast
                </div>
            </div>
        </div>
        <div class="absolute bottom-0 left-0 w-full h-1 bg-gradient-to-r from-blue-600 to-transparent transform scale-x-0 group-hover:scale-x-100 transition-transform duration-500 origin-left"></div>
    </div>

    <!-- Step 2 -->
    <div class="process-step enterprise-card p-8 rounded-2xl card-hover scroll-reveal group relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-blue-50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
        <div class="flex items-start gap-6 relative z-10">
            <div class="w-14 h-14 bg-gradient-to-br from-blue-700 to-blue-600 rounded-xl flex items-center justify-center flex-shrink-0 shadow-md group-hover:shadow-lg group-hover:scale-110 transition-all duration-300">
                <span class="text-white font-bold text-2xl">2</span>
            </div>
            <div class="flex-1">
                <h3 class="text-xl font-bold text-slate-900 mb-3 tracking-tight">Receive Quotations</h3>
                <p class="text-slate-600 leading-relaxed text-sm">Get multiple competitive quotes from verified suppliers matched to your needs.</p>
                <div class="mt-4 flex items-center gap-2 text-blue-600 text-xs font-medium opacity-0 group-hover:opacity-100 transition-opacity">
                    <span>✓</span> Best Offers
                </div>
            </div>
        </div>
        <div class="absolute bottom-0 left-0 w-full h-1 bg-gradient-to-r from-blue-600 to-transparent transform scale-x-0 group-hover:scale-x-100 transition-transform duration-500 origin-left"></div>
    </div>

    <!-- Step 3 -->
    <div class="process-step enterprise-card p-8 rounded-2xl card-hover scroll-reveal group relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-blue-50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
        <div class="flex items-start gap-6 relative z-10">
            <div class="w-14 h-14 bg-gradient-to-br from-blue-700 to-blue-600 rounded-xl flex items-center justify-center flex-shrink-0 shadow-md group-hover:shadow-lg group-hover:scale-110 transition-all duration-300">
                <span class="text-white font-bold text-2xl">3</span>
            </div>
            <div class="flex-1">
                <h3 class="text-xl font-bold text-slate-900 mb-3 tracking-tight">Accept/Reject</h3>
                <p class="text-slate-600 leading-relaxed text-sm">Review quotations and accept the best offer with our simple approval workflow.</p>
                <div class="mt-4 flex items-center gap-2 text-blue-600 text-xs font-medium opacity-0 group-hover:opacity-100 transition-opacity">
                    <span>✓</span> Your Choice
                </div>
            </div>
        </div>
        <div class="absolute bottom-0 left-0 w-full h-1 bg-gradient-to-r from-blue-600 to-transparent transform scale-x-0 group-hover:scale-x-100 transition-transform duration-500 origin-left"></div>
    </div>

    <!-- Step 4 -->
    <div class="process-step enterprise-card p-8 rounded-2xl card-hover scroll-reveal group relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-blue-50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
        <div class="flex items-start gap-6 relative z-10">
            <div class="w-14 h-14 bg-gradient-to-br from-blue-700 to-blue-600 rounded-xl flex items-center justify-center flex-shrink-0 shadow-md group-hover:shadow-lg group-hover:scale-110 transition-all duration-300">
                <span class="text-white font-bold text-2xl">4</span>
            </div>
            <div class="flex-1">
                <h3 class="text-xl font-bold text-slate-900 mb-3 tracking-tight">Track Order</h3>
                <p class="text-slate-600 leading-relaxed text-sm">Monitor your order status in real-time from production to shipment.</p>
                <div class="mt-4 flex items-center gap-2 text-blue-600 text-xs font-medium opacity-0 group-hover:opacity-100 transition-opacity">
                    <span>✓</span> Real-time Updates
                </div>
            </div>
        </div>
        <div class="absolute bottom-0 left-0 w-full h-1 bg-gradient-to-r from-blue-600 to-transparent transform scale-x-0 group-hover:scale-x-100 transition-transform duration-500 origin-left"></div>
    </div>

    <!-- Step 5 -->
    <div class="process-step enterprise-card p-8 rounded-2xl card-hover scroll-reveal group relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-blue-50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
        <div class="flex items-start gap-6 relative z-10">
            <div class="w-14 h-14 bg-gradient-to-br from-blue-700 to-blue-600 rounded-xl flex items-center justify-center flex-shrink-0 shadow-md group-hover:shadow-lg group-hover:scale-110 transition-all duration-300">
                <span class="text-white font-bold text-2xl">5</span>
            </div>
            <div class="flex-1">
                <h3 class="text-xl font-bold text-slate-900 mb-3 tracking-tight">Secure Payment</h3>
                <p class="text-slate-600 leading-relaxed text-sm">Complete transactions safely with our integrated payment processing system.</p>
                <div class="mt-4 flex items-center gap-2 text-blue-600 text-xs font-medium opacity-0 group-hover:opacity-100 transition-opacity">
                    <span>✓</span> Safe & Encrypted
                </div>
            </div>
        </div>
        <div class="absolute bottom-0 left-0 w-full h-1 bg-gradient-to-r from-blue-600 to-transparent transform scale-x-0 group-hover:scale-x-100 transition-transform duration-500 origin-left"></div>
    </div>

    <!-- Step 6 -->
    <div class="process-step enterprise-card p-8 rounded-2xl card-hover scroll-reveal group relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-blue-50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
        <div class="flex items-start gap-6 relative z-10">
            <div class="w-14 h-14 bg-gradient-to-br from-blue-700 to-blue-600 rounded-xl flex items-center justify-center flex-shrink-0 shadow-md group-hover:shadow-lg group-hover:scale-110 transition-all duration-300">
                <span class="text-white font-bold text-2xl">6</span>
            </div>
            <div class="flex-1">
                <h3 class="text-xl font-bold text-slate-900 mb-3 tracking-tight">Delivery</h3>
                <p class="text-slate-600 leading-relaxed text-sm">Receive your products with full tracking and support throughout delivery.</p>
                <div class="mt-4 flex items-center gap-2 text-blue-600 text-xs font-medium opacity-0 group-hover:opacity-100 transition-opacity">
                    <span>✓</span> On Time
                </div>
            </div>
        </div>
        <div class="absolute bottom-0 left-0 w-full h-1 bg-gradient-to-r from-blue-600 to-transparent transform scale-x-0 group-hover:scale-x-100 transition-transform duration-500 origin-left"></div>
    </div>
</div>
            </div>
        </section>

        <!-- Benefits Section -->
        <section id="benefits" class="section-spacing px-4 sm:px-6 lg:px-8 glass border-y border-slate-200/80">
            <div class="max-w-7xl mx-auto">
                <div class="grid lg:grid-cols-2 gap-16 items-center">
                    <div class="scroll-reveal">
                        <div class="inline-flex items-center space-x-2 bg-green-100 text-green-800 px-5 py-2.5 rounded-full mb-8 border border-green-200">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                            </svg>
                            <span class="text-sm font-bold tracking-wide">Key Benefits</span>
                        </div>
                        <h2 class="text-4xl sm:text-5xl font-bold text-slate-900 mb-8 tracking-tight">
                            Why Businesses Choose SourceHub
                        </h2>
                        <p class="text-xl text-slate-600 mb-10 leading-relaxed">
                            Transform your procurement process with transparency, efficiency, and cost savings.
                        </p>
                        <div class="space-y-8">
                            <div class="flex items-start space-x-5">
                                <div class="w-14 h-14 bg-green-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                    <svg class="w-7 h-7 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-bold text-slate-900 mb-2 text-lg">Save Time</h4>
                                    <p class="text-slate-600 leading-relaxed">Reduce procurement cycle time by up to 70% with automated workflows and instant supplier connections.</p>
                                </div>
                            </div>
                            <div class="flex items-start space-x-5">
                                <div class="w-14 h-14 bg-blue-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                    <svg class="w-7 h-7 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-bold text-slate-900 mb-2 text-lg">Complete Transparency</h4>
                                    <p class="text-slate-600 leading-relaxed">Track every step of your order with real-time updates and full visibility into pricing and timelines.</p>
                                </div>
                            </div>
                            <div class="flex items-start space-x-5">
                                <div class="w-14 h-14 bg-blue-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                    <svg class="w-7 h-7 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-bold text-slate-900 mb-2 text-lg">Connect with Suppliers</h4>
                                    <p class="text-slate-600 leading-relaxed">Access a network of verified suppliers and receive competitive quotes from multiple vendors instantly.</p>
                                </div>
                            </div>
                            <div class="flex items-start space-x-5">
                                <div class="w-14 h-14 bg-blue-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                    <svg class="w-7 h-7 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-bold text-slate-900 mb-2 text-lg">Boost Efficiency</h4>
                                    <p class="text-slate-600 leading-relaxed">Streamline your entire procurement workflow from request to delivery with our intuitive platform.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="scroll-reveal">
                        <div class="enterprise-card p-10 rounded-2xl shadow-lg">
                            <h3 class="text-3xl font-bold text-slate-900 mb-8 tracking-tight">Platform Impact</h3>
                            <div class="space-y-8">
                                <div>
                                    <div class="flex justify-between mb-3">
                                        <span class="text-slate-700 font-semibold text-lg">Time Reduction</span>
                                        <span class="text-green-700 font-bold text-lg">70%</span>
                                    </div>
                                    <div class="w-full bg-slate-200 rounded-full h-4">
                                        <div class="bg-gradient-to-r from-green-600 to-green-700 h-4 rounded-full" style="width: 70%"></div>
                                    </div>
                                </div>
                                <div>
                                    <div class="flex justify-between mb-3">
                                        <span class="text-slate-700 font-semibold text-lg">Cost Savings</span>
                                        <span class="text-blue-700 font-bold text-lg">45%</span>
                                    </div>
                                    <div class="w-full bg-slate-200 rounded-full h-4">
                                        <div class="bg-gradient-to-r from-blue-600 to-blue-700 h-4 rounded-full" style="width: 45%"></div>
                                    </div>
                                </div>
                                <div>
                                    <div class="flex justify-between mb-3">
                                        <span class="text-slate-700 font-semibold text-lg">Process Efficiency</span>
                                        <span class="text-blue-700 font-bold text-lg">85%</span>
                                    </div>
                                    <div class="w-full bg-slate-200 rounded-full h-4">
                                        <div class="bg-gradient-to-r from-blue-600 to-blue-700 h-4 rounded-full" style="width: 85%"></div>
                                    </div>
                                </div>
                                <div>
                                    <div class="flex justify-between mb-3">
                                        <span class="text-slate-700 font-semibold text-lg">User Satisfaction</span>
                                        <span class="text-blue-700 font-bold text-lg">99%</span>
                                    </div>
                                    <div class="w-full bg-slate-200 rounded-full h-4">
                                        <div class="bg-gradient-to-r from-blue-600 to-blue-700 h-4 rounded-full" style="width: 99%"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-10 p-8 bg-gradient-to-r from-blue-50 to-blue-100 rounded-xl border border-blue-200">
                                <p class="text-sm text-slate-600 mb-2 font-semibold">Average Order Value</p>
                                <p class="text-5xl font-bold gradient-text">$50K+</p>
                                <p class="text-sm text-slate-600 mt-3">Per successful procurement</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Testimonials Section -->
        <section id="testimonials" class="section-spacing px-4 sm:px-6 lg:px-8 relative">
            <div class="max-w-7xl mx-auto">
                <div class="text-center mb-16 scroll-reveal">
                    <div class="inline-flex items-center space-x-2 bg-blue-100 text-blue-800 px-5 py-2.5 rounded-full mb-6 border border-blue-200">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                        <span class="text-sm font-bold tracking-wide">Client Success Stories</span>
                    </div>
                    <h2 class="text-4xl sm:text-5xl font-bold text-slate-900 mb-6 tracking-tight">
                        Trusted by Industry Leaders
                    </h2>
                    <p class="text-xl text-slate-600 max-w-3xl mx-auto font-medium">
                        See how businesses are transforming their procurement with SourceHub
                    </p>
                </div>

                <div class="grid md:grid-cols-3 gap-8">
                    <!-- Testimonial 1 -->
                    <div class="enterprise-card p-8 rounded-2xl card-hover scroll-reveal">
                        <div class="flex items-center mb-6">
                            <svg class="w-5 h-5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            <svg class="w-5 h-5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            <svg class="w-5 h-5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            <svg class="w-5 h-5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            <svg class="w-5 h-5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        </div>
                        <p class="text-slate-600 mb-8 italic leading-relaxed">"SourceHub transformed our procurement process completely. The transparency and ease of use are unmatched. We've reduced our sourcing time by 65% and saved significantly on costs."</p>
                        <div class="flex items-center">
                            <div class="w-14 h-14 bg-gradient-to-br from-blue-600 to-blue-700 rounded-full flex items-center justify-center text-white font-bold mr-4">
                                AS
                            </div>
                            <div>
                                <p class="font-bold text-slate-900 text-lg">Ayman Sami</p>
                                <p class="text-sm text-slate-600">client</p>
                            </div>
                        </div>
                    </div>

                    <!-- Testimonial 2 -->
                    <div class="enterprise-card p-8 rounded-2xl card-hover scroll-reveal">
                        <div class="flex items-center mb-6">
                            <svg class="w-5 h-5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            <svg class="w-5 h-5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            <svg class="w-5 h-5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            <svg class="w-5 h-5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            <svg class="w-5 h-5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        </div>
                        <p class="text-slate-600 mb-8 italic leading-relaxed">"The real-time tracking and notification system is a game-changer. We always know exactly where our orders are. The WhatsApp support is incredibly responsive and helpful."</p>
                        <div class="flex items-center">
                            <div class="w-14 h-14 bg-gradient-to-br from-blue-600 to-blue-700 rounded-full flex items-center justify-center text-white font-bold mr-4">
                                HN
                            </div>
                            <div>
                                <p class="font-bold text-slate-900 text-lg">hicham nadi</p>
                                <p class="text-sm text-slate-600">client</p>
                            </div>
                        </div>
                    </div>

                    <!-- Testimonial 3 -->
                    <div class="enterprise-card p-8 rounded-2xl card-hover scroll-reveal">
                        <div class="flex items-center mb-6">
                            <svg class="w-5 h-5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            <svg class="w-5 h-5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            <svg class="w-5 h-5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            <svg class="w-5 h-5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            <svg class="w-5 h-5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        </div>
                        <p class="text-slate-600 mb-8 italic leading-relaxed">"Getting multiple quotes from verified suppliers in one place has revolutionized how we source materials. The secure payment system gives us peace of mind for every transaction."</p>
                        <div class="flex items-center">
                            <div class="w-14 h-14 bg-gradient-to-br from-blue-600 to-blue-700 rounded-full flex items-center justify-center text-white font-bold mr-4">
                                AA
                            </div>
                            <div>
                                <p class="font-bold text-slate-900 text-lg">Ahmad alami</p>
                                <p class="text-sm text-slate-600">Client</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Trust Badges -->
                <div class="mt-16 flex flex-wrap justify-center items-center gap-8 scroll-reveal">
                    <div class="flex items-center space-x-3 bg-white px-6 py-4 rounded-xl border border-slate-200 shadow-sm enterprise-card">
                        <svg class="w-8 h-8 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <div>
                            <p class="font-bold text-slate-900">Secure Payment</p>
                            <p class="text-xs text-slate-600">256-bit Encryption</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-3 bg-white px-6 py-4 rounded-xl border border-slate-200 shadow-sm enterprise-card">
                        <svg class="w-8 h-8 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <div>
                            <p class="font-bold text-slate-900">Quality Control</p>
                            <p class="text-xs text-slate-600">Verified Suppliers</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-3 bg-white px-6 py-4 rounded-xl border border-slate-200 shadow-sm enterprise-card">
                        <svg class="w-8 h-8 text-blue-700" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                        </svg>
                        <div>
                            <p class="font-bold text-slate-900">Guaranteed Delivery</p>
                            <p class="text-xs text-slate-600">On-Time Promise</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section id="cta" class="section-spacing px-4 sm:px-6 lg:px-8 relative">
            <div class="max-w-5xl mx-auto">
                <div class="relative bg-gradient-to-r from-blue-700 to-blue-600 rounded-3xl p-12 sm:p-16 text-center shadow-2xl overflow-hidden scroll-reveal">
                    <div class="absolute inset-0 bg-gradient-to-br from-blue-600/20 to-transparent"></div>
                    <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjAiIGhlaWdodD0iNjAiIHZpZXdCb3g9IjAgMCA2MCA2MCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48ZyBmaWxsPSJub25lIiBmaWxsLXJ1bGU9ImV2ZW5vZGQiPjxnIGZpbGw9IiNmZmZmZmYiIGZpbGwtb3BhY2l0eT0iMC4wOCI+PHBhdGggZD0iTTM2IDM0YzAtMi4yMSAxLjc5LTQgNC00czQgMS43OSA0IDQtMS43OSA0LTQgNC00LTEuNzktNC00em0wIDEwYzAtMi4yMSAxLjc5LTQgNC00czQgMS43OSA0IDQtMS43OSA0LTQgNC00LTEuNzktNC00eiIvPjwvZz48L2c+PC9zdmc+')] opacity-20"></div>
                    <div class="relative z-10">
                        <h2 class="text-4xl sm:text-5xl font-bold text-white mb-6 tracking-tight">
                            Ready to Transform Your Procurement?
                        </h2>
                        <p class="text-xl text-blue-100 mb-10 max-w-2xl mx-auto leading-relaxed">
                            Join thousands of businesses that have streamlined their sourcing process with SourceHub. Start saving time and money today.
                        </p>
                        <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                            <a href="{{ route('register') }}" class="inline-flex items-center px-10 py-5 bg-white text-blue-700 rounded-xl hover:shadow-2xl hover:scale-105 transition-all font-bold text-lg">
                                <span>Start Your Sourcing Request</span>
                                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                </svg>
                            </a>
                            <a href="{{ route('register') }}" class="inline-flex items-center px-10 py-5 bg-transparent text-white rounded-xl border-2 border-white hover:bg-white hover:text-blue-700 transition-all font-bold text-lg">
                                <span>Get a Free Quote</span>
                            </a>
                        </div>
                        <div class="mt-8 flex flex-col sm:flex-row justify-center items-center gap-6 text-blue-100">
                            <div class="flex items-center space-x-2">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span class="font-medium">No credit card required</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span class="font-medium">Free consultation</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span class="font-medium">24/7 support</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="bg-slate-900 text-slate-300 py-12 px-4 sm:px-6 lg:px-8 border-t border-slate-800">
            <div class="max-w-7xl mx-auto">
                <div class="flex flex-col md:flex-row justify-between items-center gap-8">
                    <!-- Logo and Company Name -->
                    <div class="flex items-center space-x-3">
                        <div class="w-11 h-11 bg-gradient-to-br from-blue-600 to-blue-700 rounded-xl flex items-center justify-center shadow-lg shadow-blue-600/30">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                            </svg>
                        </div>
                        <div>
                            <span class="text-xl font-bold text-white tracking-tight">FastSourcingBrothers</span>
                            <span class="block text-xs text-slate-400 font-semibold">Procurement Made Simple</span>
                        </div>
                    </div>

                    <!-- Social Media -->
                    <div class="flex items-center space-x-6">
                        <a href="#" class="text-slate-400 hover:text-blue-400 transition-colors" aria-label="Facebook">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                            </svg>
                        </a>
                        <a href="#" class="text-slate-400 hover:text-blue-400 transition-colors" aria-label="LinkedIn">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                            </svg>
                        </a>
                        <a href="#" class="text-slate-400 hover:text-blue-400 transition-colors" aria-label="Twitter">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84"/>
                            </svg>
                        </a>
                    </div>

                    <!-- Contact -->
                    <div class="flex items-center space-x-2 text-slate-400">
                        <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        <span class="text-sm font-medium"><a href="/cdn-cgi/l/email-protection" class="__cf_email__" data-cfemail="f88b8d8888978a8cb88b978d8a9b9d908d9ad69b9795">[email&#160;protected]</a></span>
                    </div>
                </div>

                <!-- Copyright -->
                <div class="border-t border-slate-800 mt-8 pt-8 text-center">
                    <p class="text-sm text-slate-400">© 2025 FastSourcingBrothers. All rights reserved. Simplifying procurement worldwide.</p>
                </div>
            </div>
        </footer>
    </div>

    <!-- JavaScript -->
    <script data-cfasync="false" src="/cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script><script>
        // Custom cursor
        const cursor = document.getElementById('custom-cursor');
        const cursorDot = document.getElementById('custom-cursor-dot');

        document.addEventListener('mousemove', (e) => {
            cursor.style.left = e.clientX + 'px';
            cursor.style.top = e.clientY + 'px';
            cursorDot.style.left = (e.clientX - 3) + 'px';
            cursorDot.style.top = (e.clientY - 3) + 'px';
        });

        // Add hover effect to interactive elements
        const interactiveElements = document.querySelectorAll('a, button, .card-hover');
        interactiveElements.forEach(el => {
            el.addEventListener('mouseenter', () => {
                cursor.classList.add('hover');
            });
            el.addEventListener('mouseleave', () => {
                cursor.classList.remove('hover');
            });
        });

        // Mobile menu toggle
        function toggleMenu() {
            const menu = document.getElementById('mobileMenu');
            menu.classList.toggle('hidden');
        }

        // Close menu when clicking on a link
        document.querySelectorAll('#mobileMenu a').forEach(link => {
            link.addEventListener('click', () => {
                document.getElementById('mobileMenu').classList.add('hidden');
            });
        });

        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Counter animation
        function animateCounter(element) {
            const target = parseFloat(element.getAttribute('data-target'));
            const duration = 2000;
            const step = target / (duration / 16);
            let current = 0;
            const isDecimal = target % 1 !== 0;

            const timer = setInterval(() => {
                current += step;
                if (current >= target) {
                    current = target;
                    clearInterval(timer);
                }
                if (isDecimal) {
                    element.textContent = current.toFixed(1) + '%';
                } else if (target >= 1000) {
                    element.textContent = Math.floor(current) + '+';
                } else {
                    element.textContent = Math.floor(current) + '%';
                }
            }, 16);
        }

        // Scroll reveal animation
        const scrollRevealObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    if (entry.target.classList.contains('scroll-reveal')) {
                        entry.target.classList.add('fade-in-up');
                    }
                    if (entry.target.classList.contains('counter') && !entry.target.classList.contains('counted')) {
                        entry.target.classList.add('counted');
                        animateCounter(entry.target);
                    }
                }
            });
        }, { threshold: 0.2 });

        // Observe all scroll reveal elements
        document.querySelectorAll('.scroll-reveal').forEach(el => {
            scrollRevealObserver.observe(el);
        });

        // Observe counters
        document.querySelectorAll('.counter').forEach(counter => {
            scrollRevealObserver.observe(counter);
        });

        // Parallax effect on scroll
        let lastScrollY = window.scrollY;
        window.addEventListener('scroll', () => {
            const scrollY = window.scrollY;
            const parallaxElements = document.querySelectorAll('.parallax');
            
            parallaxElements.forEach(el => {
                const speed = el.dataset.speed || 0.5;
                el.style.setProperty('--scroll-offset', `${scrollY * speed}px`);
            });

            lastScrollY = scrollY;
        });

        // Three.js Scene Setup
        const canvas = document.getElementById('three-canvas');
        const scene = new THREE.Scene();
        const camera = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 0.1, 1000);
        const renderer = new THREE.WebGLRenderer({ canvas, alpha: true, antialias: true });
        
        renderer.setSize(window.innerWidth, window.innerHeight);
        renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
        camera.position.z = 5;

        // Create particles
        const particlesGeometry = new THREE.BufferGeometry();
        const particlesCount = 1500;
        const posArray = new Float32Array(particlesCount * 3);

        for (let i = 0; i < particlesCount * 3; i++) {
            posArray[i] = (Math.random() - 0.5) * 10;
        }

        particlesGeometry.setAttribute('position', new THREE.BufferAttribute(posArray, 3));

        const particlesMaterial = new THREE.PointsMaterial({
            size: 0.015,
            color: 0x1e40af,
            transparent: true,
            opacity: 0.5,
            blending: THREE.AdditiveBlending
        });

        const particlesMesh = new THREE.Points(particlesGeometry, particlesMaterial);
        scene.add(particlesMesh);

        // Create geometric shapes
        const geometry = new THREE.TorusGeometry(1.5, 0.3, 16, 100);
        const material = new THREE.MeshBasicMaterial({
            color: 0x2563eb,
            wireframe: true,
            transparent: true,
            opacity: 0.12
        });
        const torus = new THREE.Mesh(geometry, material);
        scene.add(torus);

        // Create second shape
        const geometry2 = new THREE.IcosahedronGeometry(1, 0);
        const material2 = new THREE.MeshBasicMaterial({
            color: 0x1e40af,
            wireframe: true,
            transparent: true,
            opacity: 0.15
        });
        const icosahedron = new THREE.Mesh(geometry2, material2);
        icosahedron.position.set(2, 1, -2);
        scene.add(icosahedron);

        // Create third shape
        const geometry3 = new THREE.OctahedronGeometry(0.8, 0);
        const material3 = new THREE.MeshBasicMaterial({
            color: 0x2563eb,
            wireframe: true,
            transparent: true,
            opacity: 0.13
        });
        const octahedron = new THREE.Mesh(geometry3, material3);
        octahedron.position.set(-2, -1, -1);
        scene.add(octahedron);

        // Mouse movement effect
        let mouseX = 0;
        let mouseY = 0;

        document.addEventListener('mousemove', (event) => {
            mouseX = (event.clientX / window.innerWidth) * 2 - 1;
            mouseY = -(event.clientY / window.innerHeight) * 2 + 1;
        });

        // Animation loop
        function animate() {
            requestAnimationFrame(animate);

            // Rotate shapes
            torus.rotation.x += 0.001;
            torus.rotation.y += 0.002;
            icosahedron.rotation.x += 0.002;
            icosahedron.rotation.y += 0.001;
            octahedron.rotation.x += 0.0015;
            octahedron.rotation.z += 0.0015;

            // Rotate particles
            particlesMesh.rotation.y += 0.0005;

            // Mouse interaction
            camera.position.x = mouseX * 0.5;
            camera.position.y = mouseY * 0.5;

            renderer.render(scene, camera);
        }

        animate();

        // Handle window resize
        window.addEventListener('resize', () => {
            camera.aspect = window.innerWidth / window.innerHeight;
            camera.updateProjectionMatrix();
            renderer.setSize(window.innerWidth, window.innerHeight);
        });
    </script>
</body>
</html>