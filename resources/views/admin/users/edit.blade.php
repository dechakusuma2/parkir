{{-- Menampilkan formulir untuk memperbarui data pengguna yang dipilih. --}}
@extends('layouts.admin')
@section('title', 'Edit User')
@section('page-title', 'Edit User')
@section('content')
<div class="card card-custom p-4" style="max-width:600px;">
    <h5 class="fw-bold mb-4" style="color:var(--primary);">
        <i class="bi bi-pencil-square"></i> Edit User
    </h5>
    <form action="/admin/users/{{ $user->id }}" method="POST">
        @csrf @method('PUT')
        {{-- Mengisi komponen pengguna bersama dengan data akun saat ini. --}}
        @include('admin.users._form')
        <div class="d-flex gap-2 mt-4">
            <button class="btn btn-primary-custom"><i class="bi bi-save"></i> Update</button>
            <a href="/admin/users" class="btn btn-outline-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection
