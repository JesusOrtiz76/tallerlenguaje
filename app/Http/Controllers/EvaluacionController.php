<?php

namespace App\Http\Controllers;

use App\Models\Evaluacion;
use App\Models\Modulo;
use App\Models\Opcion;
use App\Models\Resultado;
use App\Traits\VerificaAccesoTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class EvaluacionController extends Controller
{
    use VerificaAccesoTrait;

    // Obtener contenido de la evaluación (Preguntas y respuestas)
    public function show($id)
    {
        // Obtener el usuario autenticado
        $user = Auth::user();

        // Obtener el tiempo de caché global desde el archivo .env
        $cacheGlobalExpiration = env('CACHE_GLOBAL_EXPIRATION', 60);

        // Definir la clave de caché para la evaluación
        $evaluacionCacheKey = 'evaluacion_' . $id . '_user_' . $user->id;

        // Obtener o almacenar en caché la evaluación y sus preguntas
        $evaluacionData = Cache::remember($evaluacionCacheKey, now()->addMinutes($cacheGlobalExpiration), function () use ($id) {
            $evaluacion = Evaluacion::where('id', $id)->with('modulo')->firstOrFail();
            return [
                'evaluacion' => $evaluacion,
                'preguntas' => $evaluacion->preguntas()->with('opciones')->get()
            ];
        });

        $evaluacion = $evaluacionData['evaluacion'];
        $preguntas = $evaluacionData['preguntas'];

        // Obtener el módulo y curso relacionados
        $modulo = $evaluacion->modulo;
        $curso = $modulo->curso;

        // Usar el trait para verificar tanto las fechas de acceso como la inscripción del usuario
        $resultado = $this->verificarAccesoCurso($user, $curso);
        if ($resultado['error']) {
            return redirect()->route('home')->with('warning', $resultado['message']);
        }

        // Verificar si hay preguntas
        if ($preguntas->isEmpty()) {
            return redirect()->back()->with('warning', 'No hay preguntas disponibles en esta evaluación');
        }

        $numeroPreguntas = $evaluacion->onumero_preguntas;
        $preguntasMostradas = $preguntas->take($numeroPreguntas);

        // Verificar si el usuario ha accedido previamente a la evaluación y, si no, agregar primer intento
        $pivot = $user->evaluaciones()->where('evaluacion_id', $evaluacion->id)->first();
        if (!$pivot) {
            DB::transaction(function () use ($user, $evaluacion) {
                // Crear el intento inicial (ointentos = 0)
                $user->evaluaciones()->attach($evaluacion->id, ['ointentos' => 0]);
            });
            $pivot = $user->evaluaciones()->where('evaluacion_id', $evaluacion->id)->first();
            if (!$pivot) {
                return redirect()->back()->with('error', 'Error al registrar el intento inicial de la evaluación. Por favor, intenta de nuevo.');
            }
        }

        // Verificar si se han agotado los intentos
        if ($pivot->pivot->ointentos >= $evaluacion->ointentos_max) {
            return redirect()->route('evaluaciones.resultado', [$evaluacion->modulo_id, $evaluacion->id])
                ->with('warning', 'Ya has agotado tus intentos para esta evaluación');
        }

        // Cargar la vista con las preguntas y la evaluación
        return view('evaluaciones.show', [
            'modulo' => $evaluacion->modulo,
            'evaluacion' => $evaluacion,
            'preguntas' => $preguntasMostradas,
            'pivot' => $pivot
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

        return view('evaluaciones.resultado', compact(
            'evaluacion',
            'resultado',
            'respuestas',
            'modulo',
            'puntaje',
            'preguntas'));
    }
}
