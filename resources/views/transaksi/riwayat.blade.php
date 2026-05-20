@extends('layouts.app')

@section('title', 'Riwayat Transaksi')
@section('subtitle', 'Catatan lengkap semua pergerakan stok gudang')

@push('styles')
<style>
    /* Filter card */
    .filter-card { padding: 1.25rem 1.4rem; }
    .filter-label {
        font-size: .72rem; font-weight: 700;
        color: #64748b; letter-spacing: .5px;
        text-transform: uppercase; margin-bottom: 6px;
        display: block;
    }
    .filter-input, .filter-select {
        width: 100%;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: .55rem .85rem;
        font-size: .88rem;
        color: #0f172a;
        transition: all .2s;
        appearance: none;
        -webkit-appearance: none;
    }
    .filter-input:focus, .filter-select:focus {
        background: #fff;
        border-color: #6366f1;
        outline: none;
        box-shadow: 0 0 0 3px rgba(99,102,241,.15);
    }
    .filter-select {
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3E%3Cpath fill='%2364748b' d='M6 8L0 0h12z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 1rem center;
        padding-right: 2.5rem;
    }
    .btn-filter {
        background: linear-gradient(135deg, #3b82f6, #8b5cf6);
        color: #fff; border: none;
        padding: .55rem 1.2rem;
        border-radius: 10px;
        font-weight: 600;
        font-size: .88rem;
        flex: 1;
        box-shadow: 0 4px 12px rgba(99,102,241,.25);
    }
    .btn-filter:hover { filter: brightness(1.08); color: #fff; }
    .btn-reset {
        background: #fff; border: 1px solid #e2e8f0;
        padding: .55rem .8rem; border-radius: 10px;
        color: #64748b;
    }
    .btn-reset:hover { background: #f1f5f9; color: #0f172a; }

    /* Stat strip */
    .stat-strip {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1rem;
    }
    .stat-mini {
        background: #fff;
        border: 1px solid var(--line);
        border-radius: 14px;
        padding: 1rem 1.1rem;
        position: relative;
        overflow: hidden;
        animation: sigapFadeIn .5s both;
    }
    .stat-mini .lab {
        font-size: .7rem; letter-spacing: .5px;
        color: #64748b; text-transform: uppercase;
        font-weight: 600;
    }
    .stat-mini .val {
        font-size: 1.6rem; font-weight: 800;
        color: #0f172a; line-height: 1.1;
        margin-top: 4px;
    }
    .stat-mini .ic {
        position: absolute; top: 50%; right: 1rem;
        transform: translateY(-50%);
        width: 38px; height: 38px;
        border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.1rem;
    }
    .stat-mini.in  .ic { background: rgba(16,185,129,.12); color: #059669; }
    .stat-mini.out .ic { background: rgba(239,68,68,.12);  color: #dc2626; }
    .stat-mini.net .ic { background: rgba(99,102,241,.12); color: #6366f1; }
    .stat-mini.cnt .ic { background: rgba(14,165,233,.12); color: #0284c7; }
    @media (max-width: 768px) { .stat-strip { grid-template-columns: repeat(2, 1fr); } }

    /* Table */
    .sigap-table { margin: 0; }
    .sigap-table thead {
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
    }
    .sigap-table thead th {
        font-weight: 700; color: #475569;
        font-size: .76rem; letter-spacing: .5px;
        text-transform: uppercase;
        padding: .9rem 1rem; border: none;
    }
    .sigap-table tbody tr {
        border-bottom: 1px solid #f1f5f9;
        transition: background .15s;
    }
    .sigap-table tbody tr:hover { background: #f8fafc; }
    .sigap-table tbody td {
        padding: .85rem 1rem;
        vertical-align: middle;
        border: none; color: #334155;
    }

    .pill {
        display: inline-flex; align-items: center; gap: 4px;
        padding: 4px 10px; border-radius: 999px;
        font-size: .72rem; font-weight: 700;
        letter-spacing: .3px;
    }
    .pill-in  { background: rgba(16,185,129,.12); color: #059669; }
    .pill-out { background: rgba(239,68,68,.12);  color: #dc2626; }

    .qty-pos { color: #059669; font-weight: 800; }
    .qty-neg { color: #dc2626; font-weight: 800; }

    .btn-export {
        background: linear-gradient(135deg, #16a34a, #059669);
        color: #fff; border: none;
        padding: .5rem 1rem;
        border-radius: 10px;
        font-weight: 600;
        font-size: .85rem;
        text-decoration: none;
        display: inline-flex; align-items: center; gap: .4rem;
        box-shadow: 0 4px 12px rgba(22,163,74,.25);
    }
    .btn-export:hover { color: #fff; filter: brightness(1.08); }

    .empty-state {
        padding: 3rem 1rem; text-align: center; color: #94a3b8;
    }
    .empty-state i { font-size: 3rem; opacity: .35; }

    .sigap-pagination .page-link {
        border: 1px solid #e2e8f0;
        color: #475569; margin: 0 2px;
        border-radius: 8px;
        font-weight: 600; font-size: .85rem;
        padding: .4rem .8rem;
    }
    .sigap-pagination .page-item.active .page-link {
        background: linear-gradient(135deg, #3b82f6, #8b5cf6);
        border-color: transparent; color: #fff;
    }
    .sigap-pagination .page-item.disabled .page-link {
        color: #cbd5e1; background: #f8fafc;
    }

    .filter-active-tag {
        display: inline-flex; align-items: center; gap: 5px;
        background: rgba(99,102,241,.1);
        color: #4f46e5;
        font-size: .75rem; font-weight: 600;
        padding: 4px 10px;
        border-radius: 999px;
        margin-right: 4px;
    }
</style>
@endpush

@section('content')

{{-- ===== STAT STRIP ===== --}}
<div class="stat-strip mb-3">
    <div class="stat-mini in">
        <div class="lab">Total Masuk</div>
        <div class="val">{{ $totalMasuk }}</div>
        <div class="ic"><i class="bi bi-box-arrow-in-down"></i></div>
    </div>
    <div class="stat-mini out">
        <div class="lab">Total Keluar</div>
        <div class="val">{{ $totalKeluar }}</div>
        <div class="ic"><i class="bi bi-box-arrow-up"></i></div>
    </div>
    <div class="stat-mini net">
        <div class="lab">Net Pergerakan</div>
        <div class="val" style="color: {{ $totalNet >= 0 ? '#059669' : '#dc2626' }};">
            {{ ($totalNet >= 0 ? '+' : '') . $totalNet }}
        </div>
        <div class="ic"><i class="bi bi-arrow-down-up"></i></div>
    </div>
    <div class="stat-mini cnt">
        <div class="lab">Jumlah Transaksi</div>
        <div class="val">{{ $totalCount }}</div>
        <div class="ic"><i class="bi bi-list-ul"></i></div>
    </div>
</div>

{{-- ===== FILTER CARD ===== --}}
<div class="sigap-card filter-card mb-3">
    <form method="GET" class="row g-3 align-items-end">
        <div class="col-lg-3 col-md-6">
            <label class="filter-label">Cari Nama Barang</label>
            <input type="text" name="search" class="filter-input"
                   placeholder="Mis. Laptop…" value="{{ request('search') }}">
        </div>
        <div class="col-lg-2 col-md-6">
            <label class="filter-label">Jenis Transaksi</label>
            <select name="jenis" class="filter-select">
                <option value="">Semua</option>
                <option value="masuk"  {{ request('jenis')=='masuk'  ? 'selected':'' }}>Masuk</option>
                <option value="keluar" {{ request('jenis')=='keluar' ? 'selected':'' }}>Keluar</option>
            </select>
        </div>
        <div class="col-lg-2 col-md-6">
            <label class="filter-label">Dari Tanggal</label>
            <input type="date" name="dari" class="filter-input" value="{{ request('dari') }}">
        </div>
        <div class="col-lg-2 col-md-6">
            <label class="filter-label">Sampai Tanggal</label>
            <input type="date" name="sampai" class="filter-input" value="{{ request('sampai') }}">
        </div>
        <div class="col-lg-3 col-md-12 d-flex gap-2">
            <button type="submit" class="btn-filter">
                <i class="bi bi-funnel-fill"></i> Terapkan Filter
            </button>
            <a href="{{ route('transaksi.riwayat') }}" class="btn-reset" title="Reset filter">
                <i class="bi bi-arrow-counterclockwise"></i>
            </a>
        </div>
    </form>

    @if(request()->hasAny(['search','jenis','dari','sampai']))
        <div class="mt-3 pt-3" style="border-top: 1px dashed #e2e8f0;">
            <small class="text-muted me-2">Filter aktif:</small>
            @if(request('search'))
                <span class="filter-active-tag"><i class="bi bi-search"></i> "{{ request('search') }}"</span>
            @endif
            @if(request('jenis'))
                <span class="filter-active-tag"><i class="bi bi-tag"></i> {{ ucfirst(request('jenis')) }}</span>
            @endif
            @if(request('dari'))
                <span class="filter-active-tag"><i class="bi bi-calendar3"></i> ≥ {{ request('dari') }}</span>
            @endif
            @if(request('sampai'))
                <span class="filter-active-tag"><i class="bi bi-calendar3"></i> ≤ {{ request('sampai') }}</span>
            @endif
        </div>
    @endif
</div>

{{-- ===== TABEL ===== --}}
<div class="sigap-card" style="padding: 1.25rem 1.4rem;">
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <div>
            <h6 class="sigap-card-h6"><i class="bi bi-clock-history" style="color: #6366f1;"></i> Daftar Transaksi</h6>
            <small class="sigap-card-sub">{{ $totalCount }} transaksi ditemukan</small>
        </div>
        <a href="{{ route('transaksi.riwayat.export', request()->query()) }}" class="btn-export">
            <i class="bi bi-file-earmark-excel-fill"></i> Export CSV
        </a>
    </div>

    <div class="table-responsive">
        <table class="sigap-table table align-middle">
            <thead>
                <tr>
                    <th style="width: 50px;">#</th>
                    <th style="width: 130px;">Tanggal</th>
                    <th>Nama Barang</th>
                    <th>Jenis Barang</th>
                    <th>Transaksi</th>
                    <th class="text-end">Jumlah</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transaksi as $t)
                    <tr>
                        <td class="text-muted small fw-semibold">
                            {{ $loop->iteration + ($transaksi->currentPage()-1) * $transaksi->perPage() }}
                        </td>
                        <td>
                            <div style="font-weight: 600; color: #0f172a; font-size: .88rem;">
                                {{ $t->tanggal->locale('id')->isoFormat('D MMM Y') }}
                            </div>
                            <small class="text-muted" style="font-size: .72rem;">
                                {{ $t->tanggal->locale('id')->isoFormat('dddd') }}
                            </small>
                        </td>
                        <td class="fw-bold" style="color: #0f172a;">
                            {{ $t->barang->nama_barang ?? '(barang dihapus)' }}
                        </td>
                        <td>
                            <span style="font-size: .78rem; color: #64748b; background: #f1f5f9; padding: 3px 9px; border-radius: 6px;">
                                {{ $t->barang->jenis ?? '—' }}
                            </span>
                        </td>
                        <td>
                            @if($t->jenis === 'masuk')
                                <span class="pill pill-in"><i class="bi bi-arrow-down-circle-fill"></i> Masuk</span>
                            @else
                                <span class="pill pill-out"><i class="bi bi-arrow-up-circle-fill"></i> Keluar</span>
                            @endif
                        </td>
                        <td class="text-end {{ $t->jenis === 'masuk' ? 'qty-pos' : 'qty-neg' }}">
                            {{ $t->jenis === 'masuk' ? '+' : '−' }}{{ $t->jumlah }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">
                            <div class="empty-state">
                                <i class="bi bi-clock-history"></i>
                                <div class="mt-2 fw-semibold">Tidak ada riwayat transaksi</div>
                                <div class="small mt-1">
                                    @if(request()->hasAny(['search','jenis','dari','sampai']))
                                        Coba sesuaikan filter atau <a href="{{ route('transaksi.riwayat') }}">reset filter</a>.
                                    @else
                                        Mulai catat transaksi melalui menu <strong>Barang Masuk</strong> atau <strong>Barang Keluar</strong>.
                                    @endif
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($transaksi->hasPages())
        <div class="d-flex justify-content-between align-items-center mt-3 flex-wrap gap-2">
            <small class="text-muted">
                Menampilkan {{ $transaksi->firstItem() }}–{{ $transaksi->lastItem() }} dari {{ $transaksi->total() }} transaksi
            </small>
            <div class="sigap-pagination">
                {{ $transaksi->links('pagination::bootstrap-5') }}
            </div>
        </div>
    @endif
</div>
@endsection
