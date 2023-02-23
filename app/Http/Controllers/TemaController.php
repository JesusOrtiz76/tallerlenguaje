<?php

namespace App\Http\Controllers;

use App\Models\Tema;

class TemaController extends Controller
{
    public function show($temaId)
    {
        $tema = Tema::findOrFail($temaId);
        return view('temas.show', compact('tema'));
    }

}
