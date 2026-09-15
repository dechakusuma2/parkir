@extends('layouts.app')
@section('title', 'Edit Area')
@section('page-title', 'Edit Area')
@section('sidebar') @show

@section('content')
<div class="card card-custom p-4" style="max-width:600px;">
    <h5 class="fw-bold mb-4" style="color:var(--primary);">
        <i class="bi bi-pencil-square"></i> Edit Area
    </h5>
    <form action="/admin/areas/{{ $area->id }}" method="POST">
        @csrf @method('PUT')
        @include('admin.areas._form')
        <div class="d-flex gap-2 mt-4">
            <button class="btn btn-primary-custom"><i class="bi bi-save"></i> Update</button>
            <a href="/admin/areas" class="btn btn-outline-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection
