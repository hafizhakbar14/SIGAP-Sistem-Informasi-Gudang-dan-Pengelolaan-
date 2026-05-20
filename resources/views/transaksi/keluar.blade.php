@extends('layouts.app')

@section('title', 'Barang Keluar')
@section('subtitle', 'Catat pengurangan stok untuk barang yang dikeluarkan')

@push('styles')
<style>
    .form-card-head {
        display: flex; align-items: center; gap: 1rem;
        margin-bottom: 1.5rem;
    }
    .form-icon-circle {
        width: 52px; height: 52px;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.6rem;
    }
    .form-icon-circle.out {
        background: linear-gradient(135deg, #fee2e2, #fecaca);
        color: #dc2626;
        box-shadow: 0 6px 16px rgba(220,38,38,.18);
    }
    .form-card-head h5 { margin: 0; font-weight: 700; color: #0f172a; font-size: 1.15rem; }
    .form-card-head .sub { font-size: .85rem; color: #64748b; }

    .sg-label {
        font-weight: 600; font-size: .85rem; color: #334155;
        margin-bottom: 6px; display: block;
    }
    .sg-label .req { color: #ef4444; margin-left: 2px; }

    .sg-input, .sg-select {
        width: 100%;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: .65rem .9rem;
        font-size: .92rem;
        color: #0f172a;
        transition: all .2s;
        appearance: none;
        -webkit-appearance: none;
    }
    .sg-input:focus, .sg-select:focus {
        background: #fff;
        border-color: #dc2626;
        outline: none;
        box-shadow: 0 0 0 3px rgba(220,38,38,.15);
    }
    .sg-select {
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3E%3Cpath fill='%2364748b' d='M6 8L0 0h12z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 1rem center;
        padding-right: 2.5rem;
    }

    .btn-save-out {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: #fff; border: none;
        padding: .65rem 1.6rem;
        border-radius: 10px;
        font-weight: 600;
        box-shadow: 0 6px 18px rgba(220,38,38,.3);
        transition: transform .2s;
    }
    .btn-save-out:hover { transform: translateY(-2px); color: #fff; }
    .btn-cancel {
        background: #fff;
        color: #475569;
        border: 1px solid #e2e8f0;
        padding: .65rem 1.4rem;
        border-radius: 10px;
        font-weight: 600;
        text-decoration: none;
    }
    .btn-cancel:hover { background: #f1f5f9; color: #0f172a; }

    .info-card {
        margin-top: 1rem;
        background: linear-gradient(135deg, #fef2f2, #fff1f2);
        border: 1px solid rgba(239,68,68,.2);
        border-radius: 12px;
        padding: .9rem 1.1rem;
        display: flex; align-items: center; gap: .9rem;
    }
    .info-card i { color: #dc2626; font-size: 1.4rem; flex-shrink: 0; }
    .info-card small { color: #475569; }

    .stock-preview {
        padding: .55rem .85rem;
        border-radius: 6px;
        margin-top: .5rem;
        font-size: .82rem;
        font-weight: 600;
        display: none;
    }
    .stock-preview.show { display: block; animation: sigapFadeIn .3s; }
    .stock-preview.ok    { background: rgba(220,38,38,.08); border-left: 3px solid #dc2626; color: #991b1b; }
    .stock-preview.error { background: rgba(245,158,11,.12); border-left: 3px solid #f59e0b; color: #92400e; }

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
<div class="row justify-content-center">
    <div class="col-lg-9 col-xl-7">
        <div class="sigap-card" style="padding: 1.75rem;">
            <div class="form-card-head">
                <div class="form-icon-circle out">
                    <i class="bi bi-arrow-up-circle-fill"></i>
                </div>
                <div>
                    <h5>Catat Barang Keluar</h5>
                    <div class="sub">Kurangi stok untuk barang yang keluar dari gudang</div>
                </div>
            </div>

            @if($errors->any())
                <div class="alert-form">
                    <strong><i class="bi bi-exclamation-triangle-fill"></i> Periksa input berikut:</strong>
                    <ul class="mt-2">
                        @foreach($errors->all() as $err) <li>{{ $err }}</li> @endforeach
                    </ul>
                </div>
            @endif

            @if($barang->isEmpty())
                <div class="alert alert-warning d-flex align-items-center gap-2" style="border-radius: 10px;">
                    <i class="bi bi-info-circle-fill"></i>
                    <span>Belum ada barang dengan stok tersedia. <a href="{{ route('barang.create') }}" class="alert-link">Tambah barang</a> atau catat barang masuk dulu.</span>
                </div>
            @else
                <form action="{{ route('transaksi.keluar.store') }}" method="POST" id="keluarForm">
                    @csrf
                    <div class="mb-3">
                        <label class="sg-label">Pilih Barang <span class="req">*</span></label>
                        <select name="barang_id" class="sg-select" id="selBarang" required>
                            <option value="">— Pilih Barang —</option>
                            @foreach($barang as $b)
                                <option value="{{ $b->id }}"
                                        data-stok="{{ $b->stok }}"
                                        data-nama="{{ $b->nama_barang }}"
                                        {{ old('barang_id') == $b->id ? 'selected' : '' }}>
                                    {{ $b->nama_barang }} (stok tersedia: {{ $b->stok }})
                                </option>
                            @endforeach
                        </select>
                        <div class="stock-preview" id="stockPreview">
                            <i class="bi bi-box-seam"></i> <span id="stockPreviewText"></span>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="sg-label">Jumlah Keluar <span class="req">*</span></label>
                            <input type="number" name="jumlah" id="inpJumlah"
                                   class="sg-input" min="1"
                                   placeholder="0"
                                   value="{{ old('jumlah') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="sg-label">Tanggal <span class="req">*</span></label>
                            <input type="date" name="tanggal" class="sg-input"
                                   value="{{ old('tanggal', date('Y-m-d')) }}" required>
                        </div>
                    </div>

                    <div class="mt-4 d-flex gap-2 flex-wrap">
                        <button type="submit" class="btn-save-out">
                            <i class="bi bi-check2-circle"></i> Simpan Transaksi
                        </button>
                        <a href="{{ route('transaksi.riwayat') }}" class="btn-cancel">
                            <i class="bi bi-x-lg"></i> Batal
                        </a>
                    </div>
                </form>
            @endif
        </div>

        <div class="info-card">
            <i class="bi bi-exclamation-circle-fill"></i>
            <small>
                <strong>Perhatian:</strong> Jumlah keluar tidak boleh melebihi stok tersedia.
                Stok akan otomatis berkurang setelah disimpan.
            </small>
        </div>
    </div>
</div>

@push('scripts')
<script>
    (function () {
        const sel = document.getElementById('selBarang');
        const inp = document.getElementById('inpJumlah');
        const prev = document.getElementById('stockPreview');
        const txt = document.getElementById('stockPreviewText');
        if (!sel) return;

        function update() {
            const opt = sel.options[sel.selectedIndex];
            const stok = parseInt(opt?.dataset.stok || 0, 10);
            const nama = opt?.dataset.nama || '';
            const jml  = parseInt(inp.value || 0, 10);
            prev.classList.remove('show', 'ok', 'error');
            if (!sel.value || jml <= 0) return;

            if (jml > stok) {
                txt.textContent = `Stok ${nama} hanya ${stok} unit — tidak cukup untuk dikeluarkan ${jml} unit!`;
                prev.classList.add('show', 'error');
                inp.max = stok;
            } else {
                txt.textContent = `${nama}: stok ${stok} → akan menjadi ${stok - jml} unit setelah dikeluarkan`;
                prev.classList.add('show', 'ok');
            }
        }
        sel.addEventListener('change', update);
        inp.addEventListener('input', update);
        update();
    })();
</script>
@endpush
@endsection
