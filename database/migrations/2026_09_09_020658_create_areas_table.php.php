<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('areas')) {
            if (!Schema::hasColumn('areas', 'terisi')) {
                Schema::table('areas', function (Blueprint $table) {
                    $table->integer('terisi')->default(0);
                });
            }
            return;
        }

        Schema::create('areas', function (Blueprint $table) {
            $table->id();
            $table->string('nama_area');
            $table->integer('kapasitas');
            $table->integer('terisi')->default(0);
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // WAJIB ADA INI:
        Schema::dropIfExists('areas');
    }
};
