<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{User, Tarif, Area};
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        User::updateOrCreate(['email'=>'owner@smart.com'], ['name'=>'Owner','password'=>Hash::make('owner123'),'role'=>'owner']);
        User::updateOrCreate(['email'=>'admin@smart.com'], ['name'=>'Admin','password'=>Hash::make('admin123'),'role'=>'admin']);
        User::updateOrCreate(['email'=>'petugas@smart.com'], ['name'=>'Petugas','password'=>Hash::make('petugas123'),'role'=>'petugas']);

        Tarif::updateOrCreate(['jenis_kendaraan'=>'motor'], ['tarif_per_jam'=>2000]);
        Tarif::updateOrCreate(['jenis_kendaraan'=>'mobil'], ['tarif_per_jam'=>5000]);
        Tarif::updateOrCreate(['jenis_kendaraan'=>'truk'], ['tarif_per_jam'=>10000]);

        Area::updateOrCreate(['nama_area'=>'Area A - Motor'], ['kapasitas'=>100]);
        Area::updateOrCreate(['nama_area'=>'Area B - Mobil'], ['kapasitas'=>50]);
    }
}
