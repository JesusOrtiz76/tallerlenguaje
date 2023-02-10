<?php

namespace App\Http\Controllers;

use App\Models\Modulo;
use App\Models\Bloque;

class ModuloController extends Controller
{
    public function show(Modulo $modulo)
    {
        $secciones = Bloque::where('modulo_id', $modulo->id)->get();
        return view('modulos.show', compact('modulo', 'secciones'));
    }
}
