<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Menggunakan updateOrInsert agar bisa dijalankan berkali-kali tanpa error duplikasi
        DB::table('admins')->updateOrInsert(
            ['email' => 'admin@example.com'], // Kriteria unik
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'), // GANTI 'password' dengan password yang Anda inginkan
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }
}