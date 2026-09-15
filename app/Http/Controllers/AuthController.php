<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\Models\ActivityLog;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect($this->getDashboardRoute());
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|min:6',
        ], [
            'email.required'    => 'Email wajib diisi',
            'email.email'       => 'Format email tidak valid',
            'password.required' => 'Password wajib diisi',
            'password.min'      => 'Password minimal 6 karakter',
        ]);

        if (!Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => ['Email atau password salah'],
            ]);
        }

        $user = Auth::user();

        if (isset($user->status) && $user->status !== 'active') {
            Auth::logout();
            return back()->withInput($request->only('email'))->with('error', 'Akun Anda tidak aktif.');
        }

        ActivityLog::log('Login', 'User login ke sistem');

        return redirect()->intended($this->getDashboardRoute())->with('success', 'Selamat datang, ' . $user->name . '!');
    }

    public function logout(Request $request)
    {
        if (Auth::check()) {
            ActivityLog::log('Logout', 'User logout dari sistem');
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Anda telah berhasil logout');
    }

    private function getDashboardRoute()
    {
        if (!Auth::check()) return '/login';

        $routes = [
            'owner'   => '/owner/dashboard',
            'admin'   => '/admin/dashboard',
            'petugas' => '/petugas/dashboard',
        ];

        return $routes[Auth::user()->role] ?? '/login';
    }
}
