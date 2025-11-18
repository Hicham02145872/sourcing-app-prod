<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <title>FastSourcingBrothers - Expert en Sourcing depuis la Chine</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
    <script>
            tailwind.config = {
                theme: {
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
                box-shadow: 0 0 20px rgba(220, 38, 38, 0.3);
            }
            50% {
                box-shadow: 0 0 40px rgba(220, 38, 38, 0.6);
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
            background: #fef2f2;
        }

        ::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, #dc2626, #ef4444);
            border-radius: 5px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(180deg, #b91c1c, #dc2626);
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
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }

        /* Gradient text */
        .gradient-text {
            background: linear-gradient(135deg, #dc2626 0%, #ef4444 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Card hover effect */
        .card-hover {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .card-hover:hover {
            transform: translateY(-8px) scale(1.02);
        }

        /* Particle effect overlay */
        .particle-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            background: radial-gradient(circle at 50% 50%, rgba(220, 38, 38, 0.05) 0%, transparent 70%);
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
            background: linear-gradient(45deg, transparent, rgba(255,255,255,0.3), transparent);
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

        /* China flag colors */
        .china-accent {
            background: linear-gradient(135deg, #dc2626 0%, #fbbf24 100%);
        }
    </style>
</head>
<body class="bg-gradient-to-br from-red-50 via-white to-yellow-50 min-h-screen">
    <!-- Three.js Canvas -->
    <canvas id="three-canvas"></canvas>

    <!-- Content Wrapper -->
    <div class="content-wrapper">
        <!-- Navigation -->
        <nav class="fixed top-0 left-0 right-0 glass z-50 border-b border-slate-200 shadow-lg">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <!-- Logo -->
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-red-600 to-yellow-600 rounded-xl flex items-center justify-center shadow-lg pulse-glow flex-shrink-0">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-lg sm:text-xl font-bold gradient-text leading-tight">
                                FastSourcingBrothers
                            </span>
                            <span class="text-xs text-slate-500 font-semibold">Sourcing Expert from China</span>
                        </div>
                    </div>

                    <!-- Mobile Menu Button -->
                    <button onclick="toggleMenu()" class="mobile-nav-btn lg:hidden hover:bg-red-50 rounded-lg">
                        <svg class="w-6 h-6 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>

                    <!-- Desktop Navigation -->
                    <div class="hidden lg:flex items-center space-x-8">
                        <a href="#services" class="text-slate-600 hover:text-red-600 font-semibold">Services</a>
                        <a href="#process" class="text-slate-600 hover:text-red-600 font-semibold">Notre Processus</a>
                        <a href="#advantages" class="text-slate-600 hover:text-red-600 font-semibold">Avantages</a>
                        <a href="#testimonials" class="text-slate-600 hover:text-red-600 font-semibold">Témoignages</a>
                        <a href="#contact" class="text-slate-600 hover:text-red-600 font-semibold">Contact</a>
                    </div>

                    <!-- Auth Buttons Desktop -->
                    <div class="hidden lg:flex items-center space-x-4">
                        <a href="#contact" class="px-6 py-2.5 bg-gradient-to-r from-red-600 to-yellow-600 text-white rounded-lg hover:shadow-xl hover:scale-105 font-semibold">
                            Demander un devis
                        </a>
                    </div>
                </div>
            </div>

            <!-- Mobile Menu -->
            <div id="mobileMenu" class="hidden lg:hidden glass border-t border-slate-200">
                <div class="px-4 py-4 space-y-3 max-w-7xl mx-auto">
                    <a href="#services" class="block px-4 py-3 text-slate-700 hover:bg-red-50 rounded-lg font-semibold">Services</a>
                    <a href="#process" class="block px-4 py-3 text-slate-700 hover:bg-red-50 rounded-lg font-semibold">Notre Processus</a>
                    <a href="#advantages" class="block px-4 py-3 text-slate-700 hover:bg-red-50 rounded-lg font-semibold">Avantages</a>
                    <a href="#testimonials" class="block px-4 py-3 text-slate-700 hover:bg-red-50 rounded-lg font-semibold">Témoignages</a>
                    <a href="#contact" class="block px-4 py-3 text-slate-700 hover:bg-red-50 rounded-lg font-semibold">Contact</a>
                    <hr class="my-3 border-slate-200">
                    <a href="#contact" class="block px-4 py-3 bg-gradient-to-r from-red-600 to-yellow-600 text-white rounded-lg font-semibold text-center">
                        Demander un devis
                    </a>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <section class="pt-24 sm:pt-32 pb-16 sm:pb-20 px-4 sm:px-6 lg:px-8 relative overflow-hidden">
            <div class="particle-overlay"></div>
            <div class="max-w-7xl mx-auto">
                <div class="grid lg:grid-cols-2 gap-8 lg:gap-12 items-center">
                    <!-- Left Content -->
                    <div class="fade-in-up space-y-6 sm:space-y-8">
                        <!-- Badge -->
                        <div class="inline-flex items-center space-x-2 bg-gradient-to-r from-red-600 to-yellow-600 text-white px-4 py-2 rounded-full enterprise-badge shadow-lg">
                            <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            <span class="text-sm font-bold">N°1 du Sourcing Chine en Europe</span>
                        </div>

                        <!-- Heading -->
                        <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold text-slate-900 leading-tight">
                            Votre Partenaire de
                            <span class="block gradient-text mt-2">
                                Sourcing en Chine
                            </span>
                        </h1>

                        <!-- Description -->
                        <p class="text-base sm:text-lg lg:text-xl text-slate-600 leading-relaxed">
                            FastSourcingBrothers vous connecte directement aux meilleurs fabricants chinois. Nous gérons l'intégralité de votre chaîne d'approvisionnement : recherche de fournisseurs, négociation, contrôle qualité, et logistique internationale.
                        </p>

                        <!-- Trust Badges -->
                        <div class="flex flex-wrap gap-3 items-center">
                            <div class="flex items-center space-x-2 bg-white px-4 py-2 rounded-lg border border-slate-200 shadow-sm">
                                <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-sm font-semibold text-slate-700">Paiement Sécurisé</span>
                            </div>
                            <div class="flex items-center space-x-2 bg-white px-4 py-2 rounded-lg border border-slate-200 shadow-sm">
                                <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-sm font-semibold text-slate-700">Contrôle Qualité</span>
                            </div>
                            <div class="flex items-center space-x-2 bg-white px-4 py-2 rounded-lg border border-slate-200 shadow-sm">
                                <svg class="w-5 h-5 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-sm font-semibold text-slate-700">Livraison Garantie</span>
                            </div>
                        </div>

                        <!-- Buttons -->
                        <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 pt-4">
                            <a href="#contact" class="px-6 sm:px-8 py-3 sm:py-4 bg-gradient-to-r from-red-600 to-yellow-600 text-white rounded-lg sm:rounded-xl hover:shadow-2xl hover:scale-105 font-semibold text-center flex items-center justify-center space-x-2 active:scale-95 touch-button sm:touch-button shadow-lg shadow-red-500/30">
                                <span>Demander un devis gratuit</span>
                                <svg class="w-5 h-5 hidden sm:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                </svg>
                            </a>
                            <a href="#process" class="px-6 sm:px-8 py-3 sm:py-4 glass text-slate-700 rounded-lg sm:rounded-xl border-2 border-slate-200 hover:border-red-300 hover:shadow-xl transition-all font-semibold text-center active:scale-95 touch-button sm:touch-button">
                                Découvrir notre processus
                            </a>
                        </div>

                        <!-- Stats -->
                        <div class="grid grid-cols-3 gap-3 sm:gap-6 pt-6 sm:pt-8">
                            <div class="glass p-3 sm:p-6 rounded-lg sm:rounded-xl border border-slate-200 text-center card-hover">
                                <div class="text-2xl sm:text-3xl font-bold gradient-text counter" data-target="500">0</div>
                                <div class="text-xs sm:text-sm text-slate-600 mt-1 sm:mt-2 font-semibold">Clients</div>
                            </div>
                            <div class="glass p-3 sm:p-6 rounded-lg sm:rounded-xl border border-slate-200 text-center card-hover">
                                <div class="text-2xl sm:text-3xl font-bold gradient-text counter" data-target="2000">0</div>
                                <div class="text-xs sm:text-sm text-slate-600 mt-1 sm:mt-2 font-semibold">Fournisseurs</div>
                            </div>
                            <div class="glass p-3 sm:p-6 rounded-lg sm:rounded-xl border border-slate-200 text-center card-hover">
                                <div class="text-2xl sm:text-3xl font-bold gradient-text counter" data-target="98">0</div>
                                <div class="text-xs sm:text-sm text-slate-600 mt-1 sm:mt-2 font-semibold">Satisfaction</div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Visual Element -->
                    <div class="hidden lg:block relative">
                        <div class="relative w-full h-[500px] flex items-center justify-center">
                            <div class="absolute inset-0 bg-gradient-to-br from-red-500/10 to-yellow-500/10 rounded-3xl blur-3xl"></div>
                            <div class="relative z-10 w-full h-full flex items-center justify-center">
                                <div class="w-64 h-64 bg-gradient-to-br from-red-500 to-yellow-500 rounded-full opacity-20 blur-3xl absolute"></div>
                                <svg class="w-96 h-96 text-red-600 opacity-30 float-animation" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="0.5" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Services Section -->
        <section id="services" class="py-16 sm:py-24 px-4 sm:px-6 lg:px-8 glass border-y border-slate-200">
            <div class="max-w-7xl mx-auto">
                <div class="text-center mb-12 sm:mb-16 scroll-reveal">
                    <div class="inline-flex items-center space-x-2 bg-red-100 text-red-700 px-4 py-2 rounded-full mb-4 border border-red-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        <span class="text-sm font-bold">Nos Services</span>
                    </div>
                    <h2 class="text-3xl sm:text-4xl font-bold text-slate-900 mb-4">
                        Une solution complète de sourcing
                    </h2>
                    <p class="text-lg text-slate-600 max-w-3xl mx-auto">
                        De la recherche de fournisseurs à la livraison finale, nous gérons chaque étape de votre chaîne d'approvisionnement en Chine
                    </p>
                </div>

                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
                    <!-- Service 1 -->
                    <div class="group p-6 sm:p-8 glass rounded-2xl border border-slate-200 card-hover scroll-reveal">
                        <div class="w-14 h-14 bg-gradient-to-br from-red-600 to-yellow-600 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform shadow-lg shadow-red-500/30">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-3">Recherche de Fournisseurs</h3>
                        <p class="text-slate-600">Identification et sélection des meilleurs fabricants chinois adaptés à vos besoins spécifiques. Accès à notre réseau de plus de 2000 fournisseurs vérifiés.</p>
                    </div>

                    <!-- Service 2 -->
                    <div class="group p-6 sm:p-8 glass rounded-2xl border border-slate-200 card-hover scroll-reveal">
                        <div class="w-14 h-14 bg-gradient-to-br from-yellow-600 to-red-600 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform shadow-lg shadow-yellow-500/30">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-3">Négociation & Contrats</h3>
                        <p class="text-slate-600">Négociation des meilleurs prix et conditions. Gestion complète des contrats et protection de vos intérêts commerciaux avec expertise locale.</p>
                    </div>

                    <!-- Service 3 -->
                    <div class="group p-6 sm:p-8 glass rounded-2xl border border-slate-200 card-hover scroll-reveal">
                        <div class="w-14 h-14 bg-gradient-to-br from-red-600 to-yellow-600 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform shadow-lg shadow-red-500/30">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-3">Contrôle Qualité</h3>
                        <p class="text-slate-600">Inspections rigoureuses en usine avant expédition. Vérification de conformité aux normes internationales et à vos cahiers des charges.</p>
                    </div>

                    <!-- Service 4 -->
                    <div class="group p-6 sm:p-8 glass rounded-2xl border border-slate-200 card-hover scroll-reveal">
                        <div class="w-14 h-14 bg-gradient-to-br from-yellow-600 to-red-600 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform shadow-lg shadow-yellow-500/30">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-3">Logistique Internationale</h3>
                        <p class="text-slate-600">Gestion complète du transport maritime, aérien ou ferroviaire. Dédouanement et livraison jusqu'à votre entrepôt avec suivi en temps réel.</p>
                    </div>

                    <!-- Service 5 -->
                    <div class="group p-6 sm:p-8 glass rounded-2xl border border-slate-200 card-hover scroll-reveal">
                        <div class="w-14 h-14 bg-gradient-to-br from-red-600 to-yellow-600 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform shadow-lg shadow-red-500/30">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-3">Gestion Financière</h3>
                        <p class="text-slate-600">Sécurisation des paiements, gestion des devises et protection contre les risques financiers. Paiement sécurisé avec garantie.</p>
                    </div>

                    <!-- Service 6 -->
                    <div class="group p-6 sm:p-8 glass rounded-2xl border border-slate-200 card-hover scroll-reveal">
                        <div class="w-14 h-14 bg-gradient-to-br from-yellow-600 to-red-600 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform shadow-lg shadow-yellow-500/30">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-3">Support Continu</h3>
                        <p class="text-slate-600">Accompagnement personnalisé tout au long de votre projet. Équipe bilingue disponible pour répondre à toutes vos questions.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Process Section -->
        <section id="process" class="py-16 sm:py-24 px-4 sm:px-6 lg:px-8 relative">
            <div class="max-w-7xl mx-auto">
                <div class="text-center mb-12 sm:mb-16 scroll-reveal">
                    <div class="inline-flex items-center space-x-2 bg-red-100 text-red-700 px-4 py-2 rounded-full mb-4 border border-red-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                        <span class="text-sm font-bold">Notre Processus</span>
                    </div>
                    <h2 class="text-3xl sm:text-4xl font-bold text-slate-900 mb-4">
                        Comment nous travaillons
                    </h2>
                    <p class="text-lg text-slate-600 max-w-3xl mx-auto">
                        Un processus simple et transparent en 6 étapes pour garantir votre succès
                    </p>
                </div>

                <div class="relative">
                    <!-- Timeline line -->
                    <div class="hidden lg:block absolute left-1/2 transform -translate-x-1/2 w-1 h-full bg-gradient-to-b from-red-600 to-yellow-600"></div>

                    <!-- Steps -->
                    <div class="space-y-12">
                        <!-- Step 1 -->
                        <div class="relative grid lg:grid-cols-2 gap-8 items-center scroll-reveal">
                            <div class="lg:text-right">
                                <div class="inline-block lg:block glass p-6 rounded-2xl border border-slate-200 card-hover">
                                    <div class="flex lg:flex-row-reverse items-start gap-4">
                                        <div class="w-12 h-12 bg-gradient-to-br from-red-600 to-yellow-600 rounded-xl flex items-center justify-center flex-shrink-0 shadow-lg">
                                            <span class="text-white font-bold text-xl">1</span>
                                        </div>
                                        <div class="flex-1">
                                            <h3 class="text-xl font-bold text-slate-900 mb-2">Analyse de vos besoins</h3>
                                            <p class="text-slate-600">Nous étudions en détail vos besoins, votre cahier des charges et vos objectifs pour identifier les meilleurs fournisseurs.</p>
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
                                <div class="glass p-6 rounded-2xl border border-slate-200 card-hover">
                                    <div class="flex items-start gap-4">
                                        <div class="w-12 h-12 bg-gradient-to-br from-yellow-600 to-red-600 rounded-xl flex items-center justify-center flex-shrink-0 shadow-lg">
                                            <span class="text-white font-bold text-xl">2</span>
                                        </div>
                                        <div class="flex-1">
                                            <h3 class="text-xl font-bold text-slate-900 mb-2">Recherche et sélection</h3>
                                            <p class="text-slate-600">Identification des fabricants les plus adaptés dans notre réseau de 2000+ fournisseurs vérifiés en Chine.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Step 3 -->
                        <div class="relative grid lg:grid-cols-2 gap-8 items-center scroll-reveal">
                            <div class="lg:text-right">
                                <div class="inline-block lg:block glass p-6 rounded-2xl border border-slate-200 card-hover">
                                    <div class="flex lg:flex-row-reverse items-start gap-4">
                                        <div class="w-12 h-12 bg-gradient-to-br from-red-600 to-yellow-600 rounded-xl flex items-center justify-center flex-shrink-0 shadow-lg">
                                            <span class="text-white font-bold text-xl">3</span>
                                        </div>
                                        <div class="flex-1">
                                            <h3 class="text-xl font-bold text-slate-900 mb-2">Négociation et devis</h3>
                                            <p class="text-slate-600">Négociation des meilleurs prix et conditions. Présentation de devis détaillés et transparents pour validation.</p>
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
                                <div class="glass p-6 rounded-2xl border border-slate-200 card-hover">
                                    <div class="flex items-start gap-4">
                                        <div class="w-12 h-12 bg-gradient-to-br from-yellow-600 to-red-600 rounded-xl flex items-center justify-center flex-shrink-0 shadow-lg">
                                            <span class="text-white font-bold text-xl">4</span>
                                        </div>
                                        <div class="flex-1">
                                            <h3 class="text-xl font-bold text-slate-900 mb-2">Production et suivi</h3>
                                            <p class="text-slate-600">Lancement de la production avec suivi régulier. Inspections en cours de fabrication pour garantir la qualité.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Step 5 -->
                        <div class="relative grid lg:grid-cols-2 gap-8 items-center scroll-reveal">
                            <div class="lg:text-right">
                                <div class="inline-block lg:block glass p-6 rounded-2xl border border-slate-200 card-hover">
                                    <div class="flex lg:flex-row-reverse items-start gap-4">
                                        <div class="w-12 h-12 bg-gradient-to-br from-red-600 to-yellow-600 rounded-xl flex items-center justify-center flex-shrink-0 shadow-lg">
                                            <span class="text-white font-bold text-xl">5</span>
                                        </div>
                                        <div class="flex-1">
                                            <h3 class="text-xl font-bold text-slate-900 mb-2">Contrôle qualité final</h3>
                                            <p class="text-slate-600">Inspection complète avant expédition. Vérification de conformité et tests de qualité selon vos standards.</p>
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
                                <div class="glass p-6 rounded-2xl border border-slate-200 card-hover">
                                    <div class="flex items-start gap-4">
                                        <div class="w-12 h-12 bg-gradient-to-br from-yellow-600 to-red-600 rounded-xl flex items-center justify-center flex-shrink-0 shadow-lg">
                                            <span class="text-white font-bold text-xl">6</span>
                                        </div>
                                        <div class="flex-1">
                                            <h3 class="text-xl font-bold text-slate-900 mb-2">Livraison et SAV</h3>
                                            <p class="text-slate-600">Expédition et livraison sécurisées. Service après-vente et support continu pour votre satisfaction totale.</p>
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
        <section id="advantages" class="py-16 sm:py-24 px-4 sm:px-6 lg:px-8 glass border-y border-slate-200">
            <div class="max-w-7xl mx-auto">
                <div class="grid lg:grid-cols-2 gap-12 items-center">
                    <div class="scroll-reveal">
                        <div class="inline-flex items-center space-x-2 bg-green-100 text-green-700 px-4 py-2 rounded-full mb-6 border border-green-200">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                            </svg>
                            <span class="text-sm font-bold">Nos Avantages</span>
                        </div>
                        <h2 class="text-3xl sm:text-4xl font-bold text-slate-900 mb-6">
                            Pourquoi choisir FastSourcingBrothers ?
                        </h2>
                        <p class="text-lg text-slate-600 mb-8">
                            Notre expertise du marché chinois et notre réseau de fournisseurs vérifiés vous garantissent les meilleures conditions pour votre sourcing.
                        </p>
                        <div class="space-y-6">
                            <div class="flex items-start space-x-4">
                                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-bold text-slate-900 mb-1">Économies jusqu'à 60%</h4>
                                    <p class="text-slate-600">Réduction significative de vos coûts d'approvisionnement grâce à nos négociations directes avec les fabricants</p>
                                </div>
                            </div>
                            <div class="flex items-start space-x-4">
                                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-bold text-slate-900 mb-1">Gain de temps considérable</h4>
                                    <p class="text-slate-600">Nous gérons l'intégralité du processus pour que vous puissiez vous concentrer sur votre cœur de métier</p>
                                </div>
                            </div>
                            <div class="flex items-start space-x-4">
                                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-bold text-slate-900 mb-1">Qualité garantie</h4>
                                    <p class="text-slate-600">Contrôles qualité rigoureux à chaque étape et garantie de conformité aux normes internationales</p>
                                </div>
                            </div>
                            <div class="flex items-start space-x-4">
                                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-bold text-slate-900 mb-1">Expertise locale</h4>
                                    <p class="text-slate-600">Équipe bilingue basée en Chine avec une connaissance approfondie du marché et de la culture locale</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="scroll-reveal">
                        <div class="glass p-8 rounded-2xl border border-slate-200 shadow-xl">
                            <h3 class="text-2xl font-bold text-slate-900 mb-6">Résultats moyens de nos clients</h3>
                            <div class="space-y-6">
                                <div>
                                    <div class="flex justify-between mb-2">
                                        <span class="text-slate-600 font-semibold">Réduction des coûts</span>
                                        <span class="text-green-600 font-bold">-60%</span>
                                    </div>
                                    <div class="w-full bg-slate-200 rounded-full h-3">
                                        <div class="bg-gradient-to-r from-green-500 to-green-600 h-3 rounded-full" style="width: 60%"></div>
                                    </div>
                                </div>
                                <div>
                                    <div class="flex justify-between mb-2">
                                        <span class="text-slate-600 font-semibold">Gain de temps</span>
                                        <span class="text-red-600 font-bold">+75%</span>
                                    </div>
                                    <div class="w-full bg-slate-200 rounded-full h-3">
                                        <div class="bg-gradient-to-r from-red-500 to-red-600 h-3 rounded-full" style="width: 75%"></div>
                                    </div>
                                </div>
                                <div>
                                    <div class="flex justify-between mb-2">
                                        <span class="text-slate-600 font-semibold">Amélioration qualité</span>
                                        <span class="text-yellow-600 font-bold">+85%</span>
                                    </div>
                                    <div class="w-full bg-slate-200 rounded-full h-3">
                                        <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 h-3 rounded-full" style="width: 85%"></div>
                                    </div>
                                </div>
                                <div>
                                    <div class="flex justify-between mb-2">
                                        <span class="text-slate-600 font-semibold">Satisfaction client</span>
                                        <span class="text-blue-600 font-bold">98%</span>
                                    </div>
                                    <div class="w-full bg-slate-200 rounded-full h-3">
                                        <div class="bg-gradient-to-r from-blue-500 to-blue-600 h-3 rounded-full" style="width: 98%"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-8 p-6 bg-gradient-to-r from-red-50 to-yellow-50 rounded-xl border border-red-100">
                                <p class="text-sm text-slate-600 mb-2">ROI moyen</p>
                                <p class="text-4xl font-bold gradient-text">350%</p>
                                <p class="text-sm text-slate-600 mt-2">Sur la première année</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Testimonials Section -->
        <section id="testimonials" class="py-16 sm:py-24 px-4 sm:px-6 lg:px-8 relative">
            <div class="max-w-7xl mx-auto">
                <div class="text-center mb-12 sm:mb-16 scroll-reveal">
                    <div class="inline-flex items-center space-x-2 bg-red-100 text-red-700 px-4 py-2 rounded-full mb-4 border border-red-200">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                        <span class="text-sm font-bold">Témoignages Clients</span>
                    </div>
                    <h2 class="text-3xl sm:text-4xl font-bold text-slate-900 mb-4">
                        Ce que disent nos clients
                    </h2>
                    <p class="text-lg text-slate-600 max-w-3xl mx-auto">
                        Découvrez comment FastSourcingBrothers a aidé des entreprises comme la vôtre à réussir leur sourcing en Chine
                    </p>
                </div>

                <div class="grid md:grid-cols-3 gap-6 sm:gap-8">
                    <!-- Testimonial 1 -->
                    <div class="glass p-6 sm:p-8 rounded-2xl border border-slate-200 card-hover scroll-reveal">
                        <div class="flex items-center mb-4">
                            <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        </div>
                        <p class="text-slate-600 mb-6 italic">"FastSourcingBrothers a transformé notre approvisionnement. Nous avons réduit nos coûts de 55% tout en améliorant la qualité. Leur équipe est professionnelle et réactive."</p>
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-gradient-to-br from-red-500 to-yellow-500 rounded-full flex items-center justify-center text-white font-bold mr-3">
                                JD
                            </div>
                            <div>
                                <p class="font-bold text-slate-900">Jean Dupont</p>
                                <p class="text-sm text-slate-500">Directeur Achats, TechCorp</p>
                            </div>
                        </div>
                    </div>

                    <!-- Testimonial 2 -->
                    <div class="glass p-6 sm:p-8 rounded-2xl border border-slate-200 card-hover scroll-reveal">
                        <div class="flex items-center mb-4">
                            <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        </div>
                        <p class="text-slate-600 mb-6 italic">"Un service exceptionnel du début à la fin. Les contrôles qualité sont rigoureux et la communication est excellente. Je recommande vivement !"</p>
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-gradient-to-br from-yellow-500 to-red-500 rounded-full flex items-center justify-center text-white font-bold mr-3">
                                SM
                            </div>
                            <div>
                                <p class="font-bold text-slate-900">Sophie Martin</p>
                                <p class="text-sm text-slate-500">CEO, StyleMode</p>
                            </div>
                        </div>
                    </div>

                    <!-- Testimonial 3 -->
                    <div class="glass p-6 sm:p-8 rounded-2xl border border-slate-200 card-hover scroll-reveal">
                        <div class="flex items-center mb-4">
                            <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                            <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        </div>
                        <p class="text-slate-600 mb-6 italic">"Grâce à FastSourcingBrothers, nous avons trouvé des fournisseurs fiables et économisé énormément. Leur expertise du marché chinois est inestimable."</p>
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-gradient-to-br from-red-500 to-yellow-500 rounded-full flex items-center justify-center text-white font-bold mr-3">
                                PL
                            </div>
                            <div>
                                <p class="font-bold text-slate-900">Pierre Lefebvre</p>
                                <p class="text-sm text-slate-500">Fondateur, HomeDecor</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section id="contact" class="py-16 sm:py-20 px-4 sm:px-6 lg:px-8 relative">
            <div class="max-w-5xl mx-auto">
                <div class="relative bg-gradient-to-r from-red-600 to-yellow-600 rounded-3xl p-8 sm:p-12 text-center shadow-2xl overflow-hidden scroll-reveal">
                    <div class="absolute inset-0 bg-gradient-to-br from-red-500/20 to-transparent"></div>
                    <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjAiIGhlaWdodD0iNjAiIHZpZXdCb3g9IjAgMCA2MCA2MCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48ZyBmaWxsPSJub25lIiBmaWxsLXJ1bGU9ImV2ZW5vZGQiPjxnIGZpbGw9IiNmZmZmZmYiIGZpbGwtb3BhY2l0eT0iMC4xIj48cGF0aCBkPSJNMzYgMzRjMC0yLjIxIDEuNzktNCA0LTRzNCAxLjc5IDQgNC0xLjc5IDQtNCA0LTQtMS43OS00LTR6bTAgMTBjMC0yLjIxIDEuNzktNCA0LTRzNCAxLjc5IDQgNC0xLjc5IDQtNCA0LTQtMS43OS00LTR6Ii8+PC9nPjwvZz48L3N2Zz4=')] opacity-20"></div>
                    <div class="relative z-10">
                        <h2 class="text-3xl sm:text-4xl font-bold text-white mb-4">
                            Prêt à optimiser votre sourcing en Chine ?
                        </h2>
                        <p class="text-lg text-red-100 mb-8 max-w-2xl mx-auto">
                            Rejoignez plus de 500 entreprises qui ont déjà fait confiance à FastSourcingBrothers pour leur approvisionnement depuis la Chine.
                        </p>
                        <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                            <a href="/cdn-cgi/l/email-protection#f794989983969483b79196848384988285949e9990958598839f928584d994989a" class="inline-flex items-center px-8 py-4 bg-white text-red-600 rounded-xl hover:shadow-2xl hover:scale-105 transition-all font-semibold">
                                <span>Demander un devis gratuit</span>
                                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                </svg>
                            </a>
                            <a href="tel:+33123456789" class="inline-flex items-center px-8 py-4 bg-transparent text-white rounded-xl border-2 border-white hover:bg-white hover:text-red-600 transition-all font-semibold">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                                <span>Nous appeler</span>
                            </a>
                        </div>
                        <p class="text-sm text-red-100 mt-6">
                            ✓ Devis gratuit sous 24h • ✓ Aucun engagement • ✓ Consultation personnalisée
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="bg-slate-900 text-slate-300 py-12 px-4 sm:px-6 lg:px-8 border-t border-slate-800">
            <div class="max-w-7xl mx-auto">
                <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-8 mb-8">
                    <div>
                        <div class="flex items-center space-x-3 mb-4">
                            <div class="w-10 h-10 bg-gradient-to-br from-red-600 to-yellow-600 rounded-xl flex items-center justify-center shadow-lg shadow-red-500/30">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <span class="text-lg font-bold text-white">FastSourcingBrothers</span>
                                <span class="block text-xs text-slate-400">Sourcing Expert</span>
                            </div>
                        </div>
                        <p class="text-sm text-slate-400 mb-4">
                            Votre partenaire de confiance pour le sourcing en Chine. Qualité, économies et tranquillité d'esprit.
                        </p>
                        <div class="flex space-x-4">
                            <a href="#" class="text-slate-400 hover:text-red-400 transition-colors">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                </svg>
                            </a>
                            <a href="#" class="text-slate-400 hover:text-red-400 transition-colors">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                                </svg>
                            </a>
                            <a href="#" class="text-slate-400 hover:text-red-400 transition-colors">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                    <div>
                        <h4 class="text-white font-bold mb-4">Services</h4>
                        <ul class="space-y-2 text-sm">
                            <li><a href="#" class="hover:text-white transition-colors">Recherche fournisseurs</a></li>
                            <li><a href="#" class="hover:text-white transition-colors">Négociation</a></li>
                            <li><a href="#" class="hover:text-white transition-colors">Contrôle qualité</a></li>
                            <li><a href="#" class="hover:text-white transition-colors">Logistique</a></li>
                            <li><a href="#" class="hover:text-white transition-colors">Gestion financière</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="text-white font-bold mb-4">Secteurs</h4>
                        <ul class="space-y-2 text-sm">
                            <li><a href="#" class="hover:text-white transition-colors">Électronique</a></li>
                            <li><a href="#" class="hover:text-white transition-colors">Textile & Mode</a></li>
                            <li><a href="#" class="hover:text-white transition-colors">Mobilier</a></li>
                            <li><a href="#" class="hover:text-white transition-colors">Cosmétiques</a></li>
                            <li><a href="#" class="hover:text-white transition-colors">Jouets & Loisirs</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="text-white font-bold mb-4">Contact</h4>
                        <ul class="space-y-2 text-sm">
                            <li class="flex items-center space-x-2">
                                <svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                <a href="/cdn-cgi/l/email-protection#3c5f5352485d5f487c5a5d4f484f53494e5f55525b5e4e534854594e4f125f5351" class="hover:text-white transition-colors"><span class="__cf_email__" data-cfemail="15767a7b617476615573746661667a6067767c7b7277677a617d7067663b767a78">[email&#160;protected]</span></a>
                            </li>
                            <li class="flex items-center space-x-2">
                                <svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                                <a href="tel:+33123456789" class="hover:text-white transition-colors">+33 1 23 45 67 89</a>
                            </li>
                            <li class="flex items-start space-x-2">
                                <svg class="w-4 h-4 text-red-400 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <span>Maroc<br/>Rabat</span>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="border-t border-slate-800 pt-8 flex flex-col sm:flex-row justify-between items-center text-sm">
                    <p class="text-slate-400 mb-4 sm:mb-0">&copy; 2025 FastSourcingBrothers. Tous droits réservés.</p>
                    <div class="flex space-x-6">
                        <a href="#" class="text-slate-400 hover:text-white transition-colors">Mentions légales</a>
                        <a href="#" class="text-slate-400 hover:text-white transition-colors">Confidentialité</a>
                        <a href="#" class="text-slate-400 hover:text-white transition-colors">CGV</a>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <!-- JavaScript -->
    <script data-cfasync="false" src="/cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script><script>
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
            color: 0xdc2626,
            transparent: true,
            opacity: 0.6,
            blending: THREE.AdditiveBlending
        });

        const particlesMesh = new THREE.Points(particlesGeometry, particlesMaterial);
        scene.add(particlesMesh);

        // Create geometric shapes
        const geometry = new THREE.TorusGeometry(1.5, 0.3, 16, 100);
        const material = new THREE.MeshBasicMaterial({
            color: 0xfbbf24,
            wireframe: true,
            transparent: true,
            opacity: 0.15
        });
        const torus = new THREE.Mesh(geometry, material);
        scene.add(torus);

        // Create second shape
        const geometry2 = new THREE.IcosahedronGeometry(1, 0);
        const material2 = new THREE.MeshBasicMaterial({
            color: 0xdc2626,
            wireframe: true,
            transparent: true,
            opacity: 0.2
        });
        const icosahedron = new THREE.Mesh(geometry2, material2);
        icosahedron.position.set(2, 1, -2);
        scene.add(icosahedron);

        // Create third shape
        const geometry3 = new THREE.OctahedronGeometry(0.8, 0);
        const material3 = new THREE.MeshBasicMaterial({
            color: 0xef4444,
            wireframe: true,
            transparent: true,
            opacity: 0.18
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