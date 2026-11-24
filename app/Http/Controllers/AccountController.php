<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

/** Menangani tampilan dan pembaruan pengaturan akun admin. */
class AccountController extends Controller
{
    /** Menampilkan formulir pengaturan profil admin. */
    public function edit(Request $request)
    {
        return view('admin.account.edit', ['user' => $request->user()]);
    }

    /** Memvalidasi dan menyimpan perubahan nama atau kata sandi admin. */
    public function update(Request $request)
    {
        $user = $request->user();
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'current_password' => ['nullable', 'required_with:password', 'current_password'],
            'password' => ['nullable', 'confirmed', 'min:6'],
        ], [
            'current_password.current_password' => 'Password lama tidak sesuai.',
            'password.confirmed' => 'Konfirmasi password baru tidak sesuai.',
        ]);

        $user->name = $data['name'];
        if (! empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }
        $user->save();

        ActivityLog::log('Update Profil', 'Mengubah pengaturan akun admin');

        return back()->with('success', 'Pengaturan akun berhasil diperbarui.');
    }
}
