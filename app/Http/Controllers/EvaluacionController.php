<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Models\Evaluacion;
use App\Models\Modulo;
use App\Models\Resultado;
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

            // Obtener datos de la tabla evaluacion_user
            $pivot = $user->evaluaciones()->where('evaluacion_id', $evaluacion->id)->firstOrFail();

            // Verificar si se han agotado los intentos
            if ($pivot->pivot->intentos >= $evaluacion->intentos_max) {
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
        /*
                $data = [$user, $evaluacion, $modulo, $request->input('respuestas', [])];
                return $data;
        */
        // Calcular el puntaje del usuario
        $score = 0;
        foreach ($evaluacion->preguntas as $pregunta) {
            $user_respuesta = $respuestas[$pregunta->id];

            // Obtener la opción correcta de la pregunta actual
            $opcion_correcta = $pregunta->opciones()->where('es_correcta', true)->first();

            // Verificar si la respuesta del usuario es correcta
            if ($user_respuesta == $opcion_correcta->id) {
                $score += 1;
            }

        }

        // Asignar puntuación a la tabla evaluacion_user
        $evaluacion->pivot->resultados = $score;

        // Cerrar la evaluacion si ha alcanzado el limite de intentos
        if ($evaluacion->pivot->intentos >= $evaluacion->intentos_max){
            $evaluacion->pivot->completado = true;
        }

        $resultado = Resultado::where('user_id', auth()->id())
            ->where('evaluacion_id', $evaluacion->id)
            ->first();

        if ($resultado) {
            $resultado->respuestas = json_encode($respuestas);
            $resultado->save();
        } else {
            // Si no se encuentra ningún resultado, se podría crear uno nuevo
            $resultado = new Resultado([
                'user_id' => auth()->id(),
                'evaluacion_id' => $evaluacion->id,
                'respuestas' => json_encode($respuestas),
            ]);
            $resultado->save();
        }

        // Si el guardado es exitoso guardamos la puntuación
        if ($evaluacion->pivot->save()) {
            // Redirigir al usuario a la página de resultados
            return redirect()->route('modulos.show', $modulo->id)->with('success', 'Evaluación completada.');
        } else {
            return redirect()->route('modulos.show', $modulo->id)->with('error', 'Ocurrió un error al enviar las respuestas.');
        }
    }
    public function resultado() {
        return 'Resultados';
    }
}
