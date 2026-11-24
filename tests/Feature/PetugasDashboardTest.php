<?php

namespace Tests\Feature;

use App\Models\Area;
use App\Models\Kendaraan;
use App\Models\Tarif;
use App\Models\Transaksi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/** Menguji tampilan dashboard dan alur pencatatan kendaraan petugas. */
class PetugasDashboardTest extends TestCase
{
    use RefreshDatabase;

    /** Memastikan data pratinjau keluar dari sesi dimuat sebagai transaksi. */
    public function test_dashboard_handles_session_exit_preview_array(): void
    {
        $petugas = User::factory()->create([
            'role' => 'petugas',
            'status' => 'aktif',
        ]);

        $kendaraan = Kendaraan::create([
            'plat_nomor' => 'B1234ABC',
            'jenis' => 'motor',
            'warna' => 'Hitam',
        ]);

        $tarif = Tarif::create([
            'jenis_kendaraan' => 'motor',
            'tarif_per_jam' => 2000,
            'keterangan' => 'Motor',
        ]);

        $area = Area::create([
            'nama_area' => 'Area Utama',
            'kapasitas' => 10,
            'terisi' => 0,
            'keterangan' => 'Area utama',
        ]);

        $transaksi = Transaksi::create([
            'kode_transaksi' => 'SP202610040001',
            'kendaraan_id' => $kendaraan->id,
            'area_id' => $area->id,
            'tarif_id' => $tarif->id,
            'petugas_id' => $petugas->id,
            'jam_masuk' => now()->subHours(2),
            'status' => 'masuk',
        ]);

        $response = $this->actingAs($petugas)
            ->withSession(['exit_preview' => ['id' => $transaksi->id]])
            ->get('/petugas/dashboard?tab=keluar');

        $response->assertOk();
        $response->assertSeeText('SP202610040001');
    }

    /** Memastikan proses kendaraan masuk menghasilkan tiket parkir yang dapat dicetak. */
    public function test_vehicle_entry_redirects_to_printable_parking_ticket(): void
    {
        $petugas = User::create([
            'name' => 'Petugas Test',
            'email' => 'petugas-test@example.test',
            'password' => bcrypt('password'),
            'role' => 'petugas',
            'status' => 'aktif',
        ]);

        Tarif::create([
            'jenis_kendaraan' => 'motor',
            'tarif_per_jam' => 2000,
            'keterangan' => 'Motor',
        ]);

        Area::create([
            'nama_area' => 'Area Motor',
            'kapasitas' => 10,
            'terisi' => 0,
            'keterangan' => 'Parkir motor',
        ]);

        $response = $this->actingAs($petugas)
            ->followingRedirects()
            ->post(route('petugas.masuk'), [
                'plat_nomor' => 'B 1234 ABC',
                'jenis' => 'motor',
                'warna' => 'Hitam',
            ]);

        $response->assertOk();
        $response->assertSeeText('Tiket masuk parkir');
        $response->assertSeeText('B 1234 ABC');
        $response->assertSeeText('Simpan tiket ini dan tunjukkan saat kendaraan keluar.');
        $response->assertDontSeeText('Total bayar');
        $this->assertDatabaseHas('transaksis', ['status' => 'masuk']);
    }
}
