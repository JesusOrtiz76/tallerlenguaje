<?php

namespace App\Traits;

use Carbon\Carbon;

trait VerificaAccesoTrait
{
    /**
     * Verifica las fechas de acceso al curso.
     */
    public function verificarFechasAcceso($curso)
    {
        // Configurar la localización a español
        Carbon::setLocale('es');

        $currentDate     = Carbon::now();
        $accessStartDate = Carbon::createFromFormat('Y-m-d', $curso->ofecha_inicio)
            ->startOfDay();
        $accessEndDate   = Carbon::createFromFormat('Y-m-d', $curso->ofecha_fin)
            ->endOfDay();

        if ($currentDate->lt($accessStartDate)) {
            $formattedStartDate = $accessStartDate->isoFormat('dddd D [de] MMMM [de] Y');
            return [
                'error'   => true,
                'message' => 'El periodo para el acceso a este curso inicia el ' . $formattedStartDate
            ];
        }

        if ($currentDate->gt($accessEndDate)) {
            $formattedEndDate = $accessEndDate->isoFormat('dddd D [de] MMMM [de] Y');
            return [
                'error'   => true,
                'message' => 'El periodo para el acceso a este curso finalizó el ' . $formattedEndDate
            ];
        }

        return ['error' => false];
    }

    /**
     * Verifica si el usuario está inscrito en el curso.
     */
    public function verificarInscripcion($user, $curso)
    {
        if (!$user->cursos()->where('curso_id', $curso->id)->exists()) {
            return [
                'error' => true,
                'message' => 'No estás inscrito en este curso'
            ];
        }

        return ['error' => false];
    }

    /**
     * Verifica tanto la inscripción como las fechas de acceso.
     */
    public function verificarAccesoCurso($user, $curso)
    {
        // Verificar fechas de acceso
        $verificacionFechas = $this->verificarFechasAcceso($curso);
        if ($verificacionFechas['error']) {
            return $verificacionFechas;
        }

        // Verificar si el usuario está inscrito
        $verificacionInscripcion = $this->verificarInscripcion($user, $curso);
        if ($verificacionInscripcion['error']) {
            return $verificacionInscripcion;
        }

        return ['error' => false];
    }
}
