<?php

namespace Database\Factories;

use App\Models\Gallo;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Gallo>
 */
class GalloFactory extends Factory
{
    protected $model = Gallo::class;

    public function definition(): array
    {
        return [
            'placa' => $this->faker->unique()->bothify('GAL-####'),
            'name' => $this->faker->randomElement([
                'Gallo Rojo',
                'Gallo Negro',
                'Gallina Dorada',
                'Gallo Cenizo',
                'Gallina Blanca',
                'Gallo Colorado',
                'Gallina Morena',
                'Gallo Pinto',
                'Gallina Castaña'
            ]),
            'sexo' => $this->faker->randomElement(['M', 'H']),
            'fecha_nacimiento' => $this->faker->dateTimeBetween('-2 years', '-3 months')->format('Y-m-d'),
            'url_imagen' => null,
            'color' => $this->faker->randomElement([
                'Rojo',
                'Negro',
                'Blanco',
                'Gris',
                'Dorado',
                'Marrón'
            ]),
            'peso' => $this->faker->randomFloat(2, 2.3, 3.8),
            'talla' => $this->faker->randomFloat(2, 0.60, 0.85),
            'color_patas' => $this->faker->randomElement([
                'Amarillas',
                'Blancas',
                'Negras',
                'Rosadas'
            ]),
            'tipo_cresta' => $this->faker->randomElement([
                'Simple',
                'Rosa',
                'Doble',
                'Guía'
            ]),
            'id_padre' => null,
            'id_madre' => null,
            'id_user' =>User::inRandomOrder()->value('id')
        ];
    }
}
