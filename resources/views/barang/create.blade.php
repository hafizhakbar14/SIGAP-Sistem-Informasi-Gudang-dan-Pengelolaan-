@extends('layouts.app')

@section('title', 'Tambah Barang')
@section('subtitle', 'Tambahkan item baru ke inventaris gudang')

@push('styles')
<style>
    .form-card-head {
        display: flex; align-items: center; gap: .9rem;
        padding-bottom: 1.25rem;
        border-bottom: 1px solid #f1f5f9;
        margin-bottom: 1.5rem;
    }
    .form-icon-pill {
        width: 48px; height: 48px;
        border-radius: 12px;
        background: linear-gradient(135deg, rgba(59,130,246,.15), rgba(139,92,246,.15));
        color: #6366f1;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.4rem;
    }
    .form-card-head h5 { margin: 0; font-weight: 700; color: #0f172a; }
    .form-card-head .sub { font-size: .85rem; color: #64748b; }

    .sg-label {
        font-weight: 600;
        font-size: .85rem;
        color: #334155;
        margin-bottom: 6px;
        display: block;
    }
    .sg-label .req { color: #ef4444; margin-left: 2px; }
    .sg-input, .sg-textarea, .sg-file {
        width: 100%;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: .65rem .9rem;
        font-size: .92rem;
        color: #0f172a;
        transition: all .2s;
    }
    .sg-input:focus, .sg-textarea:focus, .sg-file:focus {
        background: #fff;
        border-color: #6366f1;
        outline: none;
        box-shadow: 0 0 0 3px rgba(99,102,241,.15);
    }
    .sg-textarea { resize: vertical; min-height: 90px; }
    .sg-help { font-size: .78rem; color: #94a3b8; margin-top: 4px; display: block; }

    .btn-save {
        background: linear-gradient(135deg, #3b82f6, #8b5cf6);
        color: #fff; border: none;
        padding: .6rem 1.6rem;
        border-radius: 10px;
        font-weight: 600;
        box-shadow: 0 6px 18px rgba(99,102,241,.3);
        transition: transform .2s;
    }
    .btn-save:hover { transform: translateY(-2px); color: #fff; }
    .btn-cancel {
        background: #fff;
        color: #475569;
        border: 1px solid #e2e8f0;
        padding: .6rem 1.4rem;
        border-radius: 10px;
        font-weight: 600;
        text-decoration: none;
    }
    .btn-cancel:hover { background: #f1f5f9; color: #0f172a; }

    .alert-form {
        background: rgba(239,68,68,.08);
        border: 1px solid rgba(239,68,68,.25);
        color: #b91c1c;
        padding: .8rem 1rem;
        border-radius: 10px;
        margin-bottom: 1.25rem;
    }
    .alert-form ul { margin: 0; padding-left: 1.25rem; font-size: .88rem; }
</style>
@endpush

@section('content')
<div class="row">
    <div class="col-lg-9 col-xl-8">
        <div class="sigap-card" style="padding: 1.75rem;">
            <div class="form-card-head">
                <div class="form-icon-pill"><i class="bi bi-plus-circle-fill"></i></div>
                <div>
                    <h5>Tambah Barang Baru</h5>
                    <div class="sub">Isi data barang lalu klik Simpan</div>
                </div>
            </div>

            @if($errors->any())
                <div class="alert-form">
                    <strong><i class="bi bi-exclamation-triangle-fill"></i> Periksa input berikut:</strong>
                    <ul class="mt-2">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('barang.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="sg-label">Nama Barang <span class="req">*</span></label>
                        <input type="text" name="nama_barang" class="sg-input"
                               placeholder="Contoh: Laptop Asus X415"
                               value="{{ old('nama_barang') }}" required>
                    </div>
                    <div class="col-md-4">
                        <label class="sg-label">Jenis <span class="req">*</span></label>
                        <input type="text" name="jenis" class="sg-input"
                               placeholder="Elektronik / Furniture / ATK"
                               value="{{ old('jenis') }}" required>
                    </div>
                    <div class="col-md-2">
                        <label class="sg-label">Stok Awal <span class="req">*</span></label>
                        <input type="number" name="stok" class="sg-input"
                               min="0" value="{{ old('stok', 0) }}" required>
                    </div>
                    <div class="col-12">
                        <label class="sg-label">Deskripsi</label>
                        <textarea name="deskripsi" rows="3" class="sg-textarea"
                                  placeholder="Tulis deskripsi barang (opsional)…">{{ old('deskripsi') }}</textarea>
                    </div>
                    <div class="col-12">
                        <label class="sg-label">Gambar Barang</label>
                        <input type="file" name="gambar" class="sg-file" accept="image/*">
                        <small class="sg-help">
                            <i class="bi bi-info-circle"></i>
                            Format: JPG, PNG, GIF, WEBP — maksimal 2MB
                        </small>
                    </div>
                </div>

                <div class="mt-4 d-flex gap-2 flex-wrap">
                    <button type="submit" class="btn-save">
                        <i class="bi bi-save2-fill"></i> Simpan Barang
                    </button>
                    <a href="{{ route('barang.index') }}" class="btn-cancel">
                        <i class="bi bi-x-lg"></i> Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
