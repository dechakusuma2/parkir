<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /** Menambahkan lokasi foto kendaraan jika kolom belum tersedia. */
    public function up(): void
    {
        if (! Schema::hasColumn('kendaraans', 'foto_path')) {
            Schema::table('kendaraans', fn (Blueprint $table) => $table->string('foto_path')->nullable());
        }
    }

    /** Menghapus kolom lokasi foto kendaraan ketika migrasi dibatalkan. */
    public function down(): void
    {
        if (Schema::hasColumn('kendaraans', 'foto_path')) {
            Schema::table('kendaraans', fn (Blueprint $table) => $table->dropColumn('foto_path'));
        }
    }
};
