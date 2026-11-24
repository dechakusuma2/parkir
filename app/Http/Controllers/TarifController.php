<?php

namespace App\Http\Controllers;

use App\Models\Tarif;
use Illuminate\Http\Request;

/** Menangani pengelolaan tarif parkir untuk kategori kendaraan. */
class TarifController extends Controller
{
    // Menampilkan daftar tarif dengan paginasi.
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tarifs = Tarif::latest()->paginate(10);

        return view('admin.tarifs.index', compact('tarifs'));
    }

    // Menampilkan formulir untuk menambahkan tarif kendaraan.
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.tarifs.create');
    }

    // Memvalidasi dan menyimpan tarif kendaraan baru.
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'jenis_kendaraan' => 'required|string|max:255|unique:tarifs,jenis_kendaraan',
            'tarif_per_jam' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string|max:255',
        ], [
            'jenis_kendaraan.required' => 'Jenis kendaraan wajib diisi',
            'jenis_kendaraan.unique' => 'Jenis kendaraan sudah ada',
            'tarif_per_jam.required' => 'Tarif per jam wajib diisi',
            'tarif_per_jam.numeric' => 'Tarif harus berupa angka',
            'tarif_per_jam.min' => 'Tarif tidak boleh kurang dari 0',
        ]);

        Tarif::create([
            'jenis_kendaraan' => strtolower($request->jenis_kendaraan),
            'tarif_per_jam' => $request->tarif_per_jam,
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->route('admin.tarifs.index')
            ->with('success', 'Data tarif berhasil ditambahkan.');
    }

    // Menampilkan detail tarif yang dipilih.
    /**
     * Display the specified resource.
     */
    public function show(Tarif $tarif)
    {
        return view('admin.tarifs.show', compact('tarif'));
    }

    // Menampilkan formulir perubahan untuk tarif yang dipilih.
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tarif $tarif)
    {
        return view('admin.tarifs.edit', compact('tarif'));
    }

    // Memvalidasi dan menyimpan perubahan tarif kendaraan.
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tarif $tarif)
    {
        $request->validate([
            'jenis_kendaraan' => 'required|string|max:255|unique:tarifs,jenis_kendaraan,'.$tarif->id,
            'tarif_per_jam' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string|max:255',
        ], [
            'jenis_kendaraan.required' => 'Jenis kendaraan wajib diisi',
            'jenis_kendaraan.unique' => 'Jenis kendaraan sudah ada',
            'tarif_per_jam.required' => 'Tarif per jam wajib diisi',
            'tarif_per_jam.numeric' => 'Tarif harus berupa angka',
            'tarif_per_jam.min' => 'Tarif tidak boleh kurang dari 0',
        ]);

        $tarif->update([
            'jenis_kendaraan' => strtolower($request->jenis_kendaraan),
            'tarif_per_jam' => $request->tarif_per_jam,
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->route('admin.tarifs.index')
            ->with('success', 'Data tarif berhasil diperbarui.');
    }

    // Menghapus tarif jika tidak digunakan oleh transaksi.
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tarif $tarif)
    {
        try {
            // Cek apakah tarif sedang digunakan di transaksi
            if ($tarif->transaksi()->exists()) {
                return back()->with('error', 'Tarif tidak dapat dihapus karena sedang digunakan dalam transaksi.');
            }

            $tarif->delete();

            return redirect()->route('admin.tarifs.index')
                ->with('success', 'Data tarif berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus tarif: '.$e->getMessage());
        }
    }
}
