<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\{User, Area, Kendaraan, Transaksi};
use Carbon\Carbon;

class AdminController extends Controller
{
    public function dashboard()
    {
        // Statistik Umum
        $totalUsers = User::count();
        $totalPetugas = User::where('role', 'petugas')->count();
        $totalAreas = Area::count();
        $totalKendaraan = Kendaraan::count();

        // Statistik Transaksi Hari Ini
        $transaksiHariIni = Transaksi::whereDate('created_at', today())->count();

        // Pendapatan Hari Ini (hanya yang sudah selesai/keluar)
        $pendapatanHariIni = Transaksi::whereDate('created_at', today())
            ->where('status', 'keluar')
            ->sum('total_bayar');

        // Transaksi Terbaru (5 terakhir)
        $transaksiTerbaru = Transaksi::with(['kendaraan', 'area', 'petugas'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalPetugas',
            'totalAreas',
            'totalKendaraan',
            'transaksiHariIni',
            'pendapatanHariIni',
            'transaksiTerbaru'
        ));
    }
}
