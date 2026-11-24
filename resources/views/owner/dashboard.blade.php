{{-- Menampilkan metrik pendapatan, jumlah transaksi, kendaraan aktif, dan status area. --}}
@extends('layouts.owner')
@section('title', 'Dashboard Owner')
@section('page-title', 'Dashboard Owner')

@section('content')
<div class="admin-page-heading">
    <div><p class="admin-eyebrow">Selamat datang kembali</p><h2 class="admin-heading">Ringkasan operasional</h2></div>
    <span class="admin-date"><i data-lucide="calendar-days"></i>{{ now()->translatedFormat('l, d F Y') }}</span>
</div>

<div class="admin-stat-grid">
    {{-- Kartu ringkasan memperlihatkan indikator keuangan dan aktivitas harian. --}}
    <article class="admin-stat-card" style="background:#fff; border:1px solid #e2e8f0; color:#0f172a; box-shadow:0 8px 24px rgba(15,23,42,.04);">
        <div class="admin-stat-icon" style="background:#dbeafe; color:#1d4ed8;"><i data-lucide="banknote"></i></div>
        <p style="color:#64748b;">Total Pendapatan</p>
        <strong style="color:#0f172a;">Rp {{ number_format($totalPendapatan ?? 0, 0, ',', '.') }}</strong>
    </article>
    <article class="admin-stat-card" style="background:#fff; border:1px solid #e2e8f0; color:#0f172a; box-shadow:0 8px 24px rgba(15,23,42,.04);">
        <div class="admin-stat-icon" style="background:#dbeafe; color:#2563eb;"><i data-lucide="calendar-check-2"></i></div>
        <p style="color:#64748b;">Pendapatan Hari Ini</p>
        <strong style="color:#0f172a;">Rp {{ number_format($pendapatanHariIni ?? 0, 0, ',', '.') }}</strong>
    </article>
    <article class="admin-stat-card" style="background:#fff; border:1px solid #e2e8f0; color:#0f172a; box-shadow:0 8px 24px rgba(15,23,42,.04);">
        <div class="admin-stat-icon" style="background:#dcfce7; color:#15803d;"><i data-lucide="receipt-text"></i></div>
        <p style="color:#64748b;">Transaksi Hari Ini</p>
        <strong style="color:#0f172a;">{{ number_format($transaksiHariIni ?? 0) }}</strong>
    </article>
    <article class="admin-stat-card" style="background:#fff; border:1px solid #e2e8f0; color:#0f172a; box-shadow:0 8px 24px rgba(15,23,42,.04);">
        <div class="admin-stat-icon" style="background:#fef3c7; color:#b45309;"><i data-lucide="car-front"></i></div>
        <p style="color:#64748b;">Kendaraan Aktif</p>
        <strong style="color:#0f172a;">{{ number_format($kendaraanAktif ?? 0) }}</strong>
    </article>
</div>

<div class="admin-dashboard-grid">
    {{-- Grafik dan daftar area menampilkan tren pendapatan serta kapasitas parkir. --}}
    <section class="admin-card admin-chart-card">
        <div class="admin-card-header">
            <div><p class="admin-eyebrow">Tren 7 hari</p><h3>Pendapatan 7 Hari Terakhir</h3></div>
        </div>
        <div class="admin-chart" data-chart data-masuk='@json(array_fill(0, 7, 0))' data-keluar='@json($chartData ?? [])' data-labels='@json($labels ?? [])'></div>
    </section>

    <section class="admin-card">
        <div class="admin-card-header">
            <div><p class="admin-eyebrow">Kapasitas</p><h3>Status Area</h3></div>
        </div>
        <div class="admin-area-list">
            @forelse($areas ?? [] as $area)
                <div class="admin-area-row">
                    <div class="admin-area-row-top">
                        <span>{{ $area->nama_area }}</span>
                        <strong>{{ $area->terisi }}/{{ $area->kapasitas }}</strong>
                    </div>
                    <div class="admin-progress"><span style="width:{{ $area->percentage }}%"></span></div>
                    <small>{{ $area->sisa }} slot tersedia</small>
                    <div class="mt-2">{!! $area->statusBadge !!}</div>
                </div>
            @empty
                <p class="admin-empty">Belum ada area parkir.</p>
            @endforelse
        </div>
    </section>
</div>
@endsection
