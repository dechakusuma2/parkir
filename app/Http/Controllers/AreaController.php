<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Area;
use Illuminate\Http\Request;

class AreaController extends Controller
{
    public function index()
    {
        $areas = Area::all();
        return view('admin.areas.index', compact('areas'));
    }

    public function create()
    {
        return view('admin.areas.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_area'  => 'required|string|max:255',
            'kapasitas'  => 'required|integer|min:1',
            'keterangan' => 'nullable|string|max:255',
        ]);

        Area::create($request->only('nama_area', 'kapasitas', 'keterangan'));

        return redirect()->route('admin.areas.index')->with('success', 'Area berhasil ditambahkan');
    }

    public function edit(Area $area)
    {
        return view('admin.areas.edit', compact('area'));
    }

    public function update(Request $request, Area $area)
    {
        $request->validate([
            'nama_area'  => 'required|string|max:255',
            'kapasitas'  => 'required|integer|min:1',
            'keterangan' => 'nullable|string|max:255',
        ]);

        if ($request->kapasitas < $area->terisi) {
            return back()->with('error', 'Kapasitas tidak boleh kurang dari kendaraan yang sedang parkir (' . $area->terisi . ')');
        }

        $area->update($request->only('nama_area', 'kapasitas', 'keterangan'));

        return redirect()->route('admin.areas.index')->with('success', 'Area berhasil diupdate');
    }

    public function destroy(Area $area)
    {
        if ($area->terisi > 0) {
            return back()->with('error', 'Tidak dapat menghapus area yang masih ada kendaraannya!');
        }

        $area->delete();

        return redirect()->route('admin.areas.index')->with('success', 'Area berhasil dihapus');
    }
}
