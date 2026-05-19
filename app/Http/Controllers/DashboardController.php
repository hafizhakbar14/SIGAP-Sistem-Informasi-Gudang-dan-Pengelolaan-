<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Transaksi;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // ---- Stat utama ----
        $totalBarang = Barang::count();
        $totalStok = (int) Barang::sum('stok');
        $totalMasuk = (int) Transaksi::where('jenis', 'masuk')->sum('jumlah');
        $totalKeluar = (int) Transaksi::where('jenis', 'keluar')->sum('jumlah');

        // ---- Data 7 hari terakhir (untuk chart & sparkline) ----
        $dayNames = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];
        $weeklyData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $weeklyData[] = [
                'day' => $dayNames[$date->dayOfWeek],
                'in'  => (int) Transaksi::where('jenis', 'masuk')->whereDate('tanggal', $date)->sum('jumlah'),
                'out' => (int) Transaksi::where('jenis', 'keluar')->whereDate('tanggal', $date)->sum('jumlah'),
            ];
        }

        $sparkMasuk  = array_column($weeklyData, 'in');
        $sparkKeluar = array_column($weeklyData, 'out');

        // Sparkline stok = stok berjalan mundur dari hari ini
        $running = $totalStok;
        $stokSeries = [$running];
        foreach (array_reverse($weeklyData) as $d) {
            $running -= ($d['in'] - $d['out']);
            array_unshift($stokSeries, max(0, $running));
        }
        $sparkStok = array_slice($stokSeries, 0, 7);

        // Sparkline jumlah barang (count per hari mundur)
        $sparkBarang = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i)->endOfDay();
            $sparkBarang[] = Barang::where('created_at', '<=', $date)->count();
        }

        // ---- Delta minggu ini vs minggu lalu ----
        $startWeek = Carbon::today()->subDays(6);
        $startLast = Carbon::today()->subDays(13);
        $endLast   = Carbon::today()->subDays(7);

        $masukWeek  = (int) Transaksi::where('jenis', 'masuk')->whereBetween('tanggal', [$startWeek, Carbon::today()])->sum('jumlah');
        $masukPrev  = (int) Transaksi::where('jenis', 'masuk')->whereBetween('tanggal', [$startLast, $endLast])->sum('jumlah');
        $keluarWeek = (int) Transaksi::where('jenis', 'keluar')->whereBetween('tanggal', [$startWeek, Carbon::today()])->sum('jumlah');
        $keluarPrev = (int) Transaksi::where('jenis', 'keluar')->whereBetween('tanggal', [$startLast, $endLast])->sum('jumlah');

        $deltaMasuk  = $this->pctDelta($masukWeek, $masukPrev);
        $deltaKeluar = $this->pctDelta($keluarWeek, $keluarPrev);
        $deltaStok   = $this->pctDelta($totalStok, max(1, $totalStok - ($masukWeek - $keluarWeek)));
        $deltaBarang = $totalBarang - (end($sparkBarang) ? reset($sparkBarang) : 0);

        // ---- Stok per barang (top 6) ----
        $stockBars = Barang::orderBy('stok', 'desc')->take(6)->get();
        $maxCap = 60; // skala visual

        // ---- Ringkasan bulan ini ----
        $startBulan = Carbon::today()->startOfMonth();
        $masukBulan  = (int) Transaksi::where('jenis', 'masuk')->where('tanggal', '>=', $startBulan)->sum('jumlah');
        $keluarBulan = (int) Transaksi::where('jenis', 'keluar')->where('tanggal', '>=', $startBulan)->sum('jumlah');
        $netBulan    = $masukBulan - $keluarBulan;
        $maxBulan    = max($masukBulan, $keluarBulan, 1);

        // ---- Aktivitas terbaru ----
        $timeline = Transaksi::with('barang')->latest('id')->take(5)->get();

        // ---- Stok rendah ----
        $barangStokRendah = Barang::where('stok', '<=', 5)->orderBy('stok', 'asc')->take(5)->get();

        return view('dashboard', compact(
            'totalBarang', 'totalStok', 'totalMasuk', 'totalKeluar',
            'weeklyData',
            'sparkBarang', 'sparkStok', 'sparkMasuk', 'sparkKeluar',
            'deltaBarang', 'deltaStok', 'deltaMasuk', 'deltaKeluar',
            'stockBars', 'maxCap',
            'masukBulan', 'keluarBulan', 'netBulan', 'maxBulan',
            'timeline', 'barangStokRendah'
        ));
    }

    private function pctDelta(int $now, int $prev): array
    {
        if ($prev === 0) {
            return ['text' => $now > 0 ? '+' . $now . ' baru' : '0%', 'up' => $now >= 0];
        }
        $pct = round((($now - $prev) / max(1, $prev)) * 100, 1);
        return ['text' => ($pct >= 0 ? '+' : '') . $pct . '%', 'up' => $pct >= 0];
    }
}
