<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>FastSourcingBrothers – Global B2B Sourcing Partner</title>
    <link rel="icon" href="{{ asset('images/logo5.jpg') }}">

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <!-- Schema.org Data -->
    @verbatim
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "Organization",
        "name": "FastSourcingBrothers",
        "url": "https://www.fastsourcingbrothers.com",
        "logo": "https://www.fastsourcingbrothers.com/images/logo5.jpg",
        "sameAs": [
            "https://www.linkedin.com/company/fastsourcingbrothers",
            "https://www.facebook.com/fastsourcingbrothers",        
            "https://www.instagram.com/fastsourcingbrothers"
        ]
    }
    </script>
    @endverbatim

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: {
                        brand: {
                            50: '#fff7ed',
                            100: '#ffedd5',
                            200: '#fed7aa',
                            300: '#fdba74',
                            400: '#fb923c',
                            500: '#f97316',
                            600: '#ea580c',
                            700: '#c2410c',
                            800: '#9a3412',
                            900: '#7c2d12',
                        },
                        slate: {
                            850: '#151e2e',
                            900: '#0f172a',
                            950: '#020617',
                        }
                    },
                    animation: {
                        'float': 'float 8s ease-in-out infinite',
                        'float-delayed': 'float 8s ease-in-out 4s infinite',
                        'fade-in-up': 'fadeInUp 1s cubic-bezier(0.16, 1, 0.3, 1) forwards',
                    },
                    keyframes: {
                        float: {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-15px)' }
                        },
                        fadeInUp: {
                            '0%': { opacity: '0', transform: 'translateY(20px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' }
                        }
                    },
                    boxShadow: {
                        'glass': '0 8px 32px 0 rgba(31, 38, 135, 0.07)',
                    }
                }
            }
        }
    </script>

    <!-- Typed.js -->
    <script src="https://unpkg.com/typed.js@2.1.0/dist/typed.umd.js"></script>

    <style>
        /* Modern Reset & Utilities */
        body {
            font-feature-settings: "cv02", "cv03", "cv04", "cv11";
        }

        .glass-nav {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(0, 0, 0, 0.03);
        }
        
        .glass-card {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.5);
            box-shadow: 0 4px 24px -1px rgba(0, 0, 0, 0.04);
        }

        .reveal {
            opacity: 0;
            transform: translateY(40px);
            transition: all 1s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .reveal.active {
            opacity: 1;
            transform: translateY(0);
        }

        /* Subtle gradients for modern feel */
        .bg-subtle-gradient {
            background: radial-gradient(circle at 50% 0%, rgba(249, 115, 22, 0.03) 0%, transparent 70%);
        }
    </style>
</head>
<body class="bg-white text-slate-900 antialiased overflow-x-hidden selection:bg-brand-100 selection:text-brand-900">

    <!-- Navbar -->
    <nav id="navbar" class="fixed top-0 w-full z-50 transition-all duration-300 py-4 border-b border-transparent">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 flex items-center justify-between">
            <!-- Logo -->
            <a href="#" class="flex items-center gap-3 group">
                <img src="{{ asset('images/logo5.jpg') }}" alt="Logo" class="w-9 h-9 rounded-lg object-cover shadow-sm group-hover:shadow-md transition-all duration-300">
                <span class="text-lg font-semibold tracking-tight text-slate-900 group-hover:text-brand-600 transition-colors">
                    FastSourcing<span class="text-brand-600">Brothers</span>
                </span>
            </a>

            <!-- Desktop Links -->
            <div class="hidden lg:flex items-center space-x-12">
                <a href="#process" class="text-sm font-medium text-slate-500 hover:text-slate-900 transition-colors">Process</a>
                <a href="#solutions" class="text-sm font-medium text-slate-500 hover:text-slate-900 transition-colors">Solutions</a>
                <a href="#testimonials" class="text-sm font-medium text-slate-500 hover:text-slate-900 transition-colors">Testimonials</a>
            </div>

            <!-- Desktop CTA -->
            <div class="hidden lg:flex items-center space-x-5">
                <a href="{{ route('login') }}" class="text-sm font-medium text-slate-600 hover:text-slate-900 transition-colors">Log in</a>
                <a href="{{ route('register') }}" class="px-5 py-2 bg-slate-900 hover:bg-slate-800 text-white text-sm font-medium rounded-full transition-all duration-300 shadow-lg shadow-slate-900/10 hover:shadow-xl hover:-translate-y-px">
                    Get Started
                </a>
            </div>

            <!-- Mobile Menu Button -->
            <button id="mobile-menu-btn" class="lg:hidden text-slate-900 hover:text-brand-600 transition-colors p-2">
                <i data-lucide="menu" class="w-6 h-6"></i>
            </button>
        </div>

        <!-- Mobile Menu -->
        <div id="mobile-menu" class="hidden lg:hidden absolute top-full left-0 w-full bg-white border-b border-gray-100 p-6 shadow-xl origin-top transition-transform z-40">
            <div class="flex flex-col space-y-6">
                <a href="#process" class="text-lg font-medium text-slate-800 mobile-link">Process</a>
                <a href="#solutions" class="text-lg font-medium text-slate-800 mobile-link">Solutions</a>
                <a href="#testimonials" class="text-lg font-medium text-slate-800 mobile-link">Testimonials</a>
                <hr class="border-gray-100">
                <a href="{{ route('login') }}" class="text-lg font-medium text-slate-800">Log in</a>
                <a href="{{ route('register') }}" class="btn w-full py-3 bg-slate-900 text-white rounded-lg text-center font-bold">Get Started</a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="relative min-h-screen flex items-center pt-28 pb-20 overflow-hidden bg-subtle-gradient">
        <!-- Minimal Abstract Background -->
        <div class="absolute top-0 right-0 w-[800px] h-[800px] bg-brand-50/40 rounded-full blur-[120px] pointer-events-none translate-x-1/3 -translate-y-1/4"></div>
        
        <div class="max-w-7xl mx-auto px-6 lg:px-8 grid lg:grid-cols-2 gap-20 items-center relative z-10">
            <!-- Left Text Content -->
            <div class="max-w-2xl reveal active">
                <div class="inline-flex items-center px-3 py-1 bg-white border border-gray-200 rounded-full text-[11px] font-semibold uppercase tracking-wider mb-8 text-slate-600 shadow-sm">
                    <span class="w-1.5 h-1.5 bg-brand-500 rounded-full mr-2"></span>
                    Global B2B Sourcing Partner
                </div>
                
                <h1 class="text-5xl lg:text-7xl font-bold text-slate-900 leading-[1.05] mb-6 tracking-tighter">
                    Supply Chain, <br />
                    <span class="text-brand-600" id="typed-output"></span>
                </h1>
                
                <p class="text-lg text-slate-500 mb-10 leading-relaxed max-w-md font-light">
                    Submit requirements, receive quotes from verified suppliers, and track orders to delivery. Procurement simplified.
                </p>

                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="{{ route('client.sourcing-requests.create') }}" class="inline-flex items-center justify-center px-8 py-3.5 bg-brand-600 hover:bg-brand-700 text-white rounded-full font-medium text-base shadow-xl shadow-brand-500/20 transition-all transform hover:-translate-y-0.5 group">
                        Start Sourcing
                        <i data-lucide="arrow-right" class="ml-2 w-4 h-4 group-hover:translate-x-1 transition-transform"></i>
                    </a>
                    <a href="#process" class="inline-flex items-center justify-center px-8 py-3.5 bg-white text-slate-600 border border-gray-200 hover:bg-gray-50 hover:border-gray-300 rounded-full font-medium text-base transition-all">
                        How it works
                    </a>
                </div>

                <div class="mt-16 pt-8 border-t border-gray-100 flex items-center gap-8 text-sm text-slate-500">
                    <div class="flex items-center gap-2">
                        <i data-lucide="check-circle" class="w-4 h-4 text-brand-600"></i>
                        <span>Verified Suppliers</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <i data-lucide="check-circle" class="w-4 h-4 text-brand-600"></i>
                        <span>Escrow Payments</span>
                    </div>
                </div>
            </div>

            <!-- Right Visual Content -->
            <div class="relative hidden lg:block reveal delay-100">
                <div class="relative rounded-2xl overflow-hidden shadow-2xl border border-gray-100 bg-white transform transition-transform duration-700 hover:shadow-brand-500/10">
                    <img 
                        src="{{ asset('images/fs1.jpg') }}" 
                        alt="Logistics Dashboard" 
                        class="w-full h-[650px] object-cover opacity-95"
                    />
                    
                    <!-- Clean Overlay -->
                    <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent"></div>

                    <!-- Floating Card: Status -->
                    <div class="absolute bottom-8 left-8 z-20 glass-card p-5 rounded-xl w-72 animate-float">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-lg bg-slate-900 flex items-center justify-center text-white">
                                <i data-lucide="container" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <div class="text-[10px] text-slate-500 uppercase font-bold tracking-widest mb-0.5">Live Shipment</div>
                                <div class="font-bold text-slate-900 text-base leading-none mb-1">#FSB-8829</div>
                                <div class="text-xs text-green-600 font-semibold flex items-center gap-1.5">
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></span>
                                    Arriving in 4 days
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Minimal Stats -->
    <div class="bg-white py-16 border-y border-gray-100">
        <div class="max-w-7xl mx-auto px-6 grid grid-cols-2 md:grid-cols-4 gap-12">
            <div class="text-center">
                <div class="text-3xl font-bold text-slate-900 mb-1">+100</div>
                <div class="text-slate-500 text-xs font-medium uppercase tracking-widest">Suppliers</div>
            </div>
            <div class="text-center">
                <div class="text-3xl font-bold text-slate-900 mb-1">85+</div>
                <div class="text-slate-500 text-xs font-medium uppercase tracking-widest">Countries</div>
            </div>
            <div class="text-center">
                <div class="text-3xl font-bold text-slate-900 mb-1">+200</div>
                <div class="text-slate-500 text-xs font-medium uppercase tracking-widest">Clients</div>
            </div>
            <div class="text-center">
                <div class="text-3xl font-bold text-slate-900 mb-1">18%</div>
                <div class="text-slate-500 text-xs font-medium uppercase tracking-widest">Savings</div>
            </div>
        </div>
    </div>

    <!-- Process Section -->
    <section id="process" class="py-32 bg-white relative">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-24 reveal">
                <h2 class="text-3xl md:text-4xl font-bold text-slate-900 mb-6 tracking-tight">How We Work</h2>
                <p class="text-lg text-slate-500 font-light">Efficient sourcing in three simple steps.</p>
            </div>

            <div class="relative space-y-24">
                <!-- Vertical Line for Desktop -->
                <div class="hidden lg:block absolute left-1/2 top-0 bottom-0 w-px bg-gray-100 -translate-x-1/2"></div>

                <!-- Step 1 -->
                <div class="flex flex-col lg:flex-row items-center gap-12 lg:gap-24 reveal">
                    <div class="flex-1 lg:text-right">
                        <span class="inline-block text-brand-600 font-bold text-sm mb-2 tracking-wider uppercase">Step 01</span>
                        <h3 class="text-2xl font-bold text-slate-900 mb-4">Submit Request</h3>
                        <p class="text-slate-500 leading-relaxed mb-6">Describe product specifications, quantities, and destination. Use our guided forms for precision.</p>
                        <ul class="inline-block text-left space-y-2 text-sm text-slate-600">
                            <li class="flex items-center gap-2"><i data-lucide="check" class="w-4 h-4 text-brand-500"></i> Guided form</li>
                            <li class="flex items-center gap-2"><i data-lucide="check" class="w-4 h-4 text-brand-500"></i> Technical specs</li>
                        </ul>
                    </div>
                    <div class="flex-1 w-full">
                        <img src="{{ asset('images/fs2.jpg') }}" alt="Submit Request" class="w-full h-80 object-cover rounded-xl shadow-lg shadow-gray-200/50">
                    </div>
                </div>

                <!-- Step 2 -->
                <div class="flex flex-col lg:flex-row-reverse items-center gap-12 lg:gap-24 reveal">
                    <div class="flex-1 lg:text-left">
                        <span class="inline-block text-brand-600 font-bold text-sm mb-2 tracking-wider uppercase">Step 02</span>
                        <h3 class="text-2xl font-bold text-slate-900 mb-4">Compare Quotes</h3>
                        <p class="text-slate-500 leading-relaxed mb-6">Receive multiple quotes from verified suppliers. Compare pricing, lead times, and terms transparently.</p>
                        <ul class="inline-block text-left space-y-2 text-sm text-slate-600">
                            <li class="flex items-center gap-2"><i data-lucide="check" class="w-4 h-4 text-brand-500"></i> Price breakdown</li>
                            <li class="flex items-center gap-2"><i data-lucide="check" class="w-4 h-4 text-brand-500"></i> Direct communication</li>
                        </ul>
                    </div>
                    <div class="flex-1 w-full">
                        <img src="{{ asset('images/fs3.jpg') }}" alt="Compare Quotes" class="w-full h-80 object-cover rounded-xl shadow-lg shadow-gray-200/50">
                    </div>
                </div>

                <!-- Step 3 -->
                <div class="flex flex-col lg:flex-row items-center gap-12 lg:gap-24 reveal">
                    <div class="flex-1 lg:text-right">
                        <span class="inline-block text-brand-600 font-bold text-sm mb-2 tracking-wider uppercase">Step 03</span>
                        <h3 class="text-2xl font-bold text-slate-900 mb-4">Track Delivery</h3>
                        <p class="text-slate-500 leading-relaxed mb-6">Follow your order from production to final delivery. Get real-time updates on your dashboard.</p>
                        <ul class="inline-block text-left space-y-2 text-sm text-slate-600">
                            <li class="flex items-center gap-2"><i data-lucide="check" class="w-4 h-4 text-brand-500"></i> Real-time tracking</li>
                            <li class="flex items-center gap-2"><i data-lucide="check" class="w-4 h-4 text-brand-500"></i> Automatic notifications</li>
                        </ul>
                    </div>
                    <div class="flex-1 w-full">
                        <img src="{{ asset('images/fs4.jpg') }}" alt="Track Delivery" class="w-full h-80 object-cover rounded-xl shadow-lg shadow-gray-200/50">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Solutions Section -->
    <section id="solutions" class="py-32 bg-slate-50">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="mb-20 reveal">
                <h2 class="text-3xl md:text-4xl font-bold text-slate-900 mb-6 tracking-tight">Why Source With Us?</h2>
                <p class="text-lg text-slate-500 font-light max-w-2xl">We bridge the gap between global supply and your business needs.</p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <!-- Card 1 -->
                <div class="bg-white p-8 rounded-2xl border border-gray-100 shadow-sm hover:shadow-lg transition-all duration-300 reveal delay-100">
                    <div class="w-12 h-12 bg-orange-50 rounded-lg flex items-center justify-center mb-6">
                        <i data-lucide="globe" class="w-6 h-6 text-brand-600"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3">Qualified Network</h3>
                    <p class="text-slate-500 text-sm leading-relaxed">Access hundreds of international suppliers, vetted for reliability and quality standards.</p>
                </div>
                
                <!-- Card 2 -->
                <div class="bg-white p-8 rounded-2xl border border-gray-100 shadow-sm hover:shadow-lg transition-all duration-300 reveal delay-200">
                    <div class="w-12 h-12 bg-orange-50 rounded-lg flex items-center justify-center mb-6">
                        <i data-lucide="zap" class="w-6 h-6 text-brand-600"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3">Maximum Efficiency</h3>
                    <p class="text-slate-500 text-sm leading-relaxed">Digitize complex tasks from quote collection to order management, saving weeks of work.</p>
                </div>

                <!-- Card 3 -->
                <div class="bg-white p-8 rounded-2xl border border-gray-100 shadow-sm hover:shadow-lg transition-all duration-300 reveal delay-300">
                    <div class="w-12 h-12 bg-orange-50 rounded-lg flex items-center justify-center mb-6">
                        <i data-lucide="shield-check" class="w-6 h-6 text-brand-600"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3">Total Transparency</h3>
                    <p class="text-slate-500 text-sm leading-relaxed">Track every step of your order in real-time. Maintain complete control and visibility.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials -->
    <section id="testimonials" class="py-32 bg-white border-t border-gray-100">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="text-center mb-20 reveal">
                <h2 class="text-3xl md:text-4xl font-bold text-slate-900 tracking-tight">Client Stories</h2>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <!-- Testimonial 1 -->
                <div class="bg-white p-8 rounded-2xl border border-gray-100 shadow-sm reveal delay-100 flex flex-col h-full hover:border-brand-100 transition-colors">
                    <div class="flex text-brand-400 mb-6 gap-0.5">
                        <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                        <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                        <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                        <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                        <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                    </div>
                    <blockquote class="text-slate-600 text-base leading-relaxed mb-6 flex-grow">"This platform has genuinely saved us a huge amount of time. The response speed is much better than before, and the whole process has become smoother."</blockquote>
                    <div class="flex items-center gap-3 mt-auto">
                        <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-xs font-bold text-slate-600">MB</div>
                        <div>
                            <div class="font-bold text-slate-900 text-sm">Mohammed Ben Lahcen</div>
                            <div class="text-xs text-slate-400">Client</div>
                        </div>
                    </div>
                </div>

                <!-- Testimonial 2 -->
                <div class="bg-white p-8 rounded-2xl border border-gray-100 shadow-sm reveal delay-200 flex flex-col h-full hover:border-brand-100 transition-colors">
                    <div class="flex text-brand-400 mb-6 gap-0.5">
                        <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                        <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                        <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                        <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                        <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                    </div>
                    <blockquote class="text-slate-600 text-base leading-relaxed mb-6 flex-grow">"The team is professional, the company is trustworthy, and communication is excellent. Working with them has been one of my best decisions."</blockquote>
                    <div class="flex items-center gap-3 mt-auto">
                        <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-xs font-bold text-slate-600">HE</div>
                        <div>
                            <div class="font-bold text-slate-900 text-sm">Hafid El Hachemi</div>
                            <div class="text-xs text-slate-400">Client</div>
                        </div>
                    </div>
                </div>

                <!-- Testimonial 3 -->
                <div class="bg-white p-8 rounded-2xl border border-gray-100 shadow-sm reveal delay-300 flex flex-col h-full hover:border-brand-100 transition-colors">
                    <div class="flex text-brand-400 mb-6 gap-0.5">
                        <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                        <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                        <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                        <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                        <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                    </div>
                    <blockquote class="text-slate-600 text-base leading-relaxed mb-6 flex-grow">"The prices are truly competitive. Plus, the quality and follow-up make the whole experience safer and more reliable. Really impressive."</blockquote>
                    <div class="flex items-center gap-3 mt-auto">
                        <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-xs font-bold text-slate-600">FM</div>
                        <div>
                            <div class="font-bold text-slate-900 text-sm">Fati Masaaoudi</div>
                            <div class="text-xs text-slate-400">Client</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-24 bg-slate-900 relative overflow-hidden">
        <div class="relative z-10 max-w-4xl mx-auto px-6 text-center reveal">
            <h2 class="text-4xl md:text-5xl font-bold text-white mb-6 tracking-tight">
                Optimize your sourcing today.
            </h2>
            <p class="text-lg text-slate-400 mb-10 font-light">
                Join thousands of companies digitizing their supply chain.
            </p>
            
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('register') }}" class="px-8 py-3.5 bg-brand-600 hover:bg-brand-500 text-white rounded-full font-medium text-base shadow-lg transition-all">
                    Create Free Account
                </a>
                <a href="#" class="px-8 py-3.5 bg-transparent border border-slate-700 hover:border-slate-500 text-white rounded-full font-medium text-base transition-all">
                    Schedule Demo
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-white text-slate-500 py-16 border-t border-gray-100">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="grid md:grid-cols-4 gap-12 mb-16">
                <div class="col-span-1 md:col-span-1">
                    <div class="flex items-center gap-3 mb-6 text-slate-900">
                        <img src="{{ asset('images/logo5.jpg') }}" alt="Logo" class="w-8 h-8 rounded-lg">
                        <span class="text-lg font-bold">FastSourcingBrothers</span>
                    </div>
                    <p class="text-sm mb-6 leading-relaxed">Simplifying global procurement with technology and trust.</p>
                    <div class="flex gap-4">
                        <a href="https://www.facebook.com/fastsourcingbrothers" target="_blank" class="text-slate-400 hover:text-brand-600 transition-colors">
                            <i data-lucide="facebook" class="w-5 h-5"></i>
                        </a>
                        <a href="https://www.linkedin.com/company/fastsourcingbrothers" target="_blank" class="text-slate-400 hover:text-brand-600 transition-colors">
                            <i data-lucide="linkedin" class="w-5 h-5"></i>
                        </a>
                        <a href="https://www.instagram.com/fastsourcingbrothers" target="_blank" class="text-slate-400 hover:text-brand-600 transition-colors">
                            <i data-lucide="instagram" class="w-5 h-5"></i>
                        </a>
                    </div>
                </div>
                
                <div>
                    <h4 class="font-semibold text-slate-900 mb-6 text-sm">Platform</h4>
                    <ul class="space-y-3 text-sm">
                        <li><a href="#" class="hover:text-brand-600 transition-colors">Verified Suppliers</a></li>
                        <li><a href="#process" class="hover:text-brand-600 transition-colors">How it Works</a></li>
                        <li><a href="#" class="hover:text-brand-600 transition-colors">Pricing</a></li>
                        <li><a href="#" class="hover:text-brand-600 transition-colors">API Integration</a></li>
                    </ul>
                </div>
                
                <div>
                    <h4 class="font-semibold text-slate-900 mb-6 text-sm">Resources</h4>
                    <ul class="space-y-3 text-sm">
                        <li><a href="#" class="hover:text-brand-600 transition-colors">Blog</a></li>
                        <li><a href="#" class="hover:text-brand-600 transition-colors">Procurement Guides</a></li>
                        <li><a href="#" class="hover:text-brand-600 transition-colors">Case Studies</a></li>
                        <li><a href="#" class="hover:text-brand-600 transition-colors">Help Center</a></li>
                    </ul>
                </div>
                
                <div>
                    <h4 class="font-semibold text-slate-900 mb-6 text-sm">Contact</h4>
                    <ul class="space-y-3 text-sm">
                        <li class="flex items-center gap-2">
                            <i data-lucide="mail" class="w-4 h-4 text-brand-600"></i>
                            contact@fastsourcingbrothers.com
                        </li>
                        <li class="flex items-center gap-2">
                            <i data-lucide="phone" class="w-4 h-4 text-brand-600"></i>
                            +1 (555) 123-4567
                        </li>
                    </ul>
                </div>
            </div>
            
            <div class="pt-8 border-t border-gray-100 text-center text-sm flex flex-col md:flex-row justify-between items-center gap-6">
                <div class="flex flex-col items-center md:items-start gap-1">
                    <p class="text-slate-500">© 2025 FastSourcingBrothers. All rights reserved.</p>
                    <p class="text-slate-400 flex items-center gap-1.5 text-xs">
                        Made with <i data-lucide="heart" class="w-3 h-3 text-red-500 fill-red-500"></i> by the FSB Team
                    </p>
                </div>
                <div class="flex gap-6 text-slate-500">
                    <a href="#" class="hover:text-slate-900 transition-colors">Privacy Policy</a>
                    <a href="#" class="hover:text-slate-900 transition-colors">Terms</a>
                    <a href="#" class="hover:text-slate-900 transition-colors">Cookies</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script>
        // Initialize Lucide Icons
        lucide.createIcons();

        // 1. Text Typing Effect
        new Typed('#typed-output', {
            strings: ['Efficient.', 'Secure.', 'Global.', 'Simple.'],
            typeSpeed: 50,
            backSpeed: 30,
            backDelay: 2000,
            loop: true,
            cursorChar: '|',
        });

        // 2. Navbar Scroll Effect
        const navbar = document.getElementById('navbar');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 20) {
                navbar.classList.add('glass-nav', 'py-3', 'shadow-sm');
                navbar.classList.remove('py-4', 'border-transparent');
            } else {
                navbar.classList.remove('glass-nav', 'py-3', 'shadow-sm');
                navbar.classList.add('py-4', 'border-transparent');
            }
        });

        // 3. Mobile Menu Toggle
        const btn = document.getElementById('mobile-menu-btn');
        const menu = document.getElementById('mobile-menu');
        const links = document.querySelectorAll('.mobile-link');

        btn.addEventListener('click', () => {
            menu.classList.toggle('hidden', !menu.classList.contains('hidden'));
        });

        // Close menu when clicking a link
        links.forEach(link => {
            link.addEventListener('click', () => {
                menu.classList.add('hidden');
            });
        });

        // 4. Scroll Reveal Animation
        const revealElements = document.querySelectorAll('.reveal');
        const revealCallback = (entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('active');
                    observer.unobserve(entry.target);
                }
            });
        };
        const observer = new IntersectionObserver(revealCallback, {
            threshold: 0.1,
            rootMargin: "0px 0px -50px 0px"
        });
        revealElements.forEach(el => observer.observe(el));
    </script>
</body>
</html>