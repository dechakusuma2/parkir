<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Transaksi;
use Illuminate\Http\Request;

/** Menyediakan data dashboard dan rekap transaksi untuk pemilik. */
class OwnerController extends Controller
{
    /** Menghitung metrik pendapatan dan aktivitas untuk dashboard pemilik. */
    public function dashboard()
    {
        $totalPendapatan = Transaksi::sum('total_bayar');
        $pendapatanHariIni = Transaksi::whereDate('created_at', today())->sum('total_bayar');
        $transaksiHariIni = Transaksi::whereDate('created_at', today())->count();
        $kendaraanAktif = Transaksi::where('status', 'masuk')->count();
        $areas = Area::orderBy('nama_area')->get();

        $labels = [];
        $chartData = [];
        $tanggal = now()->subDays(6);

        for ($i = 0; $i < 7; $i++) {
            $day = $tanggal->copy()->addDays($i);
            $labels[] = $day->translatedFormat('D');
            $chartData[] = (float) Transaksi::whereDate('created_at', $day)->sum('total_bayar');
        }

        return view('owner.dashboard', compact(
            'totalPendapatan',
            'pendapatanHariIni',
            'transaksiHariIni',
            'kendaraanAktif',
            'areas',
            'labels',
            'chartData'
        ));
    }

    /** Menampilkan rekap transaksi untuk bulan berjalan. */
    public function rekap()
    {
        $data = Transaksi::with(['kendaraan', 'area', 'petugas'])
            ->whereBetween('created_at', [now()->startOfMonth(), now()->endOfDay()])
            ->latest()
            ->get();

        return view('owner.rekap', compact('data'));
    }

    /** Memvalidasi rentang tanggal dan menampilkan rekap transaksi sesuai filter. */
    public function rekapFilter(Request $r)
    {
        $r->validate(['dari' => 'required|date', 'sampai' => 'required|date']);

        $data = Transaksi::with(['kendaraan', 'area', 'petugas'])
            ->whereBetween('created_at', [$r->dari, $r->sampai.' 23:59:59'])
            ->latest('created_at')
            ->get();

        return view('owner.rekap', compact('data'));
    }
}
