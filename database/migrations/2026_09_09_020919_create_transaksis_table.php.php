<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Membuat tabel transaksi parkir beserta referensi kendaraan, area, tarif, dan petugas.
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('transaksis', function (Blueprint $table) {
            $table->id();
            $table->string('kode_transaksi')->unique();
            $table->foreignId('kendaraan_id')->constrained('kendaraans');
            $table->foreignId('area_id')->constrained('areas');
            $table->foreignId('tarif_id')->constrained('tarifs');
            $table->foreignId('petugas_id')->constrained('users');
            $table->dateTime('jam_masuk');
            $table->dateTime('jam_keluar')->nullable();
            $table->unsignedInteger('durasi_jam')->nullable();
            $table->decimal('total_bayar', 10, 2)->default(0);
            $table->string('metode_bayar')->nullable();
            $table->enum('status', ['masuk', 'keluar'])->default('masuk');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    // Menghapus tabel transaksi saat migrasi dibatalkan.
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksis');
    }
};
