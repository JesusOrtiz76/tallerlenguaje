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
            'onombre' => $this->faker->sentence(3),
            'odescripcion' => $this->faker->paragraph(1),
            'oimg_path' => $this->faker->imageUrl(),
            'ofecha_inicio' => $this->faker->dateTimeBetween('now'),
            'ofecha_fin' => $this->faker->dateTimeBetween('+1 week', '+4 weeks'),
        ];
    }
}
