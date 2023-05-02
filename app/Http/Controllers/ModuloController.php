<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Models\Modulo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class ModuloController extends Controller
{
    public function __construct()
    {
        $cursos = Curso::with('modulos')->get();
        View::share('cursos', $cursos);
    }

    public function index(Curso $curso)
    {
        $modulos = $curso->modulos;

        if ($modulos->isEmpty()) {
            return redirect()->back()->with('warning', 'No hay módulos registrados en este curso.');
        } else {
            return view('modulos.index', compact('curso', 'modulos'));
        }
    }

    public function show(Curso $curso, Modulo $modulo)
    {
        // Obtener el usuario autenticado
        $user = Auth::user();

        $temas = $modulo->temas;
        $evaluaciones = $modulo->evaluaciones;
        return view('modulos.show', compact('curso', 'modulo', 'temas', 'evaluaciones', 'user'));
    }

}
