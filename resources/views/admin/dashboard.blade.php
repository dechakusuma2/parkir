{{-- Menampilkan statistik, kapasitas area, aktivitas, dan transaksi terbaru admin. --}}
@extends('layouts.admin')
@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard Admin')

@section('content')
<div class="admin-page-heading">
    <div><p class="admin-eyebrow">Selamat datang kembali</p><h2 class="admin-heading">Ringkasan operasional hari ini</h2></div>
    <span class="admin-date"><i data-lucide="calendar-days"></i>{{ now()->translatedFormat('l, d F Y') }}</span>
</div>

<div class="admin-stat-grid">
    {{-- Kartu ini merangkum metrik transaksi, pendapatan, slot, dan petugas harian. --}}
    <article class="admin-stat-card blue"><div class="admin-stat-icon"><i data-lucide="car-front"></i></div><p>Total Kendaraan Masuk</p><strong>{{ number_format($transaksiHariIni ?? 0) }}</strong><small class="{{ ($perubahanKendaraan ?? 0) >= 0 ? 'positive' : 'negative' }}"><i data-lucide="trending-up"></i>{{ ($perubahanKendaraan ?? 0) >= 0 ? '+' : '' }}{{ $perubahanKendaraan ?? 0 }}% <span>dari kemarin</span></small></article>
    <article class="admin-stat-card cyan"><div class="admin-stat-icon"><i data-lucide="banknote"></i></div><p>Pendapatan Hari Ini</p><strong>Rp {{ number_format($pendapatanHariIni ?? 0, 0, ',', '.') }}</strong><div class="admin-sparkline"><span style="height:34%"></span><span style="height:52%"></span><span style="height:40%"></span><span style="height:68%"></span><span style="height:58%"></span><span style="height:84%"></span><span style="height:72%"></span><span style="height:100%"></span></div></article>
    <article class="admin-stat-card violet"><div class="admin-stat-icon"><i data-lucide="parking-square"></i></div><p>Slot Parkir Tersedia</p><strong>{{ number_format($slotTersedia ?? 0) }}</strong><div class="admin-progress"><span style="width:{{ $persentaseTerisi ?? 0 }}%"></span></div><small>{{ $slotTerisi ?? 0 }} terisi dari {{ $kapasitasTotal ?? 0 }} slot</small></article>
    <article class="admin-stat-card orange"><div class="admin-stat-icon"><i data-lucide="users-round"></i></div><p>Total Petugas Aktif</p><strong>{{ number_format($totalPetugas ?? 0) }}</strong><small class="neutral"><i data-lucide="circle-check"></i> Siap bertugas hari ini</small></article>
</div>

<div class="admin-dashboard-grid">
    {{-- Grafik dan daftar area memperlihatkan tren serta pemakaian kapasitas parkir. --}}
    <section class="admin-card admin-chart-card"><div class="admin-card-header"><div><p class="admin-eyebrow">Tren mingguan</p><h3>Statistik kendaraan</h3></div><span class="admin-chart-legend"><i></i> Masuk <i class="out"></i> Keluar</span></div><div class="admin-chart" data-chart data-masuk='@json($chartMasuk ?? [])' data-keluar='@json($chartKeluar ?? [])' data-labels='@json($chartLabels ?? [])'></div></section>
    <section class="admin-card"><div class="admin-card-header"><div><p class="admin-eyebrow">Kapasitas</p><h3>Area parkir</h3></div><a href="{{ url('/admin/area') }}" class="admin-text-link">Lihat semua <i data-lucide="arrow-up-right"></i></a></div><div class="admin-area-list">@forelse($areas ?? [] as $area)<div class="admin-area-row"><div class="admin-area-row-top"><span>{{ $area->nama_area }}</span><strong>{{ $area->terisi }}/{{ $area->kapasitas }}</strong></div><div class="admin-progress"><span style="width:{{ $area->percentage }}%"></span></div><small>{{ $area->sisa }} slot tersedia</small></div>@empty<p class="admin-empty">Belum ada area parkir.</p>@endforelse</div></section>
</div>

<div class="admin-dashboard-grid lower">
    {{-- Panel bawah menampilkan aktivitas sistem dan transaksi kendaraan terbaru. --}}
    <section class="admin-card"><div class="admin-card-header"><div><p class="admin-eyebrow">Live feed</p><h3>Aktivitas terbaru</h3></div><a href="{{ url('/admin/log-aktivitas') }}" class="admin-text-link">Lihat log <i data-lucide="arrow-up-right"></i></a></div><div class="admin-activity-list">@forelse($logs ?? [] as $log)<div class="admin-activity-item"><span class="admin-activity-icon"><i data-lucide="activity"></i></span><div><strong>{{ $log->aktivitas }}</strong><p>{{ $log->user->name ?? 'System' }} · {{ $log->created_at->diffForHumans() }}</p></div></div>@empty<p class="admin-empty">Belum ada aktivitas.</p>@endforelse</div></section>
    <section class="admin-card"><div class="admin-card-header"><div><p class="admin-eyebrow">Transaksi terakhir</p><h3>Kendaraan terbaru</h3></div><a href="{{ url('/admin/kendaraan') }}" class="admin-text-link">Lihat semua <i data-lucide="arrow-up-right"></i></a></div><div class="admin-transaction-list">@forelse($transaksiTerbaru ?? [] as $transaksi)<div class="admin-transaction-item"><span class="admin-plate">{{ $transaksi->kendaraan->plat_nomor ?? '-' }}</span><div><strong>{{ ucfirst($transaksi->kendaraan->jenis ?? 'Kendaraan') }}</strong><p>{{ $transaksi->jam_masuk?->format('H:i') ?? '-' }} · {{ $transaksi->status === 'masuk' ? 'Masih parkir' : 'Sudah keluar' }}</p></div><span class="admin-status-dot {{ $transaksi->status === 'masuk' ? 'inside' : 'outside' }}"></span></div>@empty<p class="admin-empty">Belum ada transaksi.</p>@endforelse</div></section>
</div>
@endsection
