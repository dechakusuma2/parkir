<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /** Membuat tabel tarif berdasarkan jenis kendaraan dan biaya per jam. */
    public function up(): void
    {
        Schema::create('tarifs', function (Blueprint $table) {
            $table->id();
            $table->string('jenis_kendaraan')->unique();
            $table->decimal('tarif_per_jam', 10, 2);
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /** Menghapus tabel tarif ketika migrasi dibatalkan. */
    public function down(): void
    {
        Schema::dropIfExists('tarifs');
    }
};
