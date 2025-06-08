<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            AdminSeeder::class, // Panggil AdminSeeder di sini
            // TierSeeder::class, // Jika Anda punya TierSeeder, panggil juga di sini
            // UserSeeder::class, // Jika Anda punya UserSeeder, panggil juga di sini
        ]);
    }
}