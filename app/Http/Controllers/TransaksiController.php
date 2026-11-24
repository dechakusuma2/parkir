<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Kendaraan;
use App\Models\Tarif;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/** Menangani proses transaksi kendaraan masuk dan keluar untuk petugas. */
class PetugasController extends Controller
{
    /** Menampilkan transaksi aktif dan area yang masih memiliki kapasitas. */
    public function dashboard()
    {
        $transaksi = Transaksi::with(['kendaraan', 'area', 'tarif'])
            ->where('status', 'masuk')
            ->latest()
            ->get();

        // Hanya tampilkan area yang masih tersedia
        $areas = Area::whereColumn('kapasitas', '>', 'terisi')->get();

        return view('petugas.dashboard', compact('transaksi', 'areas'));
    }

    /** Memvalidasi data kendaraan dan mencatat transaksi masuk. */
    public function masuk(Request $request)
    {
        $request->validate([
            'plat_nomor' => 'required|string|max:20',
            'jenis' => 'required|string|max:50',
            'warna' => 'required|string|max:50',
            'pemilik' => 'required|string|max:255',
            'area_id' => 'required|exists:areas,id',
        ], [
            'plat_nomor.required' => 'Plat nomor wajib diisi',
            'jenis.required' => 'Jenis kendaraan wajib diisi',
            'warna.required' => 'Warna kendaraan wajib diisi',
            'pemilik.required' => 'Nama pemilik wajib diisi',
            'area_id.required' => 'Area parkir wajib dipilih',
            'area_id.exists' => 'Area parkir tidak valid',
        ]);

        // Simpan atau ambil data kendaraan
        $kendaraan = Kendaraan::firstOrCreate(
            ['plat_nomor' => strtoupper($request->plat_nomor)],
            [
                'jenis' => strtolower($request->jenis),
                'warna' => $request->warna,
                'pemilik' => $request->pemilik,
            ]
        );

        // Cek apakah tarif sudah diatur
        $tarif = Tarif::where('jenis_kendaraan', strtolower($request->jenis))->first();
        if (! $tarif) {
            return back()
                ->withInput()
                ->with('error', 'Tarif untuk jenis kendaraan "'.$request->jenis.'" belum diatur!');
        }

        $area = Area::getAreaForJenis($request->jenis ?? strtolower($kendaraan->jenis));
        if (! $area || $area->id != $request->area_id) {
            $area = Area::findOrFail($request->area_id);
        }

        // Cek kapasitas area
        if ($area->terisi >= $area->kapasitas) {
            return back()
                ->withInput()
                ->with('error', 'Area parkir "'.$area->nama_area.'" sudah penuh!');
        }

        // Tambah jumlah terisi
        $area->increment('terisi');

        // Buat transaksi baru
        try {
            $transaksi = Transaksi::create([
                'kode_transaksi' => Transaksi::generateKode(),
                'kendaraan_id' => $kendaraan->id,
                'area_id' => $area->id,
                'tarif_id' => $tarif->id,
                'petugas_id' => Auth::id(),
                'jam_masuk' => now(),
                'status' => 'masuk',
            ]);

            return redirect()->route('petugas.struk', $transaksi->id)
                ->with('success', 'Kendaraan berhasil dicatat masuk.');

        } catch (\Exception $e) {
            // Rollback: kurangi lagi kapasitas jika gagal
            $area->decrement('terisi');

            return back()
                ->withInput()
                ->with('error', 'Gagal membuat transaksi: '.$e->getMessage());
        }
    }

    /** Memproses kendaraan keluar dan memperbarui status transaksi. */
    public function keluar($id)
    {
        try {
            $transaksi = Transaksi::with(['area', 'tarif'])->findOrFail($id);

            if ($transaksi->status === 'keluar') {
                return back()->with('error', 'Transaksi ini sudah selesai.');
            }

            // Gunakan method prosesKeluar() dari Model Transaksi
            $transaksi->prosesKeluar('tunai');

            return redirect()->route('petugas.struk', $transaksi->id)
                ->with('success', 'Kendaraan berhasil keluar.');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memproses keluar: '.$e->getMessage());
        }
    }

    /** Memuat relasi transaksi untuk menampilkan tiket atau struk parkir. */
    public function struk($id)
    {
        $t = Transaksi::with(['kendaraan', 'area', 'tarif', 'petugas'])->findOrFail($id);

        return view('petugas.struk', compact('t'));
    }
}
