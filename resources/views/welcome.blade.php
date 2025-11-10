<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <title>SmartSource - Votre Solution de Sourcing Intelligent</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark')
        }

        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            200: '#bae6fd',
                            300: '#7dd3fc',
                            400: '#38bdf8',
                            500: '#0ea5e9',
                            600: '#0284c7',
                            700: '#0369a1',
                            800: '#075985',
                            900: '#0c4a6e',
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
            animation: fadeInUp 0.8s ease-out;
        }
        body {
            -webkit-font-smoothing: antialiased;
            -webkit-tap-highlight-color: transparent;
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
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }

        .dark ::-webkit-scrollbar-thumb {
            background: #475569;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #3b82f6;
        }

        .dark ::-webkit-scrollbar-thumb:hover {
            background: #2563eb;
        }

        /* Smooth transitions */
        * {
            transition-property: background-color, border-color, color, fill, stroke;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
            transition-duration: 150ms;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-blue-50 via-white to-cyan-50 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900 min-h-screen">
    <!-- Navigation -->
    <nav class="fixed top-0 left-0 right-0 bg-white/80 dark:bg-slate-800/80 backdrop-blur-md z-50 border-b border-slate-200 dark:border-slate-700 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-cyan-500 rounded-xl flex items-center justify-center shadow-lg flex-shrink-0">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    </div>
                    <span class="text-lg sm:text-xl font-bold bg-gradient-to-r from-blue-600 to-cyan-600 bg-clip-text text-transparent">
                        SmartSource
                    </span>
                </div>

                <!-- Mobile Menu Button -->
                <button onclick="toggleMenu()" class="mobile-nav-btn lg:hidden">
                    <svg class="w-6 h-6 text-slate-600 dark:text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>

                <!-- Desktop Navigation -->
                <div class="hidden lg:flex items-center space-x-8">
                    <a href="#features" class="text-slate-600 dark:text-slate-300 hover:text-blue-600 dark:hover:text-blue-400 transition-colors font-semibold">Fonctionnalités</a>
                    <a href="#how-it-works" class="text-slate-600 dark:text-slate-300 hover:text-blue-600 dark:hover:text-blue-400 transition-colors font-semibold">Comment ça marche</a>
                    <a href="#pricing" class="text-slate-600 dark:text-slate-300 hover:text-blue-600 dark:hover:text-blue-400 transition-colors font-semibold">Tarifs</a>
                </div>

                <!-- Auth Buttons Desktop -->
                <div class="hidden lg:flex items-center space-x-4">
                    <a href="/login" class="text-slate-700 dark:text-slate-300 hover:text-blue-600 dark:hover:text-blue-400 font-semibold transition-colors">
                        Connexion
                    </a>
                    <a href="/register" class="px-6 py-2.5 bg-gradient-to-r from-blue-600 to-cyan-600 text-white rounded-lg hover:shadow-lg hover:scale-105 transition-all font-semibold">
                        Commencer
                    </a>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div id="mobileMenu" class="hidden lg:hidden bg-white dark:bg-slate-800 border-t border-slate-200 dark:border-slate-700">
            <div class="px-4 py-4 space-y-3 max-w-7xl mx-auto">
                <a href="#features" class="block px-4 py-3 text-slate-700 dark:text-slate-300 hover:bg-blue-50 dark:hover:bg-slate-700 rounded-lg transition-colors font-semibold">Fonctionnalités</a>
                <a href="#how-it-works" class="block px-4 py-3 text-slate-700 dark:text-slate-300 hover:bg-blue-50 dark:hover:bg-slate-700 rounded-lg transition-colors font-semibold">Comment ça marche</a>
                <a href="#pricing" class="block px-4 py-3 text-slate-700 dark:text-slate-300 hover:bg-blue-50 dark:hover:bg-slate-700 rounded-lg transition-colors font-semibold">Tarifs</a>
                <hr class="dark:border-slate-700 my-3">
                <a href="/login" class="block px-4 py-3 text-slate-700 dark:text-slate-300 hover:bg-blue-50 dark:hover:bg-slate-700 rounded-lg transition-colors font-semibold">
                    Connexion
                </a>
                <a href="/register" class="block px-4 py-3 bg-gradient-to-r from-blue-600 to-cyan-600 text-white rounded-lg font-semibold text-center">
                    Commencer
                </a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="pt-24 sm:pt-32 pb-16 sm:pb-20 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <div class="grid lg:grid-cols-2 gap-8 lg:gap-12 items-center">
                <!-- Left Content -->
                <div class="fade-in-up space-y-6 sm:space-y-8">
                    <!-- Badge -->
                    <div class="inline-flex items-center space-x-2 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 px-3 sm:px-4 py-2 rounded-full border border-blue-200 dark:border-blue-800">
                        <svg class="w-4 sm:w-5 h-4 sm:h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                        <span class="text-xs sm:text-sm font-bold">Solution de Sourcing N°1</span>
                    </div>

                    <!-- Heading -->
                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold text-slate-900 dark:text-white leading-tight">
                        Simplifiez votre
                        <span class="block bg-gradient-to-r from-blue-600 to-cyan-600 bg-clip-text text-transparent">
                            Sourcing
                        </span>
                    </h1>

                    <!-- Description -->
                    <p class="text-base sm:text-lg lg:text-xl text-slate-600 dark:text-slate-300 leading-relaxed">
                        Gérez vos demandes d'approvisionnement et commandes en toute simplicité. Une plateforme intuitive pour connecter acheteurs et fournisseurs.
                    </p>

                    <!-- Buttons -->
                    <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 pt-4">
                        <a href="/register" class="px-6 sm:px-8 py-3 sm:py-4 bg-gradient-to-r from-blue-600 to-cyan-600 text-white rounded-lg sm:rounded-xl hover:shadow-xl hover:scale-100 sm:hover:scale-105 transition-all font-semibold text-center flex items-center justify-center space-x-2 active:scale-95 touch-button sm:touch-button shadow-lg shadow-blue-500/30">
                            <span>Démarrer gratuitement</span>
                            <svg class="w-5 h-5 hidden sm:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                            </svg>
                        </a>
                        <a href="#demo" class="px-6 sm:px-8 py-3 sm:py-4 bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-200 rounded-lg sm:rounded-xl border-2 border-slate-200 dark:border-slate-700 hover:border-blue-300 dark:hover:border-blue-600 hover:shadow-lg transition-all font-semibold text-center active:scale-95 touch-button sm:touch-button">
                            Voir la démo
                        </a>
                    </div>

                    <!-- Stats -->
                    <div class="grid grid-cols-3 gap-3 sm:gap-6 pt-6 sm:pt-8">
                        <div class="bg-white dark:bg-slate-800 p-3 sm:p-6 rounded-lg sm:rounded-xl border border-slate-200 dark:border-slate-700 text-center hover:shadow-lg transition-all duration-300">
                            <div class="text-2xl sm:text-3xl font-bold text-blue-600 dark:text-blue-400">5K+</div>
                            <div class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 mt-1 sm:mt-2 font-semibold">Utilisateurs</div>
                        </div>
                        <div class="bg-white dark:bg-slate-800 p-3 sm:p-6 rounded-lg sm:rounded-xl border border-slate-200 dark:border-slate-700 text-center hover:shadow-lg transition-all duration-300">
                            <div class="text-2xl sm:text-3xl font-bold text-cyan-600 dark:text-cyan-400">98%</div>
                            <div class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 mt-1 sm:mt-2 font-semibold">Satisfaction</div>
                        </div>
                        <div class="bg-white dark:bg-slate-800 p-3 sm:p-6 rounded-lg sm:rounded-xl border border-slate-200 dark:border-slate-700 text-center hover:shadow-lg transition-all duration-300">
                            <div class="text-2xl sm:text-3xl font-bold text-blue-600 dark:text-blue-400">50K+</div>
                            <div class="text-xs sm:text-sm text-slate-600 dark:text-slate-400 mt-1 sm:mt-2 font-semibold">Commandes</div>
                        </div>
                    </div>
                </div>

                <!-- Right Image - Hidden on Mobile, Shown on Desktop -->
                <div class="hidden lg:block relative">
                    <img src="https://illustrations.popsy.co/violet/app-launch.svg" alt="App Launch Illustration" class="w-full h-auto float-animation">
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-12 sm:py-20 px-4 sm:px-6 lg:px-8 bg-white dark:bg-slate-800">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-8 sm:mb-16">
                <div class="inline-flex items-center space-x-2 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 px-4 py-2 rounded-full mb-4 border border-blue-200 dark:border-blue-800">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                    <span class="text-sm font-bold">Fonctionnalités</span>
                </div>
                <h2 class="text-3xl sm:text-4xl font-bold text-slate-900 dark:text-white mb-2 sm:mb-4">
                    Fonctionnalités puissantes
                </h2>
                <p class="text-base sm:text-lg lg:text-xl text-slate-600 dark:text-slate-300 max-w-2xl mx-auto px-2 sm:px-0">
                    Tout ce dont vous avez besoin pour gérer efficacement vos opérations de sourcing
                </p>
            </div>

            <div class="grid md:grid-cols-3 gap-4 sm:gap-8">
                <!-- Feature 1 -->
                <div class="group p-4 sm:p-8 bg-gradient-to-br from-blue-50 to-white dark:from-slate-900 dark:to-slate-800 rounded-xl sm:rounded-2xl border border-slate-200 dark:border-slate-700 hover:shadow-xl hover:scale-105 transition-all duration-300 active:scale-95">
                    <div class="w-12 sm:w-14 h-12 sm:h-14 bg-gradient-to-br from-blue-500 to-cyan-500 rounded-lg sm:rounded-xl flex items-center justify-center mb-3 sm:mb-4 group-hover:scale-110 transition-transform flex-shrink-0 shadow-lg shadow-blue-500/30">
                        <svg class="w-6 sm:w-7 h-6 sm:h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <h3 class="text-lg sm:text-xl font-bold text-slate-900 dark:text-white mb-2 sm:mb-3">Gestion des demandes</h3>
                    <p class="text-sm sm:text-base text-slate-600 dark:text-slate-300">Créez et suivez facilement toutes vos demandes d'approvisionnement en temps réel.</p>
                </div>

                <!-- Feature 2 -->
                <div class="group p-4 sm:p-8 bg-gradient-to-br from-cyan-50 to-white dark:from-slate-900 dark:to-slate-800 rounded-xl sm:rounded-2xl border border-slate-200 dark:border-slate-700 hover:shadow-xl hover:scale-105 transition-all duration-300 active:scale-95">
                    <div class="w-12 sm:w-14 h-12 sm:h-14 bg-gradient-to-br from-cyan-500 to-blue-500 rounded-lg sm:rounded-xl flex items-center justify-center mb-3 sm:mb-4 group-hover:scale-110 transition-transform flex-shrink-0 shadow-lg shadow-cyan-500/30">
                        <svg class="w-6 sm:w-7 h-6 sm:h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg sm:text-xl font-bold text-slate-900 dark:text-white mb-2 sm:mb-3">Réseau de fournisseurs</h3>
                    <p class="text-sm sm:text-base text-slate-600 dark:text-slate-300">Connectez-vous avec des fournisseurs vérifiés et élargissez votre réseau professionnel.</p>
                </div>

                <!-- Feature 3 -->
                <div class="group p-4 sm:p-8 bg-gradient-to-br from-blue-50 to-white dark:from-slate-900 dark:to-slate-800 rounded-xl sm:rounded-2xl border border-slate-200 dark:border-slate-700 hover:shadow-xl hover:scale-105 transition-all duration-300 active:scale-95">
                    <div class="w-12 sm:w-14 h-12 sm:h-14 bg-gradient-to-br from-blue-500 to-cyan-500 rounded-lg sm:rounded-xl flex items-center justify-center mb-3 sm:mb-4 group-hover:scale-110 transition-transform flex-shrink-0 shadow-lg shadow-blue-500/30">
                        <svg class="w-6 sm:w-7 h-6 sm:h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg sm:text-xl font-bold text-slate-900 dark:text-white mb-2 sm:mb-3">Analyses & Rapports</h3>
                    <p class="text-sm sm:text-base text-slate-600 dark:text-slate-300">Obtenez des insights détaillés sur vos opérations avec des rapports analytiques avancés.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-12 sm:py-20 px-4 sm:px-6 lg:px-8">
        <div class="max-w-5xl mx-auto">
            <div class="relative bg-gradient-to-r from-blue-600 to-cyan-600 rounded-2xl sm:rounded-3xl p-6 sm:p-12 text-center shadow-2xl overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-blue-500/20 to-transparent"></div>
                <div class="relative z-10">
                    <h2 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-white mb-2 sm:mb-4">
                        Prêt à transformer votre sourcing ?
                    </h2>
                    <p class="text-sm sm:text-lg lg:text-xl text-blue-100 mb-6 sm:mb-8 max-w-2xl mx-auto px-2 sm:px-0">
                        Rejoignez des milliers d'entreprises qui optimisent leur chaîne d'approvisionnement avec SmartSource.
                    </p>
                    <a href="/register" class="inline-flex items-center px-6 sm:px-8 py-3 sm:py-4 bg-white text-blue-600 rounded-lg sm:rounded-xl hover:shadow-2xl hover:scale-100 sm:hover:scale-105 transition-all font-semibold active:scale-95 touch-button sm:touch-button">
                        <span>Commencer maintenant</span>
                        <svg class="w-5 h-5 ml-2 hidden sm:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-slate-900 text-slate-300 py-12 px-4 sm:px-6 lg:px-8 border-t border-slate-800">
        <div class="max-w-7xl mx-auto">
            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6 sm:gap-8 mb-8">
                <div>
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-cyan-500 rounded-xl flex items-center justify-center flex-shrink-0 shadow-lg shadow-blue-500/30">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                        </div>
                        <span class="text-lg font-bold text-white">SmartSource</span>
                    </div>
                    <p class="text-sm text-slate-400">Simplifiez votre chaîne d'approvisionnement avec intelligence.</p>
                </div>
                <div>
                    <h4 class="text-white font-bold mb-4">Produit</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="hover:text-white transition-colors">Fonctionnalités</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Tarifs</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Documentation</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-bold mb-4">Entreprise</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="hover:text-white transition-colors">À propos</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Carrières</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Contact</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-bold mb-4">Légal</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" class="hover:text-white transition-colors">Confidentialité</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Conditions</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Sécurité</a></li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-slate-800 pt-8 text-center text-sm">
                <p class="text-slate-400">&copy; 2025 SmartSource. Tous droits réservés.</p>
            </div>
        </div>
    </footer>

    <script>
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
    </script>
</body>
</html>