<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Kendaraan;
use App\Models\Tarif;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/** Menangani transaksi masuk, keluar, dan tampilan operasional petugas. */
class PetugasController extends Controller
{
    // Menampilkan transaksi, kapasitas area, ringkasan harian, dan pratinjau keluar.
    /**
     * Dashboard petugas
     */
    public function dashboard(Request $request)
    {
        $query = Transaksi::with(['kendaraan', 'petugas', 'tarif'])->whereDate('created_at', today());
        if ($request->filled('search')) {
            $search = $request->string('search')->trim();
            $query->where(function ($builder) use ($search) {
                $builder->where('kode_transaksi', 'like', "%{$search}%")
                    ->orWhereHas('kendaraan', fn ($vehicle) => $vehicle->where('plat_nomor', 'like', "%{$search}%"));
            });
        }
        if ($request->filled('status') && in_array($request->status, ['masuk', 'keluar'], true)) {
            $query->where('status', $request->status);
        }
        $transaksis = $query->latest('jam_masuk')->get();
        $transaksiAktif = Transaksi::with(['kendaraan', 'tarif'])->aktif()->latest('jam_masuk')->get();
        $areas = Area::whereColumn('kapasitas', '>', 'terisi')->get();
        $hariIni = Transaksi::whereDate('created_at', today());
        $transaksiMasukHariIni = (clone $hariIni)->count();
        $transaksiKeluarHariIni = (clone $hariIni)->where('status', 'keluar')->count();
        $pendapatanHariIni = (clone $hariIni)->where('status', 'keluar')->sum('total_bayar');

        $exitPreview = $request->session()->pull('exit_preview');
        if ($exitPreview instanceof Transaksi) {
            $exitPreview = $exitPreview->loadMissing(['kendaraan', 'tarif']);
        } elseif (is_array($exitPreview) && isset($exitPreview['id'])) {
            $exitPreview = Transaksi::with(['kendaraan', 'tarif'])->find($exitPreview['id']);
        } elseif (is_numeric($exitPreview) || is_string($exitPreview)) {
            $exitPreview = Transaksi::with(['kendaraan', 'tarif'])->find($exitPreview);
        } else {
            $exitPreview = null;
        }

        return view('petugas.dashboard', compact('transaksis', 'transaksiAktif', 'areas', 'transaksiMasukHariIni', 'transaksiKeluarHariIni', 'pendapatanHariIni', 'exitPreview'));
    }

    /** Menampilkan transaksi yang dibuat oleh petugas yang sedang masuk. */
    public function transaksi()
    {
        $transaksis = Transaksi::with(['kendaraan', 'area', 'tarif'])
            ->where('petugas_id', Auth::id())
            ->latest()
            ->get();

        return view('petugas.transaksi', compact('transaksis'));
    }

    // Memvalidasi kendaraan masuk dan membuat transaksi parkir baru.
    /**
     * Proses kendaraan masuk
     */
    public function kendaraanMasuk(Request $request)
    {
        // Validasi input
        $request->validate([
            'plat_nomor' => 'required|string|max:20',
            'jenis' => 'required|in:motor,mobil,truk',
            'warna' => 'required|string|max:50',
        ], [
            'plat_nomor.required' => 'Plat nomor wajib diisi',
            'jenis.required' => 'Jenis kendaraan wajib dipilih',
            'jenis.in' => 'Jenis kendaraan tidak valid',
            'warna.required' => 'Warna kendaraan wajib diisi',
        ]);

        // Simpan atau ambil data kendaraan
        $plat = Str::upper(trim($request->plat_nomor));
        if (Transaksi::whereHas('kendaraan', fn ($query) => $query->where('plat_nomor', $plat))->aktif()->exists()) {
            return back()->withInput()->with('error', 'Kendaraan tersebut masih tercatat sedang parkir.');
        }
        $kendaraan = Kendaraan::updateOrCreate(
            ['plat_nomor' => $plat],
            ['jenis' => $request->jenis, 'warna' => $request->string('warna')->trim()]
        );

        // Cek apakah tarif sudah diatur
        $tarif = Tarif::where('jenis_kendaraan', $request->jenis)->first();
        if (! $tarif) {
            return back()
                ->withInput()
                ->with('error', 'Tarif untuk jenis kendaraan "'.$request->jenis.'" belum diatur! Hubungi admin.');
        }

        $area = Area::getAreaForJenis($request->jenis);
        if (! $area) {
            return back()->withInput()->with('error', 'Kapasitas parkir untuk jenis kendaraan ini sedang penuh.');
        }
        $transaksi = DB::transaction(function () use ($kendaraan, $tarif, $area) {
            $area->increment('terisi');

            return Transaksi::create([
                'kode_transaksi' => Transaksi::generateKode(),
                'kendaraan_id' => $kendaraan->id,
                'area_id' => $area->id,
                'tarif_id' => $tarif->id,
                'petugas_id' => Auth::id(),
                'jam_masuk' => now(),
                'status' => 'masuk',
            ]);
        });

        return redirect()
            ->route('petugas.struk', $transaksi->id)
            ->with('success', 'Kendaraan masuk berhasil dicatat. Silakan cetak tiket parkir.');
    }

    // Mencari transaksi aktif atau menyelesaikan pembayaran kendaraan keluar.
    /**
     * Proses kendaraan keluar
     */
    public function kendaraanKeluar(Request $request)
    {
        if ($request->isMethod('get')) {
            $request->validate(['q' => 'required|string|max:50'], ['q.required' => 'Masukkan plat nomor atau nomor tiket.']);
            $term = Str::upper(trim($request->q));
            $transaksi = Transaksi::with(['kendaraan', 'tarif'])->aktif()->where(function ($builder) use ($term) {
                $builder->where('kode_transaksi', $term)->orWhereHas('kendaraan', fn ($vehicle) => $vehicle->where('plat_nomor', $term));
            })->first();
            if (! $transaksi) {
                return back()->withInput()->with('error', 'Kendaraan aktif tidak ditemukan.');
            }
            $request->session()->put('exit_preview', $transaksi->id);

            return redirect()->route('petugas.dashboard', ['tab' => 'keluar']);
        }
        $request->validate(['transaksi_id' => 'required|exists:transaksis,id']);
        $transaksi = Transaksi::with(['area', 'tarif', 'kendaraan'])->findOrFail($request->transaksi_id);
        if ($transaksi->status !== 'masuk') {
            return back()->with('error', 'Transaksi ini sudah selesai.');
        }
        $transaksi->prosesKeluar($request->input('metode_bayar', 'tunai'));

        return redirect()->route('petugas.struk', $transaksi->id)->with('success', 'Pembayaran berhasil diproses.');
    }

    // Memuat data transaksi dan menampilkan struk parkir.
    /**
     * Tampilkan struk
     */
    public function cetakStruk($id)
    {
        $t = Transaksi::with(['kendaraan', 'area', 'tarif', 'petugas'])->findOrFail($id);

        return view('petugas.struk', compact('t'));
    }

    /** Menyediakan alias rute lama untuk proses kendaraan masuk. */
    public function masuk(Request $request)
    {
        return $this->kendaraanMasuk($request);
    }

    /** Mengarahkan alias rute keluar ke tab kendaraan keluar. */
    public function keluar($id)
    {
        return redirect()->route('petugas.dashboard', ['tab' => 'keluar']);
    }

    /** Menyediakan alias rute lama untuk menampilkan struk. */
    public function struk($id)
    {
        return $this->cetakStruk($id);
    }
}
