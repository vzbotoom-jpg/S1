<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Halaman Tidak Ditemukan</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #0a0a0a;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .container {
            text-align: center;
            padding: 50px 40px;
            background: #111111;
            border-radius: 16px;
            border: 1px solid rgba(255,255,255,0.1);
            box-shadow: 0 0 60px rgba(255,255,255,0.03);
            max-width: 480px;
            width: 90%;
        }

        .icon-wrap {
            margin-bottom: 28px;
        }

        .icon-wrap svg {
            width: 52px;
            height: 52px;
            stroke: white;
            fill: none;
            stroke-width: 1.5;
            stroke-linecap: round;
            stroke-linejoin: round;
            opacity: 0.85;
        }

        .error-code {
            font-size: 110px;
            font-weight: 900;
            color: #ffffff;
            line-height: 1;
            letter-spacing: -4px;
        }

        .divider {
            width: 40px;
            height: 1px;
            background: rgba(255,255,255,0.2);
            margin: 20px auto;
        }

        .title {
            font-size: 20px;
            font-weight: 600;
            color: #ffffff;
            letter-spacing: 0.5px;
        }

        .description {
            font-size: 14px;
            color: rgba(255,255,255,0.4);
            margin-top: 10px;
            margin-bottom: 36px;
            line-height: 1.7;
        }

        .btn-group {
            display: flex;
            gap: 12px;
            justify-content: center;
        }

        .btn {
            display: inline-block;
            padding: 11px 26px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 500;
            font-size: 14px;
            letter-spacing: 0.3px;
            transition: all 0.25s ease;
        }

        .btn-primary {
            background: #ffffff;
            color: #0a0a0a;
        }

        .btn-primary:hover {
            background: #e5e5e5;
        }

        .btn-secondary {
            background: transparent;
            color: #ffffff;
            border: 1px solid rgba(255,255,255,0.25);
            position: relative;
            overflow: hidden;
        }

        .btn-secondary::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: rgba(255,255,255,0.08);
            transition: left 0.3s ease;
        }

        .btn-secondary:hover::before {
            left: 0;
        }

        .btn-secondary:hover {
            border-color: rgba(255,255,255,0.6);
            transform: translateX(-3px);
        }

        .btn-secondary span {
            display: inline-block;
            transition: transform 0.25s ease;
        }

        .btn-secondary:hover span {
            transform: translateX(-3px);
        }
    </style>
</head>
<body>
    <div class="container">

        <div class="icon-wrap">
            <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <circle cx="10.5" cy="10.5" r="6.5"/>
                <line x1="15.5" y1="15.5" x2="21" y2="21"/>
            </svg>
        </div>

        <div class="error-code">404</div>

        <div class="divider"></div>

        <h1 class="title">Halaman Tidak Ditemukan</h1>
        <p class="description">
            Halaman yang Anda cari tidak ada<br>atau telah dipindahkan.
        </p>

        <div class="btn-group">
            <a href="{{ url('/') }}" class="btn btn-primary">Ke Beranda</a>
            <a href="javascript:history.back()" class="btn btn-secondary">
                <span>← Kembali</span>
            </a>
        </div>

    </div>
</body>
</html>