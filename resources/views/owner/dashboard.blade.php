@extends('layouts.app')
@section('title', 'Dashboard Owner')
@section('page-title', 'Dashboard Owner')

@section('sidebar') @show

@section('content')
<!-- Stats -->
<div class="row g-4 mb-4">
    <div class="col-md-3 col-sm-6">
        <div class="stat-card">
            <div class="stat-icon"><i class="bi bi-cash-stack"></i></div>
            <p class="stat-label">Total Pendapatan</p>
            <h3 class="stat-value">Rp {{ number_format($totalPendapatan ?? 0, 0, ',', '.') }}</h3>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="stat-card" style="background:linear-gradient(135deg,#1e88e5,#42a5f5);">
            <div class="stat-icon"><i class="bi bi-calendar-check"></i></div>
            <p class="stat-label">Pendapatan Hari Ini</p>
            <h3 class="stat-value">Rp {{ number_format($hariIni ?? 0, 0, ',', '.') }}</h3>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="stat-card" style="background:linear-gradient(135deg,#43a047,#66bb6a);">
            <div class="stat-icon"><i class="bi bi-receipt"></i></div>
            <p class="stat-label">Transaksi Hari Ini</p>
            <h3 class="stat-value">{{ $transaksiHariIni ?? 0 }}</h3>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="stat-card" style="background:linear-gradient(135deg,#fb8c00,#ffa726);">
            <div class="stat-icon"><i class="bi bi-car-front"></i></div>
            <p class="stat-label">Kendaraan Aktif</p>
            <h3 class="stat-value">{{ $kendaraanAktif ?? 0 }}</h3>
        </div>
    </div>
</div>

<!-- Chart & Info -->
<div class="row g-4">
    <div class="col-md-8">
        <div class="card card-custom p-4">
            <h5 class="fw-bold mb-4" style="color:var(--primary);">
                <i class="bi bi-graph-up"></i> Pendapatan 7 Hari Terakhir
            </h5>
            <canvas id="chartPendapatan" height="100"></canvas>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card card-custom p-4">
            <h5 class="fw-bold mb-4" style="color:var(--primary);">
                <i class="bi bi-geo-alt"></i> Status Area
            </h5>
            @foreach($areas ?? [] as $area)
                <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                    <div>
                        <div class="fw-semibold">{{ $area->nama_area }}</div>
                        <small class="text-muted">{{ $area->terisi }}/{{ $area->kapasitas }} kendaraan</small>
                    </div>
                    {!! $area->statusBadge !!}
                </div>
            @endforeach
        </div>
    </div>
</div>

@push('scripts')
<script>
const ctx = document.getElementById('chartPendapatan');
@if(isset($chartData))
new Chart(ctx, {
    type: 'line',
    data: {
        labels: {!! json_encode($chartData['labels']) !!},
        datasets: [{
            label: 'Pendapatan (Rp)',
            data: {!! json_encode($chartData['data']) !!},
            borderColor: '#0a2540',
            backgroundColor: 'rgba(10,37,64,0.1)',
            tension: 0.4,
            fill: true
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } }
    }
});
@endif
</script>
@endpush
@endsection
