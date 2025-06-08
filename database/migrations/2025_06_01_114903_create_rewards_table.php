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
        Schema::create('rewards', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->text('description')->nullable();
            $table->integer('points_required'); // Poin yang dibutuhkan untuk menukar hadiah
            $table->integer('stock')->default(0); // Jumlah stok hadiah yang tersedia
            $table->string('image_path')->nullable(); // Path gambar hadiah (opsional)
            $table->boolean('is_active')->default(true); // Status hadiah (aktif/tidak aktif)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rewards');
    }
};