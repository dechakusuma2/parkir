<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash; // <-- TAMBAHKAN INI

/** Menangani pengelolaan akun dan peran pengguna oleh admin. */
class UserController extends Controller
{
    /** Menampilkan daftar pengguna dengan paginasi. */
    public function index()
    {
        $users = User::latest()->paginate(10);

        return view('admin.users.index', compact('users'));
    }

    /** Menampilkan formulir untuk membuat pengguna baru. */
    public function create()
    {
        return view('admin.users.create');
    }

    /** Memvalidasi dan menyimpan pengguna baru beserta kata sandi terenkripsi. */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|in:admin,petugas,owner',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'status' => 'active',
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'Data pengguna berhasil ditambahkan.');
    }

    /** Menampilkan formulir perubahan untuk pengguna yang dipilih. */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /** Memvalidasi dan menyimpan perubahan data pengguna. */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
            'password' => 'nullable|string|min:6|confirmed',
            'role' => 'required|in:admin,petugas,owner',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ];

        // Hanya update password jika diisi
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.users.index')
            ->with('success', 'Data pengguna berhasil diperbarui.');
    }

    /** Mencegah penghapusan akun sendiri lalu menghapus pengguna terpilih. */
    public function destroy(User $user)
    {
        // Cegah penghapusan diri sendiri
        if ($user->id === Auth::id()) { // <-- PERBAIKAN: Gunakan Auth::id()
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'Data pengguna berhasil dihapus.');
    }
}
