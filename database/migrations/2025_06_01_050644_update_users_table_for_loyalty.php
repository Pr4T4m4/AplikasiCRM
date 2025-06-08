<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Ubah 'name' menjadi 'full_name' dan pastikan itu ada
            // Jika kolom 'name' sudah ada dan ingin di-rename:
            // $table->renameColumn('name', 'full_name');
            // Jika belum ada dan ingin menambahkan:
            if (!Schema::hasColumn('users', 'full_name')) {
                $table->string('full_name')->after('id'); // Tambahkan kolom full_name
            }
            // Hapus kolom 'name' jika sudah ada dan Anda tidak menginginkannya
            if (Schema::hasColumn('users', 'name')) {
                $table->dropColumn('name');
            }


            // Tambahkan kolom poin jika belum ada
            if (!Schema::hasColumn('users', 'current_points')) {
                $table->integer('current_points')->default(0)->after('email');
            }
            if (!Schema::hasColumn('users', 'total_points_earned')) {
                $table->integer('total_points_earned')->default(0)->after('current_points');
            }
            if (!Schema::hasColumn('users', 'total_points_redeemed')) {
                $table->integer('total_points_redeemed')->default(0)->after('total_points_earned');
            }

            // Tambahkan kolom tier_id jika belum ada
            if (!Schema::hasColumn('users', 'tier_id')) {
                $table->foreignId('tier_id')->nullable()->constrained('tiers')->onDelete('set null')->after('total_points_redeemed');
            }

            // Tambahkan is_active jika belum ada
            if (!Schema::hasColumn('users', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('password');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // Balikkan perubahan (opsional, tergantung kebutuhan rollback)
            if (Schema::hasColumn('users', 'full_name')) {
                $table->renameColumn('full_name', 'name');
            }
            $table->dropColumn(['current_points', 'total_points_earned', 'total_points_redeemed', 'is_active']);
            $table->dropConstrainedForeignId('tier_id'); // Drop foreign key
        });
    }
};