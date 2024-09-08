<?php

namespace App\Http\Controllers;

use App\Models\Tema;
use App\Traits\VerificaAccesoTrait;
use App\Traits\VerificaEvaluacionesCompletasTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class TemaController extends Controller
{
    use VerificaAccesoTrait, VerificaEvaluacionesCompletasTrait;

    public function show($temaId)
    {
        // Obtener el usuario autenticado
        $user = Auth::user();

        // Obtener el tiempo de caché global desde el archivo .env
        $cacheGlobalExpiration = env('CACHE_GLOBAL_EXPIRATION', 60);

        // Definir la clave de caché para el tema, módulo y curso
        $temaCacheKey = 'tema_' . $temaId . '_modulo_curso';

        // Obtener o almacenar en caché el tema, módulo y curso
        $temaData = Cache::remember($temaCacheKey, now()->addMinutes($cacheGlobalExpiration), function () use ($temaId) {
            $tema = Tema::with('modulo.curso')->findOrFail($temaId);
            return [
                'tema' => $tema,
                'modulo' => $tema->modulo,
                'curso' => $tema->modulo->curso
            ];
        });

        // Extraer los datos del tema, módulo y curso del array cacheado
        $tema = $temaData['tema'];
        $modulo = $temaData['modulo'];
        $curso = $temaData['curso'];

        // Verificar acceso usando el trait
        $resultado = $this->verificarAccesoCurso($user, $curso);
        if ($resultado['error']) {
            return redirect()->route('home')->with('warning', $resultado['message']);
        }

        // Verificar si hay algún módulo pendiente antes del actual
        $mensajePendiente = $this->verificarModuloPendiente($modulo);

        // Si hay un mensaje de módulo pendiente, mostrarlo
        if ($mensajePendiente) {
            return redirect()->back()->with('warning', $mensajePendiente);
        }

        // Mostrar el tema seleccionado
        return view('temas.show', compact('tema', 'modulo'));
    }
}
