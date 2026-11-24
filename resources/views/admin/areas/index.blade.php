{{-- Menampilkan daftar area, kapasitas terpakai, ketersediaan, dan aksi admin. --}}
@extends('layouts.admin')
@section('title', 'Area Parkir')
@section('page-title', 'Area Parkir')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="fw-bold mb-0" style="color:var(--primary);">
        <i class="bi bi-geo-alt-fill"></i> Daftar Area Parkir
    </h5>
    <a href="/admin/areas/create" class="btn btn-primary-custom">
        <i class="bi bi-plus-circle"></i> Tambah Area
    </a>
</div>

<div class="card card-custom p-4">
    {{-- Tabel memperlihatkan kapasitas setiap area dan tindakan edit atau hapus. --}}
    <div class="table-responsive">
        <table class="table table-custom table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nama Area</th>
                    <th>Kapasitas</th>
                    <th>Terisi</th>
                    <th>Sisa</th>
                    <th>Status</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($areas as $i => $area)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td><strong>{{ $area->nama_area }}</strong></td>
                    <td>{{ $area->kapasitas }}</td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="progress flex-grow-1" style="height:8px;width:80px;">
                                <div class="progress-bar {{ $area->percentage > 75 ? 'bg-warning' : 'bg-success' }}"
                                     style="width:{{ $area->percentage }}%"></div>
                            </div>
                            <span>{{ $area->terisi }}</span>
                        </div>
                    </td>
                    <td>{{ $area->sisa }}</td>
                    <td>{!! $area->statusBadge !!}</td>
                    <td class="text-center">
                        <a href="/admin/areas/{{ $area->id }}/edit" class="btn btn-sm btn-warning">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form action="/admin/areas/{{ $area->id }}" method="POST" class="d-inline"
                              onsubmit="return confirm('Hapus area ini?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center py-4 text-muted">Belum ada area</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
