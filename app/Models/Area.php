<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/** Merepresentasikan kapasitas area parkir dan helper ketersediaannya. */
class Area extends Model
{
    use HasFactory;

    /** Menentukan tabel dan kolom area yang dapat diisi. */
    protected $table = 'areas';

    protected $fillable = [
        'nama_area',
        'kapasitas',
        'terisi',
        'keterangan',
    ];

    /** Menjaga kapasitas dan jumlah terisi sebagai bilangan bulat. */
    protected $casts = [
        'kapasitas' => 'integer',
        'terisi' => 'integer',
    ];

    /** Memeriksa apakah jumlah kendaraan telah mencapai kapasitas area. */
    public function isFull(): bool
    {
        return $this->terisi >= $this->kapasitas;
    }

    /** Menghitung sisa slot parkir yang tersedia. */
    public function getSisaAttribute(): int
    {
        return max(0, $this->kapasitas - $this->terisi);
    }

    /** Menghitung persentase kapasitas area yang telah terisi. */
    public function getPercentageAttribute(): int
    {
        if ($this->kapasitas <= 0) {
            return 0;
        }

        return min(100, (int) round(($this->terisi / $this->kapasitas) * 100));
    }

    /** Menampilkan lencana status ketersediaan area. */
    public function getStatusBadgeAttribute(): string
    {
        if ($this->isFull()) {
            return '<span class="badge bg-danger">Penuh</span>';
        }

        if ($this->sisa > 0) {
            return '<span class="badge bg-success">Tersedia</span>';
        }

        return '<span class="badge bg-secondary">Kosong</span>';
    }

    /** Memilih area tersedia yang sesuai dengan jenis kendaraan. */
    public static function getAreaForJenis(string $jenis): ?self
    {
        $jenis = strtolower(trim($jenis));
        $keywords = match ($jenis) {
            'motor' => ['motor'],
            'mobil' => ['mobil', 'car'],
            'truk' => ['truk', 'truck', 'box'],
            default => [$jenis],
        };

        $query = self::whereColumn('kapasitas', '>', 'terisi');
        $query = $query->where(function ($builder) use ($keywords) {
            foreach ($keywords as $keyword) {
                $builder->orWhereRaw('LOWER(nama_area) LIKE ?', ['%'.strtolower($keyword).'%']);
            }
        });

        $area = $query->orderBy('nama_area')->first();

        if ($area) {
            return $area;
        }

        return self::whereColumn('kapasitas', '>', 'terisi')->orderBy('nama_area')->first();
    }
}
