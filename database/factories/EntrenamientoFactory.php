<?php

namespace Database\Factories;

use App\Models\Gallo;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Entrenamiento>
 */
class EntrenamientoFactory extends Factory
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
                ?? Gallo::factory(), // fallback si no hay gallos
            'fecha' => $this->faker->dateTimeBetween('-6 months', 'now'),
            'duracion_minutos' => $this->faker->numberBetween(15, 90),
            'tipo_entrenamiento' => $this->faker->randomElement([
                'Resistencia',
                'Velocidad',
                'Fuerza',
                'Técnica',
                'Sparring',
                'Acondicionamiento'
            ]),
            'observaciones' => $this->faker->optional()->sentence(10),
        ];
    }
}
