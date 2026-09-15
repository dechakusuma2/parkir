@extends('layouts.app')
@section('title', 'Tarif Parkir')
@section('page-title', 'Tarif Parkir')
@section('sidebar') @show

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
    @forelse($tarifs as $tarif)
    <div class="col-md-4">
        <div class="card card-custom p-4 h-100">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div class="rounded-circle d-flex align-items-center justify-content-center"
                     style="width:50px;height:50px;background:rgba(10,37,64,0.1);">
                    <span class="fs-3">{{ $tarif->jenisLabel }}</span>
                </div>
                <div class="dropdown">
                    <button class="btn btn-sm btn-light" data-bs-toggle="dropdown">
                        <i class="bi bi-three-dots-vertical"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="/admin/tarifs/{{ $tarif->id }}/edit">
                            <i class="bi bi-pencil"></i> Edit</a></li>
                        <li>
                            <form action="/admin/tarifs/{{ $tarif->id }}" method="POST" onsubmit="return confirm('Hapus tarif ini?')">
                                @csrf @method('DELETE')
                                <button class="dropdown-item text-danger"><i class="bi bi-trash"></i> Hapus</button>
                            </form>
                        </li>
                    </ul>
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
