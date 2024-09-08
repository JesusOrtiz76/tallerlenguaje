<?php

namespace App\Http\Controllers;

use App\Models\Evaluacion;
use App\Models\Modulo;
use App\Models\Opcion;
use App\Models\Resultado;
use App\Traits\VerificaAccesoTrait;
use App\Traits\VerificaEvaluacionesCompletasTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class EvaluacionController extends Controller
{
    use VerificaAccesoTrait;
    use VerificaEvaluacionesCompletasTrait;

    // Obtener contenido de la evaluación (Preguntas y respuestas)
    public function show($id)
    {
        $user = Auth::user();
        $evaluacion = Evaluacion::with('modulo')->findOrFail($id);
        $modulo = $evaluacion->modulo;

        // Verificar si hay algún módulo pendiente antes del actual
        $mensajePendiente = $this->verificarModuloPendiente($modulo);

        if ($mensajePendiente) {
            return redirect()->back()->with('warning', $mensajePendiente);
        }

        $cacheGlobalExpiration = env('CACHE_GLOBAL_EXPIRATION', 60);
        $evaluacionCacheKey = 'evaluacion_' . $id . '_user_' . $user->id;

        $evaluacionData = Cache::remember($evaluacionCacheKey, now()->addMinutes($cacheGlobalExpiration), function () use ($id) {
            $evaluacion = Evaluacion::where('id', $id)->with('modulo')->firstOrFail();
            return [
                'evaluacion' => $evaluacion,
                'preguntas' => $evaluacion->preguntas()->with('opciones')->get()
            ];
        });

        $evaluacion = $evaluacionData['evaluacion'];
        $preguntas = $evaluacionData['preguntas'];
        $modulo = $evaluacion->modulo;
        $curso = $modulo->curso;

        // Verificar acceso a curso
        $resultado = $this->verificarAccesoCurso($user, $curso);
        if ($resultado['error']) {
            return redirect()->route('home')->with('warning', $resultado['message']);
        }

        if ($preguntas->isEmpty()) {
            return redirect()->back()->with('warning', 'No hay preguntas disponibles en esta evaluación');
        }

        // Utilizar los métodos del modelo para obtener los intentos
        $intentoActual = $evaluacion->intentoActual();
        $intentosRestantes = $evaluacion->intentosRestantes();

        // Mostrar preguntas según el límite
        $numeroPreguntas = $evaluacion->onumero_preguntas;
        $preguntasMostradas = $preguntas->take($numeroPreguntas);

        // Cargar la vista con las preguntas, la evaluación y los intentos
        return view('evaluaciones.show', [
            'modulo' => $modulo,
            'evaluacion' => $evaluacion,
            'preguntas' => $preguntasMostradas,
            'intentoActual' => $intentoActual,
            'intentosRestantes' => $intentosRestantes,
        ]);
    }

    // Guardar respuestas
    public function submit(Request $request, $evaluacion_id)
    {
        // Obtener usuario autenticado y evaluación
        $user = Auth::user();
        $evaluacion = $user->evaluaciones()->findOrFail($evaluacion_id);
        $modulo = Modulo::find($evaluacion->modulo_id);
        $curso = $modulo->curso;

        // Usar el trait para verificar tanto las fechas de acceso como la inscripción del usuario
        $resultado = $this->verificarAccesoCurso($user, $curso);
        if ($resultado['error']) {
            return redirect()->route('home')->with('warning', $resultado['message']);
        }

        // Verificar si el usuario ha alcanzado los intentos permitidos
        if ($evaluacion->pivot->ointentos >= $evaluacion->ointentos_max) {
            return redirect()->back()->with('warning', 'Has alcanzado la cantidad máxima de intentos para esta evaluación.');
        }

        // Obtener las respuestas del usuario
        $respuestas = $request->input('respuestas', []);

        // Verificar que se hayan contestado todas las preguntas
        $numeroPreguntasMostradas = $evaluacion->onumero_preguntas;
        if (count($respuestas) !== $numeroPreguntasMostradas) {
            return redirect()->back()->with('warning', 'Completa todas las preguntas.');
        }

        // Incrementar intentos del usuario
        $user->evaluaciones()->updateExistingPivot($evaluacion->id, [
            'ointentos' => $evaluacion->pivot->ointentos + 1
        ]);

        // Guardar respuestas
        Resultado::updateOrCreate(
            ['user_id' => $user->id, 'evaluacion_id' => $evaluacion->id],
            ['orespuestas' => json_encode(array_values($respuestas))]
        );

        // Redirigir al usuario
        return redirect()->route('modulos.show', $modulo->id)->with('success', 'Evaluación completada.');
    }

    // Ver resultados
    public function resultado($evaluacion_id)
    {
        $user = Auth::user();
        $evaluacion = $user->evaluaciones()->findOrFail($evaluacion_id);
        $resultado = Resultado::where('user_id', $user->id)
            ->where('evaluacion_id', $evaluacion_id)
            ->firstOrFail();
        $respuestas = json_decode($resultado->orespuestas, true);

        $modulo = $evaluacion->modulo;
        $puntaje = 0;
        $preguntas = [];

        foreach ($respuestas as $pregunta_id => $opcion_id) {
            $opcion = Opcion::find($opcion_id);
            if ($opcion->oes_correcta) {
                $puntaje++;
            }

            $preguntas[] = [
                'enunciado' => $opcion->pregunta->oenunciado,
                'opcion' => $opcion->otexto,
                'es_correcta' => $opcion->oes_correcta,
            ];
        }

        // Cargar la vista con el resultado
        return view('evaluaciones.resultado', compact(
            'evaluacion',
            'resultado',
            'respuestas',
            'modulo',
            'puntaje',
            'preguntas'
        ));
    }
}
