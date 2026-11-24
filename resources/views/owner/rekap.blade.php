{{-- Menyediakan filter periode, ringkasan pendapatan, dan tabel rekap transaksi. --}}
@extends('layouts.owner')
@section('title', 'Rekap Transaksi')
@section('page-title', 'Rekap Transaksi')

@section('content')
<div class="card card-custom p-4 mb-4">
    {{-- Filter tanggal mengirimkan rentang rekap atau mengembalikan periode default. --}}
    <h5 class="fw-bold mb-4" style="color:var(--primary);"><i data-lucide="funnel" class="me-2"></i>Filter Rekap</h5>

    <form method="GET" action="{{ route('owner.rekap.filter') }}">
        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label fw-semibold">Dari Tanggal</label>
                <input type="date" name="dari" class="form-control" value="{{ request('dari', now()->startOfMonth()->format('Y-m-d')) }}" required>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">Sampai Tanggal</label>
                <input type="date" name="sampai" class="form-control" value="{{ request('sampai', now()->format('Y-m-d')) }}" required>
            </div>
            <div class="col-md-4 d-flex align-items-end gap-2">
                <button type="submit" class="btn btn-primary-custom"><i data-lucide="search"></i>Filter</button>
                <a href="{{ route('owner.rekap') }}" class="btn btn-light"><i data-lucide="rotate-ccw"></i>Reset</a>
                <button type="button" onclick="window.print()" class="btn btn-success"><i data-lucide="printer"></i>Cetak</button>
            </div>
        </div>
    </form>
</div>

<div class="admin-stat-grid">
    {{-- Kartu ini meringkas jumlah transaksi, total pendapatan, dan nilai rata-rata. --}}
    <article class="admin-stat-card" style="background:#fff; border:1px solid #e2e8f0; color:#0f172a; box-shadow:0 8px 24px rgba(15,23,42,.04);">
        <div class="admin-stat-icon" style="background:#e0f2fe; color:#0369a1;"><i data-lucide="receipt-text"></i></div>
        <p style="color:#64748b;">Total Transaksi</p>
        <strong style="color:#0f172a;">{{ number_format($data->count() ?? 0) }}</strong>
    </article>
    <article class="admin-stat-card" style="background:#fff; border:1px solid #e2e8f0; color:#0f172a; box-shadow:0 8px 24px rgba(15,23,42,.04);">
        <div class="admin-stat-icon" style="background:#dcfce7; color:#15803d;"><i data-lucide="banknote"></i></div>
        <p style="color:#64748b;">Total Pendapatan</p>
        <strong style="color:#0f172a;">Rp {{ number_format($data->sum('total_bayar') ?? 0, 0, ',', '.') }}</strong>
    </article>
    <article class="admin-stat-card" style="background:#fff; border:1px solid #e2e8f0; color:#0f172a; box-shadow:0 8px 24px rgba(15,23,42,.04);">
        <div class="admin-stat-icon" style="background:#fef3c7; color:#b45309;"><i data-lucide="trending-up"></i></div>
        <p style="color:#64748b;">Rata-rata / Transaksi</p>
        <strong style="color:#0f172a;">Rp {{ number_format($data->avg('total_bayar') ?? 0, 0, ',', '.') }}</strong>
    </article>
</div>

<div class="card card-custom p-4 mt-4">
    {{-- Tabel merinci transaksi yang memenuhi rentang tanggal terpilih. --}}
    <h5 class="fw-bold mb-4" style="color:var(--primary);"><i data-lucide="table-2"></i> Detail Transaksi</h5>

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
                    <td>{{ $t->created_at?->format('d/m/Y') ?? '-' }}</td>
                    <td><strong>{{ $t->kendaraan->plat_nomor ?? '-' }}</strong></td>
                    <td>{{ $t->kendaraan->jenisLabel ?? '-' }}</td>
                    <td>{{ $t->area->nama_area ?? '-' }}</td>
                    <td>{{ $t->durasi_jam ?? 0 }} jam</td>
                    <td>{{ $t->petugas->name ?? '-' }}</td>
                    <td class="text-end fw-bold text-success">Rp {{ number_format($t->total_bayar ?? 0, 0, ',', '.') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center py-4 text-muted"><i data-lucide="inbox" class="d-block mb-2"></i> Tidak ada data transaksi</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
