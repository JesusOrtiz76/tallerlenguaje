<?php

namespace App\Http\Controllers;

use App\Models\Evaluacion;

class PreguntaController extends Controller
{
    public function index(Evaluacion $evaluacion)
    {
        $preguntas = $evaluacion->preguntas()->inRandomOrder()->get();

        return view('preguntas.index', compact('evaluacion', 'preguntas'));
    }
}
