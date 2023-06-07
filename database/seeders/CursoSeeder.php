<?php

namespace Database\Seeders;

use App\Models\Curso;
use App\Models\Evaluacion;
use App\Models\Modulo;
use App\Models\Opcion;
use App\Models\Pregunta;
use App\Models\Tema;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CursoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear un curso
        $curso = Curso::factory()->create();

        // Crear 6 módulos para el curso
        $modulos = Modulo::factory(6)->create(['curso_id' => $curso->id]);

        foreach ($modulos as $index => $modulo) {
            $modulo->update([
                'nombre' => $modulo->nombre.($index+1)
            ]);

            // Crear una evaluación para el módulo
            $evaluacion = Evaluacion::factory()->create([
                'modulo_id' => $modulo->id
            ]);
        }
    }
}
