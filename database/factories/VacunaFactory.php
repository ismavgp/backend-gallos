<?php

namespace Database\Factories;

use App\Models\Gallo;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Vacuna>
 */
class VacunaFactory extends Factory
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
                ?? Gallo::factory(), // fallback si no existen gallos
            'nombre_vacuna' => $this->faker->randomElement([
                'Newcastle',
                'Viruela Aviar',
                'Bronquitis Infecciosa',
                'Gumboro',
                'Influenza Aviar'
            ]),
            'fecha_aplicacion' => $this->faker->dateTimeBetween('-1 year', 'now')
                ->format('Y-m-d'),
            'dosis' => $this->faker->randomElement([
                '0.5 ml',
                '1 ml',
                '1 dosis',
                '2 gotas'
            ]),
            'observaciones' => $this->faker->optional()->sentence(8),
        ];
    }
}
