<?php

namespace App\Http\Controllers;

use App\Models\Modulo;
use App\Models\User;
use App\Models\Resultado;
use App\Models\Inscripcion;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Devuelve solo la vista
        return view('admin.dashboard.index');
    }

    public function getDataIndex()
    {
        // Contadores
        $userCount = User::count();
        $inscripcionesCount = Inscripcion::count();
        $resultadosCount = Resultado::count();

        // Obtener la fecha mínima y máxima de la tabla de resultados
        $minDate = Resultado::min(DB::raw("DATE(created_at)"));
        $maxDate = Resultado::max(DB::raw("DATE(created_at)"));

        // Generar el rango de fechas completo
        $allDates = $this->generateDateRange($minDate, $maxDate);

        // Consulta de respuestas por módulo y fecha
        $respuestasCountByDateAndModulo = $this->getRespuestasCountByDateAndModulo();

        // Agrupar los resultados por módulo y fechas
        $timeseriesData = $this->generateTimeseriesData($allDates, $respuestasCountByDateAndModulo);

        // Preparar los datos para la gráfica
        $chartData = $this->prepareChartData($timeseriesData);

        // Definir el esquema para FusionCharts
        $chartSchema = [
            ["name" => "Fecha", "type" => "date", "format" => "%Y-%m-%d"],
            ["name" => "Módulo", "type" => "string"],
            ["name" => "Cantidad", "type" => "number"]
        ];

        // Datos para la gráfica de anillos
        $donutData = $this->prepareDonutData(Modulo::with('evaluaciones.resultados')->get());

        // Gráfica de calor: Concurrencia
        $heatmapData = $this->generateHeatmapData();

        // Respuesta en formato JSON
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

    private function generateDateRange($startDate, $endDate)
    {
        $allDates = [];
        $currentDate = Carbon::parse($startDate);
        $endDate = Carbon::parse($endDate);

        while ($currentDate->lte($endDate)) {
            $allDates[] = $currentDate->format('Y-m-d');
            $currentDate->addDay();
        }

        return $allDates;
    }

    private function getRespuestasCountByDateAndModulo()
    {
        return DB::table('r10resultados as rr')
            ->select(
                'rm.onombre as modulo',
                DB::raw("DATE_FORMAT(rr.created_at, '%Y-%m-%d') as date"),
                DB::raw('COUNT(*) as total')
            )
            ->leftJoin('r10evaluaciones as re', 'rr.evaluacion_id', '=', 're.id')
            ->leftJoin('r10modulos as rm', 're.modulo_id', '=', 'rm.id')
            ->groupBy('rm.onombre', DB::raw("DATE_FORMAT(rr.created_at, '%Y-%m-%d')"))
            ->orderBy('date', 'asc')
            ->get();
    }

    private function generateTimeseriesData($allDates, $respuestasCountByDateAndModulo)
    {
        return $respuestasCountByDateAndModulo->groupBy('modulo')->map(function ($items, $modulo) use ($allDates) {
            $data = collect($allDates)->map(function ($date) use ($items) {
                $item = $items->firstWhere('date', $date);
                return [
                    'date' => $date,
                    'count' => $item ? $item->total : 0
                ];
            });

            return [
                'modulo' => $modulo,
                'data' => $data
            ];
        })->values()->all();
    }

    private function prepareChartData($timeseriesData)
    {
        $chartData = [];
        foreach ($timeseriesData as $moduloData) {
            foreach ($moduloData['data'] as $dataPoint) {
                $chartData[] = [
                    $dataPoint['date'],
                    $moduloData['modulo'],
                    $dataPoint['count']
                ];
            }
        }
        return $chartData;
    }

    private function prepareDonutData($modulos)
    {
        $donutData = [];

        foreach ($modulos as $modulo) {
            $evaluacionesArray = [];

            foreach ($modulo->evaluaciones as $evaluacion) {
                $evaluationResultsCount = $evaluacion->resultados->count();
                $evaluacionesArray[] = [
                    "label" => $evaluacion->onombre,
                    "value" => $evaluationResultsCount
                ];
            }

            $totalResultsForModule = array_sum(array_column($evaluacionesArray, 'value'));

            $donutData[] = [
                "label" => $modulo->onombre,
                "value" => $totalResultsForModule,
                "category" => $evaluacionesArray
            ];
        }

        return $donutData;
    }

    private function generateHeatmapData()
    {
        $days = ["Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado"];
        $data = collect();
        foreach ($days as $dayName) {
            for ($hour = 0; $hour < 24; $hour++) {
                $hourFormatted = str_pad($hour, 2, '0', STR_PAD_LEFT);
                $data->push(["rowid" => $dayName, "columnid" => "{$hourFormatted}:00", "value" => 0]);
            }
        }

        $results = Resultado::selectRaw('DAYOFWEEK(updated_at) as DayOfWeek, HOUR(updated_at) as Hour, COUNT(*) as Count')
            ->groupBy('DayOfWeek', 'Hour')
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

        return [
            "chart" => [
                "xAxisName" => "Hora del Día",
                "yAxisName" => "Día de la Semana",
                "theme" => "fusion",
                "bgColor" => "#ffffff"
            ],
            "colorrange" => [
                "gradient" => "1",
                "minvalue" => "0",
                "code" => "#ffffff",
                "startlabel" => "Bajo",
                "endlabel" => "Alto",
                "color" => [["code" => "#b4005a", "maxvalue" => $maxValue]]
            ],
            "dataset" => [["data" => $data->values()->all()]]
        ];
    }
}
