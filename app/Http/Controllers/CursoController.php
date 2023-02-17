<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Curso;
use App\Models\Inscripcion;
use Illuminate\Support\Facades\View;

class CursoController extends Controller
{

    public function __construct()
    {
        $cursos = Curso::all();
        View::share('cursos', $cursos);
    }

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

    public function inscribirse($curso_id)
    {

        // Obtener el objeto de curso correspondiente
        $curso = Curso::find($curso_id);

        // Verificar que el objeto de curso es válido
        if ($curso) {

            // Verificar que el usuario no esté ya matriculado en el curso
            if (Inscripcion::where('curso_id', $curso_id)->where('user_id', Auth::id())->first()) {
                return redirect()->route('cursos.index', $curso_id)->with('warning', 'Ya estás matriculado en este curso');
            } else {
                // Matricular al usuario en el curso
                $curso->users()->attach(Auth::user()->id);
                return redirect()->route('cursos.index', $curso_id)->with('success', 'Te has matriculado en este curso');
            }
        } else {
            // Redirigir al usuario a la página de inicio con un mensaje de error
            return redirect()->route('home')->with('error', 'No se pudo encontrar el curso que deseas matricularte');
        }
    }
}
