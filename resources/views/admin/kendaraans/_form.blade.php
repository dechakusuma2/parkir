{{-- Komponen bersama untuk memasukkan identitas dan informasi pemilik kendaraan. --}}
<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label fw-semibold">Plat Nomor <span class="text-danger">*</span></label>
        <input type="text" name="plat_nomor" class="form-control text-uppercase"
               value="{{ old('plat_nomor', $kendaraan->plat_nomor ?? '') }}" required>
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label fw-semibold">Jenis Kendaraan <span class="text-danger">*</span></label>
        <select name="jenis" class="form-select" required>
            <option value="">-- Pilih --</option>
            <option value="motor" {{ old('jenis', $kendaraan->jenis ?? '') == 'motor' ? 'selected' : '' }}>Motor</option>
            <option value="mobil" {{ old('jenis', $kendaraan->jenis ?? '') == 'mobil' ? 'selected' : '' }}>Mobil</option>
            <option value="truk" {{ old('jenis', $kendaraan->jenis ?? '') == 'truk' ? 'selected' : '' }}>Truk</option>
        </select>
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label fw-semibold">Merk</label>
        <input type="text" name="merk" class="form-control"
               value="{{ old('merk', $kendaraan->merk ?? '') }}" placeholder="Honda, Toyota, dll">
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label fw-semibold">Warna <span class="text-danger">*</span></label>
        <input type="text" name="warna" class="form-control"
               value="{{ old('warna', $kendaraan->warna ?? '') }}" required>
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label fw-semibold">Nama Pemilik <span class="text-danger">*</span></label>
        <input type="text" name="pemilik" class="form-control"
               value="{{ old('pemilik', $kendaraan->pemilik ?? '') }}" required>
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label fw-semibold">No. Telepon</label>
        <input type="text" name="no_telp" class="form-control"
               value="{{ old('no_telp', $kendaraan->no_telp ?? '') }}">
    </div>
</div>
