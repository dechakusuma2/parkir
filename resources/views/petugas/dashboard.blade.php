@extends('layouts.app')
@section('title', 'Dashboard Petugas')
@section('page-title', 'Dashboard Petugas')
@section('sidebar') @show

@section('content')
<div class="row g-4 mb-4">
    <div class="col-md-3 col-sm-6">
        <div class="stat-card">
            <div class="stat-icon"><i class="bi bi-receipt"></i></div>
            <p class="stat-label">Transaksi Hari Ini</p>
            <h3 class="stat-value">{{ $transaksiHariIni ?? 0 }}</h3>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="stat-card" style="background:linear-gradient(135deg,#43a047,#66bb6a);">
            <div class="stat-icon"><i class="bi bi-cash-stack"></i></div>
            <p class="stat-label">Pendapatan Hari Ini</p>
            <h3 class="stat-value">Rp {{ number_format($pendapatanHariIni ?? 0, 0, ',', '.') }}</h3>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="stat-card" style="background:linear-gradient(135deg,#1e88e5,#42a5f5);">
            <div class="stat-icon"><i class="bi bi-car-front"></i></div>
            <p class="stat-label">Kendaraan Aktif</p>
            <h3 class="stat-value">{{ $kendaraanAktif ?? 0 }}</h3>
        </div>
    </div>
    <div class="col-md-3 col-sm-6">
        <div class="stat-card" style="background:linear-gradient(135deg,#fb8c00,#ffa726);">
            <div class="stat-icon"><i class="bi bi-clock-history"></i></div>
            <p class="stat-label">Total Transaksi Saya</p>
            <h3 class="stat-value">{{ $totalTransaksiSaya ?? 0 }}</h3>
        </div>
    </div>
</div>

<!-- Quick Action -->
<div class="row g-4">
    <div class="col-md-6">
        <div class="card card-custom p-4">
            <h5 class="fw-bold mb-4" style="color:var(--primary);">
                <i class="bi bi-plus-circle"></i> Kendaraan Masuk
            </h5>
            <form action="{{ route('petugas.masuk') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-semibold">Plat Nomor <span class="text-danger">*</span></label>
                    <input type="text" name="plat_nomor" class="form-control form-control-lg text-uppercase"
                           placeholder="B 1234 ABC" required autofocus>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Jenis <span class="text-danger">*</span></label>
                        <select name="jenis" class="form-select" required>
                            <option value="motor">Motor</option>
                            <option value="mobil">Mobil</option>
                            <option value="truk">Truk</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-semibold">Warna <span class="text-danger">*</span></label>
                        <input type="text" name="warna" class="form-control" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Nama Pemilik <span class="text-danger">*</span></label>
                    <input type="text" name="pemilik" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Area Parkir <span class="text-danger">*</span></label>
                    <select name="area_id" class="form-select" required>
                        <option value="">-- Pilih Area --</option>
                        @foreach($areas as $area)
                            <option value="{{ $area->id }}" {{ $area->isFull() ? 'disabled' : '' }}>
                                {{ $area->nama_area }} (Sisa: {{ $area->sisa }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <button class="btn btn-primary-custom btn-lg w-100">
                    <i class="bi bi-box-arrow-in-right"></i> Proses Kendaraan Masuk
                </button>
            </form>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card card-custom p-4">
            <h5 class="fw-bold mb-4" style="color:var(--primary);">
                <i class="bi bi-car-front"></i> Kendaraan Sedang Parkir
            </h5>
            <div class="table-responsive" style="max-height:450px;overflow-y:auto;">
                <table class="table table-hover">
                    <thead style="position:sticky;top:0;background:#fff;">
                        <tr>
                            <th>Plat</th>
                            <th>Jenis</th>
                            <th>Masuk</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transaksiAktif as $t)
                        <tr>
                            <td><code>{{ strtoupper($t->kendaraan->plat_nomor) }}</code></td>
                            <td>{{ $t->kendaraan->jenisLabel }}</td>
                            <td><small>{{ \Carbon\Carbon::parse($t->jam_masuk)->format('H:i') }}</small></td>
                            <td>
                                <form action="{{ route('petugas.keluar', $t->id) }}" method="POST"
                                      onsubmit="return confirm('Proses kendaraan keluar?')">
                                    @csrf
                                    <button class="btn btn-sm btn-success">
                                        <i class="bi bi-box-arrow-right"></i> Keluar
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center py-3 text-muted">Tidak ada kendaraan aktif</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
