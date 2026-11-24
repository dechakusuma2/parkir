{{-- Menyediakan informasi profil admin dan formulir pengubahan nama atau kata sandi. --}}
@extends('layouts.admin')
@section('title', 'Pengaturan Akun')
@section('page-title', 'Pengaturan Akun')
@section('content')
<div class="admin-page-heading"><div><p class="admin-eyebrow">Akun Anda</p><h2 class="admin-heading">Pengaturan Akun</h2></div></div>
<div class="admin-account-grid">
    <section class="admin-card admin-account-profile"><span class="admin-account-avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</span><h3>{{ $user->name }}</h3><p>{{ $user->email }}</p><span class="admin-role-badge">Administrator</span><div class="admin-account-note"><i data-lucide="shield-check"></i><span>Akses akun ini memiliki hak administrator.</span></div></section>
    <section class="admin-card"><div class="admin-card-header"><div><p class="admin-eyebrow">Informasi pribadi</p><h3>Perbarui profil</h3></div></div><form method="POST" action="{{ url('/admin/pengaturan-akun') }}" class="admin-form">@csrf @method('PATCH')<label>Nama lengkap<input name="name" value="{{ old('name', $user->name) }}" required></label><label>Email / username<input value="{{ $user->email }}" disabled></label><label>Role<input value="Admin" disabled></label><div class="admin-form-divider"><p class="admin-eyebrow">Ganti password</p><small>Kosongkan jika tidak ingin mengganti password.</small></div><label>Password lama<input type="password" name="current_password" autocomplete="current-password"></label><label>Password baru<input type="password" name="password" autocomplete="new-password"></label><label>Konfirmasi password baru<input type="password" name="password_confirmation" autocomplete="new-password"></label><button class="admin-button primary" type="submit"><i data-lucide="save"></i>Simpan perubahan</button></form></section>
</div>
@endsection
