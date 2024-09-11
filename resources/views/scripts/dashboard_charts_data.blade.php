@push('scripts')
    <script src="https://cdn.fusioncharts.com/fusioncharts/latest/fusioncharts.js"></script>
    <script src="https://cdn.fusioncharts.com/fusioncharts/latest/themes/fusioncharts.theme.fusion.js"></script>
    <script src="https://cdn.fusioncharts.com/fusioncharts/latest/fusioncharts.timeseries.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const refreshInterval = document.getElementById('refresh-interval');
            let interval = parseInt(refreshInterval.value);

            let timeSeriesChart, donutChart, heatmapChart; // Guardamos las instancias de los gráficos

            function loadData() {
                fetch('{{ route('dashboard.data') }}')
                    .then(response => response.json())
                    .then(data => {
                        updateDashboard(data);
                    })
                    .catch(error => {
                        console.error('Error al cargar los datos:', error);
                    });
            }

            function initializeCharts(chartData, chartSchema, donutData, heatmapData) {
                // Inicializa las gráficas solo si los datos son válidos
                if (!chartData || !chartSchema || !donutData || !heatmapData) {
                    console.error('Faltan datos para inicializar las gráficas');
                    return;
                }

                console.log('Inicializando gráficos...');

                // Inicializa la gráfica de time series
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
                    yaxis: [{ plot: "Cantidad", title: "Cantidad" }],
                    data: dataTable
                };

                timeSeriesChart = new FusionCharts({
                    type: 'timeseries',
                    renderAt: 'chart-container',
                    width: '100%',
                    height: '500',
                    dataSource: dataSource
                }).render();

                // Inicializa la gráfica de anillos
                donutChart = new FusionCharts({
                    type: 'multilevelpie',
                    renderAt: 'donut-chart-container',
                    width: '100%',
                    height: '400',
                    dataFormat: 'json',
                    dataSource: {
                        chart: {
                            subcaption: "Distribución de evaluaciones por módulo",
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
                    }
                }).render();

                // Inicializa la gráfica de heatmap
                heatmapChart = new FusionCharts({
                    type: 'heatmap',
                    renderAt: 'heatmap-chart-container',
                    width: '100%',
                    height: '400',
                    dataFormat: 'json',
                    dataSource: heatmapData
                }).render();
            }

            function updateDashboard(data) {
                // Actualiza los contadores
                document.getElementById('userCount').textContent = data.userCount;
                document.getElementById('inscripcionesCount').textContent = data.inscripcionesCount;
                document.getElementById('resultadosCount').textContent = data.resultadosCount;

                // Verificar si los gráficos ya están inicializados
                if (!timeSeriesChart || !donutChart || !heatmapChart) {
                    console.log('Inicializando gráficos por primera vez');
                    initializeCharts(data.chartData, data.chartSchema, data.donutData, data.heatmapData);
                } else {
                    console.log('Actualizando datos de los gráficos');
                    const dataStore = new FusionCharts.DataStore();
                    const newDataTable = dataStore.createDataTable(data.chartData, data.chartSchema);

                    // Actualiza la gráfica de time series
                    timeSeriesChart.setJSONData({
                        chart: {
                            caption: "Resultados por Módulo",
                            subcaption: "Visualización del total de resultados agrupados por módulo",
                            xAxisName: "Fecha",
                            yAxisName: "Cantidad",
                            theme: "fusion"
                        },
                        series: "Módulo",
                        yaxis: [{ plot: "Cantidad", title: "Cantidad" }],
                        data: newDataTable
                    });

                    // Actualiza la gráfica de anillos
                    donutChart.setJSONData({
                        chart: {
                            subcaption: "Distribución de evaluaciones por módulo",
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
                                category: data.donutData
                            }
                        ]
                    });

                    // Actualiza la gráfica de heatmap
                    heatmapChart.setJSONData(data.heatmapData);
                }
            }

            // Cargar los datos inicialmente y después en intervalos
            loadData();
            let intervalId = setInterval(loadData, interval);

            // Escuchar los cambios en el selector de intervalo y actualizar
            refreshInterval.addEventListener('change', function() {
                clearInterval(intervalId);
                interval = parseInt(this.value);
                intervalId = setInterval(loadData, interval);
            });
        });
    </script>
@endpush
