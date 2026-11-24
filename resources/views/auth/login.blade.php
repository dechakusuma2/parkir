{{-- Menampilkan halaman login beserta validasi, opsi ingat akun, dan tautan kembali. --}}
@extends('layouts.app')
@section('title', 'Login')

@section('content')
<div style="min-height:100vh;background:linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);display:flex;align-items:center;padding:20px 0;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card card-custom p-5">
                    <div class="text-center mb-4">
                        <div class="mx-auto mb-3 d-flex align-items-center justify-content-center rounded-circle" style="width:80px;height:80px;background:var(--primary);">
                            <i class="bi bi-p-circle-fill text-white fs-1"></i>
                        </div>
                        <h3 class="fw-bold" style="color:var(--primary);">PARKING POINT</h3>
                        <p class="text-muted small mb-0">Silakan login untuk melanjutkan</p>
                    </div>

                    @include('components.alert')

                    {{-- Form autentikasi mengirim email, kata sandi, dan opsi sesi. --}}
                    <form method="POST" action="/login">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-envelope"></i> Email
                            </label>
                            <input type="email" name="email" class="form-control form-control-lg"
                                   value="{{ old('email') }}" placeholder="email@parkingpoint.id" required autofocus>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-lock"></i> Password
                            </label>
                            <input type="password" name="password" class="form-control form-control-lg"
                                   placeholder="••••••••" required>
                        </div>

                        <div class="mb-4 form-check">
                            <input type="checkbox" name="remember" class="form-check-input" id="remember">
                            <label class="form-check-label" for="remember">Ingat saya</label>
                        </div>

                        <button type="submit" class="btn btn-primary-custom btn-lg w-100">
                            <i class="bi bi-box-arrow-in-right"></i> LOGIN
                        </button>
                    </form>

                    <div class="text-center mt-4">
                        <a href="/" class="text-muted small text-decoration-none">
                            <i class="bi bi-arrow-left"></i> Kembali ke Beranda
                        </a>
                    </div>
                </div>

                <div class="text-center text-white mt-3 small opacity-75">
                    <p class="mb-1">Demo Account:</p>
                    <p class="mb-0">owner@smart.com / admin@smart.com / petugas@smart.com</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
