<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SIGAP') · Sistem Informasi Gudang dan Pengelolaan</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --sb-w: 260px;
            --bg: #f1f5f9;
            --ink: #0f172a;
            --muted: #64748b;
            --line: rgba(226,232,240,.8);
            --brand1: #3b82f6;
            --brand2: #8b5cf6;
        }

        * {
            box-sizing: border-box;
        }

        body {
            background: var(--bg);
            font-family: 'Inter','Segoe UI',Tahoma,sans-serif;
            color: var(--ink);
        }

        /* === SIDEBAR === */
        .sigap-sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: var(--sb-w);
            background: linear-gradient(180deg, #0f172a 0%, #1e1b4b 60%, #1e293b 100%);
            color: #fff;
            padding: 1.5rem 1rem;
            overflow-y: auto;
            z-index: 1000;
            border-right: 1px solid rgba(148,163,184,.1);
        }

        .sigap-brand {
            display: flex;
            align-items: center;
            gap: .7rem;
            padding: .5rem .25rem 1.25rem;
            border-bottom: 1px solid rgba(148,163,184,.15);
            margin-bottom: .5rem;
            font-weight: 800;
            font-size: 1.4rem;
            letter-spacing: 1px;
        }

        .sigap-brand-logo {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background: linear-gradient(135deg, var(--brand1), var(--brand2));
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 16px rgba(59,130,246,.4);
            font-size: 1.2rem;
        }

        .sigap-brand-tag {
            font-size: .62rem;
            font-weight: 500;
            color: rgba(148,163,184,.7);
            letter-spacing: .5px;
            margin-top: 2px;
        }

        .sigap-menu-label {
            font-size: .7rem;
            letter-spacing: 2px;
            color: rgba(148,163,184,.6);
            text-transform: uppercase;
            font-weight: 700;
            margin: 1.5rem .5rem .5rem;
        }

        .sigap-nav {
            color: rgba(226,232,240,.7);
            background: transparent;
            border: 1px solid transparent;
            padding: .7rem .9rem;
            border-radius: 10px;
            margin-bottom: .25rem;
            display: flex;
            align-items: center;
            gap: .75rem;
            text-decoration: none;
            font-size: .92rem;
            font-weight: 500;
            transition: all .2s;
            position: relative;
        }

        .sigap-nav i {
            font-size: 1.1rem;
        }

        .sigap-nav:hover {
            background: rgba(255,255,255,.05);
            color: #fff;
        }

        .sigap-nav.active {
            color: #fff;
            font-weight: 600;
            background: linear-gradient(135deg, rgba(59,130,246,.25), rgba(139,92,246,.25));
            border-color: rgba(96,165,250,.4);
            box-shadow: 0 0 24px rgba(96,165,250,.25);
        }

        .sigap-nav.active i {
            color: #60a5fa;
        }

        .sigap-nav.active::before {
            content: '';
            position: absolute;
            left: -16px;
            top: 50%;
            transform: translateY(-50%);
            width: 4px;
            height: 24px;
            background: #60a5fa;
            border-radius: 4px;
            box-shadow: 0 0 12px #60a5fa;
        }

        .sigap-nav-badge {
            margin-left: auto;
            background: #ef4444;
            color: #fff;
            font-size: .65rem;
            font-weight: 700;
            padding: 2px 7px;
            border-radius: 999px;
        }

        .sigap-storage-card {
            margin-top: 2rem;
            padding: 1rem;
            border-radius: 12px;
            background: linear-gradient(135deg, rgba(59,130,246,.15), rgba(139,92,246,.15));
            border: 1px solid rgba(96,165,250,.25);
        }

        .sigap-storage-card .lab {
            font-size: .75rem;
            color: rgba(226,232,240,.7);
            margin-bottom: 4px;
        }

        .sigap-storage-card .val {
            font-size: .85rem;
            font-weight: 600;
            margin-bottom: 8px;
        }

        /* === MAIN === */
        .sigap-main {
            margin-left: var(--sb-w);
            min-height: 100vh;
        }

        .sigap-topbar {
            background: rgba(255,255,255,.85);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            padding: 1rem 1.75rem;
            border-bottom: 1px solid rgba(148,163,184,.18);
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 999;
            gap: 1rem;
        }

        .sigap-topbar h5 {
            font-weight: 700;
            color: var(--ink);
            margin: 0;
            font-size: 1.15rem;
        }

        .sigap-topbar .sub {
            font-size: .8rem;
            color: var(--muted);
            margin-top: 2px;
        }

        /* === NOTIFIKASI AKTIVITAS GUDANG === */
        .sigap-bell {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: .45rem .65rem;
            position: relative;
        }

        .sigap-bell:hover {
            background: #f8fafc;
        }

        .sigap-bell i {
            color: #475569;
        }

        .sigap-bell-count {
            position: absolute;
            top: -7px;
            right: -7px;
            min-width: 18px;
            height: 18px;
            padding: 0 5px;
            background: #ef4444;
            color: #fff;
            border-radius: 999px;
            font-size: .62rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid #fff;
        }

        .sigap-notif-menu {
            width: 340px;
            padding: 0;
            border-radius: 14px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 12px 28px rgba(15,23,42,.14);
            overflow: hidden;
        }

        .sigap-notif-head {
            padding: .9rem 1rem;
            font-weight: 700;
            font-size: .9rem;
            border-bottom: 1px solid #e2e8f0;
        }

        .sigap-notif-body {
            padding: .85rem 1rem;
        }

        .sigap-notif-stat {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: .45rem 0;
            font-size: .84rem;
            border-bottom: 1px solid #f1f5f9;
        }

        .sigap-notif-stat:last-child {
            border-bottom: none;
        }

        .sigap-notif-stat span:first-child {
            color: #64748b;
        }

        .sigap-notif-stat span:last-child {
            font-weight: 700;
            color: #0f172a;
        }

        .sigap-notif-latest {
            background: #f8fafc;
            padding: .85rem 1rem;
            border-top: 1px solid #e2e8f0;
        }

        .sigap-notif-latest .ttl {
            font-size: .78rem;
            color: #64748b;
            margin-bottom: .25rem;
        }

        .sigap-notif-latest .txt {
            font-size: .85rem;
            font-weight: 600;
            color: #0f172a;
        }

        .sigap-notif-latest .desc {
            font-size: .75rem;
            color: #64748b;
        }

        .sigap-user {
            display: flex;
            align-items: center;
            gap: .5rem;
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 999px;
            padding: 4px 12px 4px 4px;
            cursor: pointer;
        }

        .sigap-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--brand1), var(--brand2));
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: .85rem;
        }

        .sigap-user .nm {
            font-size: .82rem;
            font-weight: 600;
            color: var(--ink);
            line-height: 1.1;
        }

        .sigap-user .rl {
            font-size: .68rem;
            color: #94a3b8;
        }

        .sigap-content {
            padding: 1.75rem;
        }

        /* === CARDS === */
        .sigap-card {
            background: #fff;
            border-radius: 16px;
            padding: 1.25rem 1.4rem;
            border: 1px solid var(--line);
            box-shadow: 0 4px 14px rgba(15,23,42,.04);
            animation: sigapFadeIn .5s ease both;
            transition: transform .25s, box-shadow .25s;
        }

        .sigap-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 28px rgba(15,23,42,.12);
        }

        .sigap-card-h6 {
            font-weight: 700;
            color: var(--ink);
            margin: 0;
            font-size: 1rem;
        }

        .sigap-card-sub {
            font-size: .8rem;
            color: var(--muted);
        }

        /* === BUTTON OVERRIDES === */
        .btn-primary {
            background: linear-gradient(135deg, var(--brand1), var(--brand2));
            border: none;
        }

        .btn-primary:hover {
            filter: brightness(1.08);
        }

        /* === TABLE === */
        .table thead {
            background: #f8fafc;
        }

        .table thead th {
            color: var(--ink);
            font-weight: 700;
            border: none;
            font-size: .82rem;
            letter-spacing: .3px;
        }

        @keyframes sigapFadeIn {
            from {
                opacity: 0;
                transform: translateY(8px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes sigapPulse {
            0%, 100% {
                opacity: 1;
                transform: scale(1);
            }

            50% {
                opacity: .35;
                transform: scale(.85);
            }
        }

        @media (max-width: 992px) {
            .sigap-sidebar {
                transform: translateX(-100%);
                transition: transform .3s;
            }

            .sigap-sidebar.open {
                transform: translateX(0);
            }

            .sigap-main {
                margin-left: 0;
            }
        }

        @media (max-width: 640px) {
            .sigap-bell {
                display: none;
            }

            .sigap-content {
                padding: 1rem;
            }
        }
    </style>

    @stack('styles')
</head>
<body>

<aside class="sigap-sidebar" id="sigapSidebar">
    <div class="sigap-brand">
        <div class="sigap-brand-logo"><i class="bi bi-box-seam-fill"></i></div>
        <div>
            <div style="line-height:1;">SIGAP</div>
            <div class="sigap-brand-tag">WAREHOUSE MGMT</div>
        </div>
    </div>

    <div class="sigap-menu-label">Menu Utama</div>

    <a href="{{ route('dashboard') }}" class="sigap-nav {{ request()->routeIs('dashboard') ? 'active' : '' }}">
        <i class="bi bi-grid-1x2-fill"></i>
        <span>Dashboard</span>
    </a>

    <a href="{{ route('barang.index') }}" class="sigap-nav {{ request()->routeIs('barang.*') ? 'active' : '' }}">
        <i class="bi bi-boxes"></i>
        <span>Data Barang</span>
        @php $countBarang = \App\Models\Barang::count(); @endphp
        @if($countBarang > 0)
            <span class="sigap-nav-badge">{{ $countBarang }}</span>
        @endif
    </a>

    <div class="sigap-menu-label">Transaksi</div>

    <a href="{{ route('transaksi.masuk') }}" class="sigap-nav {{ request()->routeIs('transaksi.masuk*') ? 'active' : '' }}">
        <i class="bi bi-box-arrow-in-down"></i>
        <span>Barang Masuk</span>
    </a>

    <a href="{{ route('transaksi.keluar') }}" class="sigap-nav {{ request()->routeIs('transaksi.keluar*') ? 'active' : '' }}">
        <i class="bi bi-box-arrow-up"></i>
        <span>Barang Keluar</span>
    </a>

    <a href="{{ route('transaksi.riwayat') }}" class="sigap-nav {{ request()->routeIs('transaksi.riwayat') ? 'active' : '' }}">
        <i class="bi bi-clock-history"></i>
        <span>Riwayat</span>
    </a>

    @php
        $jumlahJenisBarang = \App\Models\Barang::count();
        $totalStokBarang   = \App\Models\Barang::sum('stok');
    @endphp

    <div class="sigap-storage-card">
        <div class="lab">Ringkasan Gudang</div>
        <div class="val">{{ $jumlahJenisBarang }} jenis barang</div>

        <div class="lab mt-3">Total Stok</div>
        <div class="val mb-0">{{ $totalStokBarang }} unit tersimpan</div>
    </div>
</aside>

<div class="sigap-main">
    <div class="sigap-topbar">
        <div class="d-flex align-items-center gap-3">
            <button class="btn btn-light d-lg-none" onclick="document.getElementById('sigapSidebar').classList.toggle('open')">
                <i class="bi bi-list"></i>
            </button>

            <div>
                <h5>@yield('title', 'Dashboard')</h5>

                @hasSection('subtitle')
                    <div class="sub">@yield('subtitle')</div>
                @else
                    <div class="sub">
                        Selamat datang kembali, {{ Auth::user()->name ?? 'Admin' }} · {{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, D MMMM Y') }}
                    </div>
                @endif
            </div>
        </div>

        <div class="d-flex align-items-center gap-2">
            @php
                $hariIni = \Carbon\Carbon::today();

                $barangMasukHariIni = \App\Models\Transaksi::whereDate('tanggal', $hariIni)
                    ->where('jenis', 'masuk')
                    ->count();

                $barangKeluarHariIni = \App\Models\Transaksi::whereDate('tanggal', $hariIni)
                    ->where('jenis', 'keluar')
                    ->count();

                $stokHabis = \App\Models\Barang::where('stok', '<=', 0)->count();

                $aktivitasTerakhir = \App\Models\Transaksi::with('barang')
                    ->whereDate('tanggal', $hariIni)
                    ->latest('created_at')
                    ->latest('id')
                    ->first();

                $jumlahAktivitas = $barangMasukHariIni + $barangKeluarHariIni + $stokHabis;
            @endphp

            <div class="dropdown">
                <button class="sigap-bell" type="button" data-bs-toggle="dropdown" title="Aktivitas Gudang">
                    <i class="bi bi-bell"></i>

                    @if($jumlahAktivitas > 0)
                        <span class="sigap-bell-count">
                            {{ $jumlahAktivitas > 99 ? '99+' : $jumlahAktivitas }}
                        </span>
                    @endif
                </button>

                <div class="dropdown-menu dropdown-menu-end sigap-notif-menu mt-2">
                    <div class="sigap-notif-head">
                        Aktivitas Gudang Hari Ini
                        <div class="small text-muted fw-normal mt-1">
                            Ringkasan transaksi dan kondisi stok
                        </div>
                    </div>

                    <div class="sigap-notif-body">
                        <div class="sigap-notif-stat">
                            <span>Barang masuk</span>
                            <span>{{ $barangMasukHariIni }} transaksi</span>
                        </div>

                        <div class="sigap-notif-stat">
                            <span>Barang keluar</span>
                            <span>{{ $barangKeluarHariIni }} transaksi</span>
                        </div>

                        <div class="sigap-notif-stat">
                            <span>Stok habis</span>
                            <span>{{ $stokHabis }} barang</span>
                        </div>
                    </div>

                    <div class="sigap-notif-latest">
                        <div class="ttl">Aktivitas terbaru</div>

                        @if($aktivitasTerakhir)
                            <div class="txt">
                                Barang {{ $aktivitasTerakhir->jenis }} - {{ $aktivitasTerakhir->barang->nama_barang ?? 'Barang dihapus' }}
                            </div>
                            <div class="desc">
                                Jumlah {{ $aktivitasTerakhir->jumlah }} unit · {{ $aktivitasTerakhir->tanggal->format('d/m/Y') }}
                            </div>
                        @else
                            <div class="desc">
                                Belum ada aktivitas gudang hari ini.
                            </div>
                        @endif
                    </div>

                    <div class="p-2">
                        <a href="{{ route('transaksi.riwayat') }}" class="btn btn-sm btn-primary w-100">
                            Lihat Riwayat Lengkap
                        </a>
                    </div>
                </div>
            </div>

            <div class="dropdown">
                <div class="sigap-user" data-bs-toggle="dropdown">
                    <div class="sigap-avatar">{{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 2)) }}</div>
                    <div>
                        <div class="nm">{{ Auth::user()->name ?? 'Admin' }}</div>
                        <div class="rl">Administrator</div>
                    </div>
                </div>

                <ul class="dropdown-menu dropdown-menu-end mt-2">
                    <li>
                        <span class="dropdown-item-text small text-muted">{{ Auth::user()->email ?? '' }}</span>
                    </li>

                    <li><hr class="dropdown-divider"></li>

                    <li>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger">
                                <i class="bi bi-box-arrow-right"></i> Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <main class="sigap-content">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
