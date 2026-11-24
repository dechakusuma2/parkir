{{-- Menampilkan tarif parkir per jenis kendaraan beserta aksi edit dan hapus. --}}
@extends('layouts.admin')
@section('title', 'Tarif Parkir')
@section('page-title', 'Tarif Parkir')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="fw-bold mb-0" style="color:var(--primary);">
        <i class="bi bi-cash-coin"></i> Daftar Tarif
    </h5>
    <a href="/admin/tarifs/create" class="btn btn-primary-custom">
        <i class="bi bi-plus-circle"></i> Tambah Tarif
    </a>
</div>

<div class="row g-4">
    {{-- Setiap kartu berisi tarif satu kategori kendaraan atau pesan tanpa data. --}}
    @forelse($tarifs as $tarif)
    <div class="col-md-4">
        <div class="card card-custom p-4 h-100 admin-tarif-card">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div class="admin-card-menu" data-card-menu>
                    <button type="button" class="admin-card-menu-button" data-menu-toggle aria-label="Buka menu tarif">
                        <i data-lucide="more-vertical"></i>
                    </button>
                    <div class="admin-card-menu-panel" data-menu-panel>
                        <a href="/admin/tarifs/{{ $tarif->id }}/edit"><i data-lucide="pencil"></i> Edit</a>
                        <form action="/admin/tarifs/{{ $tarif->id }}" method="POST" onsubmit="return confirm('Hapus tarif ini?')">
                            @csrf @method('DELETE')
                            <button type="submit"><i data-lucide="trash-2"></i> Hapus</button>
                        </form>
                    </div>
                </div>
            </div>
            <h5 class="fw-bold">{{ ucfirst($tarif->jenis_kendaraan) }}</h5>
            <h3 class="fw-bold my-2" style="color:var(--primary);">{{ $tarif->tarifFormat }}<small class="text-muted">/jam</small></h3>
            <p class="text-muted small mb-0">{{ $tarif->keterangan ?? 'Tidak ada keterangan' }}</p>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="card card-custom p-5 text-center">
            <i class="bi bi-inbox fs-1 text-muted"></i>
            <p class="text-muted mt-2">Belum ada tarif</p>
        </div>
    </div>
    @endforelse
</div>
@endsection
