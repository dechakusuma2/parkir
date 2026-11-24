{{-- Menampilkan halaman publik pengenalan Parking Point dan tautan masuk. --}}
@extends('layouts.app')
@section('title', 'Manajemen Parkir Modern')

@section('content')
<div class="parking-landing">
    <nav class="landing-nav">
        <a class="brand-mark" href="{{ url('/') }}" aria-label="Parking Point">
            <span class="brand-icon"><i class="bi bi-p-circle-fill"></i></span>
            <span>PARKING <strong>POINT</strong></span>
        </a>
        <a href="{{ route('login') }}" class="landing-login">Masuk <i class="bi bi-arrow-up-right"></i></a>
    </nav>

    <main>
        {{-- Bagian hero memperkenalkan manfaat utama dan pratinjau dashboard. --}}
        <section class="landing-hero">
            <div class="hero-copy">
                <p class="eyebrow"><span></span> Sistem parkir yang lebih tertata</p>
                <h1>Parkir lancar,<br><em>bisnis</em> berkembang.</h1>
                <p class="hero-description">Kelola kendaraan, transaksi, area, dan laporan parkir dalam satu ruang kerja yang sederhana.</p>
                <div class="hero-actions">
                    <a href="{{ route('login') }}" class="landing-button landing-button-primary">Mulai kelola <i class="bi bi-arrow-right"></i></a>
                    <a href="#fitur" class="landing-button landing-button-ghost">Lihat fitur</a>
                </div>
                <div class="hero-note"><i class="bi bi-shield-check"></i> Data tercatat rapi dan mudah dipantau</div>
            </div>

            <div class="hero-preview" aria-label="Ringkasan dashboard parkir">
                <div class="preview-glow"></div>
                <div class="preview-window">
                    <div class="preview-topbar"><span class="preview-dots"><i></i><i></i><i></i></span><span>Ringkasan hari ini</span><i class="bi bi-three-dots"></i></div>
                    <div class="preview-heading"><div><small>Selamat datang kembali</small><h2>Dashboard parkir</h2></div><span class="live-dot">Live</span></div>
                    <div class="preview-stats">
                        <div><small>Kendaraan masuk</small><strong>128</strong><span class="trend-up">+12,4%</span></div>
                        <div><small>Pendapatan hari ini</small><strong>Rp 4,8 jt</strong><span class="trend-up">+8,2%</span></div>
                    </div>
                    <div class="preview-chart"><div class="chart-label"><span>Aktivitas parkir</span><small>07.00 - 19.00</small></div><div class="chart-bars"><i style="height:32%"></i><i style="height:48%"></i><i style="height:40%"></i><i style="height:70%"></i><i style="height:56%"></i><i style="height:88%"></i><i style="height:66%"></i><i style="height:96%"></i><i style="height:76%"></i></div></div>
                </div>
                <div class="floating-ticket"><i class="bi bi-check2-circle"></i><span><small>Transaksi berhasil</small><strong>Mobil • B 1234 ABC</strong></span></div>
            </div>
        </section>

        {{-- Bagian ini merangkum nilai layanan sebelum daftar fitur. --}}
        <section class="trust-strip"><span>Dipakai untuk operasional yang lebih tenang</span><div><i class="bi bi-lightning-charge-fill"></i> Cepat <i class="bi bi-bar-chart-fill"></i> Terukur <i class="bi bi-lock-fill"></i> Aman</div></section>

        <section class="feature-section" id="fitur">
            <div class="section-heading"><p class="eyebrow"><span></span> Semua dalam satu tempat</p><h2>Lebih sedikit pekerjaan manual.<br><em>Lebih banyak kendali.</em></h2></div>
            <div class="feature-grid">
                <article class="feature-card feature-card-dark"><i class="bi bi-speedometer2 feature-icon"></i><h3>Transaksi secepat kilat</h3><p>Catat kendaraan masuk dan keluar tanpa antrean panjang.</p><a href="{{ route('login') }}">Pelajari <i class="bi bi-arrow-up-right"></i></a></article>
                <article class="feature-card"><i class="bi bi-pie-chart feature-icon"></i><h3>Laporan yang berbicara</h3><p>Pantau pendapatan dan aktivitas area dengan data yang jelas.</p><a href="{{ route('login') }}">Pelajari <i class="bi bi-arrow-up-right"></i></a></article>
                <article class="feature-card feature-card-accent"><i class="bi bi-people feature-icon"></i><h3>Tim lebih terarah</h3><p>Owner, admin, dan petugas bekerja dengan akses yang tepat.</p><a href="{{ route('login') }}">Pelajari <i class="bi bi-arrow-up-right"></i></a></article>
            </div>
        </section>
    </main>

    <footer class="landing-footer"><span>&copy; {{ date('Y') }} Parking Point</span><span>Manajemen parkir tanpa ribet.</span></footer>
</div>
@endsection
