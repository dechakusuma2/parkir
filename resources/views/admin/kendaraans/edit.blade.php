@extends('layouts.app')
@section('title', 'Edit Kendaraan')
@section('page-title', 'Edit Kendaraan')
@section('sidebar') @show

@section('content')
<div class="card card-custom p-4" style="max-width:700px;">
    <h5 class="fw-bold mb-4" style="color:var(--primary);">
        <i class="bi bi-pencil-square"></i> Edit Kendaraan
    </h5>
    <form action="/admin/kendaraans/{{ $kendaraan->id }}" method="POST">
        @csrf @method('PUT')
        @include('admin.kendaraans._form')
        <div class="d-flex gap-2 mt-4">
            <button class="btn btn-primary-custom"><i class="bi bi-save"></i> Update</button>
            <a href="/admin/kendaraans" class="btn btn-outline-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection
