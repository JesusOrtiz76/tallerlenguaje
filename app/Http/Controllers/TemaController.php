<?php

namespace App\Http\Controllers;

use App\Models\Modulo;
use App\Models\Tema;
use Illuminate\Support\Facades\View;

class TemaController extends Controller
{
    public function __construct()
    {
        $modulos = Modulo::all();
        View::share('modulos', $modulos);
    }
    public function show($temaId)
    {
        $tema = Tema::findOrFail($temaId);
        return view('temas.show', compact('tema'));
    }

}
