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

        /* Three.js Canvas */
        #three-canvas { position:fixed; top:0; left:0; width:100%; height:100vh; z-index:0; pointer-events:none; }
        .content-wrapper { position:relative; z-index:1; }

        /* Glass Navbar */
        .glass-nav {
            background: rgba(255,255,255,0.85);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(226,232,240,0.6);
        }

        /* Gradient Text */
        .gradient-text {
            background: linear-gradient(135deg, #dc2626 0%, #ca8a04 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Enterprise Frame */
        .enterprise-frame {
            position:relative; border-radius:1rem; overflow:hidden; background:white;
            box-shadow: 0 25px 50px -12px rgba(0,0,0,0.15);
            border: 1px solid rgba(255,255,255,0.8);
            transform: translateZ(0);
        }
        .enterprise-frame:hover img { transform: scale(1.03); }
        .enterprise-frame img { transition: transform 0.7s cubic-bezier(0.4,0,0.2,1); }

        /* Browser Mockup */
        .browser-header {
            height:32px; background:#f8fafc; border-bottom:1px solid #e2e8f0;
            display:flex; align-items:center; padding:0 12px; gap:6px;
        }
        .dot { width:10px; height:10px; border-radius:50%; }

        /* Animations */
        @keyframes fadeUp { from { opacity:0; transform:translateY(20px); } to { opacity:1; transform:translateY(0); } }
        .reveal { opacity:0; animation: fadeUp 0.8s ease-out forwards; }
        .delay-100 { animation-delay:0.1s; }
        .delay-200 { animation-delay:0.2s; }
        .delay-300 { animation-delay:0.3s; }

        /* Pulsing dot */
        @keyframes pulse-dot { 0%,100%{opacity:1;transform:scale(1)} 50%{opacity:.5;transform:scale(1.5)} }
        .pulse-dot { animation: pulse-dot 2s ease-in-out infinite; }

        /* Maintenance icon bounce */
        @keyframes float { 0%,100%{transform:translateY(0)} 50%{transform:translateY(-8px)} }
        .float-anim { animation: float 3s ease-in-out infinite; }

        /* RTL */
        [dir="rtl"] body { font-family:'Segoe UI',Tahoma,Arial,sans-serif; }
        [dir="rtl"] .lg\:text-left { text-align:right; }
        [dir="rtl"] .lg\:order-1 { order:2; }
        [dir="rtl"] .lg\:order-2 { order:1; }
    </style>
</head>
<body class="bg-slate-50 text-slate-900 selection:bg-red-100 selection:text-red-900">

    <!-- Three.js Canvas -->
    <canvas id="three-canvas"></canvas>

    <div class="content-wrapper">

        <!-- Floating Navbar (same as welcome) -->
        <div class="fixed top-0 left-0 right-0 z-50 pt-4 px-4 flex justify-center">
            <nav class="glass-nav w-full max-w-6xl rounded-2xl shadow-sm border border-white/50 px-6 h-16 flex items-center justify-between">
                <a href="/" class="flex-shrink-0 flex items-center gap-2">
                    <img class="h-20 w-20" src="{{ asset('images/logo1.png') }}" alt="{{ config('app.name') }}">
                </a>
                <div class="flex items-center gap-3">
                    <div class="hidden sm:flex items-center bg-slate-100 rounded-lg p-0.5 text-xs font-bold">
                        <a href="/eng/maintenance" class="px-2.5 py-1 rounded-md transition-all {{ app()->getLocale() === 'en' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-500 hover:text-slate-900' }}">EN</a>
                        <a href="/fr/maintenance" class="px-2.5 py-1 rounded-md transition-all {{ app()->getLocale() === 'fr' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-500 hover:text-slate-900' }}">FR</a>
                        <a href="/ar/maintenance" class="px-2.5 py-1 rounded-md transition-all {{ app()->getLocale() === 'ar' ? 'bg-white text-slate-900 shadow-sm' : 'text-slate-500 hover:text-slate-900' }}">AR</a>
                    </div>
                </div>
            </nav>
        </div>

        <!-- Hero Section (matching welcome layout) -->
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

                        <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                            <a href="/" class="px-8 py-4 bg-red-600 text-white rounded-xl font-semibold hover:bg-red-700 transition-all shadow-xl shadow-red-500/20 hover:scale-[1.02]">
                                {{ __('maintenance.back_home') }}
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
                                <div class="text-2xl font-bold text-slate-900">SOON</div>
                                <div class="text-xs text-slate-500 uppercase font-medium mt-1">{{ __('maintenance.back') }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Side: Enterprise Frame (illustration) -->
                    <div class="relative reveal delay-200">
                        <div class="absolute -top-12 -right-12 w-64 h-64 bg-yellow-400/10 rounded-full blur-3xl"></div>
                        <div class="absolute -bottom-12 -left-12 w-64 h-64 bg-red-400/10 rounded-full blur-3xl"></div>

                        <div class="enterprise-frame transform rotate-1 hover:rotate-0 transition-transform duration-500">
                            <!-- Browser Mockup Header -->
                            <div class="browser-header">
                                <div class="dot" style="background:#ef4444"></div>
                                <div class="dot" style="background:#f59e0b"></div>
                                <div class="dot" style="background:#22c55e"></div>
                                <span class="ml-3 text-[10px] text-slate-400 font-medium">smart-sourcing.com</span>
                            </div>

                            <!-- Maintenance Illustration -->
                            <div class="bg-gradient-to-br from-slate-50 to-slate-100 p-12 flex flex-col items-center justify-center min-h-[320px]">
                                <div class="float-anim mb-6">
                                    <svg class="w-20 h-20 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                </div>
                                <p class="text-slate-400 text-sm font-medium uppercase tracking-widest">{{ __('maintenance.under_maintenance') }}</p>
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

        <!-- Footer (same as welcome) -->
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

    <!-- Three.js Particles + Shapes (same as welcome) -->
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
            var particleCount = 800;
            var particleGeometry = new THREE.BufferGeometry();
            var positions = new Float32Array(particleCount * 3);
            for (var i = 0; i < particleCount * 3; i++) {
                positions[i] = (Math.random() - 0.5) * 20;
            }
            particleGeometry.setAttribute('position', new THREE.BufferAttribute(positions, 3));
            var particleMaterial = new THREE.PointsMaterial({ color: 0xdc2626, size: 0.015, transparent: true, opacity: 0.6, blending: THREE.AdditiveBlending });
            var particles = new THREE.Points(particleGeometry, particleMaterial);
            scene.add(particles);

            // Wireframe shapes (same as welcome)
            var torusGeom = new THREE.TorusGeometry(1, 0.3, 16, 100);
            var torusMat = new THREE.MeshBasicMaterial({ color: 0xca8a04, wireframe: true, transparent: true, opacity: 0.25 });
            var torus = new THREE.Mesh(torusGeom, torusMat);
            torus.position.set(2, 1, -2);
            scene.add(torus);

            var icoGeom = new THREE.IcosahedronGeometry(0.8, 0);
            var icoMat = new THREE.MeshBasicMaterial({ color: 0xdc2626, wireframe: true, transparent: true, opacity: 0.2 });
            var ico = new THREE.Mesh(icoGeom, icoMat);
            ico.position.set(-2, -1, -3);
            scene.add(ico);

            var octGeom = new THREE.OctahedronGeometry(0.6, 0);
            var octMat = new THREE.MeshBasicMaterial({ color: 0xef4444, wireframe: true, transparent: true, opacity: 0.15 });
            var oct = new THREE.Mesh(octGeom, octMat);
            oct.position.set(0, 2, -4);
            scene.add(oct);

            // Mouse parallax
            var mouseX = 0, mouseY = 0;
            document.addEventListener('mousemove', function(e) {
                mouseX = (e.clientX / window.innerWidth - 0.5) * 0.5;
                mouseY = (e.clientY / window.innerHeight - 0.5) * 0.5;
            });

            // Animate
            function animate() {
                requestAnimationFrame(animate);
                particles.rotation.y += 0.0003;
                torus.rotation.x += 0.003;
                torus.rotation.y += 0.002;
                ico.rotation.x += 0.004;
                ico.rotation.z += 0.002;
                oct.rotation.y += 0.005;
                oct.rotation.z += 0.003;
                camera.position.x += (mouseX - camera.position.x) * 0.02;
                camera.position.y += (-mouseY - camera.position.y) * 0.02;
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
        var observer = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) entry.target.style.animationPlayState = 'running';
            });
        }, { threshold: 0.1 });
        document.querySelectorAll('.reveal').forEach(function(el) {
            el.style.animationPlayState = 'paused';
            observer.observe(el);
        });
    </script>
</body>
</html>
