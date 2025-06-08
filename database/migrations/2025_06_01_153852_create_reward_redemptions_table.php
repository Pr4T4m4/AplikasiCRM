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
        Schema::create('reward_redemptions', function (Blueprint $table) {
            $table->id(); // Kolom ID otomatis
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Foreign key ke tabel users
            $table->foreignId('reward_id')->constrained('rewards')->onDelete('cascade'); // Foreign key ke tabel rewards
            $table->integer('points_redeemed'); // Jumlah poin yang ditukarkan
            $table->string('status')->default('pending'); // Status penukaran (misal: pending, completed, rejected)
            $table->timestamp('redeemed_at')->nullable(); // Waktu penukaran
            $table->timestamps(); // created_at dan updated_at otomatis
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reward_redemptions');
    }
};
