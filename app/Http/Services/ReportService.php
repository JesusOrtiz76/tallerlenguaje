<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ReportService
{
    public static function generateReport($reportPath, $properties, $filename)
    {
        $data = [
            'reportPath' => $reportPath,
            'properties' => $properties,
            'filename' => $filename,
        ];

        try {
            // Realizar la solicitud POST al microservicio
            $response = Http::post(env('REPORT_SERVICE_URL'), $data);

            // Verificar si la solicitud fue exitosa
            if ($response->successful()) {
                return $response->json();
            } else {
                // Registrar el error para depuración
                Log::error('Error en el servicio de reportes', ['response' => $response->body()]);
                return [
                    'status' => 'warning',
                    'message' => 'La constancia no está disponible en este momento'
                ];
            }
        } catch (\Exception $e) {
            // Registrar la excepción para depuración
            Log::error('Excepción en ReportService', ['exception' => $e->getMessage()]);
            return [
                'status' => 'error',
                'message' => 'Error en el servicio de reportes'
            ];
        }
    }
}
