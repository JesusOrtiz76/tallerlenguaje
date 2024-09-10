<?php

namespace App\Http\Controllers;

use App\Models\Modulo;
use App\Models\User;
use App\Models\Resultado;
use App\Models\Evaluacion;
use App\Models\Inscripcion;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Contadores
        $userCount = User::count();
        $verifiedUserCount = User::whereNotNull('email_verified_at')->count();
        $inscripcionesCount = Inscripcion::count();
        $resultadosCount = Resultado::count();

        // Obtener las fechas de inicio y fin para el rango de datos
        $startDate = Resultado::min('created_at');
        $endDate = Resultado::max('created_at');

        // Generar un rango de fechas completo
        $allDates = [];
        $currentDate = Carbon::parse($startDate);
        $endDate = Carbon::parse($endDate);

        while ($currentDate->lte($endDate)) {
            $allDates[] = $currentDate->format('Y-m-d');
            $currentDate->addDay();
        }
        // Obtener el rango de fechas para las series de tiempo
        $startDate = Resultado::min('created_at');
        $endDate = Resultado::max('created_at');

        // Generar un rango de fechas completo
        $allDates = [];
        $currentDate = Carbon::parse($startDate);
        $endDate = Carbon::parse($endDate);

        while ($currentDate->lte($endDate)) {
            $allDates[] = $currentDate->format('Y-m-d');
            $currentDate->addDay();
        }

        // Timeseries: Respuestas por módulo, pasando por alto la evaluación
        $respuestasCountByDateAndModulo = Resultado::with('evaluacion.modulo')
            ->select(
                DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d') as date"),
                'evaluacion_id',
                DB::raw('count(*) as count')
            )
            ->groupBy('date', 'evaluacion_id')
            ->get();

        // Agrupamos los resultados por módulo
        $timeseriesData = $respuestasCountByDateAndModulo->groupBy(function ($item) {
            return $item->evaluacion->modulo->onombre;  // Agrupamos por el nombre del módulo
        })->map(function ($items, $modulo) use ($allDates) {
            $data = collect($allDates)->map(function ($date) use ($items) {
                $item = $items->firstWhere('date', $date);
                return [
                    'date' => $date,
                    'count' => $item ? $item->count : 0
                ];
            });
            return [
                'modulo' => $modulo,
                'data' => $data
            ];
        })->values()->all();

        // Estructuramos los datos para la gráfica de FusionCharts
        $chartData = [];
        foreach ($timeseriesData as $moduloData) {
            foreach ($moduloData['data'] as $dataPoint) {
                $chartData[] = [
                    $dataPoint['date'],  // Fecha
                    $moduloData['modulo'],  // Nombre del módulo
                    $dataPoint['count']  // Cantidad de resultados
                ];
            }
        }

        // Definir el esquema para FusionCharts
        $chartSchema = [
            ["name" => "Fecha", "type" => "date", "format" => "%Y-%m-%d"],
            ["name" => "Módulo", "type" => "string"],
            ["name" => "Cantidad", "type" => "number"]
        ];

        // Obtener todos los módulos con sus evaluaciones y resultados
        $modulos = Modulo::with('evaluaciones.resultado')->get();

        // Crear el arreglo para la gráfica de anillos anidados en el formato correcto
        $donutData = [];
        foreach ($modulos as $modulo) {
            // Crear las subcategorías (evaluaciones) para cada módulo
            $evaluacionesArray = [];
            foreach ($modulo->evaluaciones as $evaluacion) {
                $evaluationResultsCount = $evaluacion->resultado ? 1 : 0;

                $evaluacionesArray[] = [
                    "label" => $evaluacion->onombre, // Nombre de la evaluación
                    "value" => $evaluationResultsCount
                ];
            }

            // Agregar el módulo como una categoría, con sus evaluaciones anidadas
            $donutData[] = [
                "label" => $modulo->onombre, // Nombre del módulo
                "value" => array_sum(array_column($evaluacionesArray, 'value')), // Sumar los resultados de todas las evaluaciones
                "category" => $evaluacionesArray  // Las evaluaciones como subcategorías
            ];
        }

        // Gráfica de calor: Concurrencia basada en 'updated_at' en la tabla de Resultados
        $days = ["Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado"];
        $data = collect();
        foreach ($days as $dayName) {
            for ($hour = 0; $hour < 24; $hour++) {
                $hourFormatted = str_pad($hour, 2, '0', STR_PAD_LEFT);
                $data->push(["rowid" => $dayName, "columnid" => "{$hourFormatted}:00", "value" => 0]);
            }
        }

        // Consultar la concurrencia y contar las ocurrencias en función de 'updated_at'
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

        $heatmapData = [
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

        return view('admin.dashboard.index', compact(
            'userCount',
            'verifiedUserCount',
            'inscripcionesCount',
            'resultadosCount',
            'chartData',
            'chartSchema',
            'donutData',
            'heatmapData'
        ));
    }
}
