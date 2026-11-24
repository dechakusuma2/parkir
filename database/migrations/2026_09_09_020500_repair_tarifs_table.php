<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /** Membuat tabel tarif hanya jika migrasi sebelumnya belum membuatnya. */
    public function up(): void
    {
        if (! Schema::hasTable('tarifs')) {
            Schema::create('tarifs', function (Blueprint $table) {
                $table->id();
                $table->string('jenis_kendaraan')->unique();
                $table->decimal('tarif_per_jam', 10, 2);
                $table->text('keterangan')->nullable();
                $table->timestamps();
            });
        }
    }

    /** Tidak menghapus tabel karena migrasi utama bertanggung jawab atas tabel tarif. */
    public function down(): void
    {
        // The canonical tarif migration owns this table.
    }
};
