<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Membuat tabel dasar untuk mencatat aktivitas pengguna di aplikasi.
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable();
            $table->string('aktivitas');
            $table->string('ip_address')->nullable();
            $table->timestamps();
        });
    }

    // Menghapus tabel log aktivitas saat migrasi dibatalkan.
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
