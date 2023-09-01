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
        $registerStartDate = Carbon::createFromFormat('Y/m/d', env('REGISTER_START_DATE'));
        $registerEndDate = Carbon::createFromFormat('Y/m/d', env('REGISTER_END_DATE'));

        $formattedStartDate = $registerStartDate->isoFormat('dddd D [de] MMMM [de] Y');
        $formattedEndDate = $registerEndDate->isoFormat('dddd D [de] MMMM [de] Y');

        if ($currentDate->lt($registerStartDate)) {
            return redirect()
                ->route('login')
                ->with('warning', 'El periodo de registro inicia el ' . $formattedStartDate);
        }

        if ($currentDate->gt($registerEndDate)) {
            return redirect()
                ->route('login')
                ->with('warning', 'El periodo de registro finalizó el ' . $formattedEndDate);
        }

        return $next($request);
    }
}
