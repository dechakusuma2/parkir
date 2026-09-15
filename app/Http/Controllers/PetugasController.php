<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // <-- TAMBAHKAN INI
use App\Models\{Transaksi, Kendaraan, Area, Tarif};

class PetugasController extends Controller
{
    /**
     * Dashboard petugas
     */
    public function dashboard()
    {
        $transaksiAktif = Transaksi::with(['kendaraan', 'area', 'tarif'])
            ->where('status', 'masuk')
            ->latest()
            ->get();

        $areas = Area::whereColumn('kapasitas', '>', 'terisi')->get();
        $transaksiHariIni = Transaksi::whereDate('created_at', today())->count();
        $pendapatanHariIni = Transaksi::whereDate('created_at', today())
            ->where('status', 'keluar')
            ->sum('total_bayar');
        $kendaraanAktif = $transaksiAktif->count();
        $totalTransaksiSaya = Transaksi::where('petugas_id', Auth::id())->count();

        return view('petugas.dashboard', compact(
            'transaksiAktif',
            'areas',
            'transaksiHariIni',
            'pendapatanHariIni',
            'kendaraanAktif',
            'totalTransaksiSaya'
        ));
    }

    public function transaksi()
    {
        $transaksis = Transaksi::with(['kendaraan', 'area', 'tarif'])
            ->where('petugas_id', Auth::id())
            ->latest()
            ->get();

        return view('petugas.transaksi', compact('transaksis'));
    }

    /**
     * Proses kendaraan masuk
     */
    public function masuk(Request $request)
    {
        // Validasi input
        $request->validate([
            'plat_nomor' => 'required|string|max:20',
            'jenis'      => 'required|string|max:50',
            'warna'      => 'required|string|max:50',
            'pemilik'    => 'required|string|max:255',
            'area_id'    => 'required|exists:areas,id'
        ], [
            'plat_nomor.required' => 'Plat nomor wajib diisi',
            'jenis.required'      => 'Jenis kendaraan wajib diisi',
            'warna.required'      => 'Warna kendaraan wajib diisi',
            'pemilik.required'    => 'Nama pemilik wajib diisi',
            'area_id.required'    => 'Area parkir wajib dipilih',
            'area_id.exists'      => 'Area parkir tidak valid'
        ]);

        // Simpan atau ambil data kendaraan
        $kendaraan = Kendaraan::firstOrCreate(
            ['plat_nomor' => strtoupper($request->plat_nomor)],
            [
                'jenis'   => strtolower($request->jenis),
                'warna'   => $request->warna,
                'pemilik' => $request->pemilik
            ]
        );

        // Cek apakah tarif sudah diatur
        $tarif = Tarif::where('jenis_kendaraan', strtolower($request->jenis))->first();
        if (!$tarif) {
            return back()
                ->withInput()
                ->with('error', 'Tarif untuk jenis kendaraan "' . $request->jenis . '" belum diatur! Hubungi admin.');
        }

        // Ambil area dan cek kapasitas
        $area = Area::findOrFail($request->area_id);

        if ($area->terisi >= $area->kapasitas) {
            return back()
                ->withInput()
                ->with('error', 'Area "' . $area->nama_area . '" sudah penuh (Kapasitas: ' . $area->kapasitas . ')');
        }

        // Tambah jumlah kendaraan di area
        $area->increment('terisi');

        // Buat transaksi baru
        try {
            $transaksi = Transaksi::create([
                'kode_transaksi' => Transaksi::generateKode(),
                'kendaraan_id'   => $kendaraan->id,
                'area_id'        => $area->id,
                'tarif_id'       => $tarif->id,
                'petugas_id'     => Auth::id(), // <-- GUNAKAN Auth::id() LEBIH AMAN
                'jam_masuk'      => now(),
                'status'         => 'masuk'
            ]);

            return redirect()
                ->route('petugas.struk', $transaksi->id)
                ->with('success', 'Kendaraan berhasil dicatat masuk.');

        } catch (\Exception $e) {
            // Rollback: kurangi lagi kapasitas jika gagal
            $area->decrement('terisi');

            return back()
                ->withInput()
                ->with('error', 'Gagal membuat transaksi: ' . $e->getMessage());
        }
    }

    /**
     * Proses kendaraan keluar
     */
    public function keluar($id)
    {
        try {
            $transaksi = Transaksi::with(['area', 'tarif'])->findOrFail($id);

            // Cek apakah sudah keluar
            if ($transaksi->status === 'keluar') {
                return back()->with('error', 'Transaksi ini sudah selesai.');
            }

            // Proses keluar menggunakan method dari Model
            $transaksi->prosesKeluar('tunai');

            return redirect()
                ->route('petugas.struk', $transaksi->id)
                ->with('success', 'Kendaraan berhasil keluar. Total bayar: ' . $transaksi->total_format);

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memproses keluar: ' . $e->getMessage());
        }
    }

    /**
     * Tampilkan struk
     */
    public function struk($id)
    {
        $t = Transaksi::with(['kendaraan', 'area', 'tarif', 'petugas'])->findOrFail($id);

        return view('petugas.struk', compact('t'));
    }
}
