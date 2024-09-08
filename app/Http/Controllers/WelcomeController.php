<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

class WelcomeController extends Controller
{
    public function index()
    {
        // Obtener el tiempo de caché global desde el archivo .env
        $cacheGlobalExpiration = env('CACHE_GLOBAL_EXPIRATION', 60);

        // Definir la clave de caché para el primer curso
        $cursoCacheKey = 'primer_curso_disponible';

        // Obtener o almacenar en caché el primer curso disponible
        $curso = Cache::remember($cursoCacheKey, now()->addMinutes($cacheGlobalExpiration), function () {
            return Curso::first();
        });

        // Obtener las fechas del periodo de registro desde las variables de entorno
        $registerStartDate = Carbon::createFromFormat('Y/m/d', env('REGISTER_START_DATE'));
        $registerEndDate   = Carbon::createFromFormat('Y/m/d', env('REGISTER_END_DATE'));

        // Obtener las fechas de acceso del primer curso (inicio y fin)
        $accessStartDate   = Carbon::createFromFormat('Y-m-d', $curso->ofecha_inicio);
        $accessEndDate     = Carbon::createFromFormat('Y-m-d', $curso->ofecha_fin);

        // Configurar la localización a español para formatear las fechas correctamente
        Carbon::setLocale('es');

        // Formatear las fechas para mostrarlas en el formato "día de mes de año"
        $formattedStartRegisterDate = $this->formatDate($registerStartDate);
        $formattedEndRegisterDate   = $this->formatDate($registerEndDate);
        $formattedStartDate         = $this->formatDate($accessStartDate);
        $formattedEndDate           = $this->formatDate($accessEndDate);

        // Retornar la vista 'welcome' con las fechas formateadas
        return view(
            'welcome', compact(
                'formattedStartRegisterDate',
                'formattedEndRegisterDate',
                'formattedStartDate',
                'formattedEndDate'
            )
        );
    }

    /**
     * Formatear la fecha en el formato "día de mes de año"
     */
    private function formatDate($date)
    {
        return $date->isoFormat('dddd D [de] MMMM [de] Y');
    }
}
