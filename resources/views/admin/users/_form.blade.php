{{-- Komponen bersama untuk memasukkan identitas, kata sandi, dan peran akun. --}}
<div class="mb-3">
    <label class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
    <input type="text" name="name" class="form-control" value="{{ old('name', $user->name ?? '') }}" required>
</div>

<div class="mb-3">
    <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
    <input type="email" name="email" class="form-control" value="{{ old('email', $user->email ?? '') }}" required>
</div>

<div class="mb-3">
    <label class="form-label fw-semibold">
        Password {{ isset($user) ? '(kosongkan jika tidak diubah)' : '' }}
        @if(!isset($user)) <span class="text-danger">*</span> @endif
    </label>
    <input type="password" name="password" class="form-control" {{ isset($user) ? '' : 'required' }}>
</div>

<div class="mb-3">
    <label class="form-label fw-semibold">Role <span class="text-danger">*</span></label>
    <select name="role" class="form-select" required>
        <option value="">-- Pilih Role --</option>
        <option value="owner" {{ old('role', $user->role ?? '') == 'owner' ? 'selected' : '' }}>Owner</option>
        <option value="admin" {{ old('role', $user->role ?? '') == 'admin' ? 'selected' : '' }}>Admin</option>
        <option value="petugas" {{ old('role', $user->role ?? '') == 'petugas' ? 'selected' : '' }}>Petugas</option>
    </select>
</div>
