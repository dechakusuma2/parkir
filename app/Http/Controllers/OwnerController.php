<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;

class OwnerController extends Controller
{
    public function dashboard()
    {
        return view('owner.dashboard', [
            'total' => Transaksi::sum('total_bayar'),
            'hari_ini' => Transaksi::whereDate('created_at', today())->sum('total_bayar')
        ]);
    }

    public function rekap()
    {
        $data = Transaksi::with(['kendaraan', 'area', 'petugas'])
            ->whereBetween('created_at', [now()->startOfMonth(), now()->endOfDay()])
            ->latest()
            ->get();

        return view('owner.rekap', compact('data'));
    }

    public function rekapFilter(Request $r)
    {
        $r->validate(['dari'=>'required|date','sampai'=>'required|date']);
        $data = Transaksi::with(['kendaraan','petugas'])
            ->whereBetween('created_at', [$r->dari, $r->sampai.' 23:59:59'])
            ->get();
        return view('owner.rekap', compact('data'));
    }
}
