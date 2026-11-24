<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /** Menambahkan kolom detail permintaan ke tabel log jika belum tersedia. */
    public function up(): void
    {
        Schema::table('activity_logs', function (Blueprint $table): void {
            if (! Schema::hasColumn('activity_logs', 'deskripsi')) {
                $table->text('deskripsi')->nullable()->after('aktivitas');
            }

            if (! Schema::hasColumn('activity_logs', 'user_agent')) {
                $table->text('user_agent')->nullable()->after('ip_address');
            }

            if (! Schema::hasColumn('activity_logs', 'url')) {
                $table->text('url')->nullable()->after('user_agent');
            }
        });
    }

    /** Menghapus kolom detail log yang ditambahkan oleh migrasi ini. */
    public function down(): void
    {
        Schema::table('activity_logs', function (Blueprint $table): void {
            $columns = [];

            foreach (['deskripsi', 'user_agent', 'url'] as $column) {
                if (Schema::hasColumn('activity_logs', $column)) {
                    $columns[] = $column;
                }
            }

            if ($columns !== []) {
                $table->dropColumn($columns);
            }
        });
    }
};
