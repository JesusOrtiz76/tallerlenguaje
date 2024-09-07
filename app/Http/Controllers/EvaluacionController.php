<?php

namespace App\Http\Controllers;

use App\Models\Evaluacion;
use App\Models\Modulo;
use App\Models\Opcion;
use App\Models\Resultado;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class EvaluacionController extends Controller
{
    // Obtener contenido de la evaluación (Preguntas y respuestas)
    public function show($id)
    {
        // Obtener el usuario autenticado
        $user = Auth::user();

        // Obtener la evaluación con el módulo correspondiente
        $evaluacion = Evaluacion::where('id', $id)->with('modulo')->firstOrFail();

        // Obtener el curso al que pertenece la evaluación
        $curso = $evaluacion->modulo->curso;

        // Comprobar periodo de evaluación
        $currentDate = Carbon::now();
        $accessStartDate = Carbon::createFromFormat('Y-m-d', $curso->ofecha_inicio);
        $accessEndDate = Carbon::createFromFormat('Y-m-d', $curso->ofecha_fin);

        Carbon::setLocale('es');

        if ($currentDate->lt($accessStartDate)) {
            return redirect()->route('home')
                ->with('warning', 'El periodo para el acceso a este curso inicia el ' . $accessStartDate->isoFormat('dddd D [de] MMMM [de] Y'));
        }

        if ($currentDate->gt($accessEndDate)) {
            return redirect()->route('home')
                ->with('warning', 'El periodo para el acceso a este curso finalizó el ' . $accessEndDate->isoFormat('dddd D [de] MMMM [de] Y'));
        }

        // Verificar si el usuario está inscrito en el curso
        if (!$user->cursos()->where('curso_id', $curso->id)->exists()) {
            return redirect()->route('home')->with('warning', 'No estás inscrito en este curso');
        }

        // Obtener todas las preguntas de la evaluación con opciones
        $preguntas = $evaluacion->preguntas()->with('opciones')->get();

        // Verificar si existen preguntas
        if ($preguntas->isEmpty()) {
            return redirect()->back()->with('warning', 'No hay preguntas disponibles en esta evaluación');
        }

        $numeroPreguntas = $evaluacion->onumero_preguntas;
        $preguntasMostradas = $preguntas->take($numeroPreguntas);

        // Verificar si el usuario ha accedido previamente a la evaluación y, si no, agregar primer intento
        $pivot = $user->evaluaciones()->where('evaluacion_id', $evaluacion->id)->first();

        if (!$pivot) {
            $user->evaluaciones()->attach($evaluacion->id, ['ointentos' => 0]);
            $pivot = $user->evaluaciones()->where('evaluacion_id', $evaluacion->id)->first();
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
        // Obtener usuario y evaluación
        $user = Auth::user();
        $evaluacion = $user->evaluaciones()->findOrFail($evaluacion_id);
        $modulo = Modulo::find($evaluacion->modulo_id);

        // Verificar si el usuario ha alcanzado los intentos permitidos
        if ($evaluacion->pivot->ointentos >= $evaluacion->ointentos_max) {
            return redirect()->back()
                ->with('warning', 'Has alcanzado la cantidad máxima de intentos para esta evaluación.');
        }

        // Obtener las respuestas del usuario
        $respuestas = $request->input('respuestas', []);

        // Verificar que se hayan contestado todas las preguntas
        $numeroPreguntasMostradas = $evaluacion->onumero_preguntas;

        if (count($respuestas) !== $numeroPreguntasMostradas) {
            return redirect()
                ->back()->with('warning', 'Completa todas las preguntas.');
        }

        // Incrementar intentos del usuario
        $user->evaluaciones()->updateExistingPivot($evaluacion->id, [
            'ointentos' => $evaluacion->pivot->ointentos + 1
        ]);

        // Guardar respuestas
        $resultado = Resultado::updateOrCreate(
            ['user_id' => $user->id, 'evaluacion_id' => $evaluacion->id],
            ['orespuestas' => json_encode(array_values($respuestas))]
        );

        // Redirigir al usuario
        return redirect()->route('modulos.show', $modulo->id)
            ->with('success', 'Evaluación completada.');
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
