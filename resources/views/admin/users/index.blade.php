@extends('layouts.app')
@section('title', 'User Management')
@section('page-title', 'User Management')

@section('sidebar') @show

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="fw-bold mb-0" style="color:var(--primary);">
        <i class="bi bi-people-fill"></i> Daftar User
    </h5>
    <a href="/admin/users/create" class="btn btn-primary-custom">
        <i class="bi bi-plus-circle"></i> Tambah User
    </a>
</div>

<div class="card card-custom p-4">
    <div class="table-responsive">
        <table class="table table-custom table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Terdaftar</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $i => $user)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold"
                                 style="width:40px;height:40px;background:var(--primary);">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <strong>{{ $user->name }}</strong>
                        </div>
                    </td>
                    <td>{{ $user->email }}</td>
                    <td>{!! $user->roleBadge !!}</td>
                    <td>{{ $user->created_at->format('d/m/Y') }}</td>
                    <td class="text-center">
                        <a href="/admin/users/{{ $user->id }}/edit" class="btn btn-sm btn-warning">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form action="/admin/users/{{ $user->id }}" method="POST" class="d-inline"
                              onsubmit="return confirm('Yakin hapus user ini?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center py-4 text-muted">Belum ada data user</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
