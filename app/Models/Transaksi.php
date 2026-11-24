<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

/** Merepresentasikan transaksi parkir, perhitungan biaya, dan status kendaraan. */
class Transaksi extends Model
{
    /** Menentukan tabel dan kolom transaksi yang dapat diisi. */
    protected $table = 'transaksis';

    protected $fillable = [
        'kode_transaksi',
        'kendaraan_id',
        'area_id',
        'tarif_id',
        'petugas_id',
        'jam_masuk',
        'jam_keluar',
        'durasi_jam',
        'total_bayar',
        'metode_bayar',
        'status',
        'keterangan',
    ];

    protected $casts = [
        'jam_masuk' => 'datetime',
        'jam_keluar' => 'datetime',
        'total_bayar' => 'decimal:2',
        'durasi_jam' => 'integer',
    ];

    // ==========================================
    // RELASI
    // ==========================================
    /** Menghubungkan transaksi dengan kendaraan yang diparkir. */
    public function kendaraan(): BelongsTo
    {
        return $this->belongsTo(Kendaraan::class);
    }

    /** Menghubungkan transaksi dengan area parkir yang digunakan. */
    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class);
    }

    /** Menghubungkan transaksi dengan tarif kendaraan yang berlaku. */
    public function tarif(): BelongsTo
    {
        return $this->belongsTo(Tarif::class);
    }

    /** Menghubungkan transaksi dengan petugas yang mencatatnya. */
    public function petugas(): BelongsTo
    {
        return $this->belongsTo(User::class, 'petugas_id');
    }

    // ==========================================
    // HELPER / LOGIKA BISNIS
    // ==========================================

    /**
     * Hitung durasi parkir dalam jam (minimal 1 jam, dibulatkan ke atas)
     */
    public function hitungDurasi(): int
    {
        $waktuKeluar = $this->jam_keluar ? Carbon::parse($this->jam_keluar) : now();
        $waktuMasuk = Carbon::parse($this->jam_masuk);

        $menit = $waktuMasuk->diffInMinutes($waktuKeluar);

        return max(1, (int) ceil($menit / 60)); // Minimal 1 jam, dibulatkan ke atas
    }

    /**
     * Hitung total bayar berdasarkan durasi dan tarif
     */
    public function hitungTotal(): float
    {
        if (! $this->tarif) {
            return 0.00;
        }

        return $this->hitungDurasi() * $this->tarif->tarif_per_jam;
    }

    /**
     * Proses kendaraan keluar
     */
    public function prosesKeluar(string $metodeBayar = 'tunai'): self
    {
        $durasi = $this->hitungDurasi();
        $total = $this->hitungTotal();

        $this->update([
            'jam_keluar' => now(),
            'durasi_jam' => $durasi,
            'total_bayar' => $total,
            'metode_bayar' => $metodeBayar,
            'status' => 'keluar',
        ]);

        // Kembalikan kapasitas area (KURANGI jumlah terisi)
        if ($this->area) {
            $this->area->decrement('terisi');
        }

        return $this;
    }

    // ==========================================
    // ACCESSORS (Format Tampilan)
    // ==========================================
    /** Memformat nilai pembayaran sebagai mata uang rupiah. */
    public function getTotalFormatAttribute(): string
    {
        return 'Rp '.number_format($this->total_bayar ?? 0, 0, ',', '.');
    }

    /** Menyediakan lencana tampilan berdasarkan status transaksi. */
    public function getStatusBadgeAttribute(): string
    {
        if ($this->status === 'masuk') {
            return '<span class="badge bg-success">Sedang Parkir</span>';
        }

        return '<span class="badge bg-secondary">Selesai</span>';
    }

    // ==========================================
    // QUERY SCOPES
    // ==========================================
    /** Membatasi kueri pada transaksi kendaraan yang masih parkir. */
    public function scopeAktif(Builder $query): Builder
    {
        return $query->where('status', 'masuk');
    }

    /** Membatasi kueri pada transaksi yang sudah selesai. */
    public function scopeSelesai(Builder $query): Builder
    {
        return $query->where('status', 'keluar');
    }

    /** Membatasi kueri pada transaksi yang dibuat hari ini. */
    public function scopeHariIni(Builder $query): Builder
    {
        return $query->whereDate('created_at', today());
    }

    // ==========================================
    // STATIC HELPER
    // ==========================================
    /** Membuat kode transaksi unik dengan awalan, tanggal, urutan, dan karakter acak. */
    public static function generateKode(): string
    {
        $prefix = 'SP';
        $date = now()->format('Ymd');
        $last = static::whereDate('created_at', today())->count() + 1;
        $random = strtoupper(Str::random(4));

        return $prefix.$date.str_pad($last, 4, '0', STR_PAD_LEFT).$random;
    }
}
