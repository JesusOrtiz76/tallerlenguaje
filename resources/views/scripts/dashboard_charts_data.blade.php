@push('scripts')
    <!-- FusionCharts y dependencias -->
    <script src="https://cdn.fusioncharts.com/fusioncharts/latest/fusioncharts.js"></script>
    <script src="https://cdn.fusioncharts.com/fusioncharts/latest/themes/fusioncharts.theme.fusion.js"></script>
    <script src="https://cdn.fusioncharts.com/fusioncharts/latest/fusioncharts.timeseries.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const refreshIntervalSelect = document.getElementById('refresh-interval');
            const dateFilterForm = document.getElementById('dateFilterForm');
            const fechainicialInput = document.getElementById('fechainicial');
            const fechafinalInput   = document.getElementById('fechafinal');
            const cursoSelect       = document.getElementById('curso');

            let interval = parseInt(refreshIntervalSelect.value);
            let timeSeriesChart, donutChart, heatmapChart;
            let intervalId;

            // Función para poblar el select de cursos
            // Se recibe el valor del curso seleccionado (selectedCurso) para marcarlo
            function populateCursos(cursos, selectedCurso) {
                cursoSelect.innerHTML = '';
                cursos.forEach((curso, index) => {
                    const option = document.createElement('option');
                    option.value = curso.id;
                    option.textContent = curso.onombre; // Asumiendo que "onombre" es la propiedad del nombre
                    // Si se indica selectedCurso, se marca ese; de lo contrario, se selecciona el primero
                    if (selectedCurso && curso.id == selectedCurso) {
                        option.selected = true;
                    } else if (!selectedCurso && index === 0) {
                        option.selected = true;
                    }
                    cursoSelect.appendChild(option);
                });
            }

            // Función para cargar los datos del dashboard
            function loadData() {
                // Mostrar spinner (si tienes un contenedor con id="loader-container")
                document.getElementById('loader-container') && (document.getElementById('loader-container').style.display = 'block');

                const fechainicial = fechainicialInput.value;
                const fechafinal   = fechafinalInput.value;
                const selectedCurso = cursoSelect.value;
                const url = `{{ route('dashboard.data') }}?fechainicial=${fechainicial}&fechafinal=${fechafinal}&curso=${selectedCurso}`;

                fetch(url)
                    .then(response => response.json())
                    .then(data => {
                        updateDashboard(data);
                    })
                    .catch(error => console.error('Error al cargar los datos:', error))
                    .finally(() => {
                        document.getElementById('loader-container') && (document.getElementById('loader-container').style.display = 'none');
                    });
            }

            function initializeCharts(chartData, chartSchema, donutData, heatmapData) {
                if (!chartData || !chartSchema || !donutData || !heatmapData) {
                    console.error('Faltan datos para inicializar las gráficas');
                    return;
                }

                // Gráfica de Time Series
                const dataStore = new FusionCharts.DataStore();
                const dataTable = dataStore.createDataTable(chartData, chartSchema);
                const timeSeriesDataSource = {
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
                    dataSource: timeSeriesDataSource
                }).render();

                // Gráfica de anillos
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
                        category: [{
                            label: "Módulos",
                            tooltext: "Módulos con evaluaciones",
                            category: donutData
                        }]
                    }
                }).render();

                // Gráfica de Heatmap
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
                // Solo poblar el select de cursos la primera vez o si está vacío
                if (!cursoSelect.options.length && data.cursos) {
                    populateCursos(data.cursos, data.selectedCurso);
                } else if(data.selectedCurso) {
                    // Asegurar que el select mantenga la selección según lo recibido
                    cursoSelect.value = data.selectedCurso;
                }

                document.getElementById('userCount').textContent = data.userCount;
                document.getElementById('inscripcionesCount').textContent = data.inscripcionesCount;
                document.getElementById('resultadosCount').textContent = data.resultadosCount;

                if (!timeSeriesChart || !donutChart || !heatmapChart) {
                    initializeCharts(data.chartData, data.chartSchema, data.donutData, data.heatmapData);
                } else {
                    const dataStore = new FusionCharts.DataStore();
                    const newDataTable = dataStore.createDataTable(data.chartData, data.chartSchema);
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
                        category: [{
                            label: "Módulos",
                            tooltext: "Módulos con evaluaciones",
                            category: data.donutData
                        }]
                    });

                    heatmapChart.setJSONData(data.heatmapData);
                }
            }

            function startAutoRefresh() {
                loadData();
                intervalId = setInterval(loadData, interval);
            }

            dateFilterForm.addEventListener('submit', function(e) {
                e.preventDefault();
                clearInterval(intervalId);
                loadData();
                intervalId = setInterval(loadData, interval);
            });

            refreshIntervalSelect.addEventListener('change', function() {
                clearInterval(intervalId);
                interval = parseInt(this.value);
                intervalId = setInterval(loadData, interval);
            });

            // Recargar data al cambiar el curso (sin perder la selección)
            cursoSelect.addEventListener('change', function() {
                clearInterval(intervalId);
                loadData();
                intervalId = setInterval(loadData, interval);
            });

            startAutoRefresh();
        });
    </script>
@endpush
