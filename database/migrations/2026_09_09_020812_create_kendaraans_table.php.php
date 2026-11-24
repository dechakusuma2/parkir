<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /** Membuat tabel data kendaraan beserta nomor polisi dan informasi pemilik. */
    public function up(): void
    {
        Schema::create('kendaraans', function (Blueprint $table) {
            $table->id();
            $table->string('jenis'); // Motor, Mobil, Truck
            $table->string('merk')->nullable();
            $table->string('warna')->nullable();
            $table->string('plat_nomor')->unique();
            $table->string('pemilik')->nullable();
            $table->string('no_telp')->nullable();
            $table->timestamps();
        });
    }

    /** Menghapus tabel kendaraan ketika migrasi dibatalkan. */
    public function down(): void
    {
        Schema::dropIfExists('kendaraans');
    }
};
