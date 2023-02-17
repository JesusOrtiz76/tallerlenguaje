<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Curso;
use App\Models\Modulo;
use Illuminate\Support\Facades\View;

class ModuloController extends Controller
{
    public function __construct()
    {
        $cursos = Curso::all();
        View::share('cursos', $cursos);
    }

    public function index($curso_id)
    {
        $curso = Curso::find($curso_id);
        $modulos = Modulo::where('curso_id', $curso_id)->get();
        return view('modulos.index', compact('curso', 'modulos'));
    }

    public function show(Modulo $modulo)
    {
        $curso = $modulo->curso;

        $user = Auth::user();

        $temas = $modulo->temas;
        $evaluacion = $modulo->evaluacion;

        if ($evaluacion) {
            $evaluado = $evaluacion->users->contains($user);

            if (!$evaluado) {
                return redirect()->route('evaluaciones.show', $evaluacion);
            }
        }

        return view('modulos.show', compact('curso', 'modulo', 'temas', 'evaluacion'));
    }
}
