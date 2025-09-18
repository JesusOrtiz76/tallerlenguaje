<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Models\Modulo;
use App\Models\User;
use App\Models\Resultado;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard.index');
    }

    public function getDataIndex(Request $request)
    {
        // Parámetros de fecha y curso recibidos por GET
        $startDateParam = $request->query('fechainicial');
        $endDateParam   = $request->query('fechafinal');
        $cursoId        = $request->query('curso');

        // Se obtienen todos los cursos (para llenar el select)
        $cursos = Curso::all();
        // Si no se envió un curso, se toma el primero por defecto (si existe)
        $selectedCurso = $cursoId ?: ($cursos->first()->id ?? null);

        /*
         * Lógica de filtros de fecha:
         * - Si ambos campos están vacíos, se traen TODOS los datos.
         * - Si se envía solo uno, se aplica el límite correspondiente.
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

            // Usuarios registrados
            $userCount = User::where('orol', 'user')
                ->whereBetween('created_at', [$filterStart, $filterEnd])
                ->count();

            // Inscripciones (filtrado adicional por curso si se indica)
            $inscripcionesQuery = DB::table('r12inscripciones as i')
                ->join('r12users as u', 'i.user_id', '=', 'u.id')
                ->where('u.orol', 'user')
                ->whereBetween('i.created_at', [$filterStart, $filterEnd]);
            if ($selectedCurso) {
                $inscripcionesQuery->where('i.curso_id', $selectedCurso);
            }
            $inscripcionesCount = $inscripcionesQuery->count();

            // Resultados: se filtra también por curso vía la relación Evaluación → Módulo
            $resultadosQuery = Resultado::whereHas('user', function($query) {
                $query->where('orol', 'user');
            })
                ->whereBetween('created_at', [$filterStart, $filterEnd]);
            if ($selectedCurso) {
                $resultadosQuery->whereHas('evaluacion.modulo', function($q) use ($selectedCurso) {
                    $q->where('curso_id', $selectedCurso);
                });
            }
            $resultadosCount = $resultadosQuery->count();
        } else {
            $userCount = User::where('orol', 'user')->count();

            $inscripcionesQuery = DB::table('r12inscripciones as i')
                ->join('r12users as u', 'i.user_id', '=', 'u.id')
                ->where('u.orol', 'user');
            if ($selectedCurso) {
                $inscripcionesQuery->where('i.curso_id', $selectedCurso);
            }
            $inscripcionesCount = $inscripcionesQuery->count();

            $resultadosQuery = Resultado::whereHas('user', function($query) {
                $query->where('orol', 'user');
            });
            if ($selectedCurso) {
                $resultadosQuery->whereHas('evaluacion.modulo', function($q) use ($selectedCurso) {
                    $q->where('curso_id', $selectedCurso);
                });
            }
            $resultadosCount = $resultadosQuery->count();
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
            $allDates = $this->generateDateRange($minDate, $maxDate);
        }

        // Consulta de respuestas por módulo y fecha (para la gráfica de time series)
        $query = DB::table('r12resultados as r')
            ->select(
                'm.id as modulo_id',
                'm.onombre as modulo',
                DB::raw("DATE_FORMAT(r.created_at, '%Y-%m-%d') as date"),
                DB::raw('COUNT(*) as total')
            )
            ->join('r12users as u', 'r.user_id', '=', 'u.id')
            ->where('u.orol', 'user')
            ->leftJoin('r12evaluaciones as e', 'r.evaluacion_id', '=', 'e.id')
            ->leftJoin('r12modulos as m', 'e.modulo_id', '=', 'm.id');
        if ($selectedCurso) {
            $query->where('m.curso_id', $selectedCurso);
        }
        if ($applyFilter) {
            $query->whereBetween('r.created_at', [$filterStart, $filterEnd]);
        }
        $respuestasCountByDateAndModulo = $query->groupBy('m.id', 'm.onombre', DB::raw("DATE_FORMAT(r.created_at, '%Y-%m-%d')"))
            ->orderBy('m.id', 'asc')
            ->orderBy('date', 'asc')
            ->get();

        // Si no hay resultados, crear series con cero para cada módulo del curso (o todos si no se filtra por curso)
        if ($respuestasCountByDateAndModulo->isEmpty()) {
            if ($selectedCurso) {
                $modulosForChart = Modulo::where('curso_id', $selectedCurso)->get();
            } else {
                $modulosForChart = Modulo::all();
            }
            $timeseriesData = [];
            foreach ($modulosForChart as $modulo) {
                $data = [];
                foreach ($allDates as $date) {
                    $data[] = ['date' => $date, 'count' => 0];
                }
                $timeseriesData[] = ['modulo' => $modulo->onombre, 'data' => $data];
            }
        } else {
            $timeseriesData = $this->generateTimeseriesData($allDates, $respuestasCountByDateAndModulo);
        }

        $chartData   = $this->prepareChartData($timeseriesData);
        $chartSchema = [
            ["name" => "Módulo", "type" => "string"],
            ["name" => "Fecha", "type" => "date", "format" => "%Y-%m-%d"],
            ["name" => "Cantidad", "type" => "number"]
        ];

        // Para la gráfica de anillos se filtran los módulos según el curso (si se selecciona)
        if ($selectedCurso) {
            $modulos = Modulo::with('evaluaciones')->where('curso_id', $selectedCurso)->get();
        } else {
            $modulos = Modulo::with('evaluaciones')->get();
        }
        $donutData = $this->prepareDonutData($modulos, $applyFilter, $startDate ?? null, $endDate ?? null);

        // Gráfica de calor (heatmap)
        $queryHeatmap = DB::table('r12resultados as r')
            ->selectRaw('DAYOFWEEK(r.updated_at) as DayOfWeek, HOUR(r.updated_at) as Hour, COUNT(*) as Count')
            ->join('r12users as u', 'r.user_id', '=', 'u.id')
            ->where('u.orol', 'user');
        if ($selectedCurso) {
            $queryHeatmap->join('r12evaluaciones as e', 'r.evaluacion_id', '=', 'e.id')
                ->join('r12modulos as m', 'e.modulo_id', '=', 'm.id')
                ->where('m.curso_id', $selectedCurso);
        }
        if ($applyFilter) {
            $queryHeatmap->whereBetween('r.updated_at', [$filterStart, $filterEnd]);
        }
        $resultsHeatmap = $queryHeatmap->groupBy('DayOfWeek', 'Hour')
            ->orderByRaw('DAYOFWEEK(r.updated_at), HOUR(r.updated_at)')
            ->get();

        $days = ["Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado"];
        $dataHeatmap = collect();
        for ($d = 0; $d < count($days); $d++) {
            for ($hour = 0; $hour < 24; $hour++) {
                $hourFormatted = str_pad($hour, 2, '0', STR_PAD_LEFT);
                $dataHeatmap->push([
                    "rowid"    => $days[$d],
                    "columnid" => "{$hourFormatted}:00",
                    "value"    => 0
                ]);
            }
        }

        $maxValue = 0;

        foreach ($resultsHeatmap as $result) {
            $dayName = $days[$result->DayOfWeek - 1];
            $hourFormatted = str_pad($result->Hour, 2, '0', STR_PAD_LEFT);
            $dataHeatmap->transform(function ($item) use ($dayName, $hourFormatted, $result, &$maxValue) {
                if ($item['rowid'] === $dayName && $item['columnid'] === "{$hourFormatted}:00") {
                    $item['value'] = (int)$result->Count;
                    $maxValue = max($maxValue, $result->Count);
                }
                return $item;
            });
        }

        $maxValue = $maxValue > 0 ? $maxValue : 1;

        $heatmapData = [
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
                        "maxvalue" => $maxValue
                    ]
                ]
            ],
            "dataset" => [
                [
                    "data" => $dataHeatmap->values()->all()
                ]
            ]
        ];

        return response()->json(compact(
            'cursos',
            'selectedCurso',
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
     * Si la fecha final es hoy se usa la hora actual; de lo contrario, se usa el final del día.
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

    private function generateTimeseriesData($allDates, $respuestasCountByDateAndModulo)
    {
        $grouped = $respuestasCountByDateAndModulo->groupBy('modulo');
        $sortedGrouped = $grouped->sortBy(function ($group) {
            return $group->first()->modulo_id;
        });
        return $sortedGrouped->map(function ($items, $modulo) use ($allDates) {
            $data = collect($allDates)->map(function ($date) use ($items) {
                $item = $items->firstWhere('date', $date);
                return [
                    'date'  => $date,
                    'count' => $item ? $item->total : 0
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
            foreach ($moduloData['data'] as $dataPoint) {
                $chartData[] = [
                    $moduloData['modulo'],
                    $dataPoint['date'],
                    $dataPoint['count']
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
                        ->whereHas('user', function($query) {
                            $query->where('orol', 'user');
                        })
                        ->whereBetween('created_at', [$filterStart, $filterEnd])
                        ->count();
                } else {
                    $evaluationResultsCount = $evaluacion->resultados()
                        ->whereHas('user', function($query) {
                            $query->where('orol', 'user');
                        })
                        ->count();
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
}
