<?php

namespace Database\Seeders;

use App\Models\Pelea;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PeleaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Pelea::factory()->count(120)->create();
    }
}
