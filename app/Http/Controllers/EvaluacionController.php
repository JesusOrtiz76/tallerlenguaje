<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Models\Evaluacion;
use App\Models\Modulo;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class EvaluacionController extends Controller
{

    public function __construct()
    {
        $modulos = Modulo::all();
        View::share('modulos', $modulos);
    }
    public function show($id)
    {
        // Obtener el usuario autenticado
        $user = Auth::user();

        try {
            // Obtener la evaluación con el módulo correspondiente
            $evaluacion = Evaluacion::where('id', $id)->with('modulo')->firstOrFail();

            // Verificar si el usuario ha accedido previamente a la evaluación
            if (!$user->evaluaciones()->where('evaluacion_id', $evaluacion->id)->exists()) {
                // Si el usuario no ha accedido previamente, agregar un registro a la tabla intermedia de evaluaciones y usuarios con un intento
                $pivotData = ['intentos' => 1];
                $user->evaluaciones()->syncWithoutDetaching([$evaluacion->id => $pivotData]);
            } else {
                // Si el usuario ya ha accedido previamente, agregar un intento adicional a la tabla intermedia de evaluaciones y usuarios
                $user->evaluaciones()->where('evaluacion_id', $evaluacion->id)->increment('intentos');
            }

            $pivot = $user->evaluaciones()->where('evaluacion_id', $evaluacion->id)->firstOrFail();

            // Verificar si se han agotado los intentos
            if ($pivot->intentos >= $evaluacion->intentos_max) {
                return redirect()->route('evaluaciones.resultado', [$evaluacion->modulo_id, $evaluacion->id])
                    ->with('warning', 'Ya has agotado tus intentos para esta evaluación');
            }

            // Obtener las preguntas de la evaluación, con opciones cargadas y ordenadas aleatoriamente
            $preguntas = $evaluacion->preguntas()->with('opciones')->inRandomOrder()->get();

            // Cargar la vista con las preguntas y la evaluación
            return view('evaluaciones.show', [
                'modulo' => $evaluacion->modulo,
                'evaluacion' => $evaluacion,
                'preguntas' => $preguntas,
                'pivot' => $pivot
            ]);
        } catch (ModelNotFoundException $e) {
            // Manejar la excepción si la evaluación no existe
            return redirect()->back()->with('error', 'No hay evaluación disponible en este módulo');
        }
    }


    public function submit(Request $request, $evaluacion_id)
    {
        $user = Auth::user();
        $evaluacion = $user->evaluaciones()->findOrFail($evaluacion_id);
        $modulo = Modulo::find($evaluacion->modulo_id);


        // Verificar si el usuario ya ha completado la evaluación la cantidad máxima de veces permitida
        if ($evaluacion && $evaluacion->pivot->intentos >= $evaluacion->intentos_max) {
            return redirect()->back()->with('warning', 'Has alcanzado la cantidad máxima de intentos para esta evaluación.');
        }

        // Verificar si el usuario ya ha completado la evaluación previamente
        if ($evaluacion->users()->where('user_id', $user->id)->where('completado', true)->exists()) {
            return redirect()->back()->with('warning', 'Ya has completado esta evaluación previamente.');
        }

        // Obtener las respuestas del usuario
        $respuestas = $request->input('respuestas', []);

        // Verificar que se hayan contestado todas las preguntas
        if (count($respuestas) !== $evaluacion->preguntas->count()) {
            return redirect()->back()->with('warning', 'Completa las preguntas.');
        }

        $data = [$user, $evaluacion, $modulo, $request->input('respuestas', [])];
        return $data;

        return "Has contestado todas";

        // Calcular el puntaje del usuario
        $score = 0;
        foreach ($evaluation->questions as $question) {
            $user_answer = $answers[$question->id];
            if ($user_answer == $question->correct_answer) {
                $score += $question->points;
            }
        }

        // Si no existe registro en tabla intermedia, se crea el registro y comienza el primer intento de la evaluacion
        if (!$evaluacion) {
            $user->evaluaciones()->attach($evaluacion->id, ['intentos' => 1, 'resultados' => 0]);
        } else {
            $evaluacion->pivot->increment('intentos');
            $user_evaluation->score = $score;
            $user_evaluation->completed = true;
            $user_evaluation->completed_at = now();
            $user_evaluation->save();
        }

        // Redirigir al usuario a la página de resultados
        return redirect()->route('evaluations.show', $evaluation->id)->with('success', 'Evaluación completada.');
    }
    public function resultado() {
        return 'Resultados';
    }
}
