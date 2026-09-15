@extends('layouts.app')
@section('title', 'Transaksi')
@section('page-title', 'Riwayat Transaksi')
@section('sidebar') @show

@section('content')
<div class="card card-custom p-4">
    <h5 class="fw-bold mb-4" style="color:var(--primary);">
        <i class="bi bi-receipt"></i> Riwayat Transaksi Saya
    </h5>

    <div class="table-responsive">
        <table class="table table-custom table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Kode</th>
                    <th>Plat</th>
                    <th>Jenis</th>
                    <th>Masuk</th>
                    <th>Keluar</th>
                    <th>Durasi</th>
                    <th>Status</th>
                    <th class="text-end">Total</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transaksis as $i => $t)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td><code class="small">{{ $t->kode_transaksi }}</code></td>
                    <td><strong>{{ strtoupper($t->kendaraan->plat_nomor ?? '-') }}</strong></td>
                    <td>{{ $t->kendaraan->jenisLabel ?? '-' }}</td>
                    <td><small>{{ \Carbon\Carbon::parse($t->jam_masuk)->format('d/m H:i') }}</small></td>
                    <td><small>{{ $t->jam_keluar ? \Carbon\Carbon::parse($t->jam_keluar)->format('d/m H:i') : '-' }}</small></td>
                    <td>{{ $t->durasi_jam ?? 0 }} jam</td>
                    <td>{!! $t->statusBadge !!}</td>
                    <td class="text-end fw-bold text-success">Rp {{ number_format($t->total_bayar, 0, ',', '.') }}</td>
                    <td class="text-center">
                        <a href="/petugas/struk/{{ $t->id }}" target="_blank" class="btn btn-sm btn-primary-custom">
                            <i class="bi bi-printer"></i> Struk
                        </a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="10" class="text-center py-4 text-muted">Belum ada transaksi</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
