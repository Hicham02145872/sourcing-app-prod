<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    
    <!-- Facebook Pixel -->
    <x-facebook-pixel/>
    
    <link rel="icon" type="image/png" href="/favicon-96x96.png" sizes="96x96" />
    <link rel="icon" type="image/svg+xml" href="/favicon.svg" />
    <link rel="shortcut icon" href="/favicon.ico" />
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png" />
    <link rel="manifest" href="/site.webmanifest" />
    <title>FastSourcingBrothers - Simplify Your Sourcing Process</title>
    
    <!-- Font: Inter & Style Script & Montserrat -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;800&family=Style+Script&family=Montserrat:wght@300;400;600&display=swap" rel="stylesheet">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
    <script src="https://unpkg.com/typed.js@2.1.0/dist/typed.umd.js"></script>
    
    <script>
            tailwind.config = {
                theme: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    extend: {
                        colors: {
                            primary: {
                                50: '#fef2f2',
                                100: '#fee2e2',
                                200: '#fecaca',
                                300: '#fca5a5',
                                400: '#f87171',
                                500: '#ef4444',
                                600: '#dc2626',
                                700: '#b91c1c',
                                800: '#991b1b',
                                900: '#7f1d1d',
                            }
                        }
                    }
                }
            }
    </script>
    <style>
        /* Base optimizations */
        body {
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            overflow-x: hidden;
        }

        /* Three.js Background */
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

        /* Modern Glassmorphism */
        .glass-panel {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.5);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
        }

        .glass-nav {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(226, 232, 240, 0.6);
        }

        /* Text Gradients */
        .gradient-text {
            background: linear-gradient(135deg, #dc2626 0%, #ca8a04 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Modern Cards */
        .pro-card {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 1rem;
            transition: all 0.3s ease;
        }
        .pro-card:hover {
            border-color: #fca5a5;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05), 0 10px 10px -5px rgba(0, 0, 0, 0.01);
            transform: translateY(-4px);
        }

        /* Image Framing - "Enterprise Look" */
        .enterprise-frame {
            position: relative;
            border-radius: 1rem;
            overflow: hidden;
            background: white;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.8);
            transform: translateZ(0); /* Hardware acceleration */
        }
        
        .enterprise-frame img {
            transition: transform 0.7s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .enterprise-frame:hover img {
            transform: scale(1.03);
        }

        /* Browser Mockup Header */
        .browser-header {
            height: 32px;
            background: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            padding: 0 12px;
            gap: 6px;
        }
        .dot { width: 10px; height: 10px; border-radius: 50%; }
        .dot-red { background: #ef4444; }
        .dot-yellow { background: #f59e0b; }
        .dot-green { background: #22c55e; }

        /* Animations */
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .reveal {
            opacity: 0;
            animation: fadeUp 0.8s ease-out forwards;
        }
        .delay-100 { animation-delay: 0.1s; }
        .delay-200 { animation-delay: 0.2s; }
        .delay-300 { animation-delay: 0.3s; }


        /* ═══════════ Splash Screen Animations ═══════════ */
        #splash-screen {
            position: fixed;
            inset: 0;
            z-index: 99999;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #ffffff;
            overflow: hidden;
            transition: opacity 0.8s cubic-bezier(0.7, 0, 0.3, 1);
        }
        
        /* Premium Mesh Background */
        #splash-screen::before {
            content: "";
            position: absolute;
            inset: 0;
            background: 
                radial-gradient(circle at 20% 20%, rgba(239, 119, 34, 0.05) 0%, transparent 40%),
                radial-gradient(circle at 80% 80%, rgba(250, 165, 51, 0.05) 0%, transparent 40%);
            opacity: 0.8;
            filter: blur(60px);
            animation: meshMove 8s ease-in-out infinite alternate;
        }

        @keyframes meshMove {
            from { transform: scale(1) translate(0, 0); }
            to { transform: scale(1.1) translate(2%, 2%); }
        }

        #splash-screen.splash-hidden { opacity: 0; pointer-events: none; }

        .splash-content {
            position: relative;
            z-index: 10;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 32px;
            text-align: center;
        }

        /* Logo & Laser Container */
        .logo-container {
            position: relative;
            padding: 20px;
        }

        .splash-logo {
            opacity: 0;
            transform: scale(0.9) translateY(30px);
            animation: splashLogoReveal 0.5s cubic-bezier(0.7, 0, 0.3, 1) 0.1s forwards;
            filter: drop-shadow(0 20px 40px rgba(0,0,0,0.05));
        }

        /* Modern Scanning Laser */
        .laser-line {
            position: absolute;
            top: 20px;
            left: 20px;
            right: 20px;
            height: 2px;
            background: linear-gradient(90deg, transparent, #EF7722, transparent);
            box-shadow: 0 0 15px rgba(239,119,34,0.8);
            opacity: 0;
            z-index: 20;
            animation: laserScan 0.5s ease-in-out 0.2s forwards;
        }

        @keyframes laserScan {
            0% { top: 10%; opacity: 0; }
            50% { opacity: 1; }
            100% { top: 90%; opacity: 0; }
        }

        @keyframes splashLogoReveal {
            to { opacity: 1; transform: scale(1) translateY(0); }
        }

        /* Brand Text Styling */
        #splash-brand {
            font-family: 'Style Script', cursive;
            font-size: 56px;
            line-height: 1.2;
            color: #0f172a;
            margin-top: 10px;
        }

        .splash-char {
            display: inline-block;
            opacity: 0;
            transform: translateX(-10px);
            filter: blur(5px);
            animation: charReveal 0.6s cubic-bezier(0.7, 0, 0.3, 1) forwards;
        }

        @keyframes charReveal {
            to { opacity: 1; transform: translateX(0); filter: blur(0); }
        }

        /* Tech Status Text */
        .splash-status-container {
            margin-top: 15px;
            height: 20px;
            overflow: hidden;
        }

        #splash-status {
            font-family: 'Montserrat', sans-serif;
            font-size: 10px;
            font-weight: 600;
            letter-spacing: 3px;
            text-transform: uppercase;
            color: #64748b;
            opacity: 0;
            animation: fadeIn 0.3s ease 0.2s forwards;
        }

        @keyframes fadeIn { to { opacity: 1; } }

        /* Professional Progress Bar */
        .progress-container {
            position: absolute;
            bottom: 60px;
            width: 320px;
            height: 2px;
            background: #f1f5f9;
            border-radius: 4px;
            overflow: hidden;
        }

        .splash-progress-inner {
            height: 100%;
            width: 0%;
            background: linear-gradient(90deg, #EF7722, #FAA533);
            box-shadow: 0 0 10px rgba(239,119,34,0.4);
            animation: progressFill 0.8s cubic-bezier(0.7, 0, 0.3, 1) forwards;
        }

        @keyframes progressFill {
            0% { width: 0%; }
            50% { width: 60%; }
            100% { width: 100%; }
        }

        /* Animated connection lines */
        .connection-dot {
            position: absolute;
            width: 10px; height: 10px;
            background: rgba(239,119,34,0.25);
            border-radius: 50%;
            filter: blur(1px);
            animation: orbit 20s linear infinite;
        }

        @keyframes orbit {
            from { transform: rotate(0deg) translateX(150px) rotate(0deg); }
            to { transform: rotate(360deg) translateX(150px) rotate(-360deg); }
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-900 selection:bg-red-100 selection:text-red-900">
    
    {{-- ═══════════ Splash Screen Overlay ═══════════ --}}
    <div id="splash-screen">
        {{-- Connection Dots (Background) --}}
        <div class="connection-dot" style="top:25%; left:30%; animation-delay: 0s;"></div>
        <div class="connection-dot" style="top:65%; left:70%; animation-delay: -5s;"></div>
        <div class="connection-dot" style="top:40%; left:85%; animation-delay: -10s;"></div>
        <div class="connection-dot" style="top:80%; left:15%; animation-delay: -15s;"></div>

        <div class="splash-content">
            <div class="logo-container">
                <div class="laser-line"></div>
                <img src="{{ asset('images/logo1.png') }}" alt="FastSourcingBrothers" class="splash-logo" style="width:420px;height:auto;">
            </div>

            <div id="splash-brand"></div>

            <div class="splash-status-container">
                <div id="splash-status">INITIALIZING GLOBAL NETWORK...</div>
            </div>
        </div>

        <div class="progress-container">
            <div class="splash-progress-inner"></div>
        </div>
    </div>

    <script>
        (function() {
            var brand = document.getElementById('splash-brand');
            var statusEl = document.getElementById('splash-status');
            var text = 'FastSourcingBrothers';
            var statusMessages = [
                'SECURE SUPPLY CHAIN ACTIVE',
                'VERIFYING GLOBAL PARTNERS',
                'OPTIMIZING LANDED COSTS',
                'SENSING MARKET DYNAMICS',
                'FASTSOURCING BROTHERS LIVE'
            ];
            
            // Build brand characters
            for (var i = 0; i < text.length; i++) {
                var span = document.createElement('span');
                span.className = 'splash-char';
                span.textContent = text[i];
                span.style.animationDelay = (0.3 + i * 0.02) + 's';
                if (i === 0 || i === 4 || i === 12) {
                    span.style.color = '#EF7722';
                }
                brand.appendChild(span);
            }

            // Sync status messages
            var msgIndex = 0;
            var statusInterval = setInterval(function() {
                msgIndex = (msgIndex + 1) % statusMessages.length;
                statusEl.style.opacity = 0;
                setTimeout(function() {
                    statusEl.textContent = statusMessages[msgIndex];
                    statusEl.style.opacity = 1;
                }, 100);
            }, 300);

            // Dismiss after 1 second
            setTimeout(function() {
                var s = document.getElementById('splash-screen');
                if (s) {
                    clearInterval(statusInterval);
                    s.classList.add('splash-hidden');
                    setTimeout(function() { s.remove(); }, 300);
                }
            }, 1000);
        })();
    </script>
    {{-- ═══════════ End Splash Screen ═══════════ --}}

    <!-- Three.js Canvas -->
    <canvas id="three-canvas"></canvas>

    <div class="content-wrapper">
        
        <!-- Modern Floating Navbar -->
        <div class="fixed top-0 left-0 right-0 z-50 pt-4 px-4 flex justify-center">
            <nav class="glass-nav w-full max-w-6xl rounded-2xl shadow-sm border border-white/50 px-6 h-16 flex items-center justify-between transition-all duration-300">
                <!-- Logo -->
                <a href="/" class="flex-shrink-0 flex items-center gap-2">
                    <img class="h-20 w-20" src="{{ asset('images/logo1.png') }}" alt="{{ config('app.name') }}">
                </a>

                <!-- Desktop Menu -->
                <div class="hidden lg:flex items-center space-x-1">
                    <a href="#how-it-works" class="px-4 py-2 text-sm font-medium text-slate-600 hover:text-red-600 rounded-lg hover:bg-red-50 transition-colors">Process</a>
                    <a href="#benefits" class="px-4 py-2 text-sm font-medium text-slate-600 hover:text-red-600 rounded-lg hover:bg-red-50 transition-colors">Benefits</a>
                    <a href="#testimonials" class="px-4 py-2 text-sm font-medium text-slate-600 hover:text-red-600 rounded-lg hover:bg-red-50 transition-colors">Reviews</a>
                </div>

                <!-- Auth & Mobile Toggle -->
                <div class="flex items-center gap-3">
                    @auth
                        <a href="{{ route('dashboard') }}" class="px-5 py-2.5 bg-slate-900 text-white text-sm font-semibold rounded-xl hover:bg-slate-800 transition-all shadow-lg shadow-slate-900/10">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="hidden sm:inline-block px-4 py-2 text-sm font-medium text-slate-700 hover:text-red-600">Login</a>
                        <a href="{{ route('register') }}" class="px-5 py-2.5 bg-slate-900 text-white text-sm font-semibold rounded-xl hover:bg-red-600 transition-all shadow-lg shadow-slate-900/10">
                            Get Started
                        </a>
                    @endauth
                    <button onclick="toggleMenu()" class="lg:hidden p-2 text-slate-600 hover:bg-slate-100 rounded-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>
                </div>
            </nav>
        </div>

        <!-- Mobile Menu Overlay -->
        <div id="mobileMenu" class="hidden fixed inset-0 z-40 bg-white/95 backdrop-blur-xl pt-24 px-6">
            <div class="flex flex-col space-y-4 text-center">
                <a href="#how-it-works" class="text-xl font-medium text-slate-900 py-2 border-b border-slate-100">How It Works</a>
                <a href="#benefits" class="text-xl font-medium text-slate-900 py-2 border-b border-slate-100">Benefits</a>
                <a href="#testimonials" class="text-xl font-medium text-slate-900 py-2 border-b border-slate-100">Testimonials</a>
                @auth
                    <a href="{{ route('dashboard') }}" class="text-xl font-medium text-slate-900 py-2">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="text-xl font-medium text-slate-900 py-2">Login</a>
                @endauth
                <button onclick="toggleMenu()" class="absolute top-6 right-6 p-2 bg-slate-100 rounded-full">
                    <svg class="w-6 h-6 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </div>

        <!-- Hero Section (Clean & Modern Layout) -->
        <section class="pt-40 pb-20 px-4 sm:px-6 relative">
            <div class="max-w-7xl mx-auto">
                <div class="grid lg:grid-cols-2 gap-16 lg:gap-24 items-center">
                    
                    <!-- Text Content -->
                    <div class="text-center lg:text-left reveal">
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-red-50 border border-red-100 text-red-700 text-xs font-bold uppercase tracking-wide mb-6">
                            <span class="w-2 h-2 rounded-full bg-red-500 animate-pulse"></span>
                            Trusted by 200+ 
                        </div>

                        <h1 class="text-5xl sm:text-6xl lg:text-7xl font-extrabold text-slate-900 tracking-tight leading-[1.1] mb-6">
                            Sourcing made <br>
                            <span class="gradient-text" id="typed-text"></span>
                        </h1>

                        <p class="text-lg text-slate-600 leading-relaxed mb-8 max-w-lg mx-auto lg:mx-0">
                            Stop wrestling with complex supply chains. We verify suppliers, negotiate prices, and handle logistics so you can scale faster.
                        </p>

                        <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                            <a href="{{ route('register') }}" class="px-8 py-4 bg-red-600 text-white rounded-xl font-semibold hover:bg-red-700 transition-all shadow-xl shadow-red-500/20 hover:scale-[1.02]">
                                Start Your Request
                            </a>
                            <a href="#how-it-works" class="px-8 py-4 bg-white text-slate-700 border border-slate-200 rounded-xl font-semibold hover:border-slate-300 hover:bg-slate-50 transition-all">
                                How It Works
                            </a>
                        </div>

                        <!-- Stats Strip -->
                        <div class="mt-12 pt-8 border-t border-slate-200 grid grid-cols-3 gap-8">
                            <div>
                                <div class="text-2xl font-bold text-slate-900 counter" data-target="200">0</div>
                                <div class="text-xs text-slate-500 uppercase font-medium mt-1">Clients</div>
                            </div>
                            <div>
                                <div class="text-2xl font-bold text-slate-900 counter" data-target="2000">0</div>
                                <div class="text-xs text-slate-500 uppercase font-medium mt-1">Suppliers</div>
                            </div>
                            <div>
                                <div class="text-2xl font-bold text-slate-900 counter" data-target="90">0</div>
                                <div class="text-xs text-slate-500 uppercase font-medium mt-1">Satisfaction</div>
                            </div>
                        </div>
                    </div>

                    <!-- Hero Image (Enterprise Frame) -->
                    <div class="relative reveal delay-200">
                        <!-- Decorative Blob -->
                        <div class="absolute -top-12 -right-12 w-64 h-64 bg-yellow-400/10 rounded-full blur-3xl"></div>
                        <div class="absolute -bottom-12 -left-12 w-64 h-64 bg-red-400/10 rounded-full blur-3xl"></div>

                        <div class="enterprise-frame transform rotate-1 hover:rotate-0 transition-transform duration-500">
                            <img src="{{ asset('images/fsb-team.jpg') }}" alt="Fast Sourcing Brothers Team" class="w-full h-auto object-cover">
                            
                            <!-- Floating Status Card -->
                            <div class="absolute bottom-6 left-6 right-6 bg-white/95 backdrop-blur-md p-4 rounded-xl shadow-lg border border-slate-100 flex items-center gap-4">
                                <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-slate-900">Verified Experts</p>
                                    <p class="text-xs text-slate-500">Active Support Team</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        
        <!-- Trust Bar (Payment & Shipping) -->
        <section class="py-12 bg-white border-y border-slate-100 reveal delay-300">
            <div class="max-w-7xl mx-auto px-4">
                <div class="flex flex-col items-center gap-10">
                    <p class="text-[11px] font-bold uppercase tracking-[0.2em] text-slate-400">Secure Payments & Global Logistics Partners</p>
                    
                    <div class="flex flex-wrap items-center justify-center gap-10 md:gap-20 opacity-40 grayscale hover:opacity-100 hover:grayscale-0 transition-all duration-700">
                        <!-- Payments -->
                        <div class="flex items-center gap-10">
                            <!-- Visa SVG -->
                            <svg class="h-8 md:h-10 w-auto" viewBox="0 0 750 471" xmlns="http://www.w3.org/2000/svg">
                                <rect width="750" height="471" rx="40" fill="#1A1F71"/>
                                <path d="M278.2 334.5l33.4-195.7h53.4l-33.4 195.7h-53.4zM524.3 142.8c-10.6-3.9-27.2-8.1-47.9-8.1-52.8 0-90 26.5-90.3 64.4-.3 28 26.5 43.6 46.7 52.9 20.7 9.5 27.7 15.6 27.6 24.1-.1 13-16.6 19-31.9 19-21.3 0-32.6-2.9-50.1-10l-6.9-3.1-7.5 43.5c12.5 5.4 35.5 10.1 59.4 10.3 56.1 0 92.5-26.2 93-66.8.2-22.2-14-39.2-44.7-53.2-18.6-9.1-30-15.1-29.9-24.3 0-8.1 9.7-16.8 30.5-16.8 17.4-.3 30 3.5 39.8 7.5l4.8 2.2 7.4-43.6zM657.5 139.8h-41.3c-12.8 0-22.3 3.5-27.9 16.3l-79.2 178.4h56l11.1-29h68.4l6.5 29.1h49.5l-43.1-194.8zm-65.8 127.8l21.1-54.2 12 54.2h-33.1zM221.6 139.8l-52.4 133.3-5.6-27-18.7-89c-3.2-12.3-12.5-15.9-24-16.3H38.6l-.9 4.3c20.3 4.9 38.5 12 52.1 20 7.8 4.5 10.1 8.4 12.6 18.9l42.1 152.5h56.6l84.1-196.7h-63.6z" fill="white"/>
                            </svg>
                            <!-- Mastercard SVG -->
                            <svg class="h-7 md:h-10 w-auto" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="12" cy="16" r="10" fill="#EB001B" fill-opacity="0.8"/><circle cx="20" cy="16" r="10" fill="#F79E1B" fill-opacity="0.8"/><path d="M16 10.3c1.9 1.5 3.1 3.8 3.1 6.3s-1.2 4.8-3.1 6.3c-1.9-1.5-3.1-3.8-3.1-6.3s1.2-4.8 3.1-6.3z" fill="#FF5F00"/></svg>
                            <!-- American Express Official Logo -->
                            <svg class="h-7 md:h-10 w-auto" viewBox="0 0 750 471" xmlns="http://www.w3.org/2000/svg">
                                <rect width="750" height="471" rx="40" fill="#016FD0"/>
                                <path d="M 128 169 L 96 248 L 96 169 L 50 169 L 50 302 L 96 302 L 96 278 L 106 302 L 150 302 L 160 278 L 160 302 L 700 302 L 700 169 Z M 128 193 L 144 236 L 112 236 Z M 180 169 L 180 302 L 226 302 L 226 260 L 258 302 L 296 302 L 296 169 L 250 169 L 250 212 L 218 169 Z M 316 169 L 316 302 L 430 302 L 430 278 L 362 278 L 362 248 L 428 248 L 428 224 L 362 224 L 362 193 L 430 193 L 430 169 Z M 450 169 L 450 302 L 496 302 L 496 260 L 510 260 L 540 302 L 594 302 L 556 254 C 574 248 584 234 584 214 C 584 188 566 169 540 169 Z M 496 193 L 534 193 C 542 193 546 199 546 207 C 546 215 542 221 534 221 L 496 221 Z M 604 169 L 604 302 L 700 302 L 700 278 L 650 278 L 650 248 L 698 248 L 698 224 L 650 224 L 650 193 L 700 193 L 700 169 Z" fill="white"/>
                            </svg>
                        </div>
                        
                        <!-- Divider -->
                        <div class="hidden md:block w-px h-8 bg-slate-200"></div>

                        <!-- Logistics -->
                        <div class="flex items-center gap-10">
                            <!-- DHL styled -->
                            <span class="text-2xl md:text-3xl font-black text-[#D40511] italic tracking-tighter">DHL</span>
                            <!-- FedEx styled -->
                            <div class="flex items-center text-xl md:text-2xl font-black italic tracking-tighter">
                                <span class="text-[#4D148C]">Fed</span><span class="text-[#FF6600]">Ex</span>
                            </div>
                            <!-- UPS styled -->
                            <span class="text-2xl md:text-3xl font-black text-[#351C15] tracking-tighter">UPS</span>
                            <!-- Aramex styled -->
                            <span class="text-lg md:text-xl font-bold text-[#e11d48]">aramex</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Process Section (Modern Timeline) -->
        <section id="how-it-works" class="py-24 bg-white relative">
            <div class="max-w-7xl mx-auto px-4 sm:px-6">
                <div class="grid lg:grid-cols-2 gap-16 items-center">
                    
                    <!-- Content -->
                    <div class="order-2 lg:order-1 reveal">
                        <span class="text-red-600 font-bold tracking-wider text-sm uppercase">The Workflow</span>
                        <h2 class="text-3xl md:text-4xl font-bold text-slate-900 mt-2 mb-6">From request to delivery,<br>we handle the heavy lifting.</h2>
                        
                        <div class="space-y-0 relative mt-10">
                            <!-- Vertical Line -->
                            <div class="absolute left-6 top-4 bottom-4 w-px bg-slate-200"></div>

                            <!-- Item 1 -->
                            <div class="relative flex gap-6 pb-12 group">
                                <div class="relative z-10 w-12 h-12 bg-white border border-slate-200 rounded-full flex items-center justify-center shadow-sm group-hover:border-red-500 group-hover:bg-red-50 transition-colors">
                                    <span class="font-bold text-slate-400 group-hover:text-red-600">1</span>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-slate-900">Submit Requirements</h3>
                                    <p class="text-slate-600 mt-1 text-sm leading-relaxed">Fill out our smart form. We analyze your specs to match with the perfect factory.</p>
                                </div>
                            </div>

                            <!-- Item 2 -->
                            <div class="relative flex gap-6 pb-12 group">
                                <div class="relative z-10 w-12 h-12 bg-white border border-slate-200 rounded-full flex items-center justify-center shadow-sm group-hover:border-yellow-500 group-hover:bg-yellow-50 transition-colors">
                                    <span class="font-bold text-slate-400 group-hover:text-yellow-600">2</span>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-slate-900">Compare Quotes</h3>
                                    <p class="text-slate-600 mt-1 text-sm leading-relaxed">Receive a detailed report with competitive pricing and landed cost analysis.</p>
                                </div>
                            </div>

                            <!-- Item 3 -->
                            <div class="relative flex gap-6 group">
                                <div class="relative z-10 w-12 h-12 bg-white border border-slate-200 rounded-full flex items-center justify-center shadow-sm group-hover:border-green-500 group-hover:bg-green-50 transition-colors">
                                    <span class="font-bold text-slate-400 group-hover:text-green-600">3</span>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-slate-900">Production & Delivery</h3>
                                    <p class="text-slate-600 mt-1 text-sm leading-relaxed">We oversee manufacturing, conduct QC, and manage shipping to your door.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Image (Browser Mockup Style) -->
                    <div class="order-1 lg:order-2 reveal delay-200">
                        <div class="enterprise-frame bg-slate-50 !p-0">
                            <!-- Fake Browser Header -->
                            <div class="browser-header">
                                <div class="dot dot-red"></div>
                                <div class="dot dot-yellow"></div>
                                <div class="dot dot-green"></div>
                                <div class="flex-1 text-center text-[10px] text-slate-400 font-mono">FastSourcingBrothers/order</div>
                            </div>
                            <img src="{{ asset('images/fsb-tracking.jpg') }}" alt="Tracking Dashboard" class="w-full h-auto">
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Operations (Bento Grid Layout - Very Modern) -->
        <section class="py-24 px-4 bg-slate-50 border-t border-slate-200">
            <div class="max-w-7xl mx-auto">
                <div class="text-center max-w-2xl mx-auto mb-16 reveal">
                    <h2 class="text-3xl font-bold text-slate-900">Operations Excellence</h2>
                    <p class="text-slate-600 mt-4">We don't just send emails. We are on the ground ensuring your product quality and supply chain integrity.</p>
                </div>
                
                <div class="grid md:grid-cols-2 gap-6 lg:gap-8">
                    <!-- Big Card 1 -->
                    <div class="group relative h-[400px] rounded-2xl overflow-hidden reveal">
                        <img src="{{ asset('images/fsb-warehouse.jpg') }}" class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" alt="Warehouse">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
                        <div class="absolute bottom-0 left-0 p-8">
                            <div class="inline-block px-2 py-1 bg-yellow-500 text-white text-[10px] font-bold rounded mb-2">QUALITY CONTROL</div>
                            <h3 class="text-2xl font-bold text-white mb-2">On-Site Inspections</h3>
                            <p class="text-slate-300 text-sm opacity-0 group-hover:opacity-100 transition-opacity duration-300 transform translate-y-2 group-hover:translate-y-0">Strict AQL standards applied before any payment release.</p>
                        </div>
                    </div>

                    <!-- Big Card 2 -->
                    <div class="group relative h-[400px] rounded-2xl overflow-hidden reveal delay-100">
                        <img src="{{ asset('images/fsb-container.jpg') }}" class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" alt="Container">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
                        <div class="absolute bottom-0 left-0 p-8">
                            <div class="inline-block px-2 py-1 bg-red-600 text-white text-[10px] font-bold rounded mb-2">LOGISTICS</div>
                            <h3 class="text-2xl font-bold text-white mb-2">Secure Shipping</h3>
                            <p class="text-slate-300 text-sm opacity-0 group-hover:opacity-100 transition-opacity duration-300 transform translate-y-2 group-hover:translate-y-0">Full container load supervision and freight management.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Benefits (Clean Grid) -->
        <section id="benefits" class="py-24 px-4 relative">
            <div class="max-w-7xl mx-auto">
                <div class="grid md:grid-cols-3 gap-8">
                    <!-- Benefit 1 -->
                    <div class="pro-card p-8 reveal">
                        <div class="w-12 h-12 bg-red-50 rounded-lg flex items-center justify-center mb-6">
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-3">Save 40% Time</h3>
                        <p class="text-slate-600 leading-relaxed">Stop searching for suppliers. We utilize our pre-vetted network to get you results in hours, not weeks.</p>
                    </div>
                    <!-- Benefit 2 -->
                    <div class="pro-card p-8 reveal delay-100">
                        <div class="w-12 h-12 bg-yellow-50 rounded-lg flex items-center justify-center mb-6">
                            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-3">Risk Free</h3>
                        <p class="text-slate-600 leading-relaxed">We hold payments in escrow until quality is verified. Zero tolerance for defects or scams.</p>
                    </div>
                    <!-- Benefit 3 -->
                    <div class="pro-card p-8 reveal delay-200">
                        <div class="w-12 h-12 bg-green-50 rounded-lg flex items-center justify-center mb-6">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-3">Direct Factory Price</h3>
                        <p class="text-slate-600 leading-relaxed">No middlemen fees. We negotiate directly with manufacturers to get you the best landed cost.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Testimonials (Glass Cards) -->
        <section id="testimonials" class="py-24 bg-white border-t border-slate-100">
            <div class="max-w-7xl mx-auto px-4">
                <div class="text-center mb-16 reveal">
                    <h2 class="text-3xl font-bold text-slate-900">Client Feedback</h2>
                </div>

                <div class="grid md:grid-cols-3 gap-8">
                    <!-- Review 1 -->
                    <div class="bg-slate-50 p-8 rounded-2xl reveal">
                        <div class="flex text-yellow-400 mb-4 text-sm">★★★★★</div>
                        <p class="text-slate-700 mb-6 italic">"The response speed is much better than before, and the whole process has become smoother and more efficient. A great experience overall."</p>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-slate-200 rounded-full flex items-center justify-center font-bold text-slate-600">M</div>
                            <div>
                                <div class="font-bold text-slate-900 text-sm">Mohammed Ben Lahcen</div>
                                <div class="text-xs text-slate-500 uppercase">Client</div>
                            </div>
                        </div>
                    </div>

                    <!-- Review 2 -->
                    <div class="bg-slate-50 p-8 rounded-2xl reveal delay-100">
                        <div class="flex text-yellow-400 mb-4 text-sm">★★★★★</div>
                        <p class="text-slate-700 mb-6 italic">"This service directly contributed to the growth of my business. The team is professional, the company is trustworthy."</p>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-slate-200 rounded-full flex items-center justify-center font-bold text-slate-600">H</div>
                            <div>
                                <div class="font-bold text-slate-900 text-sm">Hafid El Hachemi</div>
                                <div class="text-xs text-slate-500 uppercase">Client</div>
                            </div>
                        </div>
                    </div>

                    <!-- Review 3 -->
                    <div class="bg-slate-50 p-8 rounded-2xl reveal delay-200">
                        <div class="flex text-yellow-400 mb-4 text-sm">★★★★★</div>
                        <p class="text-slate-700 mb-6 italic">"The prices are truly competitive and much better than platforms like Alibaba. Plus, the quality and follow-up make it safer."</p>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-slate-200 rounded-full flex items-center justify-center font-bold text-slate-600">F</div>
                            <div>
                                <div class="font-bold text-slate-900 text-sm">Fati Masaaoudi</div>
                                <div class="text-xs text-slate-500 uppercase">Client</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA (Sleek Dark Mode) -->
        <section class="py-16 px-4">
            <div class="max-w-5xl mx-auto reveal">
                <div class="bg-slate-900 rounded-3xl p-12 md:p-16 text-center shadow-2xl relative overflow-hidden">
                    <!-- Glow Effects -->
                    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-full bg-gradient-to-b from-slate-800 to-transparent opacity-50"></div>
                    
                    <div class="relative z-10">
                        <h2 class="text-3xl md:text-4xl font-bold text-white mb-6">Start your sourcing journey</h2>
                        <p class="text-slate-400 mb-10 max-w-lg mx-auto">Access our global network of verified suppliers. No upfront commitment required.</p>
                        <a href="{{ route('register') }}" class="inline-block px-8 py-4 bg-white text-slate-900 rounded-xl font-bold hover:bg-red-50 transition-colors shadow-lg shadow-white/10">
                            Create Free Account
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <!-- Footer (Clean SaaS Style) -->
        <footer class="bg-white border-t border-slate-200 pt-16 pb-8">
            <div class="max-w-7xl mx-auto px-4">
                <div class="flex flex-col md:flex-row justify-between items-start gap-12 mb-12">
                    
                    <div class="max-w-xs">
                        <div class="flex items-center gap-2 mb-4">
                            <span class="text-xl font-bold text-slate-900 tracking-tight">FastSourcingBrothers</span>
                        </div>
                        <p class="text-slate-500 text-sm leading-relaxed">Simplifying global trade through technology and expert on-ground support.</p>
                    </div>

                    <div class="flex flex-col gap-4 text-sm">
                        <h4 class="font-bold text-slate-900">Contact</h4>
                        <a href="mailto:support@fastsourcingbrothers.com" class="text-slate-500 hover:text-red-600 transition-colors flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            support@fastsourcingbrothers.com
                        </a>
                        <p class="text-slate-500 flex items-start gap-2">
                            <svg class="w-4 h-4 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 12.414A8 8 0 106.343 17.657l4.243-4.243m7.071 3.243a8 8 0 11-11.314 0 8 8 0 0111.314 0z"/></svg>
                            5830 E 2nd St, Ste 7000 #34612, Casper, WY 82609, USA
                        </p>
                        <a href="{{ route('support') }}" class="text-slate-500 hover:text-red-600 transition-colors">Contact Us</a>
                    </div>

                    <div class="flex flex-col gap-3 text-sm">
                        <h4 class="font-bold text-slate-900">Legal</h4>
                        <a href="{{ route('refund-policy') }}" class="text-slate-500 hover:text-red-600 transition-colors">Refund Policy</a>
                        <a href="{{ route('shipping-policy') }}" class="text-slate-500 hover:text-red-600 transition-colors">Shipping Policy</a>
                        <a href="{{ route('privacy-policy') }}" class="text-slate-500 hover:text-red-600 transition-colors">Privacy Policy</a>
                    </div>

                    <div class="flex gap-4">
                        <a href="#" class="w-10 h-10 flex items-center justify-center rounded-full bg-slate-100 text-slate-600 hover:bg-slate-200 hover:text-red-600 transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                        </a>
                        <a href="#" class="w-10 h-10 flex items-center justify-center rounded-full bg-slate-100 text-slate-600 hover:bg-slate-200 hover:text-red-600 transition-colors">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452z"/></svg>
                        </a>
                    </div>
                </div>
                
                <div class="border-t border-slate-100 pt-8 space-y-6">
                    <div class="space-y-4">
                        <p class="text-[10px] font-bold tracking-[0.2em] text-slate-400 text-center uppercase">Verified Professional Network</p>
                        <div class="flex flex-wrap items-center justify-center gap-8 md:gap-12 opacity-50 grayscale hover:opacity-100 hover:grayscale-0 transition-all duration-500">
                            {{-- Payments Row --}}
                            <div class="flex items-center gap-6">
                                <svg class="h-6 md:h-7 w-auto" viewBox="0 0 750 471" xmlns="http://www.w3.org/2000/svg">
                                    <rect width="750" height="471" rx="40" fill="#1A1F71"/>
                                    <path d="M278.2 334.5l33.4-195.7h53.4l-33.4 195.7h-53.4zM524.3 142.8c-10.6-3.9-27.2-8.1-47.9-8.1-52.8 0-90 26.5-90.3 64.4-.3 28 26.5 43.6 46.7 52.9 20.7 9.5 27.7 15.6 27.6 24.1-.1 13-16.6 19-31.9 19-21.3 0-32.6-2.9-50.1-10l-6.9-3.1-7.5 43.5c12.5 5.4 35.5 10.1 59.4 10.3 56.1 0 92.5-26.2 93-66.8.2-22.2-14-39.2-44.7-53.2-18.6-9.1-30-15.1-29.9-24.3 0-8.1 9.7-16.8 30.5-16.8 17.4-.3 30 3.5 39.8 7.5l4.8 2.2 7.4-43.6zM657.5 139.8h-41.3c-12.8 0-22.3 3.5-27.9 16.3l-79.2 178.4h56l11.1-29h68.4l6.5 29.1h49.5l-43.1-194.8zm-65.8 127.8l21.1-54.2 12 54.2h-33.1zM221.6 139.8l-52.4 133.3-5.6-27-18.7-89c-3.2-12.3-12.5-15.9-24-16.3H38.6l-.9 4.3c20.3 4.9 38.5 12 52.1 20 7.8 4.5 10.1 8.4 12.6 18.9l42.1 152.5h56.6l84.1-196.7h-63.6z" fill="white"/>
                                </svg>
                                <svg class="h-6 md:h-7 w-auto" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="12" cy="16" r="10" fill="#EB001B" fill-opacity="0.8"/><circle cx="20" cy="16" r="10" fill="#F79E1B" fill-opacity="0.8"/><path d="M16 10.3c1.9 1.5 3.1 3.8 3.1 6.3s-1.2 4.8-3.1 6.3c-1.9-1.5-3.1-3.8-3.1-6.3s1.2-4.8 3.1-6.3z" fill="#FF5F00"/></svg>
                                <!-- American Express Official Logo -->
                                <svg class="h-6 md:h-7 w-auto" viewBox="0 0 750 471" xmlns="http://www.w3.org/2000/svg">
                                    <rect width="750" height="471" rx="40" fill="#016FD0"/>
                                    <path d="M 128 169 L 96 248 L 96 169 L 50 169 L 50 302 L 96 302 L 96 278 L 106 302 L 150 302 L 160 278 L 160 302 L 700 302 L 700 169 Z M 128 193 L 144 236 L 112 236 Z M 180 169 L 180 302 L 226 302 L 226 260 L 258 302 L 296 302 L 296 169 L 250 169 L 250 212 L 218 169 Z M 316 169 L 316 302 L 430 302 L 430 278 L 362 278 L 362 248 L 428 248 L 428 224 L 362 224 L 362 193 L 430 193 L 430 169 Z M 450 169 L 450 302 L 496 302 L 496 260 L 510 260 L 540 302 L 594 302 L 556 254 C 574 248 584 234 584 214 C 584 188 566 169 540 169 Z M 496 193 L 534 193 C 542 193 546 199 546 207 C 546 215 542 221 534 221 L 496 221 Z M 604 169 L 604 302 L 700 302 L 700 278 L 650 278 L 650 248 L 698 248 L 698 224 L 650 224 L 650 193 L 700 193 L 700 169 Z" fill="white"/>
                                </svg>
                            </div>
                            
                            {{-- Shipping Row --}}
                            <div class="flex items-center gap-6">
                                <span class="text-lg font-black text-[#D40511] italic tracking-tight">DHL</span>
                                <div class="flex items-center text-sm font-black italic tracking-tighter">
                                    <span class="text-[#4D148C]">Fed</span><span class="text-[#FF6600]">Ex</span>
                                </div>
                                <span class="text-lg font-black text-[#351C15] tracking-tight">UPS</span>
                                <span class="text-sm font-bold text-[#e11d48]">aramex</span>
                            </div>
                        </div>
                    </div>
                    <div class="text-center text-xs text-slate-400 space-y-1">
                        <p>© 2026 Fast Sourcing Brothers LLC. Registered in Wyoming, USA.</p>
                        <p>Address: 5830 E 2nd St, Ste 7000 #34612, Casper, WY 82609.</p>
                    </div>
                </div>
            </div>
        </footer>

    </div>

    <!-- Scripts Logic -->
    <script>
        // Toggle Mobile Menu
        function toggleMenu() {
            const menu = document.getElementById('mobileMenu');
            if (menu.classList.contains('hidden')) {
                menu.classList.remove('hidden');
                document.body.style.overflow = 'hidden'; // Prevent scrolling
            } else {
                menu.classList.add('hidden');
                document.body.style.overflow = 'auto';
            }
        }

        // Close menu on link click
        document.querySelectorAll('#mobileMenu a').forEach(link => {
            link.addEventListener('click', () => {
                document.getElementById('mobileMenu').classList.add('hidden');
                document.body.style.overflow = 'auto';
            });
        });

        // Counter Animation
        function animateCounter(element) {
            const target = parseInt(element.getAttribute('data-target'));
            let current = 0;
            const increment = target / 40; 
            const timer = setInterval(() => {
                current += increment;
                if (current >= target) {
                    element.textContent = target + (target > 99 ? '+' : '%');
                    clearInterval(timer);
                } else {
                    element.textContent = Math.ceil(current) + (target > 99 ? '+' : '%');
                }
            }, 30);
        }

        // Intersection Observer (Scroll Reveal)
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('reveal'); // Trigger CSS animation
                    
                    // Trigger counters
                    const counters = entry.target.querySelectorAll('.counter');
                    counters.forEach(counter => {
                        if (!counter.classList.contains('counted')) {
                            counter.classList.add('counted');
                            animateCounter(counter);
                        }
                    });

                    // Trigger main counter if element itself is a counter
                    if (entry.target.classList.contains('counter') && !entry.target.classList.contains('counted')) {
                         entry.target.classList.add('counted');
                         animateCounter(entry.target);
                    }
                    
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.15 });

        document.querySelectorAll('.reveal, .counter').forEach(el => observer.observe(el));

        // --- THREE.JS ANIMATION (Kept Identical) ---
        const canvas = document.getElementById('three-canvas');
        const scene = new THREE.Scene();
        const camera = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 0.1, 1000);
        const renderer = new THREE.WebGLRenderer({ canvas, alpha: true, antialias: true });
        
        renderer.setSize(window.innerWidth, window.innerHeight);
        renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
        camera.position.z = 5;

        // Particles
        const particlesGeometry = new THREE.BufferGeometry();
        const particlesCount = 1500;
        const posArray = new Float32Array(particlesCount * 3);
        for(let i = 0; i < particlesCount * 3; i++) {
            posArray[i] = (Math.random() - 0.5) * 10;
        }
        particlesGeometry.setAttribute('position', new THREE.BufferAttribute(posArray, 3));
        const particlesMaterial = new THREE.PointsMaterial({
            size: 0.015,
            color: 0xdc2626,
            transparent: true,
            opacity: 0.5, // Slightly softer opacity for cleaner look
            blending: THREE.AdditiveBlending
        });
        const particlesMesh = new THREE.Points(particlesGeometry, particlesMaterial);
        scene.add(particlesMesh);

        // Shapes
        const geometry = new THREE.TorusGeometry(1.5, 0.3, 16, 100);
        const material = new THREE.MeshBasicMaterial({ color: 0xfbbf24, wireframe: true, transparent: true, opacity: 0.12 });
        const torus = new THREE.Mesh(geometry, material);
        scene.add(torus);

        const geometry2 = new THREE.IcosahedronGeometry(1, 0);
        const material2 = new THREE.MeshBasicMaterial({ color: 0xdc2626, wireframe: true, transparent: true, opacity: 0.15 });
        const icosahedron = new THREE.Mesh(geometry2, material2);
        icosahedron.position.set(2, 1, -2);
        scene.add(icosahedron);

        const geometry3 = new THREE.OctahedronGeometry(0.8, 0);
        const material3 = new THREE.MeshBasicMaterial({ color: 0xef4444, wireframe: true, transparent: true, opacity: 0.15 });
        const octahedron = new THREE.Mesh(geometry3, material3);
        octahedron.position.set(-2, -1, -1);
        scene.add(octahedron);

        let mouseX = 0, mouseY = 0;
        document.addEventListener('mousemove', (event) => {
            mouseX = (event.clientX / window.innerWidth) * 2 - 1;
            mouseY = -(event.clientY / window.innerHeight) * 2 + 1;
        });

        function animate() {
            requestAnimationFrame(animate);
            torus.rotation.x += 0.001; torus.rotation.y += 0.002;
            icosahedron.rotation.x += 0.002; icosahedron.rotation.y += 0.001;
            octahedron.rotation.x += 0.0015; octahedron.rotation.z += 0.0015;
            particlesMesh.rotation.y += 0.0005;
            camera.position.x += (mouseX * 0.5 - camera.position.x) * 0.05;
            camera.position.y += (mouseY * 0.5 - camera.position.y) * 0.05;
            renderer.render(scene, camera);
        }
        animate();

        window.addEventListener('resize', () => {
            camera.aspect = window.innerWidth / window.innerHeight;
            camera.updateProjectionMatrix();
            renderer.setSize(window.innerWidth, window.innerHeight);
        });

        // --- TYPED.JS ANIMATION ---
        document.addEventListener('DOMContentLoaded', function() {
            const typed = new Typed('#typed-text', {
                strings: [
                    'effortless.',
                    'efficient.',
                    'reliable.',
                    'seamless.',
                    'transparent.'
                ],
                typeSpeed: 80,
                backSpeed: 50,
                backDelay: 2000,
                startDelay: 500,
                loop: true,
                showCursor: true,
                cursorChar: '|',
                smartBackspace: true
            });
        });
    </script>
</body>
</html>