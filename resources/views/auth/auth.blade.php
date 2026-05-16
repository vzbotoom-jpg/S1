<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }} – @yield('title')</title>
    <link href="https://fonts.googleapis.com/css2?family=Space+Mono:wght@700&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #0d0f14; --surface: #13151c; --border: #252836;
            --accent: #6ee7b7; --accent2: #38bdf8;
            --text: #e2e8f0; --muted: #64748b; --danger: #f87171;
        }
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            background: var(--bg);
            color: var(--text);
            font-family: 'DM Sans', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
        }
        /* grid bg */
        body::before {
            content: '';
            position: fixed; inset: 0;
            background-image:
                linear-gradient(var(--border) 1px, transparent 1px),
                linear-gradient(90deg, var(--border) 1px, transparent 1px);
            background-size: 40px 40px;
            opacity: 0.4;
            pointer-events: none;
        }
        /* glow blob */
        body::after {
            content: '';
            position: fixed;
            width: 500px; height: 500px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(110,231,183,.12) 0%, transparent 70%);
            top: 50%; left: 50%;
            transform: translate(-50%, -60%);
            pointer-events: none;
        }

        .auth-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 2.5rem 2rem;
            width: 100%; max-width: 420px;
            position: relative; z-index: 1;
        }
        .auth-logo {
            font-family: 'Space Mono', monospace;
            font-size: 1.5rem;
            color: var(--accent);
            text-align: center;
            margin-bottom: 0.25rem;
        }
        .auth-logo span { color: var(--accent2); }
        .auth-subtitle {
            text-align: center;
            color: var(--muted);
            font-size: 0.85rem;
            margin-bottom: 2rem;
        }
        .form-group { margin-bottom: 1.1rem; }
        label {
            display: block;
            font-size: 0.8rem;
            font-weight: 500;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.4rem;
        }
        input[type=text], input[type=email], input[type=password] {
            width: 100%;
            background: #0d0f14;
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 0.65rem 0.9rem;
            color: var(--text);
            font-family: 'DM Sans', sans-serif;
            font-size: 0.95rem;
            transition: border-color 0.2s;
            outline: none;
        }
        input:focus { border-color: var(--accent); }
        .invalid-feedback { color: var(--danger); font-size: 0.8rem; margin-top: 0.3rem; }
        .btn-submit {
            width: 100%;
            padding: 0.75rem;
            background: var(--accent);
            color: #0d0f14;
            border: none;
            border-radius: 8px;
            font-family: 'DM Sans', sans-serif;
            font-size: 0.95rem;
            font-weight: 600;
            cursor: pointer;
            margin-top: 0.5rem;
            transition: opacity 0.2s;
        }
        .btn-submit:hover { opacity: 0.85; }
        .auth-footer {
            text-align: center;
            margin-top: 1.5rem;
            font-size: 0.85rem;
            color: var(--muted);
        }
        .auth-footer a { color: var(--accent2); text-decoration: none; }
        .auth-footer a:hover { text-decoration: underline; }
        .form-check { display: flex; align-items: center; gap: 0.5rem; margin: 0.5rem 0; }
        .form-check input { width: auto; }
        .form-check label { margin: 0; text-transform: none; font-size: 0.875rem; }
        .alert { padding: 0.75rem 1rem; border-radius: 8px; font-size: 0.875rem; margin-bottom: 1rem; }
        .alert-danger { background: rgba(248,113,113,.1); color: var(--danger); border: 1px solid rgba(248,113,113,.3); }
        .alert-success { background: rgba(110,231,183,.1); color: var(--accent); border: 1px solid rgba(110,231,183,.3); }
        .divider { border: none; border-top: 1px solid var(--border); margin: 1.25rem 0; }
    </style>
</head>
<body>
<div class="auth-card">
    <div class="auth-logo">Chat<span>Bot</span></div>
    <p class="auth-subtitle">@yield('subtitle', 'Powered by Google Gemini')</p>

    @yield('content')
</div>
</body>
</html>