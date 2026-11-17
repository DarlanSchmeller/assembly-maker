<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Renault IIoT | @yield('title')</title>
    <link rel="icon" type="image/png" href="{{ asset('images/renault_logo.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/laravel-echo@1.16.1/dist/echo.iife.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root {
            --app-bg: #f8fafc;
            --card-bg: #ffffff;
            --card-border: #f1f5f9;
            --brand-primary: #7c3aed;
            --brand-primary-light: #ede9fe;
            --text-heading: #0f172a;
            --text-body: #475569;
            --text-muted: #94a3b8;
            --sidebar-width: 270px;
            --sidebar-collapsed-width: 80px;
        }
        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: var(--app-bg);
            color: var(--text-body);
        }
        .font-mono {
            font-family: 'JetBrains Mono', monospace;
        }
        .soft-card {
            background: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: 20px;
            box-shadow: 0 4px 20px -2px rgba(15, 23, 42, 0.03);
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.88);
            backdrop-filter: blur(14px);
            border: 1px solid rgba(255, 255, 255, 0.6);
            border-radius: 24px;
        }
        [v-cloak] { display: none; }
    </style>
</head>
<body class="antialiased selection:bg-purple-500 selection:text-white">
    @yield('content')
    
    <script>
        window.Pusher = Pusher;
        window.Echo = new Echo({
            broadcaster: 'reverb',
            key: '{{ env("REVERB_APP_KEY") }}',
            wsHost: '{{ env("REVERB_HOST", "localhost") }}',
            wsPort: {{ env("REVERB_PORT", 8080) }},
            wssPort: {{ env("REVERB_PORT", 8080) }},
            forceTLS: {{ env("REVERB_SCHEME", "http") === "https" ? "true" : "false" }},
            enabledTransports: ['ws', 'wss'],
        });
    </script>
    @stack('scripts')
</body>
</html>