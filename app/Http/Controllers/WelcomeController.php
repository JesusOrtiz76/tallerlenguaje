<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use Illuminate\Support\Carbon;

class WelcomeController extends Controller
{
    public function index()
    {
        // Obtener el primer curso
        $curso = Curso::first();

        // Comprobar fechas del periodo de registro
        $registerStartDate = Carbon::createFromFormat('Y/m/d', env('REGISTER_START_DATE'));
        $registerEndDate   = Carbon::createFromFormat('Y/m/d', env('REGISTER_END_DATE'));

        // Comprobar las fechas de acceso del primer curso
        $accessStartDate   = Carbon::createFromFormat('Y-m-d', $curso->fecha_inicio);
        $accessEndDate     = Carbon::createFromFormat('Y-m-d', $curso->fecha_fin);

        // Configurar la localización a español para el formato de fecha
        Carbon::setLocale('es');

        $formattedStartRegisterDate = $this->formatDate($registerStartDate);
        $formattedEndRegisterDate   = $this->formatDate($registerEndDate);
        $formattedStartDate         = $this->formatDate($accessStartDate);
        $formattedEndDate           = $this->formatDate($accessEndDate);

        return view(
            'welcome', compact(
                'formattedStartRegisterDate',
                'formattedEndRegisterDate',
                'formattedStartDate',
                'formattedEndDate'
            )
        );
    }

    private function formatDate($date)
    {
        return $date->isoFormat('dddd D [de] MMMM [de] Y');
    }
}
