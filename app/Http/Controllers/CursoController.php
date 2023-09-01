<?php

namespace App\Http\Controllers;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\Curso;
use App\Models\Inscripcion;

class CursoController extends Controller
{
    public function inscribirse($curso_id)
    {
        $currentDate = Carbon::now();

        // Obtener el objeto de curso correspondiente
        $curso = Curso::find($curso_id);

        // Verificar que el objeto de curso es válido
        if (!$curso) {
            // Redirigir al usuario a la página de inicio con un mensaje de error
            return redirect()->route('home')
                ->with('error', 'No se pudo encontrar el curso que deseas matricularte');
        }

        // Comprobar las fechas de acceso
        $accessStartDate = Carbon::createFromFormat('Y-m-d', $curso->fecha_inicio);
        $accessEndDate = Carbon::createFromFormat('Y-m-d', $curso->fecha_fin);

        // Configurar la localización a español para el formato de fecha
        Carbon::setLocale('es');

        if ($currentDate->lt($accessStartDate)) {
            $formattedStartDate = $accessStartDate->isoFormat('dddd D [de] MMMM [de] Y');
            return redirect()->route('home')->with('warning', 'El periodo para el acceso a este curso inicia el ' . $formattedStartDate);
        }

        if ($currentDate->gt($accessEndDate)) {
            $formattedEndDate = $accessEndDate->isoFormat('dddd D [de] MMMM [de] Y');
            return redirect()->route('home')->with('warning', 'El periodo para el acceso a este curso finalizó el ' . $formattedEndDate);
        }

        // Verificar que el usuario no esté ya inscrito en el curso
        if (Inscripcion::where('curso_id', $curso_id)->where('user_id', Auth::id())->first()) {
            return redirect()->route('home')
                ->with('warning', 'Ya estás inscrito en este curso');
        }

        // Matricular al usuario en el curso
        $curso->users()->attach(Auth::user()->id);
        return redirect()->route('home')
            ->with('success', 'Te has inscrito en este curso ' . $curso->nombre);
    }
}
