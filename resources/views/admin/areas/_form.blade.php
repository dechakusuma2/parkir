<div class="mb-3">
    <label class="form-label fw-semibold">Nama Area <span class="text-danger">*</span></label>
    <input type="text" name="nama_area" class="form-control"
           value="{{ old('nama_area', $area->nama_area ?? '') }}" required>
</div>

<div class="mb-3">
    <label class="form-label fw-semibold">Kapasitas <span class="text-danger">*</span></label>
    <input type="number" name="kapasitas" class="form-control" min="1"
           value="{{ old('kapasitas', $area->kapasitas ?? '') }}" required>
</div>

<div class="mb-3">
    <label class="form-label fw-semibold">Keterangan</label>
    <textarea name="keterangan" class="form-control" rows="3">{{ old('keterangan', $area->keterangan ?? '') }}</textarea>
</div>
