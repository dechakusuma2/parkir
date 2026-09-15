<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tarif extends Model
{
    protected $table = 'tarifs';

    protected $fillable = [
        'jenis_kendaraan',
        'tarif_per_jam',
        'keterangan',
    ];

    protected $casts = [
        'tarif_per_jam' => 'decimal:2',
        'created_at'    => 'datetime:d-m-Y H:i',
        'updated_at'    => 'datetime:d-m-Y H:i',
    ];

    // ===== RELASI =====
    public function transaksi()
    {
        return $this->hasMany(Transaksi::class);
    }

    // ===== HELPER =====
    public function hitungTotal($jam)
    {
        $jam = max(1, (int) ceil($jam)); // minimal 1 jam
        return $jam * $this->tarif_per_jam;
    }

    public function getTarifFormatAttribute()
    {
        return 'Rp ' . number_format($this->tarif_per_jam, 0, ',', '.');
    }

    public function getJenisLabelAttribute()
    {
        $label = [
            'motor' => '🏍️ Motor',
            'mobil' => '🚗 Mobil',
            'truk'  => '🚚 Truk',
        ];
        return $label[$this->jenis_kendaraan] ?? ucfirst($this->jenis_kendaraan);
    }
}
