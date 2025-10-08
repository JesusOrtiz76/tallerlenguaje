<?php

namespace App\Http\Middleware;

use Closure;
use Carbon\Carbon;

class CheckRegisterDate
{
    public function handle($request, Closure $next)
    {
        // Configurar la localización a español para el formato de fecha
        Carbon::setLocale('es');

        $currentDate = Carbon::now();

        // Parseamos fechas y ajustamos extremos
        $registerStartDate = Carbon::createFromFormat('Y/m/d', env('REGISTER_START_DATE'))
            ->startOfDay();
        $registerEndDate   = Carbon::createFromFormat('Y/m/d', env('REGISTER_END_DATE'))
            ->endOfDay();

        $formattedStartDate = $registerStartDate->isoFormat('dddd D [de] MMMM [de] Y');
        $formattedEndDate   = $registerEndDate->isoFormat('dddd D [de] MMMM [de] Y');

        if ($currentDate->lt($registerStartDate)) {
            return redirect()
                ->route('login')
                ->with('warning', 'El periodo de registro inicia el ' . $formattedStartDate);
        }

        if ($currentDate->gt($registerEndDate)) {
            return redirect()->to(route('welcome') . '#ayuda')
                ->with('warning', 'El periodo de registro finalizó el ' . $formattedEndDate .
                    '. Consulta nuestro apartado de preguntas frecuentes.');
        }

        return $next($request);
    }
}
