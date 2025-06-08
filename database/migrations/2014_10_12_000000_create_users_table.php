<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('email')->unique();
            $table->string('phone_number')->unique();
            $table->string('gender')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('address_line1')->nullable();
            $table->string('address_line2')->nullable();
            $table->string('city')->nullable();
            $table->string('province')->nullable();
            $table->string('postal_code')->nullable();

            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();

            $table->bigInteger('current_points')->default(0);
            $table->unsignedBigInteger('current_tier_id')->nullable(); // Pastikan ini unsignedBigInteger
            $table->bigInteger('points_to_next_tier')->default(0);
            $table->bigInteger('total_points_earned')->default(0);
            $table->bigInteger('total_points_redeemed')->default(0);
            $table->timestamp('registration_date')->useCurrent();
            $table->string('status')->default('active');

            // HAPUS BARIS INI (foreign key akan ditambahkan di migrasi terpisah)
            // $table->foreign('current_tier_id')->references('id')->on('tiers')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
};