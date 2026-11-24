{{-- Menampilkan formulir untuk memperbarui tarif parkir yang dipilih. --}}
@extends('layouts.admin')
@section('title', 'Edit Tarif')
@section('page-title', 'Edit Tarif')
@section('content')
<div class="card card-custom p-4" style="max-width:600px;">
    <h5 class="fw-bold mb-4" style="color:var(--primary);">
        <i class="bi bi-pencil-square"></i> Edit Tarif
    </h5>
    <form action="/admin/tarifs/{{ $tarif->id }}" method="POST">
        @csrf @method('PUT')
        {{-- Mengisi komponen tarif bersama dengan nilai yang sedang tersimpan. --}}
        @include('admin.tarifs._form')
        <div class="d-flex gap-2 mt-4">
            <button class="btn btn-primary-custom"><i class="bi bi-save"></i> Update</button>
            <a href="/admin/tarifs" class="btn btn-outline-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection
