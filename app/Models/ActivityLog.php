<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth; // <-- TAMBAHKAN INI

/** Merepresentasikan catatan aktivitas beserta relasi pengguna dan lencana. */
class ActivityLog extends Model
{
    /** Menentukan tabel dan kolom yang dapat diisi untuk catatan aktivitas. */
    protected $table = 'activity_logs';

    protected $fillable = [
        'user_id',
        'aktivitas',
        'deskripsi',
        'ip_address',
        'user_agent',
        'url',
    ];

    /** Mengubah cap waktu aktivitas menjadi objek tanggal. */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /** Menghubungkan catatan aktivitas dengan pengguna pembuatnya. */
    public function user()
    {
        return $this->belongsTo(User::class)->withDefault(['name' => 'System']);
    }

    /** Membuat catatan aktivitas beserta konteks permintaan saat ini. */
    public static function log(string $aktivitas, ?string $deskripsi = null): static
    {
        return static::create([
            'user_id'    => Auth::id(), // <-- PERBAIKAN: Gunakan Auth::id()
            'aktivitas'  => $aktivitas,
            'deskripsi'  => $deskripsi,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'url'        => request()->fullUrl(),
        ]);
    }

    /** Menyediakan HTML lencana berdasarkan jenis aktivitas. */
    public function getAktivitasBadgeAttribute(): string
    {
        $badges = [
            'Login'     => 'bg-success',
            'Logout'    => 'bg-secondary',
            'Create'    => 'bg-primary',
            'Update'    => 'bg-warning text-dark',
            'Delete'    => 'bg-danger',
            'Transaksi' => 'bg-info text-dark',
        ];

        $color = 'bg-dark';
        foreach ($badges as $key => $class) {
            if (str_contains($this->aktivitas, $key)) {
                $color = $class;
                break;
            }
        }

        return '<span class="badge ' . $color . '">' . e($this->aktivitas) . '</span>';
    }
}
