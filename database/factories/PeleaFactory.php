<?php

namespace Database\Factories;

use App\Models\Gallo;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pelea>
 */
class PeleaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_gallo' => Gallo::inRandomOrder()->value('id')
                ?? Gallo::factory(), // fallback seguro
            'fecha' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'lugar' => $this->faker->randomElement([
                'Coliseo Central',
                'Plaza de Gallos San Martín',
                'Campo Abierto',
                'Gallera Municipal',
                'Evento Privado'
            ]),
            'estado' => $this->faker->randomElement([
                'Programada',
                'Ganada',
                'Perdida',
                'Empatada',
                'Suspendida'
            ]),
        ];
    }
}
