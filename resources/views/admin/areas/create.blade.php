{{-- Menampilkan formulir untuk membuat area parkir baru. --}}
@extends('layouts.admin')
@section('title', 'Tambah Area')
@section('page-title', 'Tambah Area')
@section('content')
<div class="card card-custom p-4" style="max-width:600px;">
    <h5 class="fw-bold mb-4" style="color:var(--primary);">
        <i class="bi bi-plus-circle"></i> Tambah Area Baru
    </h5>
    <form action="/admin/areas" method="POST">
        @csrf
        {{-- Memakai komponen formulir area bersama dengan halaman edit. --}}
        @include('admin.areas._form')
        <div class="d-flex gap-2 mt-4">
            <button class="btn btn-primary-custom"><i class="bi bi-save"></i> Simpan</button>
            <a href="/admin/areas" class="btn btn-outline-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection
