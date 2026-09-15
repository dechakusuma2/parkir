@extends('layouts.app')
@section('title', 'Home')

@section('content')
<!-- Navbar -->
<nav class="navbar navbar-expand-lg py-3" style="background:var(--primary);">
    <div class="container">
        <a class="navbar-brand text-white fw-bold d-flex align-items-center gap-2" href="/">
            <i class="bi bi-p-circle-fill fs-3"></i> PARKING POINT
        </a>
        <div class="ms-auto">
            <a href="/login" class="btn btn-outline-light px-4">
                <i class="bi bi-box-arrow-in-right"></i> Login
            </a>
        </div>
    </div>
</nav>

<!-- Hero -->
<section class="hero text-center">
    <div class="container position-relative" style="z-index:2;">
        <span class="badge bg-light text-dark mb-3 px-3 py-2">
            <i class="bi bi-stars"></i> Sistem Parkir Modern
        </span>
        <h1 class="display-2 fw-bold mb-4">PARKING POINT</h1>
        <p class="lead mb-4 mx-auto" style="max-width:600px;">
            Solusi manajemen parkir cerdas, cepat, dan terpercaya.
            Kelola area parkir Anda dengan teknologi terdepan.
        </p>
        <div class="d-flex gap-3 justify-content-center flex-wrap">
            <a href="/login" class="btn btn-light btn-lg px-5">
                <i class="bi bi-rocket-takeoff"></i> Mulai Sekarang
            </a>
            <a href="#fitur" class="btn btn-outline-light btn-lg px-5">
                <i class="bi bi-info-circle"></i> Pelajari Lebih
            </a>
        </div>
    </div>
</section>

<!-- Fitur -->
<section id="fitur" class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold" style="color:var(--primary);">Fitur Unggulan</h2>
            <p class="text-muted">Semua yang Anda butuhkan untuk manajemen parkir modern</p>
        </div>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="card card-custom h-100 p-4 text-center">
                    <div class="mx-auto mb-3 d-flex align-items-center justify-content-center rounded-circle" style="width:70px;height:70px;background:rgba(10,37,64,0.1);">
                        <i class="bi bi-lightning-charge-fill fs-1" style="color:var(--primary);"></i>
                    </div>
                    <h4 class="fw-bold">Super Cepat</h4>
                    <p class="text-muted">Transaksi parkir dalam hitungan detik dengan sistem otomatis</p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card card-custom h-100 p-4 text-center">
                    <div class="mx-auto mb-3 d-flex align-items-center justify-content-center rounded-circle" style="width:70px;height:70px;background:rgba(10,37,64,0.1);">
                        <i class="bi bi-shield-lock-fill fs-1" style="color:var(--primary);"></i>
                    </div>
                    <h4 class="fw-bold">Aman & Terpercaya</h4>
                    <p class="text-muted">Data tersimpan aman dengan enkripsi dan backup otomatis</p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card card-custom h-100 p-4 text-center">
                    <div class="mx-auto mb-3 d-flex align-items-center justify-content-center rounded-circle" style="width:70px;height:70px;background:rgba(10,37,64,0.1);">
                        <i class="bi bi-graph-up-arrow fs-1" style="color:var(--primary);"></i>
                    </div>
                    <h4 class="fw-bold">Real-time Report</h4>
                    <p class="text-muted">Monitoring dan laporan pendapatan secara real-time</p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card card-custom h-100 p-4 text-center">
                    <div class="mx-auto mb-3 d-flex align-items-center justify-content-center rounded-circle" style="width:70px;height:70px;background:rgba(10,37,64,0.1);">
                        <i class="bi bi-printer-fill fs-1" style="color:var(--primary);"></i>
                    </div>
                    <h4 class="fw-bold">Cetak Struk</h4>
                    <p class="text-muted">Struk parkir otomatis dengan QR code untuk validasi</p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card card-custom h-100 p-4 text-center">
                    <div class="mx-auto mb-3 d-flex align-items-center justify-content-center rounded-circle" style="width:70px;height:70px;background:rgba(10,37,64,0.1);">
                        <i class="bi bi-people-fill fs-1" style="color:var(--primary);"></i>
                    </div>
                    <h4 class="fw-bold">Multi User</h4>
                    <p class="text-muted">Sistem role Owner, Admin, dan Petugas yang terstruktur</p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card card-custom h-100 p-4 text-center">
                    <div class="mx-auto mb-3 d-flex align-items-center justify-content-center rounded-circle" style="width:70px;height:70px;background:rgba(10,37,64,0.1);">
                        <i class="bi bi-phone-fill fs-1" style="color:var(--primary);"></i>
                    </div>
                    <h4 class="fw-bold">Responsive</h4>
                    <p class="text-muted">Akses dari perangkat apapun, kapanpun dan dimanapun</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Stats -->
<section class="py-5" style="background:var(--primary);color:#fff;">
    <div class="container">
        <div class="row text-center g-4">
            <div class="col-md-3 col-6">
                <h2 class="fw-bold">10K+</h2>
                <p class="mb-0 opacity-75">Transaksi / Bulan</p>
            </div>
            <div class="col-md-3 col-6">
                <h2 class="fw-bold">500+</h2>
                <p class="mb-0 opacity-75">Area Parkir</p>
            </div>
            <div class="col-md-3 col-6">
                <h2 class="fw-bold">99.9%</h2>
                <p class="mb-0 opacity-75">Uptime</p>
            </div>
            <div class="col-md-3 col-6">
                <h2 class="fw-bold">24/7</h2>
                <p class="mb-0 opacity-75">Support</p>
            </div>
        </div>
    </div>
</section>

<!-- Footer -->
<footer class="py-4 text-center" style="background:var(--primary-dark);color:#fff;">
    <div class="container">
        <p class="mb-0">&copy; 2026 <strong>PARKING POINT</strong>. All Rights Reserved.</p>
        <small class="opacity-75">Made with <i class="bi bi-heart-fill text-danger"></i> in Indonesia</small>
    </div>
</footer>
@endsection
