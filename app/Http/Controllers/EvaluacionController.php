<?php

namespace App\Http\Controllers;

use App\Models\Evaluacion;
use App\Models\Modulo;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class EvaluacionController extends Controller
{
    public function show(Request $request, $id)
    {
        // Obtener la evaluación del usuario logueado
        $evaluacion = Evaluacion::where('id', $id)
            ->whereHas('users', function ($query) use ($request) {
                $query->where('users.id', $request->user()->id);
            })
            //->with('topics.questions')
            ->first();

        // Verificar si la evaluación existe
        if (!$evaluacion) {
            return redirect()->back()->with('error', 'La evaluación no existe.');
        }

        // Verificar si la evaluación está completa
        $user_evaluacion = $evaluacion->users->where('id', $request->user()->id)->first();

        if (!$user_evaluacion) {
            return redirect()->back()->with('warning', 'No ha completado la evaluación.');
        }

        if ($user_evaluacion->pivot->intentos >= 4) {
            return redirect()->back()->with('warning', 'Ha alcanzado el máximo de intentos para esta evaluación.');
        }

        /*
        if (Carbon::now()->diffInMinutes($user_evaluacion->updated_at) > 15) {

            return Carbon::now()->diffInMinutes($user_evaluacion->updated_at);
            //return redirect()->back()->with('warning', 'Ha excedido el tiempo máximo para completar esta evaluación.');
        }
        */

        // Calcular el resultado de la evaluación
        $result = $evaluacion->getResult($request->user()->id);

        return $result;

        // Mostrar la vista con el resultado de la evaluación
        return view('evaluacions.show', compact('evaluacion', 'result'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $modulo = Modulo::find($request->modulo_id);
        $evaluacion = $modulo->evaluacion()->where('activo', 1)->first();



        if (!$evaluacion) {
            return redirect()->back()->with('error', 'No hay evaluación disponible en este módulo');
        }

        $pivot = $user->evaluaciones()->find($evaluacion->id);

        if ($pivot && $pivot->pivot->intentos >= 3) {
            return redirect()->back()->with('warning', 'Ya has agotado tus intentos para esta evaluación');
        }

        if (!$pivot) {

            $user->evaluaciones()->attach($evaluacion->id, ['intentos' => 1, 'resultados' => 0]);
        } else {
            $pivot->pivot->increment('intentos');
        }

        return redirect()->route('evaluaciones.show', $evaluacion->id)->with('success', 'La evaluación se ha iniciado correctamente');
    }
}
