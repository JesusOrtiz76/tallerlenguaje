<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Curso;
use App\Models\Inscripcion;

class CursoController extends Controller
{
    // Inscribirse al curso
    public function inscribirse($curso_id)
    {

        // Obtener el objeto de curso correspondiente
        $curso = Curso::find($curso_id);

        // Verificar que el objeto de curso es válido
        if ($curso) {

            // Verificar que el usuario no esté ya inscrito en el curso
            if (Inscripcion::where('curso_id', $curso_id)->where('user_id', Auth::id())->first()) {
                return redirect()->route('home')
                    ->with('warning', 'Ya estás inscrito en este curso');
            } else {
                // Matricular al usuario en el curso
                $curso->users()->attach(Auth::user()->id);
                return redirect()->route('home')
                    ->with('success', 'Te has inscrito en este curso ' . $curso->nombre);
            }
        } else {
            // Redirigir al usuario a la página de inicio con un mensaje de error
            return redirect()->route('home')
                ->with('error', 'No se pudo encontrar el curso que deseas matricularte');
        }
    }
}
