<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>419 — {{ config('app.name') }}</title>
    <style>
        :root { color-scheme: light dark; }
        body { font-family: ui-sans-serif, system-ui, sans-serif; margin: 0; min-height: 100vh; display: flex; align-items: center; justify-content: center; background: #f8fafc; color: #1e293b; }
        @media (prefers-color-scheme: dark) { body { background: #0f172a; color: #f1f5f9; } }
        .box { text-align: center; padding: 2rem; max-width: 28rem; }
        h1 { font-size: 4.5rem; font-weight: 800; margin: 0; color: #2563eb; letter-spacing: 0.05em; }
        h2 { font-size: 1.5rem; font-weight: 700; margin: 1rem 0 0.5rem; }
        p { margin: 0.5rem 0; opacity: 0.85; line-height: 1.5; }
        a { display: inline-block; margin-top: 1.5rem; padding: 0.75rem 1.5rem; background: #2563eb; color: #fff; text-decoration: none; font-weight: 600; border-radius: 0.5rem; }
        a:hover { background: #1d4ed8; }
        .ref { margin-top: 2rem; font-size: 0.75rem; opacity: 0.6; }
    </style>
</head>
<body>
    <div class="box">
        <h1>419</h1>
        <h2>Page expirée</h2>
        <p>Désolé, votre session a expiré. Veuillez rafraîchir et réessayer.</p>
        @php
            $previous = url()->previous();
            $safeBack = ($previous === '' || $previous === url()->current()) ? url('/') : $previous;
        @endphp
        <a href="{{ $safeBack }}">Rafraîchir la page</a>
        @if(!empty($requestId))
            <p class="ref">Réf. : {{ $requestId }}</p>
        @endif
    </div>
</body>
</html>
