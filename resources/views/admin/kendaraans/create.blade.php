@extends('layouts.app')
@section('title', 'Tambah Kendaraan')
@section('page-title', 'Tambah Kendaraan')
@section('sidebar') @show

@section('content')
<div class="card card-custom p-4" style="max-width:700px;">
    <h5 class="fw-bold mb-4" style="color:var(--primary);">
        <i class="bi bi-plus-circle"></i> Tambah Kendaraan
    </h5>
    <form action="/admin/kendaraans" method="POST">
        @csrf
        @include('admin.kendaraans._form')
        <div class="d-flex gap-2 mt-4">
            <button class="btn btn-primary-custom"><i class="bi bi-save"></i> Simpan</button>
            <a href="/admin/kendaraans" class="btn btn-outline-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection
