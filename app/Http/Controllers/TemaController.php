<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Models\Modulo;
use App\Models\Tema;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class TemaController extends Controller
{
    // Obtener contenido del tema
    public function show($temaId)
    {
        // Obtener el usuario autenticado
        $user = Auth::user();

        // Obtener el tema y el módulo al que pertenece
        $tema = Tema::findOrFail($temaId);
        $modulo = Modulo::find($tema->modulo_id);

        // Obtener el curso al que pertenece el módulo
        $curso = Curso::find($modulo->curso_id);

        // Verificar si el usuario está inscrito en el curso
        if (!$user->cursos()->where('cursos.id', $curso->id)->exists()) {
            return redirect()->route('home')->with('warning', 'No estás inscrito en este curso');
        }

        // Mostrar el tema seleccionado
        return view('temas.show', compact('tema', 'modulo'));
    }
}
