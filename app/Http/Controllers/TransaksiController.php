<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransaksiController extends Controller
{
    public function masukForm()
    {
        $barang = Barang::orderBy('nama_barang')->get();
        return view('transaksi.masuk', compact('barang'));
    }

    public function masukStore(Request $request)
    {
        $validated = $request->validate([
            'barang_id' => ['required', 'exists:barang,id'],
            'jumlah' => ['required', 'integer', 'min:1'],
            'tanggal' => ['required', 'date'],
        ]);

        DB::transaction(function () use ($validated) {
            Transaksi::create([
                'barang_id' => $validated['barang_id'],
                'jenis' => 'masuk',
                'jumlah' => $validated['jumlah'],
                'tanggal' => $validated['tanggal'],
            ]);

            $barang = Barang::findOrFail($validated['barang_id']);
            $barang->increment('stok', $validated['jumlah']);
        });

        return redirect()->route('transaksi.riwayat')->with('success', 'Barang masuk berhasil dicatat.');
    }

    public function keluarForm()
    {
        $barang = Barang::where('stok', '>', 0)->orderBy('nama_barang')->get();
        return view('transaksi.keluar', compact('barang'));
    }

    public function keluarStore(Request $request)
    {
        $validated = $request->validate([
            'barang_id' => ['required', 'exists:barang,id'],
            'jumlah' => ['required', 'integer', 'min:1'],
            'tanggal' => ['required', 'date'],
        ]);

        $barang = Barang::findOrFail($validated['barang_id']);

        if ($validated['jumlah'] > $barang->stok) {
            return back()
                ->withInput()
                ->withErrors(['jumlah' => "Jumlah melebihi stok tersedia ({$barang->stok})."]);
        }

        DB::transaction(function () use ($validated, $barang) {
            Transaksi::create([
                'barang_id' => $validated['barang_id'],
                'jenis' => 'keluar',
                'jumlah' => $validated['jumlah'],
                'tanggal' => $validated['tanggal'],
            ]);

            $barang->decrement('stok', $validated['jumlah']);
        });

        return redirect()->route('transaksi.riwayat')->with('success', 'Barang keluar berhasil dicatat.');
    }

    public function riwayat(Request $request)
    {
        $query = $this->buildRiwayatQuery($request);

        // Ringkasan untuk filter aktif
        $sumAll = (clone $query)->selectRaw("
            SUM(CASE WHEN jenis = 'masuk'  THEN jumlah ELSE 0 END) AS total_masuk,
            SUM(CASE WHEN jenis = 'keluar' THEN jumlah ELSE 0 END) AS total_keluar,
            COUNT(*) AS total_count
        ")->first();

        $totalMasuk  = (int) ($sumAll->total_masuk  ?? 0);
        $totalKeluar = (int) ($sumAll->total_keluar ?? 0);
        $totalCount  = (int) ($sumAll->total_count  ?? 0);
        $totalNet    = $totalMasuk - $totalKeluar;

        $transaksi = $query->latest('tanggal')->latest('id')->paginate(15)->withQueryString();

        return view('transaksi.riwayat', compact(
            'transaksi', 'totalMasuk', 'totalKeluar', 'totalNet', 'totalCount'
        ));
    }

    public function riwayatExport(Request $request)
    {
        $rows = $this->buildRiwayatQuery($request)
            ->latest('tanggal')->latest('id')->get();

        $filename = 'riwayat-transaksi-' . date('Ymd-His') . '.csv';
        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        return response()->stream(function () use ($rows) {
            $out = fopen('php://output', 'w');
            // BOM agar Excel benar membaca UTF-8
            fwrite($out, "\xEF\xBB\xBF");
            fputcsv($out, ['No', 'Tanggal', 'Nama Barang', 'Jenis Barang', 'Jenis Transaksi', 'Jumlah']);
            foreach ($rows as $i => $r) {
                fputcsv($out, [
                    $i + 1,
                    optional($r->tanggal)->format('Y-m-d'),
                    $r->barang->nama_barang ?? '(barang dihapus)',
                    $r->barang->jenis ?? '-',
                    ucfirst($r->jenis),
                    $r->jumlah,
                ]);
            }
            fclose($out);
        }, 200, $headers);
    }

    private function buildRiwayatQuery(Request $request)
    {
        $query = Transaksi::with('barang');

        if ($request->filled('jenis') && in_array($request->jenis, ['masuk', 'keluar'])) {
            $query->where('jenis', $request->jenis);
        }

        if ($request->filled('search')) {
            $cari = $request->search;
            $query->whereHas('barang', function ($q) use ($cari) {
                $q->where('nama_barang', 'like', "%{$cari}%");
            });
        }

        if ($request->filled('dari')) {
            $query->whereDate('tanggal', '>=', $request->dari);
        }

        if ($request->filled('sampai')) {
            $query->whereDate('tanggal', '<=', $request->sampai);
        }

        return $query;
    }
}
