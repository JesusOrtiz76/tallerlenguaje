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
    // Obtener contenido de la evaluación (Preguntas y respuestas)
    public function show($id)
    {
        // Obtener el usuario autenticado
        $user = Auth::user();

        // Obtener la evaluación con el módulo correspondiente
        $evaluacion = Evaluacion::where('id', $id)->with('modulo')->firstOrFail();

        // Obtener las preguntas de la evaluación, con opciones cargadas y ordenadas aleatoriamente
        $preguntas = $evaluacion->preguntas()->with('opciones')->inRandomOrder()->get();

        // Verificar si existen preguntas en la evaluación
        if ($preguntas->isEmpty()) {
            return redirect()->back()->with('warning', 'No hay preguntas disponibles en esta evaluación');
        }

        // Verificar si el usuario ha accedido previamente a la evaluación y si no, agrega primer intento
        if (!$user->evaluaciones()->where('evaluacion_id', $evaluacion->id)->exists()) {
            $pivotData = ['intentos' => 0];
            $user->evaluaciones()->syncWithoutDetaching([$evaluacion->id => $pivotData]);
        }

        // Obtener datos de la tabla evaluacion_user
        $pivot = $user->evaluaciones()->where('evaluacion_id', $evaluacion->id)->firstOrFail();

        // Verificar si se han agotado los intentos
        if ($pivot->pivot->intentos >= $evaluacion->intentos_max) {
            return redirect()->route('evaluaciones.resultado', [$evaluacion->modulo_id, $evaluacion->id])
                ->with('warning', 'Ya has agotado tus intentos para esta evaluación');
        }

        // Cargar la vista con las preguntas y la evaluación
        return view('evaluaciones.show', [
            'modulo' => $evaluacion->modulo,
            'evaluacion' => $evaluacion,
            'preguntas' => $preguntas,
            'pivot' => $pivot
        ]);
    }

    // Guardar respuestas
    public function submit(Request $request, $evaluacion_id)
    {
        // Obtener usuario
        $user = Auth::user();

        // Obtener evaluaciones
        $evaluacion = $user->evaluaciones()->findOrFail($evaluacion_id);

        // Obtener modulos
        $modulo = Modulo::find($evaluacion->modulo_id);

        // Verificar si el usuario ya ha completado la evaluación la cantidad máxima de veces permitida
        if ($evaluacion && $evaluacion->pivot->intentos >= $evaluacion->intentos_max) {
            return redirect()->back()->with('warning', 'Has alcanzado la cantidad máxima de intentos para esta evaluación.');
        }

        // Obtener las respuestas del usuario
        $respuestas = $request->input('respuestas', []);

        // Verificar que se hayan contestado todas las preguntas
        if (count($respuestas) !== $evaluacion->preguntas->count()) {
            return redirect()->back()->with('warning', 'Completa las preguntas.');
        } else {
            // Si el usuario ya ha accedido previamente, agregar un intento adicional a la tabla intermedia de evaluaciones y usuarios
            $user->evaluaciones()->where('evaluacion_id', $evaluacion->id)->increment('intentos');
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

        // Si el guardado es exitoso redireccionamos a modulos.show
        if ($evaluacion->pivot->save()) {
            // Redirigir al usuario a la página de resultados
            return redirect()->route('modulos.show', $modulo->id)->with('success', 'Evaluación completada.');
        } else {
            return redirect()->route('modulos.show', $modulo->id)->with('error', 'Ocurrió un error al enviar las respuestas.');
        }
    }

    // Ver resultados
    public function resultado($evaluacion_id)
    {
        $user = Auth::user();

        $evaluacion = $user->evaluaciones()->findOrFail($evaluacion_id);

        $resultado = Resultado::where('user_id', $user->id)
            ->where('evaluacion_id', $evaluacion_id)
            ->firstOrFail();
        $respuestas = json_decode($resultado->respuestas, true);

        // Obtener el módulo al que pertenece la evaluación
        $modulo = $evaluacion->modulo;

        // Verificar las respuestas y calcular el puntaje
        $puntaje = 0;
        foreach ($evaluacion->preguntas as $pregunta) {
            $respuesta = $pregunta->opciones()->find($respuestas[strval($pregunta->id)]);
            if ($respuesta->es_correcta) {
                $puntaje++;
            }
        }

        return view('evaluaciones.resultado', compact('evaluacion', 'resultado', 'respuestas', 'modulo', 'puntaje'));
    }
}
