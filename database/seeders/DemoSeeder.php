<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $user = \App\Models\User::factory()->create(['name' => 'Demo Farmer', 'email' => 'farmer@example.com', 'password' => bcrypt('password')]);
        $farm = \App\Models\Farm::create(['user_id' => $user->id, 'name' => 'Demo Farm', 'size_ha' => 1.5, 'lat' => 16.7, 'lng' => 121.6]);
        \App\Models\CropCycle::create(['farm_id' => $farm->id, 'crop_type' => 'Rice', 'date_planted' => '2025-06-01', 'date_harvested' => '2025-09-20', 'yield_kg' => 2800]);
        \App\Models\CropCycle::create(['farm_id' => $farm->id, 'crop_type' => 'Rice', 'date_planted' => '2025-10-01', 'date_harvested' => '2026-01-15', 'yield_kg' => 2900]);
    }
}
