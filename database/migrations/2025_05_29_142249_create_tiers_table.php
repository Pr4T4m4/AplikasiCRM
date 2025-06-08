// database/migrations/xxxx_xx_xx_xxxxxx_create_tiers_table.php
// (Ganti xxxx_xx_xx_xxxxxx dengan timestamp yang sesuai, biasanya ini file paling awal untuk tiers)

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
        Schema::create('tiers', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // PASTIKAN ADA KOLOM 'name' DAN ITU UNIQUE
            $table->integer('min_points')->default(0); // Tambahkan di sini juga atau biarkan di migrasi terpisah
            $table->integer('max_points')->nullable(); // Tambahkan di sini juga atau biarkan di migrasi terpisah
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tiers');
    }
};