<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    // Obtener cursos y módulos (Para el menú lateral)
    public function __construct()
    {
        // Obtener el tiempo de caché global desde el archivo .env
        $cacheGlobalExpiration = env('CACHE_GLOBAL_EXPIRATION', 60);

        // Definir la clave de caché para los cursos
        $cursoCacheKey = 'cursos_con_modulos';

        // Obtener o almacenar en caché los cursos con sus módulos
        $cursos = Cache::remember($cursoCacheKey, now()->addMinutes($cacheGlobalExpiration), function () {
            return Curso::with('modulos')->get();
        });

        // Compartir los cursos con todas las vistas
        View::share('cursos', $cursos);
    }
}
