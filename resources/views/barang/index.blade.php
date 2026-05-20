@extends('layouts.app')

@section('title', 'Data Barang')
@section('subtitle', 'Kelola seluruh inventaris gudang Anda')

@push('styles')
<style>
    .sigap-search-box {
        position: relative; max-width: 480px; flex: 1;
    }
    .sigap-search-box i.bi-search {
        position: absolute; top: 50%; left: 14px; transform: translateY(-50%);
        color: #94a3b8; pointer-events: none;
    }
    .sigap-search-input {
        width: 100%;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: .55rem 5rem .55rem 2.4rem;
        font-size: .9rem;
        color: #1e293b;
        transition: all .2s;
    }
    .sigap-search-input:focus {
        background: #fff;
        border-color: #3b82f6;
        outline: none;
        box-shadow: 0 0 0 3px rgba(59,130,246,.15);
    }
    .sigap-search-btn {
        position: absolute; right: 4px; top: 50%; transform: translateY(-50%);
        background: linear-gradient(135deg, #3b82f6, #8b5cf6);
        color: #fff; border: none;
        padding: .35rem 1rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: .85rem;
    }
    .sigap-search-btn:hover { filter: brightness(1.1); }

    .btn-add-barang {
        background: linear-gradient(135deg, #3b82f6, #8b5cf6);
        color: #fff; border: none;
        padding: .6rem 1.2rem;
        border-radius: 10px;
        font-weight: 600;
        font-size: .9rem;
        box-shadow: 0 6px 18px rgba(99,102,241,.3);
        transition: transform .2s, box-shadow .2s;
        white-space: nowrap;
    }
    .btn-add-barang:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 24px rgba(99,102,241,.45);
        color: #fff;
    }

    .sigap-table { margin: 0; }
    .sigap-table thead {
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
    }
    .sigap-table thead th {
        font-weight: 700;
        color: #475569;
        font-size: .78rem;
        letter-spacing: .5px;
        text-transform: uppercase;
        padding: .9rem 1rem;
        border: none;
    }
    .sigap-table tbody tr {
        border-bottom: 1px solid #f1f5f9;
        transition: background .15s;
    }
    .sigap-table tbody tr:hover { background: #f8fafc; }
    .sigap-table tbody td {
        padding: .9rem 1rem;
        vertical-align: middle;
        border: none;
        color: #334155;
    }

    .sigap-thumb {
        width: 48px; height: 48px;
        border-radius: 10px;
        object-fit: cover;
        border: 1px solid #e2e8f0;
    }
    .sigap-thumb-empty {
        width: 48px; height: 48px;
        background: #f1f5f9;
        border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        color: #94a3b8; font-size: 1.1rem;
    }

    .pill {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 999px;
        font-size: .72rem;
        font-weight: 700;
        letter-spacing: .3px;
    }
    .pill-jenis {
        background: rgba(59,130,246,.12);
        color: #2563eb;
    }
    .pill-stok-ok    { background: rgba(16,185,129,.12);  color: #059669; }
    .pill-stok-mid   { background: rgba(245,158,11,.15);  color: #b45309; }
    .pill-stok-low   { background: rgba(239,68,68,.12);   color: #dc2626; animation: sigapPulse 1.6s infinite; }

    .btn-icon {
        width: 34px; height: 34px;
        display: inline-flex; align-items: center; justify-content: center;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        background: #fff;
        transition: all .15s;
        margin-right: 4px;
    }
    .btn-icon:last-child { margin-right: 0; }
    .btn-icon-edit { color: #3b82f6; }
    .btn-icon-edit:hover { background: rgba(59,130,246,.1); border-color: #3b82f6; }
    .btn-icon-del { color: #ef4444; }
    .btn-icon-del:hover { background: rgba(239,68,68,.1); border-color: #ef4444; }

    .empty-state {
        padding: 3rem 1rem; text-align: center; color: #94a3b8;
    }
    .empty-state i { font-size: 3rem; opacity: .35; }

    .sigap-pagination .page-link {
        border: 1px solid #e2e8f0;
        color: #475569;
        margin: 0 2px;
        border-radius: 8px;
        font-weight: 600;
        font-size: .85rem;
        padding: .4rem .8rem;
    }
    .sigap-pagination .page-item.active .page-link {
        background: linear-gradient(135deg, #3b82f6, #8b5cf6);
        border-color: transparent;
        color: #fff;
    }
    .sigap-pagination .page-item.disabled .page-link {
        color: #cbd5e1; background: #f8fafc;
    }
</style>
@endpush

@section('content')
<div class="sigap-card" style="padding: 1.5rem;">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
        <form method="GET" class="sigap-search-box">
            <i class="bi bi-search"></i>
            <input type="text"
                   name="search"
                   class="sigap-search-input"
                   placeholder="Cari nama / jenis barang…"
                   value="{{ request('search') }}">
            <button type="submit" class="sigap-search-btn">Cari</button>
        </form>
        <a href="{{ route('barang.create') }}" class="btn-add-barang">
            <i class="bi bi-plus-lg"></i> Tambah Barang
        </a>
    </div>

    <div class="table-responsive">
        <table class="sigap-table table align-middle">
            <thead>
                <tr>
                    <th style="width: 50px;">#</th>
                    <th style="width: 70px;">Gambar</th>
                    <th>Nama Barang</th>
                    <th>Jenis</th>
                    <th class="text-center">Stok</th>
                    <th>Deskripsi</th>
                    <th class="text-center" style="width: 130px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($barang as $b)
                    @php
                        $stokClass = $b->stok > 10 ? 'pill-stok-ok'
                                   : ($b->stok > 5 ? 'pill-stok-mid' : 'pill-stok-low');
                    @endphp
                    <tr>
                        <td class="text-muted small fw-semibold">
                            {{ $loop->iteration + ($barang->currentPage()-1) * $barang->perPage() }}
                        </td>
                        <td>
                            @if($b->gambar)
                                <img src="{{ asset('uploads/barang/'.$b->gambar) }}"
                                     alt="{{ $b->nama_barang }}"
                                     class="sigap-thumb">
                            @else
                                <div class="sigap-thumb-empty"><i class="bi bi-image"></i></div>
                            @endif
                        </td>
                        <td class="fw-bold" style="color: #0f172a;">{{ $b->nama_barang }}</td>
                        <td><span class="pill pill-jenis">{{ $b->jenis }}</span></td>
                        <td class="text-center"><span class="pill {{ $stokClass }}">{{ $b->stok }}</span></td>
                        <td class="text-muted small">
                            {{ \Illuminate\Support\Str::limit($b->deskripsi, 60) ?: '—' }}
                        </td>
                        <td class="text-center">
                            <a href="{{ route('barang.edit', $b) }}"
                               class="btn-icon btn-icon-edit"
                               title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('barang.destroy', $b) }}"
                                  method="POST"
                                  class="d-inline"
                                  onsubmit="return confirm('Yakin hapus barang \'{{ $b->nama_barang }}\'?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-icon btn-icon-del" title="Hapus">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7">
                            <div class="empty-state">
                                <i class="bi bi-inbox"></i>
                                <div class="mt-2 fw-semibold">Belum ada data barang</div>
                                <div class="small mt-1">
                                    Klik <strong>“Tambah Barang”</strong> untuk menambahkan barang pertama.
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($barang->hasPages())
        <div class="d-flex justify-content-between align-items-center mt-3 flex-wrap gap-2">
            <small class="text-muted">
                Menampilkan {{ $barang->firstItem() }}–{{ $barang->lastItem() }} dari {{ $barang->total() }} barang
            </small>
            <div class="sigap-pagination">
                {{ $barang->links('pagination::bootstrap-5') }}
            </div>
        </div>
    @endif
</div>
@endsection
