@extends('layouts.app')

@section('title', 'Dashboard')
@section('subtitle', 'Selamat datang kembali, ' . (Auth::user()->name ?? 'Admin') . ' · ' . \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, D MMMM Y'))

@push('styles')
<style>
    /* Stat cards */
    .sigap-stat {
        background: #fff;
        border-radius: 16px;
        padding: 1.1rem 1.25rem;
        position: relative; overflow: hidden;
        height: 100%;
        border: 1px solid rgba(226,232,240,.8);
        box-shadow: 0 4px 14px rgba(15,23,42,.04);
        transition: transform .25s, box-shadow .25s;
        animation: sigapFadeIn .5s ease both;
    }
    .sigap-stat:hover { transform: translateY(-3px); box-shadow: 0 12px 28px rgba(15,23,42,.12); }
    .sigap-stat:hover .sigap-stat-icon { transform: scale(1.1) rotate(-6deg); }
    .sigap-stat-icon {
        width: 42px; height: 42px; border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.2rem; transition: transform .3s;
    }
    .sigap-stat-label { font-size: .78rem; color: #64748b; font-weight: 500; }
    .sigap-stat-value { font-size: 1.85rem; font-weight: 800; color: #0f172a; line-height: 1.1; margin-top: 2px; }
    .sigap-delta {
        font-size: .72rem; font-weight: 700; padding: 3px 8px; border-radius: 999px;
        display: inline-flex; align-items: center; gap: 3px;
    }
    .sigap-delta.up { background: rgba(16,185,129,.12); color: #059669; }
    .sigap-delta.down { background: rgba(239,68,68,.12); color: #dc2626; }

    /* Summary card (right of chart) */
    .sigap-summary {
        border-radius: 16px; padding: 1.25rem;
        background: linear-gradient(135deg, #1e1b4b 0%, #312e81 100%);
        color: #fff; height: 100%;
        position: relative; overflow: hidden;
        border: 1px solid rgba(96,165,250,.25);
    }
    .sigap-summary::before {
        content: ''; position: absolute;
        top: -60px; right: -60px; width: 180px; height: 180px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(96,165,250,.4), transparent 70%);
    }
    .sigap-summary > * { position: relative; }
    .sigap-summary .lab { font-size: .75rem; color: rgba(199,210,254,.8); letter-spacing: 1px; }
    .sigap-summary .num { font-size: 2.6rem; font-weight: 800; line-height: 1.1; margin-top: 4px; }
    .sigap-summary .desc { font-size: .88rem; color: rgba(199,210,254,.85); }
    .sigap-summary-bar { height: 6px; background: rgba(15,23,42,.5); border-radius: 999px; margin-top: 6px; overflow: hidden; }
    .sigap-summary-bar > div { height: 100%; border-radius: 999px; }
    .sigap-summary-btn {
        background: rgba(255,255,255,.12); color: #fff;
        border: 1px solid rgba(255,255,255,.2); backdrop-filter: blur(4px);
        font-weight: 600;
    }
    .sigap-summary-btn:hover { background: rgba(255,255,255,.2); color: #fff; }

    /* Stock bars */
    .stock-row { padding-bottom: 4px; }
    .stock-dot { width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0; }
    .stock-tag {
        font-size: .7rem; color: #94a3b8; background: #f1f5f9;
        padding: 2px 6px; border-radius: 4px;
    }
    .stock-low {
        font-size: .68rem; color: #dc2626;
        background: rgba(239,68,68,.1); padding: 2px 7px;
        border-radius: 999px; font-weight: 700;
        animation: sigapPulse 1.6s infinite;
    }
    .stock-bar-bg { height: 8px; background: #f1f5f9; border-radius: 999px; overflow: hidden; }
    .stock-bar-fg { height: 100%; border-radius: 999px; transition: width .4s; }

    /* Timeline */
    .timeline { position: relative; padding-left: 22px; }
    .timeline::before {
        content: ''; position: absolute; left: 8px; top: 6px; bottom: 6px;
        width: 2px;
        background: linear-gradient(180deg, #e0e7ff, #f1f5f9);
    }
    .timeline-item { position: relative; padding-bottom: 18px; }
    .timeline-item:last-child { padding-bottom: 0; }
    .timeline-dot {
        position: absolute; left: -22px; top: 2px;
        width: 18px; height: 18px; border-radius: 50%;
        border: 3px solid #fff;
        display: flex; align-items: center; justify-content: center;
        color: #fff; font-size: 9px;
    }
    .timeline-dot.in { background: #10b981; box-shadow: 0 0 0 2px rgba(16,185,129,.2); }
    .timeline-dot.out { background: #ef4444; box-shadow: 0 0 0 2px rgba(239,68,68,.2); }
    .timeline-text { font-size: .88rem; font-weight: 600; color: #0f172a; }
    .timeline-meta { font-size: .74rem; color: #94a3b8; margin-top: 2px; }
    .live-badge {
        font-size: .7rem; color: #10b981;
        background: rgba(16,185,129,.12); padding: 3px 9px;
        border-radius: 999px; font-weight: 700;
        display: inline-flex; align-items: center; gap: 5px;
    }
    .live-badge .dot {
        width: 6px; height: 6px; border-radius: 50%; background: #10b981;
        animation: sigapPulse 1.4s infinite;
    }
</style>
@endpush

@php
    // Util untuk membangun SVG sparkline dari array
    $sparkSvg = function (array $data, string $color) {
        $w = 120; $h = 36;
        $max = max($data); $min = min($data);
        $range = max(1, $max - $min);
        $step = count($data) > 1 ? $w / (count($data) - 1) : $w;
        $pts = [];
        foreach ($data as $i => $v) {
            $y = $h - (($v - $min) / $range) * ($h - 4) - 2;
            $pts[] = round($i * $step, 2) . ',' . round($y, 2);
        }
        $line = 'M ' . implode(' L ', $pts);
        $area = $line . " L {$w},{$h} L 0,{$h} Z";
        $id = 'sg-' . substr(md5($color . implode(',', $data)), 0, 6);
        return <<<SVG
<svg width="{$w}" height="{$h}" style="display:block;">
  <defs>
    <linearGradient id="{$id}" x1="0" y1="0" x2="0" y2="1">
      <stop offset="0%" stop-color="{$color}" stop-opacity="0.45"/>
      <stop offset="100%" stop-color="{$color}" stop-opacity="0"/>
    </linearGradient>
  </defs>
  <path d="{$area}" fill="url(#{$id})"/>
  <path d="{$line}" fill="none" stroke="{$color}" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
</svg>
SVG;
    };

    $stats = [
        ['label' => 'Total Barang',  'value' => $totalBarang, 'icon' => 'bi-boxes',           'color' => '#3b82f6', 'glow' => 'rgba(59,130,246,.12)',  'spark' => $sparkBarang, 'delta' => $deltaBarang > 0 ? ['text' => '+'.$deltaBarang.' baru', 'up' => true] : ['text' => '0%', 'up' => true]],
        ['label' => 'Total Stok',    'value' => $totalStok,   'icon' => 'bi-stack',           'color' => '#0ea5e9', 'glow' => 'rgba(14,165,233,.12)',  'spark' => $sparkStok,   'delta' => $deltaStok],
        ['label' => 'Barang Masuk',  'value' => $totalMasuk,  'icon' => 'bi-box-arrow-in-down','color' => '#10b981', 'glow' => 'rgba(16,185,129,.12)',  'spark' => $sparkMasuk,  'delta' => $deltaMasuk],
        ['label' => 'Barang Keluar', 'value' => $totalKeluar, 'icon' => 'bi-box-arrow-up',     'color' => '#ef4444', 'glow' => 'rgba(239,68,68,.12)',   'spark' => $sparkKeluar, 'delta' => $deltaKeluar],
    ];

    $maxBar = 1;
    foreach ($weeklyData as $d) { $maxBar = max($maxBar, $d['in'], $d['out']); }
@endphp

@section('content')

{{-- ============ STAT CARDS ============ --}}
<div class="row g-3 mb-3">
    @foreach($stats as $s)
        <div class="col-md-6 col-xl-3">
            <div class="sigap-stat">
                <div class="d-flex align-items-start justify-content-between mb-2">
                    <div class="sigap-stat-icon" style="background: {{ $s['glow'] }}; color: {{ $s['color'] }};">
                        <i class="bi {{ $s['icon'] }}"></i>
                    </div>
                    <span class="sigap-delta {{ $s['delta']['up'] ? 'up' : 'down' }}">
                        <i class="bi bi-arrow-{{ $s['delta']['up'] ? 'up' : 'down' }}-right"></i>
                        {{ $s['delta']['text'] }}
                    </span>
                </div>
                <div class="sigap-stat-label">{{ $s['label'] }}</div>
                <div class="sigap-stat-value">{{ $s['value'] }}</div>
                <div style="margin-top: 6px; margin-left: -4px;">
                    {!! $sparkSvg($s['spark'], $s['color']) !!}
                </div>
            </div>
        </div>
    @endforeach
</div>

{{-- ============ CHART + SUMMARY ============ --}}
<div class="row g-3 mb-3">
    <div class="col-xl-8">
        <div class="sigap-card h-100">
            <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                <div>
                    <h6 class="sigap-card-h6">Aktivitas Mingguan</h6>
                    <small class="sigap-card-sub">Pergerakan stok 7 hari terakhir</small>
                </div>
                <div class="d-flex align-items-center gap-3 flex-wrap">
                    <div class="d-flex align-items-center gap-2" style="font-size:.8rem;">
                        <span style="width:10px;height:10px;border-radius:3px;background:#10b981;"></span>
                        <span class="text-muted">Masuk</span>
                    </div>
                    <div class="d-flex align-items-center gap-2" style="font-size:.8rem;">
                        <span style="width:10px;height:10px;border-radius:3px;background:#ef4444;"></span>
                        <span class="text-muted">Keluar</span>
                    </div>
                </div>
            </div>

            @php $chartH = 200; @endphp
            <svg width="100%" height="{{ $chartH + 30 }}" viewBox="0 0 700 {{ $chartH + 30 }}" preserveAspectRatio="none">
                <defs>
                    <linearGradient id="barIn" x1="0" y1="0" x2="0" y2="1">
                        <stop offset="0%" stop-color="#34d399"/>
                        <stop offset="100%" stop-color="#10b981"/>
                    </linearGradient>
                    <linearGradient id="barOut" x1="0" y1="0" x2="0" y2="1">
                        <stop offset="0%" stop-color="#fb7185"/>
                        <stop offset="100%" stop-color="#ef4444"/>
                    </linearGradient>
                </defs>
                @foreach([0, 0.25, 0.5, 0.75, 1] as $p)
                    <line x1="40" x2="700" y1="{{ $chartH * $p + 5 }}" y2="{{ $chartH * $p + 5 }}" stroke="#f1f5f9" stroke-width="1"/>
                    <text x="32" y="{{ $chartH * $p + 9 }}" font-size="10" fill="#94a3b8" text-anchor="end">{{ round($maxBar * (1 - $p)) }}</text>
                @endforeach
                @foreach($weeklyData as $i => $d)
                    @php
                        $groupX = 60 + $i * 92;
                        $inH  = ($d['in']  / $maxBar) * $chartH;
                        $outH = ($d['out'] / $maxBar) * $chartH;
                    @endphp
                    <rect x="{{ $groupX }}"      y="{{ $chartH - $inH + 5 }}"  width="26" height="{{ max(0, $inH) }}"  rx="5" fill="url(#barIn)"/>
                    <rect x="{{ $groupX + 32 }}" y="{{ $chartH - $outH + 5 }}" width="26" height="{{ max(0, $outH) }}" rx="5" fill="url(#barOut)"/>
                    <text x="{{ $groupX + 30 }}" y="{{ $chartH + 22 }}" font-size="11" fill="#64748b" text-anchor="middle" font-weight="500">{{ $d['day'] }}</text>
                @endforeach
            </svg>
        </div>
    </div>

    <div class="col-xl-4">
        <div class="sigap-summary">
            <div class="lab">RINGKASAN BULAN INI</div>
            <div class="num">{{ ($netBulan >= 0 ? '+' : '') . $netBulan }}</div>
            <div class="desc">Net pergerakan stok ({{ \Carbon\Carbon::now()->locale('id')->isoFormat('MMMM Y') }})</div>

            <div class="mt-4 d-flex flex-column gap-3">
                <div>
                    <div class="d-flex justify-content-between" style="font-size:.82rem;">
                        <span>Masuk</span>
                        <span class="fw-bold">{{ $masukBulan }} unit</span>
                    </div>
                    <div class="sigap-summary-bar">
                        <div style="width: {{ round(($masukBulan / $maxBulan) * 100) }}%; background: linear-gradient(90deg,#34d399,#10b981);"></div>
                    </div>
                </div>
                <div>
                    <div class="d-flex justify-content-between" style="font-size:.82rem;">
                        <span>Keluar</span>
                        <span class="fw-bold">{{ $keluarBulan }} unit</span>
                    </div>
                    <div class="sigap-summary-bar">
                        <div style="width: {{ round(($keluarBulan / $maxBulan) * 100) }}%; background: linear-gradient(90deg,#fb7185,#ef4444);"></div>
                    </div>
                </div>
            </div>

            <a href="{{ route('transaksi.riwayat') }}" class="btn w-100 mt-4 sigap-summary-btn">
                Lihat laporan lengkap <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>
    </div>
</div>

{{-- ============ STOK + TIMELINE ============ --}}
<div class="row g-3">
    <div class="col-xl-7">
        <div class="sigap-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h6 class="sigap-card-h6">Stok per Barang</h6>
                    <small class="sigap-card-sub">Top 6 barang berdasarkan stok</small>
                </div>
                <a href="{{ route('barang.index') }}" class="text-decoration-none fw-semibold" style="color:#3b82f6; font-size:.85rem;">
                    Kelola barang →
                </a>
            </div>

            @forelse($stockBars as $b)
                @php
                    $pct = min(100, round(($b->stok / $maxCap) * 100));
                    $low = $b->stok <= 5;
                    $color = $b->stok > 20 ? '#10b981'
                            : ($b->stok > 10 ? '#3b82f6'
                            : ($b->stok > 5  ? '#f59e0b' : '#ef4444'));
                @endphp
                <div class="stock-row mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            <span class="stock-dot" style="background: {{ $color }}; box-shadow: 0 0 8px {{ $color }};"></span>
                            <span style="font-weight:600; color:#0f172a; font-size:.9rem;">{{ $b->nama_barang }}</span>
                            <span class="stock-tag">{{ $b->jenis }}</span>
                            @if($low)
                                <span class="stock-low"><i class="bi bi-exclamation-triangle-fill"></i> LOW</span>
                            @endif
                        </div>
                        <span style="font-size:.82rem; color:#475569; font-weight:600;">
                            {{ $b->stok }}<span style="color:#94a3b8; font-weight:500;"> / {{ $maxCap }}</span>
                        </span>
                    </div>
                    <div class="stock-bar-bg">
                        <div class="stock-bar-fg" style="width: {{ $pct }}%; background: linear-gradient(90deg, {{ $color }}cc, {{ $color }}); box-shadow: 0 0 8px {{ $color }}66;"></div>
                    </div>
                </div>
            @empty
                <p class="text-muted text-center py-4 mb-0">Belum ada data barang.</p>
            @endforelse
        </div>
    </div>

    <div class="col-xl-5">
        <div class="sigap-card h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h6 class="sigap-card-h6">Aktivitas Terbaru</h6>
                    <small class="sigap-card-sub">Live activity feed</small>
                </div>
                <span class="live-badge"><span class="dot"></span> LIVE</span>
            </div>

            @if($timeline->isEmpty())
                <p class="text-muted text-center py-4 mb-0">Belum ada aktivitas transaksi.</p>
            @else
                <div class="timeline">
                    @foreach($timeline as $t)
                        @php $isIn = $t->jenis === 'masuk'; @endphp
                        <div class="timeline-item">
                            <div class="timeline-dot {{ $isIn ? 'in' : 'out' }}">
                                <i class="bi bi-arrow-{{ $isIn ? 'down' : 'up' }}"></i>
                            </div>
                            <div class="timeline-text">
                                {{ $t->jumlah }} {{ $t->barang->nama_barang ?? 'Barang' }} {{ $isIn ? 'masuk' : 'keluar' }}
                            </div>
                            <div class="timeline-meta">
                                {{ $t->tanggal->locale('id')->isoFormat('D MMM') }} · {{ $t->created_at->format('H:i') }}
                            </div>
                        </div>
                    @endforeach
                </div>
                <a href="{{ route('transaksi.riwayat') }}" class="btn btn-light w-100 mt-3" style="border-radius:10px; font-weight:600; font-size:.85rem; color:#3b82f6;">
                    Lihat semua riwayat
                </a>
            @endif
        </div>
    </div>
</div>

@endsection
