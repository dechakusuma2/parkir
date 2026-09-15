@extends('layouts.app')
@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard Admin')

@section('sidebar') @show

@section('content')
<div class="row g-4 mb-4">
    <div class="col-md-3 col-sm-6">
        <div class="stat-card">
            <div class="stat-icon"><i class="bi bi-people"></i></div>
            <p class="stat-label">Total User</p>
            <h3 class="stat-value">{{ $totalUser ?? 0 }}</h3>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="stat-card" style="background:linear-gradient(135deg,#1e88e5,#42a5f5);">
            <div class="stat-icon"><i class="bi bi-geo-alt"></i></div>
            <p class="stat-label">Area Parkir</p>
            <h3 class="stat-value">{{ $totalArea ?? 0 }}</h3>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="stat-card" style="background:linear-gradient(135deg,#43a047,#66bb6a);">
            <div class="stat-icon"><i class="bi bi-car-front"></i></div>
            <p class="stat-label">Kendaraan Terdaftar</p>
            <h3 class="stat-value">{{ $totalKendaraan ?? 0 }}</h3>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="stat-card" style="background:linear-gradient(135deg,#fb8c00,#ffa726);">
            <div class="stat-icon"><i class="bi bi-receipt"></i></div>
            <p class="stat-label">Transaksi Hari Ini</p>
            <h3 class="stat-value">{{ $transaksiHariIni ?? 0 }}</h3>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-6">
        <div class="card card-custom p-4">
            <h5 class="fw-bold mb-3" style="color:var(--primary);">
                <i class="bi bi-geo-alt-fill"></i> Kapasitas Area
            </h5>
            @foreach($areas ?? [] as $area)
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-1">
                        <span class="fw-semibold">{{ $area->nama_area }}</span>
                        <span class="text-muted small">{{ $area->terisi }}/{{ $area->kapasitas }}</span>
                    </div>
                    <div class="progress" style="height:10px;">
                        <div class="progress-bar {{ $area->percentage > 75 ? 'bg-warning' : ($area->isFull() ? 'bg-danger' : 'bg-success') }}"
                             style="width:{{ $area->percentage }}%"></div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="col-md-6">
        <div class="card card-custom p-4">
            <h5 class="fw-bold mb-3" style="color:var(--primary);">
                <i class="bi bi-clock-history"></i> Aktivitas Terbaru
            </h5>
            <div class="list-group list-group-flush">
                @forelse($logs ?? [] as $log)
                    <div class="list-group-item px-0 border-0 d-flex gap-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center"
                             style="width:40px;height:40px;background:rgba(10,37,64,0.1);">
                            <i class="bi bi-activity" style="color:var(--primary);"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="fw-semibold small">{!! $log->aktivitasBadge !!}</div>
                            <div class="text-muted small">{{ $log->user->name ?? 'System' }} - {{ $log->created_at->diffForHumans() }}</div>
                        </div>
                    </div>
                @empty
                    <p class="text-muted text-center py-3">Belum ada aktivitas</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
