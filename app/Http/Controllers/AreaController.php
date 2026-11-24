<?php

namespace App\Http\Controllers;

use App\Models\Area;
use Illuminate\Http\Request;

/** Menangani operasi daftar, pembuatan, perubahan, dan penghapusan area. */
class AreaController extends Controller
{
    /** Menampilkan daftar seluruh area parkir. */
    public function index()
    {
        $areas = Area::all();

        return view('admin.areas.index', compact('areas'));
    }

    /** Menampilkan formulir untuk menambahkan area parkir. */
    public function create()
    {
        return view('admin.areas.create');
    }

    /** Memvalidasi dan menyimpan area parkir baru. */
    public function store(Request $request)
    {
        $request->validate([
            'nama_area' => 'required|string|max:255',
            'kapasitas' => 'required|integer|min:1',
            'keterangan' => 'nullable|string|max:255',
        ]);

        Area::create($request->only('nama_area', 'kapasitas', 'keterangan'));

        return redirect()->route('admin.areas.index')->with('success', 'Area berhasil ditambahkan');
    }

    /** Menampilkan formulir perubahan untuk area yang dipilih. */
    public function edit(Area $area)
    {
        return view('admin.areas.edit', compact('area'));
    }

    /** Memvalidasi dan menyimpan perubahan data area parkir. */
    public function update(Request $request, Area $area)
    {
        $request->validate([
            'nama_area' => 'required|string|max:255',
            'kapasitas' => 'required|integer|min:1',
            'keterangan' => 'nullable|string|max:255',
        ]);

        if ($request->kapasitas < $area->terisi) {
            return back()->with('error', 'Kapasitas tidak boleh kurang dari kendaraan yang sedang parkir ('.$area->terisi.')');
        }

        $area->update($request->only('nama_area', 'kapasitas', 'keterangan'));

        return redirect()->route('admin.areas.index')->with('success', 'Area berhasil diupdate');
    }

    /** Menghapus area jika masih tidak memiliki kendaraan. */
    public function destroy(Area $area)
    {
        if ($area->terisi > 0) {
            return back()->with('error', 'Tidak dapat menghapus area yang masih ada kendaraannya!');
        }

        $area->delete();

        return redirect()->route('admin.areas.index')->with('success', 'Area berhasil dihapus');
    }
}
