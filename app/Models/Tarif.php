<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** Merepresentasikan tarif berdasarkan jenis kendaraan dan biaya per jamnya. */
class Tarif extends Model
{
    /** Menentukan tabel dan kolom tarif yang dapat diisi. */
    protected $table = 'tarifs';

    protected $fillable = [
        'jenis_kendaraan',
        'tarif_per_jam',
        'tarif_maksimal_harian',
        'status',
        'keterangan',
    ];

    /** Menjaga nominal tarif dan cap waktu dalam format yang sesuai. */
    protected $casts = [
        'tarif_per_jam' => 'decimal:2',
        'tarif_maksimal_harian' => 'decimal:2',
        'created_at' => 'datetime:d-m-Y H:i',
        'updated_at' => 'datetime:d-m-Y H:i',
    ];

    // ===== RELASI =====
    /** Menghubungkan tarif dengan transaksi yang menggunakan tarif tersebut. */
    public function transaksi()
    {
        return $this->hasMany(Transaksi::class);
    }

    // ===== HELPER =====
    /** Menghitung biaya parkir sesuai jumlah jam dengan minimum satu jam. */
    public function hitungTotal($jam)
    {
        $jam = max(1, (int) ceil($jam)); // minimal 1 jam

        return $jam * $this->tarif_per_jam;
    }

    /** Memformat tarif per jam sebagai nilai mata uang rupiah. */
    public function getTarifFormatAttribute()
    {
        return 'Rp '.number_format($this->tarif_per_jam, 0, ',', '.');
    }

    /** Mengubah kode jenis kendaraan menjadi label yang mudah dibaca. */
    public function getJenisLabelAttribute()
    {
        $label = [
            'motor' => '🏍️ Motor',
            'mobil' => '🚗 Mobil',
            'truk' => '🚚 Truk',
        ];

        return $label[$this->jenis_kendaraan] ?? ucfirst($this->jenis_kendaraan);
    }
}
