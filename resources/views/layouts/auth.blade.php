<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Auth') · SIGAP - Sistem Informasi Gudang dan Pengelolaan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0;
            min-height: 100vh;
            font-family: 'Inter', 'Segoe UI', Tahoma, sans-serif;
            background: #0b1120;
            color: #fff;
        }
        .sigap-login {
            min-height: 100vh;
            display: flex;
        }

        /* === LEFT PHOTO PANEL === */
        .sigap-login-photo {
            position: relative;
            flex: 1.1;
            min-width: 0;
            overflow: hidden;
        }
        .sigap-login-photo > img {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .sigap-login-photo-overlay {
            position: absolute;
            inset: 0;
            background:
              linear-gradient(135deg, rgba(15,23,42,.78) 0%, rgba(30,27,75,.68) 50%, rgba(76,29,149,.55) 100%),
              linear-gradient(180deg, rgba(11,17,32,.4) 0%, rgba(11,17,32,.85) 100%);
        }
        .sigap-login-photo-content {
            position: relative;
            z-index: 2;
            height: 100%;
            min-height: 100vh;
            padding: 2.5rem 3rem;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .sigap-login-brand {
            display: flex;
            align-items: center;
            gap: .75rem;
        }
        .sigap-login-logo {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            background: linear-gradient(135deg, #3b82f6, #8b5cf6);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            color: #fff;
            box-shadow: 0 6px 20px rgba(59,130,246,.5);
            flex-shrink: 0;
        }
        .sigap-login-brand-name {
            font-weight: 800;
            font-size: 1.2rem;
            letter-spacing: 1px;
            line-height: 1;
        }
        .sigap-login-brand-tag {
            font-size: .65rem;
            color: rgba(199,210,254,.7);
            letter-spacing: 1.5px;
            margin-top: 4px;
        }
        .sigap-login-hero h1 {
            font-size: clamp(2rem, 4vw, 3.2rem);
            font-weight: 800;
            line-height: 1.1;
            margin: 1.25rem 0 1rem;
            letter-spacing: -.02em;
            color: #fff;
        }
        .sigap-grad {
            background: linear-gradient(90deg, #60a5fa, #a78bfa, #f0abfc);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .sigap-login-hero p {
            color: rgba(226,232,240,.8);
            font-size: 1rem;
            max-width: 480px;
            line-height: 1.55;
        }
        .sigap-login-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: .72rem;
            font-weight: 700;
            letter-spacing: 1.5px;
            color: #c7d2fe;
            padding: 6px 12px;
            border-radius: 999px;
            background: rgba(255,255,255,.08);
            border: 1px solid rgba(255,255,255,.15);
            backdrop-filter: blur(8px);
        }
        .sigap-dot {
            width: 8px; height: 8px; border-radius: 50%;
            background: #34d399;
            box-shadow: 0 0 10px #34d399;
            animation: sigapPulse 1.6s infinite;
        }
        .sigap-login-stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
            margin-top: 2rem;
            padding: 1.25rem;
            background: rgba(15,23,42,.45);
            border: 1px solid rgba(255,255,255,.1);
            border-radius: 16px;
            backdrop-filter: blur(12px);
            max-width: 480px;
        }
        .sigap-stat-num {
            font-size: 1.55rem;
            font-weight: 800;
            background: linear-gradient(135deg, #60a5fa, #a78bfa);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            line-height: 1;
        }
        .sigap-stat-label {
            font-size: .72rem;
            color: rgba(199,210,254,.75);
            margin-top: 4px;
        }
        .sigap-login-foot {
            font-size: .78rem;
            color: rgba(148,163,184,.65);
        }

        /* === RIGHT FORM PANEL === */
        .sigap-login-form-wrap {
            flex: 1;
            min-width: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            background:
              radial-gradient(circle at top right, rgba(139,92,246,.12), transparent 60%),
              #0b1120;
        }
        .sigap-login-card {
            width: 100%;
            max-width: 440px;
        }
        .sigap-login-card-inner {
            background: rgba(255,255,255,.04);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255,255,255,.1);
            border-radius: 20px;
            padding: 2.25rem;
            box-shadow: 0 20px 50px rgba(0,0,0,.4);
            animation: sigapFadeIn .6s ease both;
        }
        .sigap-login-card-head { margin-bottom: 1.4rem; }
        .sigap-login-eyebrow {
            font-size: .7rem;
            color: #a78bfa;
            font-weight: 700;
            letter-spacing: 2px;
            margin-bottom: 6px;
        }
        .sigap-login-card-head h3 {
            font-weight: 800;
            font-size: 1.6rem;
            margin: 0;
            letter-spacing: -.01em;
            color: #fff;
        }
        .sigap-login-card-head p {
            color: rgba(199,210,254,.7);
            font-size: .9rem;
            margin: 6px 0 0;
        }

        .sigap-field { margin-bottom: 1rem; }
        .sigap-field > label {
            display: block;
            font-size: .8rem;
            font-weight: 600;
            color: rgba(226,232,240,.85);
            margin-bottom: 6px;
        }
        .sigap-input-wrap { position: relative; }
        .sigap-input-icon {
            position: absolute;
            top: 50%;
            left: 14px;
            transform: translateY(-50%);
            color: #94a3b8;
            pointer-events: none;
        }
        .sigap-input {
            background: rgba(15,23,42,.6) !important;
            border: 1px solid rgba(255,255,255,.1) !important;
            color: #fff !important;
            padding: .8rem 1rem .8rem 2.6rem !important;
            border-radius: 12px !important;
            font-size: .92rem !important;
            width: 100%;
            transition: all .2s;
        }
        .sigap-input::placeholder { color: rgba(148,163,184,.5); }
        .sigap-input:focus {
            background: rgba(15,23,42,.85) !important;
            border-color: #8b5cf6 !important;
            box-shadow: 0 0 0 3px rgba(139,92,246,.2) !important;
            outline: none !important;
        }
        .sigap-check {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: .85rem;
            color: rgba(226,232,240,.8);
            margin: 4px 0 1.4rem;
            cursor: pointer;
            user-select: none;
        }
        .sigap-check input {
            accent-color: #8b5cf6;
            width: 16px;
            height: 16px;
        }
        .sigap-submit {
            width: 100%;
            background: linear-gradient(135deg, #3b82f6, #8b5cf6);
            color: #fff;
            padding: .8rem;
            font-weight: 700;
            font-size: .95rem;
            border: none;
            border-radius: 12px;
            box-shadow: 0 10px 28px rgba(99,102,241,.45);
            transition: transform .2s, box-shadow .2s;
            cursor: pointer;
        }
        .sigap-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 14px 36px rgba(99,102,241,.6);
        }
        .sigap-submit i { margin-right: 8px; }

        .sigap-divider {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 1.5rem 0 1rem;
            color: rgba(148,163,184,.55);
            font-size: .72rem;
            letter-spacing: 1.5px;
        }
        .sigap-divider::before, .sigap-divider::after {
            content: "";
            flex: 1;
            height: 1px;
            background: rgba(255,255,255,.08);
        }

        .sigap-altlink {
            text-align: center;
            color: rgba(199,210,254,.75);
            font-size: .88rem;
        }
        .sigap-altlink a {
            color: #a78bfa;
            font-weight: 700;
            text-decoration: none;
        }

        .sigap-alert {
            background: rgba(239,68,68,.12);
            border: 1px solid rgba(239,68,68,.35);
            color: #fecaca;
            padding: .65rem .85rem;
            border-radius: 10px;
            font-size: .82rem;
            margin-bottom: 1rem;
        }
        .sigap-alert.success {
            background: rgba(34,197,94,.12);
            border-color: rgba(34,197,94,.35);
            color: #bbf7d0;
        }
        .sigap-alert div + div { margin-top: 4px; }

        /* mobile-only items hidden by default */
        .sigap-login-mobile-header { display: none; }
        .sigap-foot-mobile { display: none; }

        @keyframes sigapFadeIn {
            from { opacity: 0; transform: translateY(12px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes sigapPulse {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: .4; transform: scale(.85); }
        }

        /* ============ TABLET ============ */
        @media (max-width: 1100px) {
            .sigap-login-photo-content { padding: 2rem; }
            .sigap-login-stats { padding: 1rem; }
            .sigap-stat-num { font-size: 1.3rem; }
            .sigap-login-form-wrap { padding: 1.5rem; }
            .sigap-login-card-inner { padding: 1.75rem; }
        }

        /* ============ MOBILE ============ */
        @media (max-width: 820px) {
            .sigap-login { flex-direction: column; }
            .sigap-login-photo { display: none; }
            .sigap-login-form-wrap {
                padding: 0;
                min-height: 100vh;
                align-items: stretch;
                justify-content: stretch;
            }
            .sigap-login-card {
                max-width: 100%;
                display: flex;
                flex-direction: column;
            }
            .sigap-login-card-inner {
                border-radius: 24px 24px 0 0;
                margin-top: -28px;
                padding: 1.75rem 1.4rem 2rem;
                box-shadow: 0 -12px 28px rgba(0,0,0,.3);
                background: rgba(15,23,42,.95);
                border: none;
                border-top: 1px solid rgba(255,255,255,.08);
                flex: 1;
            }
            .sigap-login-card-head h3 { font-size: 1.4rem; }
            .sigap-login-mobile-header { display: block; }
            .sigap-foot-mobile {
                display: block;
                text-align: center;
                margin-top: 1.5rem;
            }
            .sigap-login-mobile-photo {
                position: relative;
                height: 220px;
                overflow: hidden;
            }
            .sigap-login-mobile-photo img {
                width: 100%;
                height: 100%;
                object-fit: cover;
            }
            .sigap-login-mobile-overlay {
                position: absolute;
                inset: 0;
                background: linear-gradient(135deg, rgba(15,23,42,.7), rgba(76,29,149,.55));
            }
            .sigap-login-mobile-brand {
                position: absolute;
                left: 1.4rem;
                bottom: 1.4rem;
                display: flex;
                align-items: center;
                gap: .75rem;
                color: #fff;
            }
        }

        @media (max-width: 420px) {
            .sigap-login-card-inner { padding: 1.5rem 1.1rem 1.75rem; }
            .sigap-login-mobile-photo { height: 180px; }
        }
    </style>
</head>
<body>
<div class="sigap-login">
    {{-- LEFT: warehouse photo + brand --}}
    <div class="sigap-login-photo">
        <img src="{{ asset('images/warehouse.jpg') }}" alt="Warehouse">
        <div class="sigap-login-photo-overlay"></div>

        <div class="sigap-login-photo-content">
            <div class="sigap-login-brand">
                <div class="sigap-login-logo"><i class="bi bi-box-seam-fill"></i></div>
                <div>
                    <div class="sigap-login-brand-name">SIGAP</div>
                    <div class="sigap-login-brand-tag">WAREHOUSE MANAGEMENT</div>
                </div>
            </div>

            <div class="sigap-login-hero">
                <div class="sigap-login-badge">
                    <span class="sigap-dot"></span> Sistem Aktif · {{ date('Y') }}
                </div>
                <h1>
                    Setiap rak,<br>
                    setiap barang,<br>
                    <span class="sigap-grad">tercatat rapi.</span>
                </h1>
                <p>
                    Pantau pergerakan stok gudang Anda secara real-time dari mana saja —
                    cepat, akurat, dan mudah dipakai.
                </p>

                <div class="sigap-login-stats">
                    <div>
                        <div class="sigap-stat-num">5+</div>
                        <div class="sigap-stat-label">Kategori barang</div>
                    </div>
                    <div>
                        <div class="sigap-stat-num">94</div>
                        <div class="sigap-stat-label">Stok tersedia</div>
                    </div>
                    <div>
                        <div class="sigap-stat-num">24/7</div>
                        <div class="sigap-stat-label">Akses dashboard</div>
                    </div>
                </div>
            </div>

            <div class="sigap-login-foot">
                © {{ date('Y') }} SIGAP · Built with Laravel 11 + MySQL
            </div>
        </div>
    </div>

    {{-- RIGHT: form card --}}
    <div class="sigap-login-form-wrap">
        <div class="sigap-login-card">
            {{-- Mobile-only photo header --}}
            <div class="sigap-login-mobile-header">
                <div class="sigap-login-mobile-photo">
                    <img src="{{ asset('images/warehouse.jpg') }}" alt="">
                    <div class="sigap-login-mobile-overlay"></div>
                    <div class="sigap-login-mobile-brand">
                        <div class="sigap-login-logo"><i class="bi bi-box-seam-fill"></i></div>
                        <div>
                            <div class="sigap-login-brand-name">SIGAP</div>
                            <div class="sigap-login-brand-tag">WAREHOUSE MANAGEMENT</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="sigap-login-card-inner">
                <div class="sigap-login-card-head">
                    <div class="sigap-login-eyebrow">@yield('eyebrow', 'ADMIN PORTAL')</div>
                    <h3>@yield('heading', 'Selamat Datang')</h3>
                    <p>@yield('subheading', 'Masuk untuk mengelola data gudang Anda.')</p>
                </div>

                @if(session('success'))
                    <div class="sigap-alert success">{{ session('success') }}</div>
                @endif

                @if($errors->any())
                    <div class="sigap-alert">
                        @foreach($errors->all() as $err)
                            <div><i class="bi bi-exclamation-circle"></i> {{ $err }}</div>
                        @endforeach
                    </div>
                @endif

                @yield('content')

                <div class="sigap-foot-mobile sigap-login-foot">
                    © {{ date('Y') }} SIGAP · Laravel 11 + MySQL
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
