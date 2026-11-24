<?php

namespace App\Http\Controllers;

use App\Models\Kendaraan;
use Illuminate\Http\Request;

/** Menangani pengelolaan dan pencarian data kendaraan oleh admin. */
class KendaraanController extends Controller
{
    // Menampilkan daftar kendaraan dengan pencarian dan paginasi.
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Kendaraan::query();

        // Fitur pencarian sederhana
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('plat_nomor', 'like', "%{$search}%")
                    ->orWhere('pemilik', 'like', "%{$search}%")
                    ->orWhere('jenis', 'like', "%{$search}%");
            });
        }

        $kendaraans = $query->latest()->paginate(15);

        return view('admin.kendaraans.index', compact('kendaraans'));
    }

    // Menampilkan formulir untuk mendaftarkan kendaraan baru.
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.kendaraans.create');
    }

    // Memvalidasi dan menyimpan data kendaraan baru.
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'plat_nomor' => 'required|string|max:20|unique:kendaraans,plat_nomor',
            'jenis' => 'required|string|max:255',
            'warna' => 'required|string|max:255',
            'pemilik' => 'required|string|max:255',
            'no_hp' => 'nullable|string|max:20',
        ], [
            'plat_nomor.required' => 'Plat nomor wajib diisi',
            'plat_nomor.unique' => 'Plat nomor sudah terdaftar',
            'jenis.required' => 'Jenis kendaraan wajib diisi',
            'warna.required' => 'Warna kendaraan wajib diisi',
            'pemilik.required' => 'Nama pemilik wajib diisi',
        ]);

        Kendaraan::create([
            'plat_nomor' => strtoupper($request->plat_nomor),
            'jenis' => strtolower($request->jenis),
            'warna' => $request->warna,
            'pemilik' => $request->pemilik,
            'no_hp' => $request->no_hp,
        ]);

        return redirect()->route('admin.kendaraans.index')
            ->with('success', 'Data kendaraan berhasil ditambahkan.');
    }

    // Menampilkan detail kendaraan yang dipilih.
    /**
     * Display the specified resource.
     */
    public function show(Kendaraan $kendaraan)
    {
        return view('admin.kendaraans.show', compact('kendaraan'));
    }

    // Menampilkan formulir perubahan untuk kendaraan yang dipilih.
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Kendaraan $kendaraan)
    {
        return view('admin.kendaraans.edit', compact('kendaraan'));
    }

    // Memvalidasi dan menyimpan perubahan data kendaraan.
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Kendaraan $kendaraan)
    {
        $request->validate([
            'plat_nomor' => 'required|string|max:20|unique:kendaraans,plat_nomor,'.$kendaraan->id,
            'jenis' => 'required|string|max:255',
            'warna' => 'required|string|max:255',
            'pemilik' => 'required|string|max:255',
            'no_hp' => 'nullable|string|max:20',
        ], [
            'plat_nomor.required' => 'Plat nomor wajib diisi',
            'plat_nomor.unique' => 'Plat nomor sudah terdaftar',
            'jenis.required' => 'Jenis kendaraan wajib diisi',
            'warna.required' => 'Warna kendaraan wajib diisi',
            'pemilik.required' => 'Nama pemilik wajib diisi',
        ]);

        $kendaraan->update([
            'plat_nomor' => strtoupper($request->plat_nomor),
            'jenis' => strtolower($request->jenis),
            'warna' => $request->warna,
            'pemilik' => $request->pemilik,
            'no_hp' => $request->no_hp,
        ]);

        return redirect()->route('admin.kendaraans.index')
            ->with('success', 'Data kendaraan berhasil diperbarui.');
    }

    // Menghapus kendaraan yang tidak sedang menjalani transaksi parkir.
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Kendaraan $kendaraan)
    {
        try {
            // Cek apakah kendaraan sedang parkir
            if ($kendaraan->transaksis()->where('status', 'masuk')->exists()) {
                return back()->with('error', 'Kendaraan tidak dapat dihapus karena sedang dalam proses parkir.');
            }

            $kendaraan->delete();

            return redirect()->route('admin.kendaraans.index')
                ->with('success', 'Data kendaraan berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus kendaraan: '.$e->getMessage());
        }
    }
}
