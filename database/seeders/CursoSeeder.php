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

        foreach ($modulos as $modulo) {
            // Crear 3 temas para el módulo
            $temas = Tema::factory(3)->create(['modulo_id' => $modulo->id]);

            // Crear una evaluación para el módulo
            $evaluacion = Evaluacion::factory()->create([
                'modulo_id' => $modulo->id,
                'tiempo_lim' => 15, // duración de la evaluación en minutos
                'intentos_max' => 3, // máximo de intentos permitidos
            ]);

            // Crear 12 preguntas para la evaluación, con 5 opciones cada una
            $preguntas = Pregunta::factory(12)->create(['evaluacion_id' => $evaluacion->id]);

            foreach ($preguntas as $pregunta) {
                // Crear 5 opciones para cada pregunta
                Opcion::factory(5)->create(['pregunta_id' => $pregunta->id]);
                // Marcar una de las opciones como correcta
                Opcion::where('pregunta_id', $pregunta->id)
                    ->inRandomOrder()
                    ->limit(1)
                    ->update(['es_correcta' => true]);
            }
        }
    }
}

