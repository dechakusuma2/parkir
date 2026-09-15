<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('areas') && Schema::hasColumn('areas', 'kode_area')) {
            DB::statement('ALTER TABLE areas MODIFY kode_area VARCHAR(255) NULL');
        }
    }

    public function down(): void
    {
        // Keep the legacy column nullable for compatibility with existing databases.
    }
};
