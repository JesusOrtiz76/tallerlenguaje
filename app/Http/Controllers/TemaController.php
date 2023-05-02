<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Models\Modulo;
use App\Models\Tema;
use Illuminate\Support\Facades\View;

class TemaController extends Controller
{
    public function __construct()
    {
        $cursos = Curso::with('modulos')->get();
        View::share('cursos', $cursos);
    }

    public function show($temaId)
    {
        $tema = Tema::findOrFail($temaId);
        $modulo = Modulo::find($tema->modulo_id);
        return view('temas.show', compact('tema', 'modulo'));
    }

}
