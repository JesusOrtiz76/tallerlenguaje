@extends('layouts.app')

@section('title',"Reportes")

@section('content')

    <div class="container-fluid">
        <!-- Título principal de la página con botón de menú en pantallas pequeñas -->
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
        </div>

        <div class="row">
            <!-- Contadores -->
            <div class="col-xl-3 mb-4">
                <div class="card border-left-success shadow-sm h-100 py-2">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Usuarios Registrados
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-muted">{{ $userCount }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-users fa-2x text-success"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 mb-4">
                <div class="card border-left-info shadow-sm h-100 py-2">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                    Usuarios Verificados
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-muted">{{ $verifiedUserCount }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-check-circle fa-2x text-info"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 mb-4">
                <div class="card border-left-primary shadow-sm h-100 py-2">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Inscripciones
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-muted">{{ $inscripcionesCount }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-file-signature fa-2x text-primary"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 mb-4">
                <div class="card border-left-warning shadow-sm h-100 py-2">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                    Resultados
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-muted">{{ $resultadosCount }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-poll fa-2x text-warning"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gráfica time series de FusionCharts -->
        <div class="row">
            <div class="col-12 mb-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h4 class="text-primary">Respuestas por módulo</h4>
                        <div id="chart-container" style="height: 500px;">Gráfica de maps aparecerá aquí.</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gráfica de calor de FusionCharts -->
        <div class="row">
            <div class="col-xl-8 col-lg-7 mb-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h4 class="text-primary">Concurrencia de resultados por día y hora</h4>
                        <div id="heatmap-chart-container" style="height: 400px;">Gráfica de calor aparecerá aquí.</div>
                    </div>
                </div>
            </div>

            <!-- Gráfica de Anillos Anidados -->
            <div class="col-xl-4 col-lg-5 mb-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h4 class="text-primary">Evaluaciones y Resultados por Módulo</h4>
                        <div id="donut-chart-container" style="height: 400px;">Gráfica de anillos anidados aparecerá aquí.</div>
                    </div>
                </div>
            </div>
        </div>

        <script src="https://cdn.fusioncharts.com/fusioncharts/latest/fusioncharts.js"></script>
        <script src="https://cdn.fusioncharts.com/fusioncharts/latest/themes/fusioncharts.theme.fusion.js"></script>
        <script src="https://cdn.fusioncharts.com/fusioncharts/latest/fusioncharts.timeseries.js"></script>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const chartData = @json($chartData);
                const chartSchema = @json($chartSchema);

                // Utiliza FusionCharts.DataStore para procesar los datos y el esquema
                const dataStore = new FusionCharts.DataStore();
                const dataTable = dataStore.createDataTable(chartData, chartSchema);

                const dataSource = {
                    chart: {
                        caption: "Resultados por Módulo",
                        subcaption: "Visualización del total de resultados agrupados por módulo",
                        xAxisName: "Fecha",
                        yAxisName: "Cantidad",
                        theme: "fusion"
                    },
                    series: "Módulo",
                    yaxis: [
                        {
                            plot: "Cantidad",
                            title: "Cantidad"
                        }
                    ],
                    data: dataTable
                };

                new FusionCharts({
                    type: 'timeseries',
                    renderAt: 'chart-container',
                    width: '100%',
                    height: '500',
                    dataSource: dataSource
                }).render();
            });

            const donutData = @json($donutData);

            const donutDataSource = {
                chart: {
                    subcaption: "Anillo Interno: Módulos, Anillo Externo: Evaluaciones",
                    showplotborder: "1",
                    plotfillalpha: "60",
                    hoverfillcolor: "#CCCCCC",
                    theme: "fusion",
                    showLabels: "0",
                    plotToolText: "<b>$label</b>: $value"
                },
                category: [
                    {
                        label: "Módulos",
                        tooltext: "Módulos con evaluaciones",
                        category: donutData
                    }
                ]
            };

            new FusionCharts({
                type: 'multilevelpie',
                renderAt: 'donut-chart-container',
                width: '100%',
                height: '400',
                dataFormat: 'json',
                dataSource: donutDataSource
            }).render();


            const heatmapData = @json($heatmapData);

            new FusionCharts({
                type: 'heatmap',
                renderAt: 'heatmap-chart-container',
                width: '100%',
                height: '400',
                dataFormat: 'json',
                dataSource: heatmapData
            }).render();
        </script>
    </div>

@endsection
