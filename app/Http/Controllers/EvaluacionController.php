<?php

namespace App\Http\Controllers;

use App\Models\Evaluacion;
use App\Models\Modulo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EvaluacionController extends Controller
{
    public function show(Evaluacion $evaluacion)
    {
        return view('evaluaciones.show', compact('evaluacion'));
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
