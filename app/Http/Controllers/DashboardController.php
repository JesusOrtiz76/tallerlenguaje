<?php

namespace App\Http\Controllers;

use App\Models\Modulo;
use App\Models\User;
use App\Models\Resultado;
use App\Models\Inscripcion;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Devuelve la vista del dashboard
        return view('admin.dashboard.index');
    }

    public function getDataIndex(Request $request)
    {
        // Obtener parámetros de fecha desde la query string
        $startDateParam = $request->query('fechainicial');
        $endDateParam   = $request->query('fechafinal');

        /*
         * Lógica de filtros:
         * - Si ambos campos están vacíos, se traen TODOS los datos (sin filtro de fecha).
         * - Si se envía solo uno de ellos, se establece:
         *      * Si falta la fecha inicial: se usa un límite inferior muy bajo.
         *      * Si falta la fecha final: se usa la fecha actual.
         */
        if (empty($startDateParam) && empty($endDateParam)) {
            $applyFilter = false;
        } else {
            $applyFilter = true;
            $startDate = $startDateParam ? Carbon::parse($startDateParam) : null;
            $endDate   = $endDateParam ? Carbon::parse($endDateParam) : Carbon::now();
        }

        // Contadores
        if ($applyFilter) {
            $filterStart = $startDate ? $startDate->copy()->startOfDay() : '1970-01-01';
            $filterEnd   = $this->getFilterEnd($endDate);

            $userCount          = User::whereBetween('created_at', [$filterStart, $filterEnd])->count();
            $inscripcionesCount = Inscripcion::whereBetween('created_at', [$filterStart, $filterEnd])->count();
            $resultadosCount    = Resultado::whereBetween('created_at', [$filterStart, $filterEnd])->count();
        } else {
            $userCount          = User::count();
            $inscripcionesCount = Inscripcion::count();
            $resultadosCount    = Resultado::count();
        }

        // Generar el rango de fechas para la gráfica
        if ($applyFilter) {
            $allDates = $this->generateDateRange(
                ($startDate ? $startDate->format('Y-m-d') : '1970-01-01'),
                ($endDate ? $endDate->format('Y-m-d') : Carbon::now()->format('Y-m-d'))
            );
        } else {
            $minDate = Resultado::min(DB::raw("DATE(created_at)"));
            $maxDate = Resultado::max(DB::raw("DATE(created_at)"));

            // Defensivo: si no hay datos aún, no construyas un rango vacío
            $allDates = ($minDate && $maxDate)
                ? $this->generateDateRange($minDate, $maxDate)
                : [];
        }

        // Consulta de respuestas por módulo y fecha
        $respuestasCountByDateAndModulo = $this->getRespuestasCountByDateAndModulo($applyFilter, $startDate ?? null, $endDate ?? null);

        // Agrupar los resultados por módulo y fecha
        $timeseriesData = $this->generateTimeseriesData($allDates, $respuestasCountByDateAndModulo);

        // Preparar datos para la gráfica de time series
        $chartData   = $this->prepareChartData($timeseriesData);
        $chartSchema = [
            ["name" => "Fecha", "type" => "date", "format" => "%Y-%m-%d"],
            ["name" => "Cantidad", "type" => "number"],
            ["name" => "Módulo", "type" => "string"]
        ];

        // Datos para la gráfica de anillos (donut)
        $modulos  = Modulo::with('evaluaciones')->get();
        $donutData = $this->prepareDonutData($modulos, $applyFilter, $startDate ?? null, $endDate ?? null);

        // Datos para la gráfica de calor (heatmap)
        $heatmapData = $this->generateHeatmapData($applyFilter, $startDate ?? null, $endDate ?? null);

        return response()->json(compact(
            'userCount',
            'inscripcionesCount',
            'resultadosCount',
            'chartData',
            'chartSchema',
            'donutData',
            'heatmapData'
        ));
    }

    /**
     * Si la fecha final es hoy se usa la hora actual; de lo contrario, el final del día.
     */
    private function getFilterEnd(Carbon $endDate)
    {
        return $endDate->isToday() ? Carbon::now() : $endDate->copy()->endOfDay();
    }

    private function generateDateRange($startDate, $endDate)
    {
        $allDates = [];
        $currentDate = Carbon::parse($startDate);
        $endDateParsed = Carbon::parse($endDate);

        while ($currentDate->lte($endDateParsed)) {
            $allDates[] = $currentDate->format('Y-m-d');
            $currentDate->addDay();
        }

        return $allDates;
    }

    private function getRespuestasCountByDateAndModulo($applyFilter, $startDate, $endDate)
    {
        $query = DB::table('r12resultados as rr')
            ->select(
                'rm.onombre as modulo',
                DB::raw("DATE_FORMAT(rr.created_at, '%Y-%m-%d') as date"),
                DB::raw('COUNT(*) as total')
            )
            ->leftJoin('r12evaluaciones as re', 'rr.evaluacion_id', '=', 're.id')
            ->leftJoin('r12modulos as rm', 're.modulo_id', '=', 'rm.id');

            if ($applyFilter) {
                $filterStart = $startDate ? $startDate->copy()->startOfDay() : '1970-01-01';
                $filterEnd   = $this->getFilterEnd($endDate);
                $query->whereBetween('rr.created_at', [$filterStart, $filterEnd]);
            }

        return $query->groupBy('rm.onombre', DB::raw("DATE_FORMAT(rr.created_at, '%Y-%m-%d')"))
            ->orderBy('date', 'asc')
            ->get();
    }

    private function generateTimeseriesData($allDates, $respuestasCountByDateAndModulo)
    {
        return $respuestasCountByDateAndModulo->groupBy('modulo')->map(function ($items, $modulo) use ($allDates) {
            $data = collect($allDates)->map(function ($date) use ($items) {
                $item = $items->firstWhere('date', $date);
                return [
                    'date'  => $date,
                    'count' => $item ? (int)$item->total : 0
                ];
            });

            return [
                'modulo' => $modulo,
                'data'   => $data
            ];
        })->values()->all();
    }

    private function prepareChartData($timeseriesData)
    {
        $chartData = [];
        foreach ($timeseriesData as $moduloData) {
            // etiqueta segura
            $labelModulo = trim((string)($moduloData['modulo'] ?? '')) !== '' ? (string)$moduloData['modulo'] : 'Sin módulo';

            foreach ($moduloData['data'] as $dataPoint) {
                // fecha segura (string YYYY-MM-DD), valor numérico
                $chartData[] = [
                    (string)$dataPoint['date'],
                    (int)$dataPoint['count'],
                    $labelModulo
                ];
            }
        }
        return $chartData;
    }

    private function prepareDonutData($modulos, $applyFilter, $startDate, $endDate)
    {
        $donutData = [];

        foreach ($modulos as $modulo) {
            $evaluacionesArray = [];
            foreach ($modulo->evaluaciones as $evaluacion) {
                if ($applyFilter) {
                    $filterStart = $startDate ? $startDate->copy()->startOfDay() : '1970-01-01';
                    $filterEnd   = $this->getFilterEnd($endDate);
                    $evaluationResultsCount = $evaluacion->resultados()
                        ->whereBetween('created_at', [$filterStart, $filterEnd])
                        ->count();
                } else {
                    $evaluationResultsCount = $evaluacion->resultados()->count();
                }

                $evaluacionesArray[] = [
                    "label" => $evaluacion->onombre,
                    "value" => $evaluationResultsCount
                ];
            }
            $totalResultsForModule = array_sum(array_column($evaluacionesArray, 'value'));
            $donutData[] = [
                "label"    => $modulo->onombre,
                "value"    => $totalResultsForModule,
                "category" => $evaluacionesArray
            ];
        }

        return $donutData;
    }

    private function generateHeatmapData($applyFilter, $startDate, $endDate)
    {
        $days = ["Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado"];
        $data = collect();
        for ($d = 0; $d < count($days); $d++) {
            for ($hour = 0; $hour < 24; $hour++) {
                $hourFormatted = str_pad($hour, 2, '0', STR_PAD_LEFT);
                $data->push([
                    "rowid"    => $days[$d],
                    "columnid" => "{$hourFormatted}:00",
                    "value"    => 0
                ]);
            }
        }

        $query = Resultado::selectRaw('DAYOFWEEK(updated_at) as DayOfWeek, HOUR(updated_at) as Hour, COUNT(*) as Count');
        if ($applyFilter) {
            $filterStart = $startDate ? $startDate->copy()->startOfDay() : '1970-01-01';
            $filterEnd   = $this->getFilterEnd($endDate);
            $query->whereBetween('updated_at', [$filterStart, $filterEnd]);
        }
        $results = $query->groupBy('DayOfWeek', 'Hour')
            ->orderByRaw('DAYOFWEEK(updated_at), HOUR(updated_at)')
            ->get();

        $maxValue = 0;
        foreach ($results as $result) {
            $dayName = $days[$result->DayOfWeek - 1];
            $hourFormatted = str_pad($result->Hour, 2, '0', STR_PAD_LEFT);
            $data->transform(function ($item) use ($dayName, $hourFormatted, $result, &$maxValue) {
                if ($item['rowid'] === $dayName && $item['columnid'] === "{$hourFormatted}:00") {
                    $item['value'] = (int)$result->Count;
                    $maxValue = max($maxValue, $result->Count);
                }
                return $item;
            });
        }

        $effectiveMax = max(1, (int) $maxValue); // ✅ evita rango 0→0 en la leyenda

        return [
            "chart" => [
                "xAxisName" => "Hora del Día",
                "yAxisName" => "Día de la Semana",
                "theme"     => "fusion",
                "bgColor"   => "#ffffff"
            ],
            "colorrange" => [
                "gradient"   => "1",
                "minvalue"   => "0",
                "code"       => "#ffffff",
                "startlabel" => "Bajo",
                "endlabel"   => "Alto",
                "color"      => [
                    [
                        "code"     => "#b4005a",
                        "maxvalue" => $effectiveMax // ✅ ya no será 0
                    ]
                ]
            ],
            "dataset" => [
                [
                    "data" => $data->values()->all()
                ]
            ]
        ];

    }
}
