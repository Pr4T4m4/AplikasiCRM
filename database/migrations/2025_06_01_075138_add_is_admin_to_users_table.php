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
        Schema::table('users', function (Blueprint $table) {
            // Tambahkan kolom is_admin sebagai boolean, dengan default false
            $table->boolean('is_admin')->default(false)->after('password'); // Anda bisa menyesuaikan 'after'
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Hapus kolom is_admin jika migrasi di-rollback
            $table->dropColumn('is_admin');
        });
    }
};