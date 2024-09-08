<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Models\Modulo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class ModuloController extends Controller
{
    // Obtener módulos
    public function index(Curso $curso)
    {
        // Obtener el tiempo de caché global desde el archivo .env
        $cacheGlobalExpiration = env('CACHE_GLOBAL_EXPIRATION', 60);

        // Definir la clave de caché para los módulos de este curso
        $modulosCacheKey = 'modulos_curso_' . $curso->id;

        // Obtener o almacenar en caché los módulos del curso
        $modulos = Cache::remember($modulosCacheKey, now()->addMinutes($cacheGlobalExpiration), function () use ($curso) {
            return $curso->modulos;
        });

        if ($modulos->isEmpty()) {
            return redirect()->back()->with('warning', 'No hay módulos registrados en este curso.');
        } else {
            return view('modulos.index', compact('curso', 'modulos'));
        }
    }

    // Obtener detalles del módulo
    public function show(Curso $curso, Modulo $modulo)
    {
        // Obtener el usuario autenticado
        $user = Auth::user();

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

        return view('modulos.show',
            compact('curso', 'modulo', 'temas', 'evaluaciones', 'user'));
    }
}
