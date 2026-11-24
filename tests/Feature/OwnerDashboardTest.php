<?php

namespace Tests\Feature;

use App\Models\Area;
use App\Models\Kendaraan;
use App\Models\Tarif;
use App\Models\Transaksi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/** Menguji penyajian metrik dashboard pemilik melalui HTTP. */
class OwnerDashboardTest extends TestCase
{
    use RefreshDatabase;

    /** Memastikan dashboard owner merender metrik transaksi dan status area nyata. */
    public function test_owner_dashboard_renders_real_metrics_and_area_status(): void
    {
        $owner = User::factory()->create([
            'role' => 'owner',
            'status' => 'aktif',
        ]);

        $kendaraan = Kendaraan::create([
            'plat_nomor' => 'B1234TST',
            'jenis' => 'mobil',
            'warna' => 'Putih',
        ]);

        $tarif = Tarif::create([
            'jenis_kendaraan' => 'mobil',
            'tarif_per_jam' => 5000,
            'keterangan' => 'Mobil',
        ]);

        $area = Area::create([
            'nama_area' => 'Area Utama',
            'kapasitas' => 5,
            'terisi' => 2,
            'keterangan' => 'Area utama',
        ]);

        Transaksi::create([
            'kode_transaksi' => 'SP202610040001',
            'kendaraan_id' => $kendaraan->id,
            'area_id' => $area->id,
            'tarif_id' => $tarif->id,
            'petugas_id' => $owner->id,
            'jam_masuk' => now()->subHours(3),
            'status' => 'masuk',
            'total_bayar' => 15000,
        ]);

        $response = $this->actingAs($owner)->get('/owner/dashboard');

        $response->assertOk();
        $response->assertSee('Pendapatan Hari Ini');
        $response->assertSee('Status Area');
        $response->assertSee('Area Utama');
    }
}
