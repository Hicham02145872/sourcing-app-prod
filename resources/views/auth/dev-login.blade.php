<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }} – DEV Login</title>
    <link rel="icon" type="image/png" href="/favicon-96x96.png" sizes="96x96" />
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Inter', sans-serif; }
        .dev-gradient { background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 50%, #0f172a 100%); }
        .dev-card { background: rgba(30, 41, 59, 0.8); backdrop-filter: blur(20px); border: 1px solid rgba(139, 92, 246, 0.2); }
        .dev-glow { box-shadow: 0 0 60px rgba(139, 92, 246, 0.15); }
        .dev-badge { background: linear-gradient(135deg, #8b5cf6, #6d28d9); animation: pulse-badge 2s ease-in-out infinite; }
        .dev-input { background: rgba(15, 23, 42, 0.6); border: 1px solid rgba(139, 92, 246, 0.2); transition: all 0.3s; }
        .dev-input:focus { border-color: #8b5cf6; box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.1); }
        .dev-btn { background: linear-gradient(135deg, #8b5cf6, #6d28d9); transition: all 0.3s; }
        .dev-btn:hover { background: linear-gradient(135deg, #a78bfa, #7c3aed); transform: translateY(-1px); box-shadow: 0 10px 30px rgba(139, 92, 246, 0.3); }
        .particle { position: absolute; border-radius: 50%; background: rgba(139, 92, 246, 0.3); animation: float linear infinite; }
        @keyframes float { 0%, 100% { transform: translateY(0) translateX(0); opacity: 0; } 10% { opacity: 1; } 90% { opacity: 1; } 100% { transform: translateY(-100vh) translateX(20px); opacity: 0; } }
        @keyframes pulse-badge { 0%, 100% { box-shadow: 0 0 0 0 rgba(139, 92, 246, 0.4); } 50% { box-shadow: 0 0 0 8px rgba(139, 92, 246, 0); } }
        .fade-in { animation: fadeIn 0.8s ease-out; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</head>
<body class="dev-gradient min-h-screen flex items-center justify-center p-4 relative overflow-hidden">
    <!-- Floating Particles -->
    <div id="particles" class="absolute inset-0 pointer-events-none"></div>

    <div class="w-full max-w-md relative z-10 fade-in">
        <!-- DEV Badge -->
        <div class="flex justify-center mb-8">
            <div class="dev-badge px-4 py-1.5 rounded-full text-white text-xs font-bold uppercase tracking-widest shadow-lg">
                Developer Access
            </div>
        </div>

        <!-- Card -->
        <div class="dev-card dev-glow rounded-3xl p-8 lg:p-10">
            <!-- Logo -->
            <div class="flex justify-center mb-6">
                <img class="h-10 w-auto" src="{{ asset('images/logo0.png') }}" alt="{{ config('app.name') }}">
            </div>

            <!-- Header -->
            <div class="text-center mb-8">
                <h1 class="text-2xl font-bold text-white mb-2">Dev Console</h1>
                <p class="text-sm text-slate-400">{{ __('Accès développeur sécurisé') }}</p>
            </div>

            <!-- Errors -->
            @if ($errors->any())
                <div class="mb-6 p-4 rounded-xl bg-red-500/10 border border-red-500/20 text-red-400 text-sm text-center">
                    {{ $errors->first() }}
                </div>
            @endif

            <!-- Form -->
            <form method="POST" action="{{ route('dev.login', app()->getLocale()) }}" class="space-y-5">
                @csrf

                <!-- Email -->
                <div>
                    <label for="email" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">
                        {{ __('Email') }}
                    </label>
                    <input id="email"
                           type="email"
                           name="email"
                           value="{{ old('email') }}"
                           required
                           autofocus
                           autocomplete="username"
                           placeholder="dev@smartsource.com"
                           class="dev-input w-full px-4 py-3 rounded-xl text-white placeholder-slate-500 outline-none text-sm">
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">
                        {{ __('Mot de passe') }}
                    </label>
                    <input id="password"
                           type="password"
                           name="password"
                           required
                           autocomplete="current-password"
                           placeholder="••••••••"
                           class="dev-input w-full px-4 py-3 rounded-xl text-white placeholder-slate-500 outline-none text-sm">
                </div>

                <!-- Remember -->
                <div class="flex items-center">
                    <input type="checkbox"
                           name="remember"
                           id="remember"
                           class="w-4 h-4 text-purple-600 bg-slate-800 border-slate-600 rounded focus:ring-purple-500 focus:ring-offset-0">
                    <label for="remember" class="ml-2 text-sm text-slate-400 cursor-pointer">
                        {{ __('Se souvenir de moi') }}
                    </label>
                </div>

                <!-- Submit -->
                <button type="submit"
                        class="dev-btn w-full py-3.5 text-white font-bold rounded-xl text-sm uppercase tracking-wider">
                    {{ __('Connexion Dev') }}
                </button>
            </form>
        </div>

        <!-- Footer -->
        <div class="mt-6 text-center">
            <p class="text-xs text-slate-600">
                {{ __('© 2026') }} {{ config('app.name') }} · {{ __('Console Développeur') }}
            </p>
        </div>
    </div>

    <script>
        // Create floating particles
        const container = document.getElementById('particles');
        for (let i = 0; i < 20; i++) {
            const p = document.createElement('div');
            p.className = 'particle';
            const size = Math.random() * 4 + 2;
            p.style.width = size + 'px';
            p.style.height = size + 'px';
            p.style.left = Math.random() * 100 + '%';
            p.style.top = Math.random() * 100 + '%';
            p.style.animationDuration = (Math.random() * 10 + 10) + 's';
            p.style.animationDelay = (Math.random() * 5) + 's';
            container.appendChild(p);
        }
    </script>
</body>
</html>
