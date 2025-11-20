<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

use App\Models\User;
use App\Models\Curso;
use App\Models\Evaluacion;
use App\Models\Resultado;
use App\Models\UserScoreView;

Artisan::command('simular:curso {userId} {cursoId} {tipo}', function ($userId, $cursoId, $tipo) {
    $tipo = strtolower($tipo); // aprobado, reprobado, final50, final75

    /** @var \App\Models\User $user */
    $user = User::find($userId);
    /** @var \App\Models\Curso $curso */
    $curso = Curso::find($cursoId);

    if (!$user || !$curso) {
        $this->error('Usuario o curso no encontrados.');
        return;
    }

    $this->info("Simulando curso para user_id={$user->id}, curso_id={$curso->id}, tipo={$tipo}");

    // Asegurar inscripción
    $user->cursos()->syncWithoutDetaching([$curso->id]);

    // Cargar evaluaciones con preguntas y opciones
    $evaluaciones = Evaluacion::with('preguntas.opciones')
        ->whereHas('modulo', function ($q) use ($curso) {
            $q->where('curso_id', $curso->id);
        })->get();

    if ($evaluaciones->isEmpty()) {
        $this->error('El curso no tiene evaluaciones.');
        return;
    }

    $this->info('Evaluaciones encontradas: ' . $evaluaciones->pluck('id')->join(', '));

    // Limpiar resultados anteriores de ese curso para ese usuario
    Resultado::where('user_id', $user->id)
        ->whereIn('evaluacion_id', $evaluaciones->pluck('id'))
        ->delete();

    $user->evaluaciones()->detach($evaluaciones->pluck('id'));

    // Ubicar la evaluación final (id = 15 en este curso)
    $evalFinal = $evaluaciones->firstWhere('id', 15);
    $totalPreguntasFinal = $evalFinal ? $evalFinal->preguntas->count() : 0;
    $this->info("Preguntas en evaluación final (id=15): {$totalPreguntasFinal}");

    $finalCorrectTarget = null;

    if ($tipo === 'final50' && $totalPreguntasFinal > 0) {
        $finalCorrectTarget = intdiv($totalPreguntasFinal, 2);
        $this->info("Escenario final50 → correctas en final: {$finalCorrectTarget}");
    } elseif ($tipo === 'final75' && $totalPreguntasFinal > 0) {
        $finalCorrectTarget = (int) round($totalPreguntasFinal * 0.75);
        $this->info("Escenario final75 → correctas en final: {$finalCorrectTarget}");
    }

    // Volver a generar respuestas simuladas según el tipo
    foreach ($evaluaciones as $evaluacion) {
        $opcionesIds = [];
        $esFinal = ($evaluacion->id == 15);
        $finalCorrectCount = 0;

        foreach ($evaluacion->preguntas as $pregunta) {

            $correctas   = $pregunta->opciones->where('oes_correcta', 1);
            $incorrectas = $pregunta->opciones->where('oes_correcta', 0);

            $opcion = null;

            switch ($tipo) {
                case 'aprobado':
                    if ($correctas->isNotEmpty()) {
                        $opcion = $correctas->random();
                    } else {
                        $this->warn("Pregunta {$pregunta->id} sin opciones correctas; usando cualquier opción.");
                        $opcion = $pregunta->opciones->isNotEmpty()
                            ? $pregunta->opciones->random()
                            : null;
                    }
                    break;

                case 'reprobado':
                    if ($esFinal) {
                        // Final: todo mal
                        if ($incorrectas->isNotEmpty()) {
                            $opcion = $incorrectas->random();
                        } else {
                            $this->warn("Pregunta {$pregunta->id} en final sin incorrectas; usando correcta como fallback.");
                            $opcion = $correctas->isNotEmpty()
                                ? $correctas->random()
                                : ($pregunta->opciones->isNotEmpty() ? $pregunta->opciones->random() : null);
                        }
                    } else {
                        // Ejercicios: todo bien
                        if ($correctas->isNotEmpty()) {
                            $opcion = $correctas->random();
                        } else {
                            $this->warn("Pregunta {$pregunta->id} en ejercicio sin correctas; usando cualquier opción.");
                            $opcion = $pregunta->opciones->isNotEmpty()
                                ? $pregunta->opciones->random()
                                : null;
                        }
                    }
                    break;

                case 'final50':
                case 'final75':
                    if ($esFinal && $finalCorrectTarget !== null) {
                        if ($finalCorrectCount < $finalCorrectTarget) {
                            // Queremos correctas
                            if ($correctas->isNotEmpty()) {
                                $opcion = $correctas->random();
                                $finalCorrectCount++;
                            } else {
                                $this->warn("Pregunta {$pregunta->id} en final sin correctas; usando cualquier opción como 'correcta'.");
                                if ($pregunta->opciones->isNotEmpty()) {
                                    $opcion = $pregunta->opciones->random();
                                    $finalCorrectCount++;
                                } else {
                                    $opcion = null;
                                }
                            }
                        } else {
                            // Queremos incorrectas
                            if ($incorrectas->isNotEmpty()) {
                                $opcion = $incorrectas->random();
                            } else {
                                $this->warn("Pregunta {$pregunta->id} en final sin incorrectas; usando cualquier opción como 'incorrecta'.");
                                $opcion = $pregunta->opciones->isNotEmpty()
                                    ? $pregunta->opciones->random()
                                    : null;
                            }
                        }
                    } else {
                        // Ejercicios: siempre correctas
                        if ($correctas->isNotEmpty()) {
                            $opcion = $correctas->random();
                        } else {
                            $this->warn("Pregunta {$pregunta->id} en ejercicio sin correctas; usando cualquier opción.");
                            $opcion = $pregunta->opciones->isNotEmpty()
                                ? $pregunta->opciones->random()
                                : null;
                        }
                    }
                    break;

                default:
                    $this->error("Tipo '{$tipo}' no soportado. Usa: aprobado, reprobado, final50, final75.");
                    return;
            }

            if ($opcion) {
                $opcionesIds[] = $opcion->id;
            }
        }

        Resultado::updateOrCreate(
            [
                'user_id'       => $user->id,
                'evaluacion_id' => $evaluacion->id,
            ],
            [
                'orespuestas' => json_encode($opcionesIds),
            ]
        );

        $user->evaluaciones()->syncWithoutDetaching([
            $evaluacion->id => ['ointentos' => 1],
        ]);
    }

    // Ver score desde la vista
    $score = UserScoreView::where('user_id', $user->id)
        ->where('curso_id', $curso->id)
        ->first();

    if (!$score) {
        $this->warn('No se encontró registro en r12user_scores_view para este usuario/curso.');
        return;
    }

    $this->info('--- RESULTADO ---');
    $this->info('Total de preguntas      = ' . $score->total_answers);
    $this->info('Correctas                = ' . $score->correct_answers);
    $this->info('Score (ponderado) [0-100]= ' . $score->score_percentage);
});

