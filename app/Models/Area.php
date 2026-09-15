<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    use HasFactory;

    protected $table = 'areas';

    protected $fillable = [
        'nama_area',
        'kapasitas',
        'terisi',
        'keterangan',
    ];

    protected $casts = [
        'kapasitas' => 'integer',
        'terisi' => 'integer',
    ];

    public function isFull(): bool
    {
        return $this->terisi >= $this->kapasitas;
    }

    public function getSisaAttribute(): int
    {
        return max(0, $this->kapasitas - $this->terisi);
    }
}
