<?php

namespace App\Http\Controllers;

use App\Models\Curso;

class CursoController extends Controller
{
    public function index()
    {
        $cursos = Curso::all();
        return view('cursos.index', compact('cursos'));
    }

    public function show(Curso $curso)
    {
        $modulos = $curso->modulos;
        return view('cursos.show', compact('curso', 'modulos'));
    }
}
