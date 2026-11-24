<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /** Menambahkan status pengguna serta tarif harian dan status tarif bila diperlukan. */
    public function up(): void
    {
        if (! Schema::hasColumn('users', 'status')) {
            Schema::table('users', function (Blueprint $table): void {
                $table->string('status')->default('active')->after('role');
            });
        }

        if (! Schema::hasColumn('tarifs', 'tarif_maksimal_harian')) {
            Schema::table('tarifs', function (Blueprint $table): void {
                $table->decimal('tarif_maksimal_harian', 10, 2)->nullable()->after('tarif_per_jam');
            });
        }

        if (! Schema::hasColumn('tarifs', 'status')) {
            Schema::table('tarifs', function (Blueprint $table): void {
                $table->string('status')->default('aktif')->after('tarif_maksimal_harian');
            });
        }
    }

    /** Menghapus kolom tambahan pengguna dan tarif saat migrasi dibatalkan. */
    public function down(): void
    {
        if (Schema::hasColumn('users', 'status')) {
            Schema::table('users', fn (Blueprint $table) => $table->dropColumn('status'));
        }
        if (Schema::hasColumn('tarifs', 'tarif_maksimal_harian')) {
            Schema::table('tarifs', fn (Blueprint $table) => $table->dropColumn('tarif_maksimal_harian'));
        }
        if (Schema::hasColumn('tarifs', 'status')) {
            Schema::table('tarifs', fn (Blueprint $table) => $table->dropColumn('status'));
        }
    }
};
