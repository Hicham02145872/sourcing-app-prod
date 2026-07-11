<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }} – Maintenance</title>
    <link rel="icon" type="image/png" href="/favicon-96x96.png" sizes="96x96" />
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700;800,900&display=swap" rel="stylesheet" />
    <style>
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; min-height: 100vh; overflow: hidden; background: #030014; }

        /* ── Animated Gradient Background ── */
        .bg-mesh {
            position: fixed; inset: 0; z-index: 0;
            background: 
                radial-gradient(ellipse 80% 60% at 20% 80%, rgba(88, 28, 135, 0.4) 0%, transparent 60%),
                radial-gradient(ellipse 60% 80% at 80% 20%, rgba(30, 58, 138, 0.4) 0%, transparent 60%),
                radial-gradient(ellipse 50% 50% at 50% 50%, rgba(88, 28, 135, 0.2) 0%, transparent 70%),
                linear-gradient(180deg, #030014 0%, #0a0520 50%, #030014 100%);
            animation: meshShift 15s ease-in-out infinite alternate;
        }
        @keyframes meshShift {
            0% { background-position: 0% 0%, 100% 100%, 50% 50%, 0% 0%; }
            100% { background-position: 100% 100%, 0% 0%, 50% 50%, 0% 0%; }
        }

        /* ── Floating Orbs ── */
        .orb {
            position: fixed; border-radius: 50%; filter: blur(80px); z-index: 0;
            animation: orbFloat 20s ease-in-out infinite alternate;
        }
        .orb-1 { width: 500px; height: 500px; background: rgba(139, 92, 246, 0.15); top: -10%; left: -10%; animation-delay: 0s; }
        .orb-2 { width: 400px; height: 400px; background: rgba(59, 130, 246, 0.12); bottom: -10%; right: -10%; animation-delay: -5s; }
        .orb-3 { width: 300px; height: 300px; background: rgba(168, 85, 247, 0.1); top: 50%; left: 50%; transform: translate(-50%, -50%); animation-delay: -10s; }
        @keyframes orbFloat {
            0% { transform: translate(0, 0) scale(1); }
            33% { transform: translate(30px, -30px) scale(1.05); }
            66% { transform: translate(-20px, 20px) scale(0.95); }
            100% { transform: translate(10px, -10px) scale(1.02); }
        }

        /* ── Particles ── */
        .particles { position: fixed; inset: 0; z-index: 0; pointer-events: none; }
        .particle {
            position: absolute; border-radius: 50%; background: rgba(139, 92, 246, 0.5);
            animation: particleRise linear infinite;
        }
        @keyframes particleRise {
            0% { transform: translateY(100vh) scale(0); opacity: 0; }
            10% { opacity: 1; transform: translateY(90vh) scale(1); }
            90% { opacity: 0.6; }
            100% { transform: translateY(-10vh) scale(0.5); opacity: 0; }
        }

        /* ── Main Container ── */
        .container {
            position: relative; z-index: 10; min-height: 100vh;
            display: flex; flex-direction: column; align-items: center; justify-content: center;
            padding: 2rem;
        }

        /* ── Glass Card ── */
        .glass-card {
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid rgba(139, 92, 246, 0.15);
            border-radius: 2rem;
            padding: 3rem 2.5rem;
            max-width: 520px; width: 100%;
            text-align: center;
            box-shadow: 
                0 0 80px rgba(139, 92, 246, 0.08),
                0 25px 50px rgba(0, 0, 0, 0.4),
                inset 0 1px 0 rgba(255, 255, 255, 0.05);
            animation: cardFadeIn 1s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            opacity: 0; transform: translateY(40px) scale(0.95);
        }
        @keyframes cardFadeIn {
            to { opacity: 1; transform: translateY(0) scale(1); }
        }

        /* ── Logo ── */
        .logo-wrap {
            margin-bottom: 2rem;
            animation: logoGlow 3s ease-in-out infinite alternate;
        }
        .logo-wrap img { height: 40px; width: auto; filter: brightness(1.2); }
        @keyframes logoGlow {
            0% { filter: drop-shadow(0 0 10px rgba(139, 92, 246, 0.3)); }
            100% { filter: drop-shadow(0 0 25px rgba(139, 92, 246, 0.6)); }
        }

        /* ── Illustration: Gears + Rocket ── */
        .illustration { position: relative; width: 200px; height: 200px; margin: 0 auto 2rem; }
        .gear {
            position: absolute; border-radius: 50%;
            border: 3px solid rgba(139, 92, 246, 0.3);
        }
        .gear::before {
            content: ''; position: absolute; inset: 6px; border-radius: 50%;
            border: 2px dashed rgba(139, 92, 246, 0.2);
        }
        .gear-1 { width: 80px; height: 80px; top: 20px; left: 10px; animation: spinGear 8s linear infinite; }
        .gear-2 { width: 60px; height: 60px; top: 70px; right: 15px; animation: spinGear 6s linear infinite reverse; }
        .gear-3 { width: 45px; height: 45px; bottom: 30px; left: 50px; animation: spinGear 10s linear infinite; }
        @keyframes spinGear { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }

        /* Rocket */
        .rocket {
            position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);
            animation: rocketFloat 3s ease-in-out infinite;
        }
        .rocket-body { font-size: 3rem; filter: drop-shadow(0 0 20px rgba(139, 92, 246, 0.6)); }
        .rocket-flame {
            position: absolute; bottom: -8px; left: 50%; transform: translateX(-50%);
            width: 12px; height: 20px;
            background: linear-gradient(180deg, #f97316, #ef4444, transparent);
            border-radius: 0 0 50% 50%;
            animation: flameFlicker 0.15s ease-in-out infinite alternate;
        }
        @keyframes rocketFloat { 0%, 100% { transform: translate(-50%, -50%) translateY(0); } 50% { transform: translate(-50%, -50%) translateY(-8px); } }
        @keyframes flameFlicker { 0% { height: 18px; opacity: 0.9; } 100% { height: 24px; opacity: 1; } }

        /* Orbit ring */
        .orbit {
            position: absolute; inset: 10px; border-radius: 50%;
            border: 1px dashed rgba(139, 92, 246, 0.15);
            animation: spinGear 20s linear infinite;
        }

        /* ── Status Dots ── */
        .status-dots {
            display: flex; justify-content: center; gap: 6px; margin-bottom: 1.5rem;
        }
        .status-dot {
            width: 8px; height: 8px; border-radius: 50%; background: #8b5cf6;
            animation: dotPulse 1.4s ease-in-out infinite;
        }
        .status-dot:nth-child(2) { animation-delay: 0.2s; }
        .status-dot:nth-child(3) { animation-delay: 0.4s; }
        @keyframes dotPulse {
            0%, 80%, 100% { transform: scale(0.6); opacity: 0.4; }
            40% { transform: scale(1); opacity: 1; }
        }

        /* ── Typography ── */
        .title {
            font-size: 1.75rem; font-weight: 800; letter-spacing: -0.02em;
            background: linear-gradient(135deg, #ffffff 0%, #c4b5fd 50%, #8b5cf6 100%);
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
            background-clip: text; margin-bottom: 0.75rem;
            animation: titleFadeIn 1.2s cubic-bezier(0.16, 1, 0.3, 1) 0.3s forwards;
            opacity: 0; transform: translateY(10px);
        }
        @keyframes titleFadeIn { to { opacity: 1; transform: translateY(0); } }

        .message {
            font-size: 1rem; color: rgba(203, 213, 225, 0.8); line-height: 1.7;
            max-width: 380px; margin: 0 auto 1.5rem;
            animation: titleFadeIn 1.2s cubic-bezier(0.16, 1, 0.3, 1) 0.5s forwards;
            opacity: 0; transform: translateY(10px);
        }

        .tagline {
            font-size: 0.75rem; color: rgba(139, 92, 246, 0.6);
            text-transform: uppercase; letter-spacing: 0.15em; font-weight: 600;
            animation: titleFadeIn 1.2s cubic-bezier(0.16, 1, 0.3, 1) 0.7s forwards;
            opacity: 0; transform: translateY(10px);
        }

        /* ── Divider ── */
        .divider {
            height: 1px; margin: 1.5rem 0;
            background: linear-gradient(90deg, transparent, rgba(139, 92, 246, 0.3), transparent);
        }

        /* ── Footer ── */
        .footer-text {
            font-size: 0.7rem; color: rgba(148, 163, 184, 0.5);
            animation: titleFadeIn 1.2s cubic-bezier(0.16, 1, 0.3, 1) 0.9s forwards;
            opacity: 0; transform: translateY(10px);
        }

        /* ── Responsive ── */
        @media (max-width: 640px) {
            .glass-card { padding: 2rem 1.5rem; border-radius: 1.5rem; }
            .illustration { width: 150px; height: 150px; }
            .gear-1 { width: 60px; height: 60px; }
            .gear-2 { width: 45px; height: 45px; }
            .gear-3 { width: 35px; height: 35px; }
            .title { font-size: 1.4rem; }
        }
    </style>
</head>
<body>
    <!-- Background -->
    <div class="bg-mesh"></div>
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>
    <div class="orb orb-3"></div>
    <div class="particles" id="particles"></div>

    <!-- Main Content -->
    <div class="container">
        <div class="glass-card">
            <!-- Logo -->
            <div class="logo-wrap">
                <img src="{{ asset('images/logo0.png') }}" alt="{{ config('app.name') }}">
            </div>

            <!-- Illustration: Gears + Rocket -->
            <div class="illustration">
                <div class="orbit"></div>
                <div class="gear gear-1"></div>
                <div class="gear gear-2"></div>
                <div class="gear gear-3"></div>
                <div class="rocket">
                    <div class="rocket-body">🚀</div>
                    <div class="rocket-flame"></div>
                </div>
            </div>

            <!-- Status Dots -->
            <div class="status-dots">
                <div class="status-dot"></div>
                <div class="status-dot"></div>
                <div class="status-dot"></div>
            </div>

            <!-- Title -->
            <h1 class="title">{{ __('maintenance.title') }}</h1>

            <!-- Message -->
            <p class="message">{{ $message ?: __('maintenance.default_message') }}</p>

            <div class="divider"></div>

            <!-- Tagline -->
            <p class="tagline">{{ __('maintenance.tagline') }}</p>

            <!-- Footer -->
            <p class="footer-text">© 2026 {{ config('app.name') }} · {{ __('maintenance.rights') }}</p>
        </div>
    </div>

    <script>
        // Create particles
        const c = document.getElementById('particles');
        for (let i = 0; i < 30; i++) {
            const p = document.createElement('div');
            p.className = 'particle';
            const s = Math.random() * 3 + 1;
            p.style.width = s + 'px';
            p.style.height = s + 'px';
            p.style.left = Math.random() * 100 + '%';
            p.style.animationDuration = (Math.random() * 15 + 10) + 's';
            p.style.animationDelay = (Math.random() * 8) + 's';
            c.appendChild(p);
        }
    </script>
</body>
</html>
