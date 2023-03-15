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
    public function show(Request $request)
    {
        // Obtener la evaluación del usuario logueado
        $user = Auth::user();
        $modulo = Modulo::find($request->modulo_id);
        $evaluacion = $modulo->evaluaciones()->where('activo', 1)->first();

        if (!$evaluacion) {
            return redirect()->back()->with('error', 'No hay evaluación disponible en este módulo');
        }

        // Obtener datos de tabla intermedia
        $pivot = $user->evaluaciones()->find($evaluacion->id);

        // Validar si existen mas intentos disponibles
        if ($pivot && $pivot->pivot->intentos >= $evaluacion->intentos_max) {
            return redirect()->route('evaluaciones.resultado', [$request->modulo_id, $evaluacion->id])
                ->with('warning', 'Ya has agotado tus intentos para esta evaluación');
        }

        // Si no existe registro en tabla intermedia, se crea el registro y comienza el primer intento de la evaluacion
        if (!$pivot) {
            $user->evaluaciones()->attach($evaluacion->id, ['intentos' => 1, 'resultados' => 0]);
        } else {
            $pivot->pivot->increment('intentos');
        }

        /*
        if (!$pivot) {
            return redirect()->back()->with('warning', 'No ha completado la evaluación.');
        }
        */

        // Obtener las preguntas de la evaluación en orden aleatorio
        $preguntas = $evaluacion->preguntas()->with('opciones')->get();;

        //return $preguntas;

        // Cargar la vista con las preguntas y la evaluación
        return view('evaluaciones.show', [
            'preguntas' => $preguntas,
            'evaluacion' => $evaluacion,
            'pivot' => $pivot,
            'modulo' => $modulo,
        ]);
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
