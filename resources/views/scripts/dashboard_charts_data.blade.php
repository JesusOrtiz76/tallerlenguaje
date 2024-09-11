@push('scripts')
    <script src="https://cdn.fusioncharts.com/fusioncharts/latest/fusioncharts.js"></script>
    <script src="https://cdn.fusioncharts.com/fusioncharts/latest/themes/fusioncharts.theme.fusion.js"></script>
    <script src="https://cdn.fusioncharts.com/fusioncharts/latest/fusioncharts.timeseries.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const refreshInterval = document.getElementById('refresh-interval');
            let interval = parseInt(refreshInterval.value);

            function loadData() {
                fetch('{{ route('dashboard.data') }}')
                    .then(response => response.json())
                    .then(data => {
                        updateDashboard(data);
                    });
            }

            function updateDashboard(data) {
                document.getElementById('userCount').textContent = data.userCount;
                document.getElementById('inscripcionesCount').textContent = data.inscripcionesCount;
                document.getElementById('resultadosCount').textContent = data.resultadosCount;

                // Lógica para actualizar las gráficas de FusionCharts
                const chartData = data.chartData;
                const chartSchema = data.chartSchema;
                const donutData = data.donutData;
                const heatmapData = data.heatmapData;

                // Gráfica de time series
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

                new FusionCharts({
                    type: 'timeseries',
                    renderAt: 'chart-container',
                    width: '100%',
                    height: '500',
                    dataSource: dataSource
                }).render();

                // Gráfica de anillos
                new FusionCharts({
                    type: 'multilevelpie',
                    renderAt: 'donut-chart-container',
                    width: '100%',
                    height: '400',
                    dataFormat: 'json',
                    dataSource: {
                        chart: {
                            caption: "Resultados por Módulo y Evaluación",
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

                // Gráfica de heatmap
                new FusionCharts({
                    type: 'heatmap',
                    renderAt: 'heatmap-chart-container',
                    width: '100%',
                    height: '400',
                    dataFormat: 'json',
                    dataSource: heatmapData
                }).render();
            }

            loadData();
            let intervalId = setInterval(loadData, interval);

            refreshInterval.addEventListener('change', function() {
                clearInterval(intervalId);
                interval = parseInt(this.value);
                intervalId = setInterval(loadData, interval);
            });
        });

    </script>
@endpush
