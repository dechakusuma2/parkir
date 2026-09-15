<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kendaraan extends Model
{
    protected $table = 'kendaraans';

    protected $fillable = [
        'plat_nomor',
        'jenis',
        'merk',
        'warna',
        'pemilik',
        'no_telp',
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

    // ===== HELPER =====
    public function getPlatFormatAttribute()
    {
        return strtoupper(str_replace(['-', ' '], '', $this->plat_nomor));
    }

    public function getJenisLabelAttribute()
    {
        $label = [
            'motor' => '🏍️ Motor',
            'mobil' => '🚗 Mobil',
            'truk'  => '🚚 Truk',
        ];
        return $label[$this->jenis] ?? ucfirst($this->jenis);
    }

    public function isInside()
    {
        return $this->transaksi()->where('status', 'masuk')->exists();
    }

    public function transaksiAktif()
    {
        return $this->transaksi()->where('status', 'masuk')->first();
    }

    // ===== SCOPE =====
    public function scopeMotor($query)
    {
        return $query->where('jenis', 'motor');
    }

    public function scopeMobil($query)
    {
        return $query->where('jenis', 'mobil');
    }

    public function scopeSearch($query, $keyword)
    {
        return $query->where(function($q) use ($keyword) {
            $q->where('plat_nomor', 'like', "%$keyword%")
              ->orWhere('pemilik', 'like', "%$keyword%")
              ->orWhere('merk', 'like', "%$keyword%");
        });
    }
}
