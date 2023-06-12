<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Models\Modulo;
use Illuminate\Support\Facades\Auth;

class ModuloController extends Controller
{
    // Obtener módulos
    public function index(Curso $curso)
    {
        $modulos = $curso->modulos;

        if ($modulos->isEmpty()) {
            return redirect()->back()->with('warning', 'No hay módulos registrados en este curso.');
        } else {
            return view('modulos.index', compact('curso', 'modulos'));
        }
    }

    // Obtener detalles del módulo
    public function show(Curso $curso, Modulo $modulo)
    {
        // Obtener el usuario autenticado
        $user = Auth::user();

        $temas = $modulo->temas;
        $evaluaciones = $modulo->evaluaciones;
        return view('modulos.show', compact('curso', 'modulo', 'temas', 'evaluaciones', 'user'));
    }

}
