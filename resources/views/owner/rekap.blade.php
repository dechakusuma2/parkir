@extends('layouts.app')
@section('title', 'Rekap Transaksi')
@section('page-title', 'Rekap Transaksi')

@section('sidebar') @show

@section('content')
<div class="card card-custom p-4 mb-4">
    <h5 class="fw-bold mb-4" style="color:var(--primary);">
        <i class="bi bi-funnel"></i> Filter Rekap
    </h5>

    <form method="GET" action="{{ route('owner.rekap.filter') }}">
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label fw-semibold">Dari Tanggal</label>
                <input type="date" name="dari" class="form-control"
                       value="{{ request('dari', now()->startOfMonth()->format('Y-m-d')) }}" required>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">Sampai Tanggal</label>
                <input type="date" name="sampai" class="form-control"
                       value="{{ request('sampai', now()->format('Y-m-d')) }}" required>
            </div>
            <div class="col-md-4 d-flex align-items-end gap-2">
                <button type="submit" class="btn btn-primary-custom">
                    <i class="bi bi-search"></i> Filter
                </button>
                <a href="{{ route('owner.rekap') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-clockwise"></i> Reset
                </a>
                <button type="button" onclick="window.print()" class="btn btn-success">
                    <i class="bi bi-printer"></i> Cetak
                </button>
            </div>
        </div>
    </form>
</div>

<!-- Summary -->
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="stat-card">
            <p class="stat-label">Total Transaksi</p>
            <h3 class="stat-value">{{ $data->count() ?? 0 }}</h3>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card" style="background:linear-gradient(135deg,#43a047,#66bb6a);">
            <p class="stat-label">Total Pendapatan</p>
            <h3 class="stat-value">Rp {{ number_format($data->sum('total_bayar') ?? 0, 0, ',', '.') }}</h3>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card" style="background:linear-gradient(135deg,#fb8c00,#ffa726);">
            <p class="stat-label">Rata-rata / Transaksi</p>
            <h3 class="stat-value">Rp {{ number_format($data->avg('total_bayar') ?? 0, 0, ',', '.') }}</h3>
        </div>
    </div>
</div>

<!-- Table -->
<div class="card card-custom p-4">
    <h5 class="fw-bold mb-4" style="color:var(--primary);">
        <i class="bi bi-table"></i> Detail Transaksi
    </h5>

    <div class="table-responsive">
        <table class="table table-custom table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Kode</th>
                    <th>Tanggal</th>
                    <th>Plat Nomor</th>
                    <th>Jenis</th>
                    <th>Area</th>
                    <th>Durasi</th>
                    <th>Petugas</th>
                    <th class="text-end">Total</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data ?? [] as $i => $t)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td><code>{{ $t->kode_transaksi }}</code></td>
                    <td>{{ $t->created_at->format('d/m/Y') }}</td>
                    <td><strong>{{ $t->kendaraan->plat_nomor ?? '-' }}</strong></td>
                    <td>{{ $t->kendaraan->jenisLabel ?? '-' }}</td>
                    <td>{{ $t->area->nama_area ?? '-' }}</td>
                    <td>{{ $t->durasi_jam }} jam</td>
                    <td>{{ $t->petugas->name ?? '-' }}</td>
                    <td class="text-end fw-bold text-success">Rp {{ number_format($t->total_bayar, 0, ',', '.') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center py-4 text-muted">
                        <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                        Tidak ada data transaksi
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
