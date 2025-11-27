<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UnidadAdministrativa;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    /**
     * Pantalla de filtros de reportes.
     */
    public function index()
    {
        // Ya no cargamos todos los centros (son muchos).
        $unidades = UnidadAdministrativa::orderBy('onombre')->get();

        return view('admin.reportes.index', compact('unidades'));
    }

    /**
     * Exporta el reporte en CSV (abrible en Excel).
     */
    public function export(Request $request): StreamedResponse
    {
        $validated = $request->validate([
            'tipo' => [
                'required',
                'string',
                'in:centro_trabajo,unidad_administrativa,aprobados,reprobados',
            ],
            'oclave' => [
                'nullable',
                'required_if:tipo,centro_trabajo',
                'string',
                'size:10',
                // si quieres el mismo regex que en registro, puedes agregarlo aquí
                // 'regex:/^15[A-Z]{3}\d{4}[A-Z]$/',
                'exists:r12centrostrabajo,oclave',
            ],
            'unidadadministrativa_id' => [
                'nullable',
                'required_if:tipo,unidad_administrativa',
                'exists:r12unidadadministrativa,id',
            ],
        ]);

        $query = User::with(['centroTrabajo', 'unidadAdministrativa', 'scores'])
            ->where('orol', 'user');

        $filenameSuffix = '';

        switch ($validated['tipo']) {
            case 'centro_trabajo':
                $oclave = $validated['oclave'];
                // filtramos por la CCT del centro de trabajo
                $query->whereHas('centroTrabajo', function ($q) use ($oclave) {
                    $q->where('oclave', $oclave);
                });
                $filenameSuffix = '_centro_trabajo_' . $oclave;
                break;

            case 'unidad_administrativa':
                $query->where('unidadadministrativa_id', $validated['unidadadministrativa_id']);
                $filenameSuffix = '_unidad_adm_' . $validated['unidadadministrativa_id'];
                break;

            case 'aprobados':
                // Usuarios que han completado y tienen score >= 80%
                $query->whereHas('scores', function ($q) {
                    $q->where('score_percentage', '>=', 80);
                });
                $filenameSuffix = '_aprobados_80';
                break;

            case 'reprobados':
                // Usuarios que han completado y tienen score < 80%
                $query->whereHas('scores', function ($q) {
                    $q->where('score_percentage', '<', 80);
                });
                $filenameSuffix = '_reprobados_80';
                break;
        }

        $usuarios = $query->orderBy('name')->get();

        $filename = 'reporte_usuarios' . $filenameSuffix . '_' . now()->format('Ymd_His') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($usuarios) {
            $handle = fopen('php://output', 'w');

            // BOM para UTF-8 en Excel
            fwrite($handle, "\xEF\xBB\xBF");

            // Encabezados (solo los que pediste)
            fputcsv($handle, [
                'Nombre',
                'RFC',
                'Sexo',
                'Centro de trabajo',
                'Unidad administrativa',
                'Calificación',
            ]);

            foreach ($usuarios as $user) {
                $maxScore = null;

                if ($user->relationLoaded('scores')) {
                    $maxScore = $user->scores->max('score_percentage');
                }

                fputcsv($handle, [
                    $user->name,
                    $user->orfc,
                    $user->sexo,
                    optional($user->centroTrabajo)->oclave,
                    optional($user->unidadAdministrativa)->onombre,
                    $maxScore !== null ? round($maxScore, 2) : null,
                ]);
            }

            fclose($handle);
        };

        return response()->streamDownload($callback, $filename, $headers);
    }
}
