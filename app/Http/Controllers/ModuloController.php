<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Models\Modulo;
use App\Traits\VerificaEvaluacionesCompletasTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class ModuloController extends Controller
{
    use VerificaEvaluacionesCompletasTrait;

    // Obtener detalles del módulo
    public function show(Curso $curso, Modulo $modulo)
    {
        // Obtener el usuario autenticado
        $user = Auth::user();

        // Verificar si hay algún módulo pendiente antes del actual
        $mensajePendiente = $this->verificarModuloPendiente($modulo);

        // Si hay un mensaje de módulo pendiente, mostrarlo
        if ($mensajePendiente) {
            return redirect()->back()->with('warning', $mensajePendiente);
        }

        // Obtener el tiempo de caché global desde el archivo .env
        $cacheGlobalExpiration = env('CACHE_GLOBAL_EXPIRATION', 60);

        // Definir la clave de caché para los temas y evaluaciones del módulo
        $moduloCacheKey = 'modulo_' . $modulo->id . '_temas_evaluaciones';

        // Obtener o almacenar en caché los temas y evaluaciones del módulo
        $moduloData = Cache::remember($moduloCacheKey, now()->addMinutes($cacheGlobalExpiration), function () use ($modulo) {
            return [
                'temas' => $modulo->temas,
                'evaluaciones' => $modulo->evaluaciones
            ];
        });

        // Extraer temas y evaluaciones del array almacenado en caché
        $temas = $moduloData['temas'];
        $evaluaciones = $moduloData['evaluaciones'];

        // Retornar la vista con el curso original, no el derivado del módulo
        return view('modulos.show', compact('curso', 'modulo', 'temas', 'evaluaciones', 'user'));
    }
}
