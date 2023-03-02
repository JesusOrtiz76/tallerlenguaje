<?php

namespace App\Http\Controllers;

use App\Models\Evaluacion;
use App\Models\Modulo;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class EvaluacionController extends Controller
{
    public function show(Request $request)
    {
        // Obtener la evaluación del usuario logueado
        $user = Auth::user();
        $modulo = Modulo::find($request->modulo_id);
        $evaluacion = $modulo->evaluacion()->where('activo', 1)->first();

        if (!$evaluacion) {
            return redirect()->back()->with('error', 'No hay evaluación disponible en este módulo');
        }

        // Obtener datos de tabla intermedia
        $pivot = $user->evaluaciones()->find($evaluacion->id);

        // Validar si existen mas intentos disponibles
        if ($pivot && $pivot->pivot->intentos >= $evaluacion->intentos_max) {
            return redirect()->route('evaluaciones.resultado', [$request->modulo_id, $evaluacion->id])->with('warning', 'Ya has agotado tus intentos para esta evaluación');
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

    public function submit(Request $request){
        return $request;
    }

    public function resultado() {
        return 'Resultados';
    }
}
