<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use App\Models\Curso;
use App\Models\Inscripcion;
use App\Traits\VerificaAccesoTrait;

class CursoController extends Controller
{
    use VerificaAccesoTrait;

    public function inscribirse($curso_id)
    {
        // Obtener el tiempo de caché global desde el archivo .env
        $cacheGlobalExpiration = env('CACHE_GLOBAL_EXPIRATION', 60);

        // Definir la clave de caché para el curso
        $cursoCacheKey = 'curso_' . $curso_id;

        // Obtener o almacenar en caché el objeto del curso
        $curso = Cache::remember($cursoCacheKey, now()->addMinutes($cacheGlobalExpiration), function () use ($curso_id) {
            return Curso::find($curso_id);
        });

        // Verificar que el objeto de curso es válido
        if (!$curso) {
            return redirect()->route('home')
                ->with('error', 'No se pudo encontrar el curso que deseas matricularte');
        }

        // Usar el trait para verificar solo las fechas de acceso al curso
        $resultado = $this->verificarFechasAcceso($curso);
        if ($resultado['error']) {
            return redirect()->route('home')->with('warning', $resultado['message']);
        }

        // Verificar que el usuario no esté ya inscrito en el curso
        if (Inscripcion::where('curso_id', $curso_id)->where('user_id', Auth::id())->first()) {
            return redirect()->route('home')
                ->with('warning', 'Ya estás inscrito en este curso');
        }

        // Matricular al usuario en el curso
        $curso->users()->attach(Auth::user()->id);

        return redirect()->route('home')
            ->with('success', 'Te has inscrito en este curso ' . $curso->onombre);
    }
}
