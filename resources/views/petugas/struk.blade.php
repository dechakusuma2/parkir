@extends('layouts.app')
@section('title', 'Struk Parkir')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card card-custom p-4" style="border-top:4px solid var(--primary);">
                <div class="text-center mb-3">
                    <div class="mx-auto mb-2 d-flex align-items-center justify-content-center rounded-circle"
                         style="width:60px;height:60px;background:var(--primary);">
                        <i class="bi bi-p-circle-fill text-white fs-2"></i>
                    </div>
                    <h4 class="fw-bold mb-1" style="color:var(--primary);">PARKING POINT</h4>
                    <small class="text-muted">Sistem Parkir Cerdas</small>
                </div>

                <hr>

                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Kode Transaksi</span>
                        <strong>{{ $t->kode_transaksi }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Status</span>
                        {!! $t->statusBadge !!}
                    </div>
                </div>

                <hr>

                <h6 class="fw-bold mb-3">Data Kendaraan</h6>
                <div class="mb-2 d-flex justify-content-between">
                    <span class="text-muted">Plat Nomor</span>
                    <strong class="fs-5" style="color:var(--primary);">{{ strtoupper($t->kendaraan->plat_nomor) }}</strong>
                </div>
                <div class="mb-2 d-flex justify-content-between">
                    <span class="text-muted">Jenis</span>
                    <span>{{ $t->kendaraan->jenisLabel }}</span>
                </div>
                <div class="mb-2 d-flex justify-content-between">
                    <span class="text-muted">Warna</span>
                    <span>{{ $t->kendaraan->warna }}</span>
                </div>
                <div class="mb-2 d-flex justify-content-between">
                    <span class="text-muted">Pemilik</span>
                    <span>{{ $t->kendaraan->pemilik }}</span>
                </div>

                <hr>

                <h6 class="fw-bold mb-3">Detail Parkir</h6>
                <div class="mb-2 d-flex justify-content-between">
                    <span class="text-muted">Area</span>
                    <span>{{ $t->area->nama_area }}</span>
                </div>
                <div class="mb-2 d-flex justify-content-between">
                    <span class="text-muted">Jam Masuk</span>
                    <span>{{ \Carbon\Carbon::parse($t->jam_masuk)->format('d/m/Y H:i') }}</span>
                </div>
                <div class="mb-2 d-flex justify-content-between">
                    <span class="text-muted">Jam Keluar</span>
                    <span>{{ $t->jam_keluar ? \Carbon\Carbon::parse($t->jam_keluar)->format('d/m/Y H:i') : '-' }}</span>
                </div>
                <div class="mb-2 d-flex justify-content-between">
                    <span class="text-muted">Durasi</span>
                    <span>{{ $t->durasi_jam ?? $t->hitungDurasi() }} jam</span>
                </div>
                <div class="mb-2 d-flex justify-content-between">
                    <span class="text-muted">Tarif</span>
                    <span>{{ $t->tarif->tarifFormat ?? '-' }}/jam</span>
                </div>

                <hr>

                <div class="p-3 rounded" style="background:rgba(10,37,64,0.05);">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="fw-bold fs-5">TOTAL BAYAR</span>
                        <span class="fw-bold fs-3" style="color:var(--primary);">
                            Rp {{ number_format($t->total_bayar ?? 0, 0, ',', '.') }}
                        </span>
                    </div>
                </div>

                <hr>

                <div class="text-center small text-muted">
                    <p class="mb-1">Terima kasih telah menggunakan PARKING POINT</p>
                    <p class="mb-0">Petugas: {{ $t->petugas->name ?? '-' }}</p>
                    <p class="mb-0">{{ now()->format('d/m/Y H:i') }}</p>
                </div>

                <div class="d-grid gap-2 mt-3 no-print">
                    <button onclick="window.print()" class="btn btn-primary-custom btn-lg">
                        <i class="bi bi-printer"></i> Cetak Struk
                    </button>
                    <a href="/petugas/dashboard" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
