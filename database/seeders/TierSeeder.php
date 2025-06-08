<?php

namespace Database\Seeders;

use App\Models\Tier;
use Illuminate\Database\Seeder;

class TierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pastikan Anda membersihkan atau mencegah duplikasi jika sudah ada data
        // Tier::truncate(); // HATI-HATI: Ini akan menghapus semua data tier yang sudah ada!

        // Pastikan urutan dibuat dari min_points terendah ke tertinggi
        Tier::firstOrCreate(['name' => 'Bronze'], ['min_points' => 0, 'max_points' => 100]);
        Tier::firstOrCreate(['name' => 'Silver'], ['min_points' => 101, 'max_points' => 500]);
        Tier::firstOrCreate(['name' => 'Gold'], ['min_points' => 501, 'max_points' => 1000]); // Gold sekarang punya batas atas
        Tier::firstOrCreate(['name' => 'Platinum'], ['min_points' => 1001, 'max_points' => null]); // Platinum: di atas 1000
    }
}