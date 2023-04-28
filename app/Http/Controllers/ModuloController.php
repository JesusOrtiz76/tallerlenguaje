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
        $modulos = Modulo::all();
        View::share('modulos', $modulos);
    }

    public function index(Curso $curso)
    {
        $modulos = $curso->modulos;
        return view('modulos.index', compact('curso', 'modulos'));
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
