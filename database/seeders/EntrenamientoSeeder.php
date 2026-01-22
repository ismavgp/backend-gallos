<?php

namespace Database\Seeders;

use App\Models\Entrenamiento;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EntrenamientoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Entrenamiento::factory()->count(120)->create();
    }
}
