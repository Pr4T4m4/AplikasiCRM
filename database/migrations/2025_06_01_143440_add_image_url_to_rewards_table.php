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
        Schema::table('rewards', function (Blueprint $table) {
            // Menambahkan kolom 'image_url' sebagai string yang nullable
            // Anda bisa menempatkannya setelah kolom tertentu, contoh: after('description')
            $table->string('image_url')->nullable()->after('description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rewards', function (Blueprint $table) {
            // Menghapus kolom 'image_url' jika migrasi di-rollback
            $table->dropColumn('image_url');
        });
    }
};