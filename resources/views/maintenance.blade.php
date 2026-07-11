<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }} – Maintenance</title>
    <link rel="icon" type="image/png" href="/favicon-96x96.png" sizes="96x96" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { margin:0; padding:0; box-sizing:border-box; }
        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f8fafc;
            overflow: hidden;
            position: relative;
        }

        /* Subtle background pattern */
        .bg-pattern {
            position: fixed; inset: 0; z-index: 0;
            background:
                radial-gradient(circle at 20% 50%, rgba(220,38,38,0.04) 0%, transparent 50%),
                radial-gradient(circle at 80% 50%, rgba(202,138,4,0.04) 0%, transparent 50%);
        }

        /* Floating dots */
        .dots { position: fixed; inset: 0; z-index: 0; pointer-events: none; }
        .dot {
            position: absolute; border-radius: 50%; opacity: 0.15;
            animation: floatUp linear infinite;
        }
        @keyframes floatUp {
            0% { transform: translateY(100vh) scale(0); opacity: 0; }
            10% { opacity: 0.15; }
            90% { opacity: 0.08; }
            100% { transform: translateY(-10vh) scale(0.5); opacity: 0; }
        }

        /* Card */
        .card {
            position: relative; z-index: 10;
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 1.5rem;
            padding: 3rem 2.5rem;
            max-width: 440px; width: 90%;
            text-align: center;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05), 0 25px 50px -12px rgba(0,0,0,0.08);
            animation: cardIn 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            opacity: 0; transform: translateY(20px) scale(0.98);
        }
        @keyframes cardIn { to { opacity:1; transform:translateY(0) scale(1); } }

        /* Logo */
        .logo { height: 36px; width: auto; margin-bottom: 2rem; }

        /* Icon */
        .icon-ring {
            width: 72px; height: 72px; margin: 0 auto 1.5rem;
            border-radius: 50%;
            background: linear-gradient(135deg, #fef2f2, #fffbeb);
            border: 2px solid #fecaca;
            display: flex; align-items: center; justify-content: center;
            animation: pulse-ring 2.5s ease-in-out infinite;
        }
        @keyframes pulse-ring {
            0%,100% { box-shadow: 0 0 0 0 rgba(220,38,38,0.15); }
            50% { box-shadow: 0 0 0 14px rgba(220,38,38,0); }
        }
        .icon-ring svg { width: 32px; height: 32px; color: #dc2626; }

        /* Title */
        .title {
            font-size: 1.5rem; font-weight: 800; letter-spacing: -0.02em;
            color: #0f172a; margin-bottom: 0.5rem;
        }

        /* Message */
        .message {
            font-size: 0.9rem; color: #64748b; line-height: 1.7;
            margin-bottom: 1.5rem;
        }

        /* Divider */
        .divider { height:1px; background:linear-gradient(90deg,transparent,#e2e8f0,transparent); margin:1.5rem 0; }

        /* Status bar */
        .status-bar {
            display: inline-flex; align-items: center; gap: 8px;
            background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 999px;
            padding: 6px 16px; font-size: 0.75rem; color: #64748b; font-weight: 600;
        }
        .status-bar .dot-live {
            width: 6px; height: 6px; border-radius: 50%;
            background: #dc2626;
            animation: blink 1.5s ease-in-out infinite;
        }
        @keyframes blink { 0%,100%{opacity:1} 50%{opacity:0.3} }

        /* Footer */
        .footer { font-size: 0.7rem; color: #94a3b8; margin-top: 1.5rem; }

        /* Responsive */
        @media (max-width: 640px) { .card { padding: 2rem 1.5rem; } }
    </style>
</head>
<body>
    <div class="bg-pattern"></div>
    <div class="dots" id="dots"></div>

    <div class="card">
        <img src="{{ asset('images/logo0.png') }}" alt="{{ config('app.name') }}" class="logo">

        <div class="icon-ring">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
        </div>

        <h1 class="title">{{ __('maintenance.title') }}</h1>
        <p class="message">{{ $message ?: __('maintenance.default_message') }}</p>

        <div class="divider"></div>

        <div class="status-bar">
            <span class="dot-live"></span>
            {{ __('maintenance.we_are_working') }}
        </div>

        <p class="footer">© 2026 {{ config('app.name') }} · {{ __('maintenance.rights') }}</p>
    </div>

    <script>
        var c = document.getElementById('dots');
        for (var i = 0; i < 20; i++) {
            var d = document.createElement('div');
            d.className = 'dot';
            var s = Math.random() * 4 + 2;
            d.style.width = s + 'px';
            d.style.height = s + 'px';
            d.style.left = Math.random() * 100 + '%';
            d.style.background = Math.random() > 0.5 ? '#dc2626' : '#ca8a04';
            d.style.animationDuration = (Math.random() * 12 + 8) + 's';
            d.style.animationDelay = (Math.random() * 6) + 's';
            c.appendChild(d);
        }
    </script>
</body>
</html>
