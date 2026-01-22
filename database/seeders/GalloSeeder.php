<?php

namespace Database\Seeders;

use App\Models\Gallo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GalloSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Gallo::factory()->count(1000)->create();
    }
}
