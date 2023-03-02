<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Curso>
 */
class CursoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nombre' => $this->faker->sentence(3),
            'descripcion' => $this->faker->paragraph(1),
            'image' => $this->faker->imageUrl(),
            'fecha_inicio' => $this->faker->dateTimeBetween('now', '+1 week'),
            'fecha_fin' => $this->faker->dateTimeBetween('+1 week', '+4 weeks'),
        ];
    }
}
