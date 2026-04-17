<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <link rel="icon" type="image/png" href="/favicon-96x96.png" sizes="96x96" />
    <link rel="icon" type="image/svg+xml" href="/favicon.svg" />
    <link rel="shortcut icon" href="/favicon.ico" />
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png" />
    <link rel="manifest" href="/site.webmanifest" />
    <title>{{ $title ?? __('legal.common.legal') }} - FastSourcingBrothers</title>
    
    <!-- Font: Inter & Style Script & Montserrat -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;800&family=Style+Script&family=Montserrat:wght@300;400;600&display=swap" rel="stylesheet">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
    
    <script>
            tailwind.config = {
                theme: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    extend: {
                        colors: {
                            primary: {
                                50: '#fef2f2', 100: '#fee2e2', 200: '#fecaca', 300: '#fca5a5',
                                400: '#f87171', 500: '#ef4444', 600: '#dc2626', 700: '#b91c1c',
                                800: '#991b1b', 900: '#7f1d1d',
                            }
                        }
                    }
                }
            }
    </script>
    <style>
        body {
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            overflow-x: hidden;
            background: #ffffff;
        }

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

        .glass-nav {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(226, 232, 240, 0.6);
        }

        .reveal {
            opacity: 0;
            animation: fadeUp 0.8s ease-out forwards;
        }
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>

<body class="font-sans antialiased">
    <!-- Three.js Canvas -->
    <canvas id="three-canvas"></canvas>

    <div class="content-wrapper min-h-screen flex flex-col">
        
        <!-- Modern Floating Navbar -->
        <div class="fixed top-0 left-0 right-0 z-50 pt-4 px-4 flex justify-center">
            <nav class="glass-nav w-full max-w-6xl rounded-2xl shadow-sm border border-white/50 px-6 h-16 flex items-center justify-between">
                <!-- Logo -->
                <a href="{{ route('welcome') }}" class="flex-shrink-0 flex items-center gap-2">
                    <img class="h-20 w-20" src="{{ asset('images/logo1.png') }}" alt="{{ config('app.name') }}">
                </a>

                <!-- Desktop Menu -->
                <div class="hidden lg:flex items-center space-x-1">
                    <a href="{{ route('welcome') }}#how-it-works" class="px-4 py-2 text-sm font-medium text-slate-600 hover:text-red-600 rounded-lg hover:bg-red-50 transition-colors">{{ __('welcome.nav.process') }}</a>
                    <a href="{{ route('welcome') }}#benefits" class="px-4 py-2 text-sm font-medium text-slate-600 hover:text-red-600 rounded-lg hover:bg-red-50 transition-colors">{{ __('welcome.nav.benefits') }}</a>
                    <a href="{{ route('welcome') }}#testimonials" class="px-4 py-2 text-sm font-medium text-slate-600 hover:text-red-600 rounded-lg hover:bg-red-50 transition-colors">{{ __('welcome.nav.reviews') }}</a>
                </div>

                <!-- Auth -->
                <div class="flex items-center gap-3">
                    @auth
                        <a href="{{ route('dashboard') }}" class="px-5 py-2.5 bg-slate-900 text-white text-sm font-semibold rounded-xl hover:bg-slate-800 transition-all shadow-lg shadow-slate-900/10">
                            {{ __('welcome.nav.dashboard') }}
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="hidden sm:inline-block px-4 py-2 text-sm font-medium text-slate-700 hover:text-red-600">{{ __('welcome.nav.login') }}</a>
                        <a href="{{ route('register') }}" class="px-5 py-2.5 bg-slate-900 text-white text-sm font-semibold rounded-xl hover:bg-red-600 transition-all shadow-lg shadow-slate-900/10">
                            {{ __('welcome.nav.get_started') }}
                        </a>
                    @endauth
                </div>
            </nav>
        </div>

        <!-- Main Content -->
        <main class="flex-1 pt-32 pb-20 px-4">
            <div class="max-w-4xl mx-auto reveal">
                {{ $slot }}
            </div>
        </main>

        <!-- Footer -->
        <footer class="bg-white border-t border-slate-200 pt-16 pb-8 mt-auto">
            <div class="max-w-7xl mx-auto px-4">
                <div class="flex flex-col md:flex-row justify-between items-start gap-12 mb-12">
                    <div class="max-w-xs">
                        <div class="flex items-center gap-2 mb-4">
                            <span class="text-xl font-bold text-slate-900 tracking-tight">FastSourcingBrothers</span>
                        </div>
                        <p class="text-slate-500 text-sm leading-relaxed">{{ __('welcome.footer.tagline') }}</p>
                    </div>

                    <div class="flex flex-col gap-4 text-sm">
                        <h4 class="font-bold text-slate-900">{{ __('welcome.footer.contact') }}</h4>
                        <a href="mailto:support@fastsourcingbrothers.com" class="text-slate-500 hover:text-red-600 transition-colors flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            support@fastsourcingbrothers.com
                        </a>
                        <a href="{{ route('support') }}" class="text-slate-500 hover:text-red-600 transition-colors">{{ __('welcome.footer.contact_us') }}</a>
                    </div>

                    <div class="flex flex-col gap-3 text-sm">
                        <h4 class="font-bold text-slate-900">{{ __('welcome.footer.legal') }}</h4>
                        <a href="{{ route('refund-policy') }}" class="text-slate-500 hover:text-red-600 transition-colors font-semibold underline decoration-red-500/30">{{ __('welcome.footer.refund_policy') }}</a>
                        <a href="{{ route('shipping-policy') }}" class="text-slate-500 hover:text-red-600 transition-colors font-semibold underline decoration-red-500/30">{{ __('welcome.footer.shipping_policy') }}</a>
                        <a href="{{ route('privacy-policy') }}" class="text-slate-500 hover:text-red-600 transition-colors font-semibold underline decoration-red-500/30">{{ __('welcome.footer.privacy_policy') }}</a>
                    </div>
                </div>

                <div class="border-t border-slate-100 pt-8 text-center text-xs text-slate-400">
                    <p>{{ __('legal.common.copyright') }}</p>
                </div>
            </div>
        </footer>
    </div>

    <!-- Background Animation Script -->
    <script>
        const canvas = document.getElementById('three-canvas');
        const scene = new THREE.Scene();
        const camera = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 0.1, 1000);
        const renderer = new THREE.WebGLRenderer({ canvas, alpha: true, antialias: true });
        
        renderer.setSize(window.innerWidth, window.innerHeight);
        renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
        camera.position.z = 5;

        // Particles
        const particlesGeometry = new THREE.BufferGeometry();
        const particlesCount = 800;
        const posArray = new Float32Array(particlesCount * 3);
        for(let i = 0; i < particlesCount * 3; i++) {
            posArray[i] = (Math.random() - 0.5) * 10;
        }
        particlesGeometry.setAttribute('position', new THREE.BufferAttribute(posArray, 3));
        const particlesMaterial = new THREE.PointsMaterial({
            size: 0.012,
            color: 0xdc2626,
            transparent: true,
            opacity: 0.3,
            blending: THREE.AdditiveBlending
        });
        const particlesMesh = new THREE.Points(particlesGeometry, particlesMaterial);
        scene.add(particlesMesh);

        function animate() {
            requestAnimationFrame(animate);
            particlesMesh.rotation.y += 0.0003;
            renderer.render(scene, camera);
        }
        animate();

        window.addEventListener('resize', () => {
            camera.aspect = window.innerWidth / window.innerHeight;
            camera.updateProjectionMatrix();
            renderer.setSize(window.innerWidth, window.innerHeight);
        });
    </script>
</body>
</html>
