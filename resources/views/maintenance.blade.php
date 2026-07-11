<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <title>{{ config('app.name') }} – Maintenance</title>
    <link rel="icon" type="image/png" href="/favicon-96x96.png" sizes="96x96" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;800&family=Style+Script&family=Montserrat:wght@300;400;600&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
    <script>
        tailwind.config = {
            theme: {
                fontFamily: { sans: ['Inter', 'sans-serif'] },
                extend: {
                    colors: {
                        primary: {
                            50:'#fef2f2',100:'#fee2e2',200:'#fecaca',300:'#fca5a5',400:'#f87171',
                            500:'#ef4444',600:'#dc2626',700:'#b91c1c',800:'#991b1b',900:'#7f1d1d',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        *, *::before, *::after { margin:0; padding:0; box-sizing:border-box; }
        body { -webkit-font-smoothing:antialiased; -moz-osx-font-smoothing:grayscale; overflow-x:hidden; }

        #three-canvas { position:fixed; top:0; left:0; width:100%; height:100vh; z-index:0; pointer-events:none; }
        .content-wrapper { position:relative; z-index:1; }

        .glass-nav {
            background: rgba(255,255,255,0.85);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(226,232,240,0.6);
        }
        .glass-panel {
            background: rgba(255,255,255,0.7);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255,255,255,0.5);
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05), 0 2px 4px -1px rgba(0,0,0,0.03);
        }

        .gradient-text {
            background: linear-gradient(135deg, #dc2626 0%, #ca8a04 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .enterprise-frame {
            position:relative; border-radius:1rem; overflow:hidden; background:white;
            box-shadow: 0 25px 50px -12px rgba(0,0,0,0.15);
            border: 1px solid rgba(255,255,255,0.8);
            transform: translateZ(0);
        }
        .enterprise-frame img { transition: transform 0.7s cubic-bezier(0.4,0,0.2,1); }
        .enterprise-frame:hover img { transform: scale(1.03); }

        .browser-header {
            height:32px; background:#f8fafc; border-bottom:1px solid #e2e8f0;
            display:flex; align-items:center; padding:0 12px; gap:6px;
        }
        .dot { width:10px; height:10px; border-radius:50%; }

        .pro-card {
            background:white; border:1px solid #e2e8f0; border-radius:1rem;
            transition: all 0.3s ease;
        }
        .pro-card:hover {
            border-color:#fca5a5;
            box-shadow: 0 20px 25px -5px rgba(0,0,0,0.05), 0 10px 10px -5px rgba(0,0,0,0.01);
            transform: translateY(-4px);
        }

        @keyframes fadeUp { from { opacity:0; transform:translateY(20px); } to { opacity:1; transform:translateY(0); } }
        .reveal { opacity:0; animation: fadeUp 0.8s ease-out forwards; }
        .delay-100 { animation-delay:0.1s; }
        .delay-200 { animation-delay:0.2s; }
        .delay-300 { animation-delay:0.3s; }
        .delay-400 { animation-delay:0.4s; }

        @keyframes pulse-dot { 0%,100%{opacity:1;transform:scale(1)} 50%{opacity:.5;transform:scale(1.5)} }
        .pulse-dot { animation: pulse-dot 2s ease-in-out infinite; }

        @keyframes float { 0%,100%{transform:translateY(0)} 50%{transform:translateY(-10px)} }
        .float-anim { animation: float 3s ease-in-out infinite; }

        @keyframes shimmer {
            0% { background-position: -200% 0; }
            100% { background-position: 200% 0; }
        }
        .shimmer-bar {
            background: linear-gradient(90deg, #e2e8f0 25%, #f1f5f9 50%, #e2e8f0 75%);
            background-size: 200% 100%;
            animation: shimmer 2s linear infinite;
        }

        @keyframes spin-slow { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
        .spin-slow { animation: spin-slow 20s linear infinite; }

        @keyframes countdown-pulse {
            0%,100% { box-shadow: 0 0 0 0 rgba(220,38,38,0.2); }
            50% { box-shadow: 0 0 0 12px rgba(220,38,38,0); }
        }
        .countdown-ring { animation: countdown-pulse 2s ease-in-out infinite; }

        [dir="rtl"] body { font-family:'Segoe UI',Tahoma,Arial,sans-serif; }
        [dir="rtl"] .lg\:text-left { text-align:right; }
        [dir="rtl"] .lg\:order-1 { order:2; }
        [dir="rtl"] .lg\:order-2 { order:1; }
        [dir="rtl"] .pl-lang { padding-left:0; padding-right:0.5rem; }
    </style>
</head>
<body class="bg-slate-50 text-slate-900 selection:bg-red-100 selection:text-red-900">

    <!-- Three.js Canvas -->
    <canvas id="three-canvas"></canvas>

    <div class="content-wrapper">

        <!-- ═══ Floating Navbar ═══ -->
        <div class="fixed top-0 left-0 right-0 z-50 pt-4 px-4 flex justify-center">
            <nav class="glass-nav w-full max-w-6xl rounded-2xl shadow-sm border border-white/50 px-6 h-16 flex items-center justify-between">
                <a href="/" class="flex-shrink-0 flex items-center gap-2">
                    <img class="h-20 w-20" src="{{ asset('images/logo1.png') }}" alt="{{ config('app.name') }}">
                </a>
                <div class="flex items-center gap-3">
                    <div class="hidden sm:flex items-center bg-slate-100 rounded-lg p-0.5 text-xs font-bold">
                        <a href="/eng" class="px-2.5 py-1 rounded-md transition-all {{ app()->getLocale() === 'en' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-500 hover:text-slate-900' }}">EN</a>
                        <a href="/fr" class="px-2.5 py-1 rounded-md transition-all {{ app()->getLocale() === 'fr' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-500 hover:text-slate-900' }}">FR</a>
                        <a href="/ar" class="px-2.5 py-1 rounded-md transition-all {{ app()->getLocale() === 'ar' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-500 hover:text-slate-900' }}">AR</a>
                    </div>
                </div>
            </nav>
        </div>

        <!-- ═══ Hero Section ═══ -->
        <section class="pt-40 pb-20 px-4 sm:px-6 relative">
            <div class="max-w-7xl mx-auto">
                <div class="grid lg:grid-cols-2 gap-16 lg:gap-24 items-center">

                    <!-- Text Content -->
                    <div class="text-center lg:text-left reveal">
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-amber-50 border border-amber-200 text-amber-700 text-xs font-bold uppercase tracking-wide mb-6">
                            <span class="w-2 h-2 rounded-full bg-amber-500 pulse-dot"></span>
                            {{ __('maintenance.badge') }}
                        </div>

                        <h1 class="text-5xl sm:text-6xl lg:text-7xl font-extrabold tracking-tight leading-[1.1] mb-6">
                            <span class="text-slate-900">{{ __('maintenance.title') }}</span>
                        </h1>

                        <p class="text-lg text-slate-600 leading-relaxed mb-8 max-w-lg mx-auto lg:mx-0">
                            {{ $message ?: __('maintenance.default_message') }}
                        </p>

                        <!-- ETA Countdown -->
                        <div class="inline-flex items-center gap-4 bg-white border border-slate-200 rounded-2xl px-6 py-4 mb-8 shadow-sm">
                            <div class="w-12 h-12 rounded-full bg-red-50 flex items-center justify-center countdown-ring">
                                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div class="text-left">
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ __('maintenance.estimated') }}</p>
                                <p class="text-sm font-bold text-slate-900">{{ __('maintenance.back_soon') }}</p>
                            </div>
                        </div>

                        <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                            <a href="/" class="px-8 py-4 bg-red-600 text-white rounded-xl font-semibold hover:bg-red-700 transition-all shadow-xl shadow-red-500/20 hover:scale-[1.02]">
                                {{ __('maintenance.back_home') }}
                            </a>
                            <a href="mailto:contact@smart-sourcing.com" class="px-8 py-4 bg-white text-slate-700 border border-slate-200 rounded-xl font-semibold hover:border-slate-300 hover:bg-slate-50 transition-all">
                                {{ __('maintenance.contact_support') }}
                            </a>
                        </div>

                        <!-- Stats Strip -->
                        <div class="mt-12 pt-8 border-t border-slate-200 grid grid-cols-3 gap-8">
                            <div>
                                <div class="text-2xl font-bold text-slate-900">99.9%</div>
                                <div class="text-xs text-slate-500 uppercase font-medium mt-1">{{ __('maintenance.uptime') }}</div>
                            </div>
                            <div>
                                <div class="text-2xl font-bold text-slate-900">24/7</div>
                                <div class="text-xs text-slate-500 uppercase font-medium mt-1">{{ __('maintenance.support') }}</div>
                            </div>
                            <div>
                                <div class="text-2xl font-bold gradient-text">{{ __('maintenance.back_value') }}</div>
                                <div class="text-xs text-slate-500 uppercase font-medium mt-1">{{ __('maintenance.back') }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Side: Enterprise Frame -->
                    <div class="relative reveal delay-200">
                        <div class="absolute -top-12 -right-12 w-64 h-64 bg-yellow-400/10 rounded-full blur-3xl"></div>
                        <div class="absolute -bottom-12 -left-12 w-64 h-64 bg-red-400/10 rounded-full blur-3xl"></div>

                        <div class="enterprise-frame transform rotate-1 hover:rotate-0 transition-transform duration-500">
                            <div class="browser-header">
                                <div class="dot" style="background:#ef4444"></div>
                                <div class="dot" style="background:#f59e0b"></div>
                                <div class="dot" style="background:#22c55e"></div>
                                <span class="ml-3 text-[10px] text-slate-400 font-medium">smart-sourcing.com/maintenance</span>
                            </div>

                            <!-- Maintenance Illustration -->
                            <div class="bg-gradient-to-br from-slate-50 via-white to-red-50/30 p-8 sm:p-12 flex flex-col items-center justify-center min-h-[360px] relative overflow-hidden">
                                <!-- Decorative grid -->
                                <div class="absolute inset-0 opacity-[0.03]" style="background-image: radial-gradient(circle, #000 1px, transparent 1px); background-size: 24px 24px;"></div>

                                <!-- Spinning gear background -->
                                <div class="absolute top-6 right-6 opacity-[0.06] spin-slow">
                                    <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 15.5A3.5 3.5 0 0 1 8.5 12 3.5 3.5 0 0 1 12 8.5a3.5 3.5 0 0 1 3.5 3.5 3.5 3.5 0 0 1-3.5 3.5m7.43-2.53c.04-.32.07-.64.07-.97 0-.33-.03-.66-.07-1l2.11-1.63c.19-.15.24-.42.12-.64l-2-3.46c-.12-.22-.39-.3-.61-.22l-2.49 1c-.52-.4-1.08-.73-1.69-.98l-.38-2.65C14.46 2.18 14.25 2 14 2h-4c-.25 0-.46.18-.49.42l-.38 2.65c-.61.25-1.17.59-1.69.98l-2.49-1c-.23-.09-.49 0-.61.22l-2 3.46c-.13.22-.07.49.12.64L4.57 11c-.04.34-.07.67-.07 1 0 .33.03.65.07.97l-2.11 1.66c-.19.15-.25.42-.12.64l2 3.46c.12.22.39.3.61.22l2.49-1.01c.52.4 1.08.73 1.69.98l.38 2.65c.03.24.24.42.49.42h4c.25 0 .46-.18.49-.42l.38-2.65c.61-.25 1.17-.58 1.69-.98l2.49 1.01c.22.08.49 0 .61-.22l2-3.46c.12-.22.07-.49-.12-.64L19.43 12.97Z"/>
                                    </svg>
                                </div>
                                <div class="absolute bottom-10 left-6 opacity-[0.04] spin-slow" style="animation-direction:reverse; animation-duration:30s;">
                                    <svg class="w-24 h-24" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 15.5A3.5 3.5 0 0 1 8.5 12 3.5 3.5 0 0 1 12 8.5a3.5 3.5 0 0 1 3.5 3.5 3.5 3.5 0 0 1-3.5 3.5m7.43-2.53c.04-.32.07-.64.07-.97 0-.33-.03-.66-.07-1l2.11-1.63c.19-.15.24-.42.12-.64l-2-3.46c-.12-.22-.39-.3-.61-.22l-2.49 1c-.52-.4-1.08-.73-1.69-.98l-.38-2.65C14.46 2.18 14.25 2 14 2h-4c-.25 0-.46.18-.49.42l-.38 2.65c-.61.25-1.17.59-1.69.98l-2.49-1c-.23-.09-.49 0-.61.22l-2 3.46c-.13.22-.07.49.12.64L4.57 11c-.04.34-.07.67-.07 1 0 .33.03.65.07.97l-2.11 1.66c-.19.15-.25.42-.12.64l2 3.46c.12.22.39.3.61.22l2.49-1.01c.52.4 1.08.73 1.69.98l.38 2.65c.03.24.24.42.49.42h4c.25 0 .46-.18.49-.42l.38-2.65c.61-.25 1.17-.58 1.69-.98l2.49 1.01c.22.08.49 0 .61-.22l2-3.46c.12-.22.07-.49-.12-.64L19.43 12.97Z"/>
                                    </svg>
                                </div>

                                <!-- Main illustration -->
                                <div class="float-anim relative z-10 mb-6">
                                    <div class="w-24 h-24 rounded-full bg-gradient-to-br from-red-500 to-red-600 flex items-center justify-center shadow-2xl shadow-red-500/30">
                                        <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                    </div>
                                    <!-- Orbiting dots -->
                                    <div class="absolute inset-0 spin-slow">
                                        <div class="absolute -top-2 left-1/2 -translate-x-1/2 w-2.5 h-2.5 bg-amber-400 rounded-full shadow-lg"></div>
                                        <div class="absolute top-1/2 -right-2 -translate-y-1/2 w-2 h-2 bg-red-400 rounded-full shadow-lg"></div>
                                        <div class="absolute -bottom-1 left-1/4 w-1.5 h-1.5 bg-slate-300 rounded-full"></div>
                                    </div>
                                </div>

                                <p class="text-slate-400 text-sm font-medium uppercase tracking-widest relative z-10 mb-2">{{ __('maintenance.under_maintenance') }}</p>

                                <!-- Progress bar -->
                                <div class="w-48 h-1 bg-slate-200 rounded-full overflow-hidden relative z-10">
                                    <div class="h-full shimmer-bar rounded-full" style="width:60%"></div>
                                </div>
                            </div>

                            <!-- Floating Status Card -->
                            <div class="absolute bottom-6 left-6 right-6 bg-white/95 backdrop-blur-md p-4 rounded-xl shadow-lg border border-slate-100 flex items-center gap-4">
                                <div class="w-10 h-10 rounded-full bg-amber-100 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-slate-900">{{ __('maintenance.status_title') }}</p>
                                    <p class="text-xs text-slate-500">{{ __('maintenance.status_subtitle') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ═══ Trust Bar ═══ -->
        <section class="py-12 bg-white border-y border-slate-100 reveal delay-300">
            <div class="max-w-7xl mx-auto px-4">
                <div class="flex flex-col items-center gap-10">
                    <p class="text-[11px] font-bold uppercase tracking-[0.2em] text-slate-400">{{ __('maintenance.trust_bar') }}</p>
                    <div class="flex flex-wrap items-center justify-center gap-10 md:gap-20">
                        <div class="flex items-center gap-10">
                            <svg class="h-8 md:h-10 w-auto" viewBox="0 0 750 471" xmlns="http://www.w3.org/2000/svg"><rect width="750" height="471" rx="40" fill="#1A1F71"/><path d="M278.2 334.5l33.4-195.7h53.4l-33.4 195.7h-53.4zM524.3 142.8c-10.6-3.9-27.2-8.1-47.9-8.1-52.8 0-90 26.5-90.3 64.4-.3 28 26.5 43.6 46.7 52.9 20.7 9.5 27.7 15.6 27.6 24.1-.1 13-16.6 19-31.9 19-21.3 0-32.6-2.9-50.1-10l-6.9-3.1-7.5 43.5c12.5 5.4 35.5 10.1 59.4 10.3 56.1 0 92.5-26.2 93-66.8.2-22.2-14-39.2-44.7-53.2-18.6-9.1-30-15.1-29.9-24.3 0-8.1 9.7-16.8 30.5-16.8 17.4-.3 30 3.5 39.8 7.5l4.8 2.2 7.4-43.6zM657.5 139.8h-41.3c-12.8 0-22.3 3.5-27.9 16.3l-79.2 178.4h56l11.1-29h68.4l6.5 29.1h49.5l-43.1-194.8zm-65.8 127.8l21.1-54.2 12 54.2h-33.1zM221.6 139.8l-52.4 133.3-5.6-27-18.7-89c-3.2-12.3-12.5-15.9-24-16.3H38.6l-.9 4.3c20.3 4.9 38.5 12 52.1 20 7.8 4.5 10.1 8.4 12.6 18.9l42.1 152.5h56.6l84.1-196.7h-63.6z" fill="white"/></svg>
                            <svg class="h-7 md:h-10 w-auto" viewBox="0 0 32 32" fill="none"><circle cx="12" cy="16" r="10" fill="#EB001B" fill-opacity="0.8"/><circle cx="20" cy="16" r="10" fill="#F79E1B" fill-opacity="0.8"/><path d="M16 10.3c1.9 1.5 3.1 3.8 3.1 6.3s-1.2 4.8-3.1 6.3c-1.9-1.5-3.1-3.8-3.1-6.3s1.2-4.8 3.1-6.3z" fill="#FF5F00"/></svg>
                        </div>
                        <div class="hidden md:block w-px h-8 bg-slate-200"></div>
                        <div class="flex items-center gap-10">
                            <span class="text-2xl md:text-3xl font-black text-[#D40511] italic tracking-tighter">DHL</span>
                            <div class="flex items-center text-xl md:text-2xl font-black italic tracking-tighter"><span class="text-[#4D148C]">Fed</span><span class="text-[#FF6600]">Ex</span></div>
                            <span class="text-2xl md:text-3xl font-black text-[#351C15] tracking-tighter">UPS</span>
                            <span class="text-lg md:text-xl font-bold text-[#e11d48]">aramex</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ═══ What We're Doing (Benefits cards) ═══ -->
        <section class="py-24 px-4 relative">
            <div class="max-w-7xl mx-auto">
                <div class="text-center max-w-2xl mx-auto mb-16 reveal">
                    <h2 class="text-3xl font-bold text-slate-900">{{ __('maintenance.works_heading') }}</h2>
                    <p class="text-slate-600 mt-4">{{ __('maintenance.works_subheading') }}</p>
                </div>
                <div class="grid md:grid-cols-3 gap-8">
                    <div class="pro-card p-8 reveal">
                        <div class="w-12 h-12 bg-red-50 rounded-lg flex items-center justify-center mb-6">
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-3">{{ __('maintenance.work1_title') }}</h3>
                        <p class="text-slate-600 leading-relaxed">{{ __('maintenance.work1_desc') }}</p>
                    </div>
                    <div class="pro-card p-8 reveal delay-100">
                        <div class="w-12 h-12 bg-amber-50 rounded-lg flex items-center justify-center mb-6">
                            <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-3">{{ __('maintenance.work2_title') }}</h3>
                        <p class="text-slate-600 leading-relaxed">{{ __('maintenance.work2_desc') }}</p>
                    </div>
                    <div class="pro-card p-8 reveal delay-200">
                        <div class="w-12 h-12 bg-green-50 rounded-lg flex items-center justify-center mb-6">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-3">{{ __('maintenance.work3_title') }}</h3>
                        <p class="text-slate-600 leading-relaxed">{{ __('maintenance.work3_desc') }}</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- ═══ CTA Section (Dark) ═══ -->
        <section class="py-24 px-4">
            <div class="max-w-4xl mx-auto reveal delay-300">
                <div class="relative bg-slate-900 rounded-3xl p-12 sm:p-16 text-center overflow-hidden">
                    <div class="absolute inset-0 opacity-10">
                        <div class="absolute top-0 left-1/4 w-64 h-64 bg-red-500 rounded-full blur-[100px]"></div>
                        <div class="absolute bottom-0 right-1/4 w-64 h-64 bg-yellow-500 rounded-full blur-[100px]"></div>
                    </div>
                    <div class="relative z-10">
                        <h2 class="text-3xl sm:text-4xl font-bold text-white mb-4">{{ __('maintenance.cta_heading') }}</h2>
                        <p class="text-slate-400 text-lg mb-8 max-w-lg mx-auto">{{ __('maintenance.cta_subheading') }}</p>
                        <a href="/" class="inline-block px-8 py-4 bg-white text-slate-900 rounded-xl font-semibold hover:bg-slate-100 transition-all shadow-xl">
                            {{ __('maintenance.cta_button') }}
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <!-- ═══ Footer ═══ -->
        <footer class="border-t border-slate-200 bg-white">
            <div class="max-w-7xl mx-auto px-6 py-12">
                <div class="flex flex-col md:flex-row items-center justify-between gap-6">
                    <div class="flex items-center gap-3">
                        <img class="h-10" src="{{ asset('images/logo0.png') }}" alt="{{ config('app.name') }}">
                        <span class="text-sm text-slate-500">© 2026 {{ config('app.name') }}. {{ __('maintenance.rights') }}</span>
                    </div>
                    <div class="flex items-center gap-6">
                        <a href="mailto:contact@smart-sourcing.com" class="text-sm text-slate-500 hover:text-red-600 transition-colors">contact@smart-sourcing.com</a>
                        <span class="text-slate-300">|</span>
                        <span class="text-sm text-slate-500">Casper, WY 82601</span>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <!-- Three.js Particles + Shapes -->
    <script>
        (function() {
            var canvas = document.getElementById('three-canvas');
            var renderer = new THREE.WebGLRenderer({ canvas: canvas, alpha: true, antialias: true });
            renderer.setSize(window.innerWidth, window.innerHeight);
            renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));

            var scene = new THREE.Scene();
            var camera = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 0.1, 1000);
            camera.position.z = 5;

            // Particles
            var count = 1500;
            var geo = new THREE.BufferGeometry();
            var pos = new Float32Array(count * 3);
            for (var i = 0; i < count * 3; i++) pos[i] = (Math.random() - 0.5) * 20;
            geo.setAttribute('position', new THREE.BufferAttribute(pos, 3));
            var mat = new THREE.PointsMaterial({ color: 0xdc2626, size: 0.015, transparent: true, opacity: 0.5, blending: THREE.AdditiveBlending });
            var pts = new THREE.Points(geo, mat);
            scene.add(pts);

            // Torus
            var t = new THREE.Mesh(
                new THREE.TorusGeometry(1, 0.3, 16, 100),
                new THREE.MeshBasicMaterial({ color: 0xca8a04, wireframe: true, transparent: true, opacity: 0.2 })
            );
            t.position.set(2.5, 1, -2); scene.add(t);

            // Icosahedron
            var ic = new THREE.Mesh(
                new THREE.IcosahedronGeometry(0.8, 0),
                new THREE.MeshBasicMaterial({ color: 0xdc2626, wireframe: true, transparent: true, opacity: 0.15 })
            );
            ic.position.set(-2, -1, -3); scene.add(ic);

            // Octahedron
            var oc = new THREE.Mesh(
                new THREE.OctahedronGeometry(0.6, 0),
                new THREE.MeshBasicMaterial({ color: 0xef4444, wireframe: true, transparent: true, opacity: 0.12 })
            );
            oc.position.set(0, 2, -4); scene.add(oc);

            // Mouse
            var mx = 0, my = 0;
            document.addEventListener('mousemove', function(e) {
                mx = (e.clientX / window.innerWidth - 0.5) * 0.5;
                my = (e.clientY / window.innerHeight - 0.5) * 0.5;
            });

            function animate() {
                requestAnimationFrame(animate);
                pts.rotation.y += 0.0003;
                t.rotation.x += 0.003; t.rotation.y += 0.002;
                ic.rotation.x += 0.004; ic.rotation.z += 0.002;
                oc.rotation.y += 0.005; oc.rotation.z += 0.003;
                camera.position.x += (mx - camera.position.x) * 0.02;
                camera.position.y += (-my - camera.position.y) * 0.02;
                camera.lookAt(scene.position);
                renderer.render(scene, camera);
            }
            animate();

            window.addEventListener('resize', function() {
                camera.aspect = window.innerWidth / window.innerHeight;
                camera.updateProjectionMatrix();
                renderer.setSize(window.innerWidth, window.innerHeight);
            });
        })();
    </script>

    <!-- Scroll reveal -->
    <script>
        var obs = new IntersectionObserver(function(entries) {
            entries.forEach(function(e) {
                if (e.isIntersecting) e.target.style.animationPlayState = 'running';
            });
        }, { threshold: 0.1 });
        document.querySelectorAll('.reveal').forEach(function(el) {
            el.style.animationPlayState = 'paused';
            obs.observe(el);
        });
    </script>
</body>
</html>
