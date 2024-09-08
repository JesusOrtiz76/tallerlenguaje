<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;

trait VerificaEvaluacionesCompletasTrait
{
    /**
     * Verifica si el usuario ha completado todas las evaluaciones de los módulos anteriores.
     * Si no, devuelve un mensaje con el nombre del módulo pendiente.
     *
     * @param  \App\Models\Modulo  $modulo
     * @return string|null
     */
    public function verificarModuloPendiente($modulo)
    {
        $user = Auth::user();
        $curso = $modulo->curso;
        $modulos = $curso->modulos->sortBy('id');
        $primerModulo = $modulos->first();

        // Si es el primer módulo, no hay módulos pendientes, permitir acceso
        if ($modulo->id === $primerModulo->id) {
            return null;
        }

        // Verificar que el usuario haya completado todas las evaluaciones de los módulos anteriores
        foreach ($modulos as $mod) {
            if ($mod->id === $modulo->id) {
                break;
            }

            foreach ($mod->evaluaciones as $evaluacion) {
                $pivot = $user->evaluaciones()->where('evaluacion_id', $evaluacion->id)->first();

                // No permitir avanzar si no ha completado intentos en evaluaciones anteriores
                if (!$pivot || $pivot->pivot->ointentos < 1) {
                    // Retornar mensaje con el nombre del módulo pendiente
                    return 'Debes completar todas las evaluaciones del "'
                        . $mod->onombre
                        . '" antes de continuar.';
                }
            }
        }

        return null; // Todo está completo
    }
}
