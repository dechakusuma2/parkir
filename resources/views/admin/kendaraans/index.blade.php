@extends('layouts.app')
@section('title', 'Kendaraan')
@section('page-title', 'Data Kendaraan')
@section('sidebar') @show

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="fw-bold mb-0" style="color:var(--primary);">
        <i class="bi bi-car-front-fill"></i> Data Kendaraan
    </h5>
    <a href="/admin/kendaraans/create" class="btn btn-primary-custom">
        <i class="bi bi-plus-circle"></i> Tambah Kendaraan
    </a>
</div>

<div class="card card-custom p-4 mb-4">
    <form method="GET" action="/admin/kendaraans">
        <div class="input-group">
            <input type="text" name="search" class="form-control"
                   placeholder="Cari plat nomor, pemilik, atau merk..."
                   value="{{ request('search') }}">
            <button class="btn btn-primary-custom"><i class="bi bi-search"></i> Cari</button>
        </div>
    </form>
</div>

<div class="card card-custom p-4">
    <div class="table-responsive">
        <table class="table table-custom table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Plat Nomor</th>
                    <th>Jenis</th>
                    <th>Merk</th>
                    <th>Warna</th>
                    <th>Pemilik</th>
                    <th>Status</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($kendaraans as $i => $k)
                <tr>
                    <td>{{ $kendaraans->firstItem() + $i }}</td>
                    <td><code class="fs-6">{{ strtoupper($k->plat_nomor) }}</code></td>
                    <td>{{ $k->jenisLabel }}</td>
                    <td>{{ $k->merk ?? '-' }}</td>
                    <td>{{ $k->warna }}</td>
                    <td>{{ $k->pemilik }}</td>
                    <td>
                        @if($k->isInside())
                            <span class="badge bg-success">Sedang Parkir</span>
                        @else
                            <span class="badge bg-secondary">Tidak Aktif</span>
                        @endif
                    </td>
                    <td class="text-center">
                        <a href="/admin/kendaraans/{{ $k->id }}/edit" class="btn btn-sm btn-warning">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form action="/admin/kendaraans/{{ $k->id }}" method="POST" class="d-inline"
                              onsubmit="return confirm('Hapus kendaraan ini?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="text-center py-4 text-muted">Belum ada data kendaraan</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($kendaraans->hasPages())
        <div class="mt-3">{{ $kendaraans->links() }}</div>
    @endif
</div>
@endsection
