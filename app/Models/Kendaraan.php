<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** Merepresentasikan data kendaraan dan pencarian transaksi terkaitnya. */
class Kendaraan extends Model
{
    /** Menentukan tabel dan kolom kendaraan yang dapat diisi. */
    protected $table = 'kendaraans';

    protected $fillable = [
        'plat_nomor',
        'jenis',
        'merk',
        'warna',
        'pemilik',
        'no_telp',
        'foto_path',
    ];

    protected $casts = [
        'created_at' => 'datetime:d-m-Y H:i',
        'updated_at' => 'datetime:d-m-Y H:i',
    ];

    // ===== RELASI =====
    public function transaksi()
    {
        return $this->hasMany(Transaksi::class);
    }

    public function transaksis()
    {
        return $this->transaksi();
    }

    // ===== HELPER =====
    /** Memformat nomor polisi agar mudah dibandingkan atau ditampilkan. */
    public function getPlatFormatAttribute()
    {
        return strtoupper(str_replace(['-', ' '], '', $this->plat_nomor));
    }

    /** Mengubah kode jenis kendaraan menjadi label yang mudah dibaca. */
    public function getJenisLabelAttribute()
    {
        $label = [
            'motor' => '🏍️ Motor',
            'mobil' => '🚗 Mobil',
            'truk' => '🚚 Truk',
        ];

        return $label[$this->jenis] ?? ucfirst($this->jenis);
    }

    /** Memeriksa apakah kendaraan memiliki transaksi parkir aktif. */
    public function isInside()
    {
        return $this->transaksi()->where('status', 'masuk')->exists();
    }

    /** Mengambil transaksi parkir aktif milik kendaraan. */
    public function transaksiAktif()
    {
        return $this->transaksi()->where('status', 'masuk')->first();
    }

    // ===== SCOPE =====
    /** Membatasi kueri hanya pada kendaraan jenis motor. */
    public function scopeMotor($query)
    {
        return $query->where('jenis', 'motor');
    }

    /** Membatasi kueri hanya pada kendaraan jenis mobil. */
    public function scopeMobil($query)
    {
        return $query->where('jenis', 'mobil');
    }

    /** Mencari kendaraan berdasarkan nomor polisi, pemilik, atau merek. */
    public function scopeSearch($query, $keyword)
    {
        return $query->where(function ($q) use ($keyword) {
            $q->where('plat_nomor', 'like', "%$keyword%")
                ->orWhere('pemilik', 'like', "%$keyword%")
                ->orWhere('merk', 'like', "%$keyword%");
        });
    }
}
