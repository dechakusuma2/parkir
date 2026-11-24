{{-- Komponen bersama untuk mengisi jenis kendaraan, tarif per jam, dan keterangan. --}}
<div class="mb-3">
    <label class="form-label fw-semibold">Jenis Kendaraan <span class="text-danger">*</span></label>
    <select name="jenis_kendaraan" class="form-select" required>
        <option value="">-- Pilih Jenis --</option>
        <option value="motor" {{ old('jenis_kendaraan', $tarif->jenis_kendaraan ?? '') == 'motor' ? 'selected' : '' }}>Motor</option>
        <option value="mobil" {{ old('jenis_kendaraan', $tarif->jenis_kendaraan ?? '') == 'mobil' ? 'selected' : '' }}>Mobil</option>
        <option value="truk" {{ old('jenis_kendaraan', $tarif->jenis_kendaraan ?? '') == 'truk' ? 'selected' : '' }}>Truk</option>
    </select>
</div>

<div class="mb-3">
    <label class="form-label fw-semibold">Tarif per Jam (Rp) <span class="text-danger">*</span></label>
    <input type="number" name="tarif_per_jam" class="form-control" min="0" step="100"
           value="{{ old('tarif_per_jam', $tarif->tarif_per_jam ?? '') }}" required>
</div>

<div class="mb-3">
    <label class="form-label fw-semibold">Keterangan</label>
    <textarea name="keterangan" class="form-control" rows="3">{{ old('keterangan', $tarif->keterangan ?? '') }}</textarea>
</div>
