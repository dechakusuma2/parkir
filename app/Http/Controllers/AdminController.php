<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Area;
use App\Models\Kendaraan;
use App\Models\Transaksi;
use App\Models\User;

/** Menyiapkan data ringkasan yang ditampilkan pada dashboard admin. */
class AdminController extends Controller
{
    /** Menghitung ringkasan operasional dan menampilkan dashboard admin. */
    public function dashboard()
    {
        $totalUsers = User::count();
        $totalPetugas = User::where('role', 'petugas')->count();
        $totalAreas = Area::count();
        $totalKendaraan = Kendaraan::count();

        $transaksiHariIni = Transaksi::whereDate('created_at', today())->count();
        $transaksiKemarin = Transaksi::whereDate('created_at', today()->subDay())->count();
        $perubahanKendaraan = $transaksiKemarin > 0
            ? round((($transaksiHariIni - $transaksiKemarin) / $transaksiKemarin) * 100)
            : ($transaksiHariIni > 0 ? 100 : 0);

        $pendapatanHariIni = Transaksi::whereDate('created_at', today())
            ->where('status', 'keluar')
            ->sum('total_bayar');
        $kapasitasTotal = Area::sum('kapasitas');
        $slotTerisi = Area::sum('terisi');
        $slotTersedia = max(0, $kapasitasTotal - $slotTerisi);
        $persentaseTerisi = $kapasitasTotal > 0 ? round(($slotTerisi / $kapasitasTotal) * 100) : 0;

        $transaksiTerbaru = Transaksi::with(['kendaraan', 'area', 'petugas'])
            ->latest()
            ->take(5)
            ->get();

        $areas = Area::orderBy('nama_area')->get();
        $logs = ActivityLog::with('user')->latest()->take(6)->get();
        $chartLabels = collect(range(0, 6))->map(fn ($daysAgo) => now()->subDays(6 - $daysAgo)->format('d M'));
        $chartMasuk = collect(range(6, 0))->map(fn ($daysAgo) => Transaksi::whereDate('created_at', now()->subDays($daysAgo))->count())->values();
        $chartKeluar = collect(range(6, 0))->map(fn ($daysAgo) => Transaksi::whereDate('created_at', now()->subDays($daysAgo))->where('status', 'keluar')->count())->values();

        return view('admin.dashboard', [
            'totalUser' => $totalUsers,
            'totalArea' => $totalAreas,
            'totalKendaraan' => $totalKendaraan,
            'transaksiHariIni' => $transaksiHariIni,
            'perubahanKendaraan' => $perubahanKendaraan,
            'pendapatanHariIni' => $pendapatanHariIni,
            'totalPetugas' => $totalPetugas,
            'kapasitasTotal' => $kapasitasTotal,
            'slotTerisi' => $slotTerisi,
            'slotTersedia' => $slotTersedia,
            'persentaseTerisi' => $persentaseTerisi,
            'transaksiTerbaru' => $transaksiTerbaru,
            'areas' => $areas,
            'logs' => $logs,
            'chartLabels' => $chartLabels,
            'chartMasuk' => $chartMasuk,
            'chartKeluar' => $chartKeluar,
        ]);
    }
}
