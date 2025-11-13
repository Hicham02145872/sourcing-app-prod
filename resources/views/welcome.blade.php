<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <title>FastSourcingBrothers - Expert Sourcing from China</title>
    <meta name="description" content="Professional sourcing solutions from China. Connect directly with verified manufacturers, reduce costs by 60%, and streamline your supply chain.">
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

        /* Blue theme accent - more subtle */
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
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-xl font-bold text-slate-900 leading-tight tracking-tight">
                                FastSourcingBrothers
                            </span>
                            <span class="text-xs text-slate-600 font-semibold tracking-wide">Sourcing Expert from China</span>
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
                        <a href="#services" class="text-slate-700 hover:text-blue-700 font-semibold text-sm tracking-wide">Services</a>
                        <a href="#process" class="text-slate-700 hover:text-blue-700 font-semibold text-sm tracking-wide">Our Process</a>
                        <a href="#advantages" class="text-slate-700 hover:text-blue-700 font-semibold text-sm tracking-wide">Advantages</a>
                        <a href="#testimonials" class="text-slate-700 hover:text-blue-700 font-semibold text-sm tracking-wide">Testimonials</a>
                        <a href="#contact" class="text-slate-700 hover:text-blue-700 font-semibold text-sm tracking-wide">Contact</a>
                    </div>

                    <!-- Auth Buttons Desktop -->
                    <div class="hidden lg:flex items-center space-x-3">
                        <a href="{{ route('login') }}" class="px-5 py-2.5 text-slate-700 hover:text-blue-700 font-semibold text-sm border-2 border-slate-300 hover:border-blue-600 rounded-lg transition-all">
                            Login
                        </a>
                        <a href="{{ route('register') }}" class="px-6 py-2.5 bg-gradient-to-r from-blue-700 to-blue-600 text-white rounded-lg hover:shadow-lg hover:scale-105 font-semibold text-sm tracking-wide">
                            Get Started
                        </a>
                    </div>
                </div>
            </div>

            <!-- Mobile Menu -->
            <div id="mobileMenu" class="hidden lg:hidden glass border-t border-slate-200">
                <div class="px-4 py-4 space-y-3 max-w-7xl mx-auto">
                    <a href="#services" class="block px-4 py-3 text-slate-700 hover:bg-slate-100 rounded-lg font-semibold">Services</a>
                    <a href="#process" class="block px-4 py-3 text-slate-700 hover:bg-slate-100 rounded-lg font-semibold">Our Process</a>
                    <a href="#advantages" class="block px-4 py-3 text-slate-700 hover:bg-slate-100 rounded-lg font-semibold">Advantages</a>
                    <a href="#testimonials" class="block px-4 py-3 text-slate-700 hover:bg-slate-100 rounded-lg font-semibold">Testimonials</a>
                    <a href="#contact" class="block px-4 py-3 text-slate-700 hover:bg-slate-100 rounded-lg font-semibold">Contact</a>
                    <hr class="my-3 border-slate-200">
                    <a href="{{ route('login') }}" class="block px-4 py-3 text-slate-700 border-2 border-slate-300 rounded-lg font-semibold text-center">
                        Login
                    </a>
                    <a href="{{ route('register') }}" class="block px-4 py-3 bg-gradient-to-r from-blue-700 to-blue-600 text-white rounded-lg font-semibold text-center">
                        Get Started
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
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            <span class="text-sm font-bold tracking-wide">#1 China Sourcing Partner</span>
                        </div>

                        <!-- Heading -->
                        <h1 class="text-5xl sm:text-6xl lg:text-7xl font-bold text-slate-900 leading-tight tracking-tight">
                            Your Trusted
                            <span class="block gradient-text mt-2">
                                China Sourcing Partner
                            </span>
                        </h1>

                        <!-- Description -->
                        <p class="text-lg sm:text-xl text-slate-600 leading-relaxed font-medium">
                            FastSourcingBrothers connects you directly with China's top manufacturers. We manage your entire supply chain: supplier research, negotiation, quality control, and international logistics.
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
                                    <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-sm font-semibold text-slate-700">Quality Control</span>
                            </div>
                            <div class="flex items-center space-x-2 bg-white px-5 py-3 rounded-xl border border-slate-200 shadow-sm enterprise-card">
                                <svg class="w-5 h-5 text-blue-700" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-sm font-semibold text-slate-700">Guaranteed Delivery</span>
                            </div>
                        </div>

                        <!-- Buttons -->
                        <div class="flex flex-col sm:flex-row gap-4 pt-6">
                            <a href="#contact" class="px-8 py-4 bg-gradient-to-r from-blue-700 to-blue-600 text-white rounded-xl hover:shadow-xl hover:scale-105 font-semibold text-center flex items-center justify-center space-x-2 active:scale-95 touch-button shadow-lg shadow-blue-600/25">
                                <span>Request Free Quote</span>
                                <svg class="w-5 h-5 hidden sm:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                </svg>
                            </a>
                            <a href="#process" class="px-8 py-4 glass text-slate-700 rounded-xl border-2 border-slate-300 hover:border-blue-600 hover:shadow-lg transition-all font-semibold text-center active:scale-95 touch-button">
                                Discover Our Process
                            </a>
                        </div>

                        <!-- Stats -->
                        <div class="grid grid-cols-3 gap-4 pt-8">
                            <div class="enterprise-card p-6 rounded-xl text-center card-hover">
                                <div class="text-3xl sm:text-4xl font-bold gradient-text counter" data-target="500">0</div>
                                <div class="text-sm text-slate-600 mt-2 font-semibold">Clients</div>
                            </div>
                            <div class="enterprise-card p-6 rounded-xl text-center card-hover">
                                <div class="text-3xl sm:text-4xl font-bold gradient-text counter" data-target="2000">0</div>
                                <div class="text-sm text-slate-600 mt-2 font-semibold">Suppliers</div>
                            </div>
                            <div class="enterprise-card p-6 rounded-xl text-center card-hover">
                                <div class="text-3xl sm:text-4xl font-bold gradient-text counter" data-target="98">0</div>
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
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="0.5" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Services Section -->
        <section id="services" class="section-spacing px-4 sm:px-6 lg:px-8 glass border-y border-slate-200/80">
            <div class="max-w-7xl mx-auto">
                <div class="text-center mb-16 scroll-reveal">
                    <div class="inline-flex items-center space-x-2 bg-blue-100 text-blue-800 px-5 py-2.5 rounded-full mb-6 border border-blue-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        <span class="text-sm font-bold tracking-wide">Our Services</span>
                    </div>
                    <h2 class="text-4xl sm:text-5xl font-bold text-slate-900 mb-6 tracking-tight">
                        Complete Sourcing Solution
                    </h2>
                    <p class="text-xl text-slate-600 max-w-3xl mx-auto font-medium">
                        From supplier research to final delivery, we manage every step of your China supply chain
                    </p>
                </div>

                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <!-- Service 1 -->
                    <div class="group p-8 enterprise-card rounded-2xl card-hover scroll-reveal">
                        <div class="w-14 h-14 bg-gradient-to-br from-blue-700 to-blue-600 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform shadow-md shadow-blue-600/25">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-4 tracking-tight">Supplier Research</h3>
                        <p class="text-slate-600 leading-relaxed">Identification and selection of China's best manufacturers tailored to your specific needs. Access to our network of 2000+ verified suppliers.</p>
                    </div>

                    <!-- Service 2 -->
                    <div class="group p-8 enterprise-card rounded-2xl card-hover scroll-reveal">
                        <div class="w-14 h-14 bg-gradient-to-br from-blue-700 to-blue-600 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform shadow-md shadow-blue-600/25">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-4 tracking-tight">Negotiation & Contracts</h3>
                        <p class="text-slate-600 leading-relaxed">Negotiation of best prices and terms. Complete contract management and protection of your commercial interests with local expertise.</p>
                    </div>

                    <!-- Service 3 -->
                    <div class="group p-8 enterprise-card rounded-2xl card-hover scroll-reveal">
                        <div class="w-14 h-14 bg-gradient-to-br from-blue-700 to-blue-600 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform shadow-md shadow-blue-600/25">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-4 tracking-tight">Quality Control</h3>
                        <p class="text-slate-600 leading-relaxed">Rigorous factory inspections before shipment. Compliance verification with international standards and your specifications.</p>
                    </div>

                    <!-- Service 4 -->
                    <div class="group p-8 enterprise-card rounded-2xl card-hover scroll-reveal">
                        <div class="w-14 h-14 bg-gradient-to-br from-blue-700 to-blue-600 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform shadow-md shadow-blue-600/25">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-4 tracking-tight">International Logistics</h3>
                        <p class="text-slate-600 leading-relaxed">Complete management of sea, air, or rail transport. Customs clearance and delivery to your warehouse with real-time tracking.</p>
                    </div>

                    <!-- Service 5 -->
                    <div class="group p-8 enterprise-card rounded-2xl card-hover scroll-reveal">
                        <div class="w-14 h-14 bg-gradient-to-br from-blue-700 to-blue-600 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform shadow-md shadow-blue-600/25">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-4 tracking-tight">Financial Management</h3>
                        <p class="text-slate-600 leading-relaxed">Secure payments, currency management, and protection against financial risks. Guaranteed secure payment.</p>
                    </div>

                    <!-- Service 6 -->
                    <div class="group p-8 enterprise-card rounded-2xl card-hover scroll-reveal">
                        <div class="w-14 h-14 bg-gradient-to-br from-blue-700 to-blue-600 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform shadow-md shadow-blue-600/25">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-4 tracking-tight">Continuous Support</h3>
                        <p class="text-slate-600 leading-relaxed">Personalized support throughout your project. Bilingual team available to answer all your questions.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Process Section -->
        <section id="process" class="section-spacing px-4 sm:px-6 lg:px-8 relative">
            <div class="max-w-7xl mx-auto">
                <div class="text-center mb-16 scroll-reveal">
                    <div class="inline-flex items-center space-x-2 bg-blue-100 text-blue-800 px-5 py-2.5 rounded-full mb-6 border border-blue-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                        <span class="text-sm font-bold tracking-wide">Our Process</span>
                    </div>
                    <h2 class="text-4xl sm:text-5xl font-bold text-slate-900 mb-6 tracking-tight">
                        How We Work
                    </h2>
                    <p class="text-xl text-slate-600 max-w-3xl mx-auto font-medium">
                        A simple and transparent 6-step process to guarantee your success
                    </p>
                </div>

                <div class="relative">
                    <!-- Timeline line -->
                    <div class="hidden lg:block absolute left-1/2 transform -translate-x-1/2 w-1 h-full bg-gradient-to-b from-blue-700 to-blue-600"></div>

                    <!-- Steps -->
                    <div class="space-y-16">
                        <!-- Step 1 -->
                        <div class="relative grid lg:grid-cols-2 gap-8 items-center scroll-reveal">
                            <div class="lg:text-right">
                                <div class="inline-block lg:block enterprise-card p-8 rounded-2xl card-hover">
                                    <div class="flex lg:flex-row-reverse items-start gap-6">
                                        <div class="w-14 h-14 bg-gradient-to-br from-blue-700 to-blue-600 rounded-xl flex items-center justify-center flex-shrink-0 shadow-md">
                                            <span class="text-white font-bold text-2xl">1</span>
                                        </div>
                                        <div class="flex-1">
                                            <h3 class="text-2xl font-bold text-slate-900 mb-3 tracking-tight">Needs Analysis</h3>
                                            <p class="text-slate-600 leading-relaxed">We study your needs, specifications, and objectives in detail to identify the best suppliers.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="hidden lg:block"></div>
                        </div>

                        <!-- Step 2 -->
                        <div class="relative grid lg:grid-cols-2 gap-8 items-center scroll-reveal">
                            <div class="hidden lg:block"></div>
                            <div>
                                <div class="enterprise-card p-8 rounded-2xl card-hover">
                                    <div class="flex items-start gap-6">
                                        <div class="w-14 h-14 bg-gradient-to-br from-blue-700 to-blue-600 rounded-xl flex items-center justify-center flex-shrink-0 shadow-md">
                                            <span class="text-white font-bold text-2xl">2</span>
                                        </div>
                                        <div class="flex-1">
                                            <h3 class="text-2xl font-bold text-slate-900 mb-3 tracking-tight">Research & Selection</h3>
                                            <p class="text-slate-600 leading-relaxed">Identification of the most suitable manufacturers in our network of 2000+ verified suppliers in China.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Step 3 -->
                        <div class="relative grid lg:grid-cols-2 gap-8 items-center scroll-reveal">
                            <div class="lg:text-right">
                                <div class="inline-block lg:block enterprise-card p-8 rounded-2xl card-hover">
                                    <div class="flex lg:flex-row-reverse items-start gap-6">
                                        <div class="w-14 h-14 bg-gradient-to-br from-blue-700 to-blue-600 rounded-xl flex items-center justify-center flex-shrink-0 shadow-md">
                                            <span class="text-white font-bold text-2xl">3</span>
                                        </div>
                                        <div class="flex-1">
                                            <h3 class="text-2xl font-bold text-slate-900 mb-3 tracking-tight">Negotiation & Quotes</h3>
                                            <p class="text-slate-600 leading-relaxed">Negotiation of best prices and terms. Presentation of detailed and transparent quotes for validation.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="hidden lg:block"></div>
                        </div>

                        <!-- Step 4 -->
                        <div class="relative grid lg:grid-cols-2 gap-8 items-center scroll-reveal">
                            <div class="hidden lg:block"></div>
                            <div>
                                <div class="enterprise-card p-8 rounded-2xl card-hover">
                                    <div class="flex items-start gap-6">
                                        <div class="w-14 h-14 bg-gradient-to-br from-blue-700 to-blue-600 rounded-xl flex items-center justify-center flex-shrink-0 shadow-md">
                                            <span class="text-white font-bold text-2xl">4</span>
                                        </div>
                                        <div class="flex-1">
                                            <h3 class="text-2xl font-bold text-slate-900 mb-3 tracking-tight">Production & Monitoring</h3>
                                            <p class="text-slate-600 leading-relaxed">Production launch with regular monitoring. Inspections during manufacturing to ensure quality.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Step 5 -->
                        <div class="relative grid lg:grid-cols-2 gap-8 items-center scroll-reveal">
                            <div class="lg:text-right">
                                <div class="inline-block lg:block enterprise-card p-8 rounded-2xl card-hover">
                                    <div class="flex lg:flex-row-reverse items-start gap-6">
                                        <div class="w-14 h-14 bg-gradient-to-br from-blue-700 to-blue-600 rounded-xl flex items-center justify-center flex-shrink-0 shadow-md">
                                            <span class="text-white font-bold text-2xl">5</span>
                                        </div>
                                        <div class="flex-1">
                                            <h3 class="text-2xl font-bold text-slate-900 mb-3 tracking-tight">Final Quality Control</h3>
                                            <p class="text-slate-600 leading-relaxed">Complete inspection before shipment. Compliance verification and quality tests according to your standards.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="hidden lg:block"></div>
                        </div>

                        <!-- Step 6 -->
                        <div class="relative grid lg:grid-cols-2 gap-8 items-center scroll-reveal">
                            <div class="hidden lg:block"></div>
                            <div>
                                <div class="enterprise-card p-8 rounded-2xl card-hover">
                                    <div class="flex items-start gap-6">
                                        <div class="w-14 h-14 bg-gradient-to-br from-blue-700 to-blue-600 rounded-xl flex items-center justify-center flex-shrink-0 shadow-md">
                                            <span class="text-white font-bold text-2xl">6</span>
                                        </div>
                                        <div class="flex-1">
                                            <h3 class="text-2xl font-bold text-slate-900 mb-3 tracking-tight">Delivery & After-Sales</h3>
                                            <p class="text-slate-600 leading-relaxed">Secure shipping and delivery. After-sales service and continuous support for your complete satisfaction.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Advantages Section -->
        <section id="advantages" class="section-spacing px-4 sm:px-6 lg:px-8 glass border-y border-slate-200/80">
            <div class="max-w-7xl mx-auto">
                <div class="grid lg:grid-cols-2 gap-16 items-center">
                    <div class="scroll-reveal">
                        <div class="inline-flex items-center space-x-2 bg-green-100 text-green-800 px-5 py-2.5 rounded-full mb-8 border border-green-200">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                            </svg>
                            <span class="text-sm font-bold tracking-wide">Our Advantages</span>
                        </div>
                        <h2 class="text-4xl sm:text-5xl font-bold text-slate-900 mb-8 tracking-tight">
                            Why Choose FastSourcingBrothers?
                        </h2>
                        <p class="text-xl text-slate-600 mb-10 leading-relaxed">
                            Our expertise in the Chinese market and our network of verified suppliers guarantee you the best conditions for your sourcing.
                        </p>
                        <div class="space-y-8">
                            <div class="flex items-start space-x-5">
                                <div class="w-14 h-14 bg-green-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                    <svg class="w-7 h-7 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-bold text-slate-900 mb-2 text-lg">Savings up to 60%</h4>
                                    <p class="text-slate-600 leading-relaxed">Significant reduction in your sourcing costs through our direct negotiations with manufacturers</p>
                                </div>
                            </div>
                            <div class="flex items-start space-x-5">
                                <div class="w-14 h-14 bg-blue-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                    <svg class="w-7 h-7 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-bold text-slate-900 mb-2 text-lg">Considerable Time Savings</h4>
                                    <p class="text-slate-600 leading-relaxed">We manage the entire process so you can focus on your core business</p>
                                </div>
                            </div>
                            <div class="flex items-start space-x-5">
                                <div class="w-14 h-14 bg-blue-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                    <svg class="w-7 h-7 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-bold text-slate-900 mb-2 text-lg">Guaranteed Quality</h4>
                                    <p class="text-slate-600 leading-relaxed">Rigorous quality controls at every stage and guaranteed compliance with international standards</p>
                                </div>
                            </div>
                            <div class="flex items-start space-x-5">
                                <div class="w-14 h-14 bg-blue-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                    <svg class="w-7 h-7 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-bold text-slate-900 mb-2 text-lg">Local Expertise</h4>
                                    <p class="text-slate-600 leading-relaxed">Bilingual team based in China with in-depth knowledge of the market and local culture</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="scroll-reveal">
                        <div class="enterprise-card p-10 rounded-2xl shadow-lg">
                            <h3 class="text-3xl font-bold text-slate-900 mb-8 tracking-tight">Average Client Results</h3>
                            <div class="space-y-8">
                                <div>
                                    <div class="flex justify-between mb-3">
                                        <span class="text-slate-700 font-semibold text-lg">Cost Reduction</span>
                                        <span class="text-green-700 font-bold text-lg">-60%</span>
                                    </div>
                                    <div class="w-full bg-slate-200 rounded-full h-4">
                                        <div class="bg-gradient-to-r from-green-600 to-green-700 h-4 rounded-full" style="width: 60%"></div>
                                    </div>
                                </div>
                                <div>
                                    <div class="flex justify-between mb-3">
                                        <span class="text-slate-700 font-semibold text-lg">Time Savings</span>
                                        <span class="text-blue-700 font-bold text-lg">+75%</span>
                                    </div>
                                    <div class="w-full bg-slate-200 rounded-full h-4">
                                        <div class="bg-gradient-to-r from-blue-600 to-blue-700 h-4 rounded-full" style="width: 75%"></div>
                                    </div>
                                </div>
                                <div>
                                    <div class="flex justify-between mb-3">
                                        <span class="text-slate-700 font-semibold text-lg">Quality Improvement</span>
                                        <span class="text-blue-700 font-bold text-lg">+85%</span>
                                    </div>
                                    <div class="w-full bg-slate-200 rounded-full h-4">
                                        <div class="bg-gradient-to-r from-blue-600 to-blue-700 h-4 rounded-full" style="width: 85%"></div>
                                    </div>
                                </div>
                                <div>
                                    <div class="flex justify-between mb-3">
                                        <span class="text-slate-700 font-semibold text-lg">Client Satisfaction</span>
                                        <span class="text-blue-700 font-bold text-lg">98%</span>
                                    </div>
                                    <div class="w-full bg-slate-200 rounded-full h-4">
                                        <div class="bg-gradient-to-r from-blue-600 to-blue-700 h-4 rounded-full" style="width: 98%"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-10 p-8 bg-gradient-to-r from-blue-50 to-blue-100 rounded-xl border border-blue-200">
                                <p class="text-sm text-slate-600 mb-2 font-semibold">Average ROI</p>
                                <p class="text-5xl font-bold gradient-text">350%</p>
                                <p class="text-sm text-slate-600 mt-3">In the first year</p>
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
                        <span class="text-sm font-bold tracking-wide">Client Testimonials</span>
                    </div>
                    <h2 class="text-4xl sm:text-5xl font-bold text-slate-900 mb-6 tracking-tight">
                        What Our Clients Say
                    </h2>
                    <p class="text-xl text-slate-600 max-w-3xl mx-auto font-medium">
                        Discover how FastSourcingBrothers has helped companies like yours succeed in China sourcing
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
                        <p class="text-slate-600 mb-8 italic leading-relaxed">"FastSourcingBrothers transformed our supply chain. We reduced costs by 55% while improving quality. Their team is professional and responsive."</p>
                        <div class="flex items-center">
                            <div class="w-14 h-14 bg-gradient-to-br from-blue-600 to-blue-700 rounded-full flex items-center justify-center text-white font-bold mr-4">
                                JD
                            </div>
                            <div>
                                <p class="font-bold text-slate-900 text-lg">Ayman DAOUDE</p>
                                <p class="text-sm text-slate-600">Client</p>
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
                        <p class="text-slate-600 mb-8 italic leading-relaxed">"Exceptional service from start to finish. Quality controls are rigorous and communication is excellent. Highly recommend!"</p>
                        <div class="flex items-center">
                            <div class="w-14 h-14 bg-gradient-to-br from-blue-600 to-blue-700 rounded-full flex items-center justify-center text-white font-bold mr-4">
                                SM
                            </div>
                            <div>
                                <p class="font-bold text-slate-900 text-lg">Amin SAMI</p>
                                <p class="text-sm text-slate-600">Client</p>
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
                        <p class="text-slate-600 mb-8 italic leading-relaxed">"Thanks to FastSourcingBrothers, we found reliable suppliers and saved tremendously. Their expertise in the Chinese market is invaluable."</p>
                        <div class="flex items-center">
                            <div class="w-14 h-14 bg-gradient-to-br from-blue-600 to-blue-700 rounded-full flex items-center justify-center text-white font-bold mr-4">
                                PW
                            </div>
                            <div>
                                <p class="font-bold text-slate-900 text-lg">ahmad MARWAN</p>
                                <p class="text-sm text-slate-600">Client</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section id="contact" class="section-spacing px-4 sm:px-6 lg:px-8 relative">
            <div class="max-w-5xl mx-auto">
                <div class="relative bg-gradient-to-r from-blue-700 to-blue-600 rounded-3xl p-12 sm:p-16 text-center shadow-2xl overflow-hidden scroll-reveal">
                    <div class="absolute inset-0 bg-gradient-to-br from-blue-600/20 to-transparent"></div>
                    <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjAiIGhlaWdodD0iNjAiIHZpZXdCb3g9IjAgMCA2MCA2MCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48ZyBmaWxsPSJub25lIiBmaWxsLXJ1bGU9ImV2ZW5vZGQiPjxnIGZpbGw9IiNmZmZmZmYiIGZpbGwtb3BhY2l0eT0iMC4wOCI+PHBhdGggZD0iTTM2IDM0YzAtMi4yMSAxLjc5LTQgNC00czQgMS43OSA0IDQtMS43OSA0LTQgNC00LTEuNzktNC00em0wIDEwYzAtMi4yMSAxLjc5LTQgNC00czQgMS43OSA0IDQtMS43OSA0LTQgNC00LTEuNzktNC00eiIvPjwvZz48L2c+PC9zdmc+')] opacity-20"></div>
                    <div class="relative z-10">
                        <h2 class="text-4xl sm:text-5xl font-bold text-white mb-6 tracking-tight">
                            Ready to Optimize Your China Sourcing?
                        </h2>
                        <p class="text-xl text-blue-100 mb-10 max-w-2xl mx-auto leading-relaxed">
                            Join over 500 clients that have already trusted FastSourcingBrothers for their China sourcing needs.
                        </p>
                        <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                            <a href="{{ route('register') }}" class="inline-flex items-center px-10 py-5 bg-white text-blue-700 rounded-xl hover:shadow-2xl hover:scale-105 transition-all font-bold text-lg">
                                <span>Request Free Quote</span>
                                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                </svg>
                            </a>
                            <a href="tel:{{ config('app.phone') }}" class="inline-flex items-center px-10 py-5 bg-transparent text-white rounded-xl border-2 border-white hover:bg-white hover:text-blue-700 transition-all font-bold text-lg">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                                <span>Call Us</span>
                            </a>
                        </div>
                        <p class="text-sm text-blue-100 mt-8 font-medium">
                            ✓ Free quote within 24h • ✓ No commitment • ✓ Personalized consultation
                        </p>
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
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <span class="text-xl font-bold text-white tracking-tight">FastSourcingBrothers</span>
                            <span class="block text-xs text-slate-400 font-semibold">Sourcing Expert from China</span>
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

                    <!-- Location -->
                    <div class="flex items-center space-x-2 text-slate-400">
                        <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span class="text-sm font-medium">Agadir, maroc</span>
                    </div>
                </div>

                <!-- Copyright -->
                <div class="border-t border-slate-800 mt-8 pt-8 text-center">
                    <p class="text-sm text-slate-400">&copy; 2025 FastSourcingBrothers. All rights reserved.</p>
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