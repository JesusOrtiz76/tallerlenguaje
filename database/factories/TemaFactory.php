<?php

namespace Database\Factories;

use App\Models\Modulo;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tema>
 */
class TemaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'titulo' => 'Tema ',
            'descripcion' => $this->faker->paragraph(2),
            'contenido' => $this->faker->paragraph(10),
            'img_path' => $this->faker->imageUrl(),
            'modulo_id' => Modulo::factory(),
        ];
    }
}
