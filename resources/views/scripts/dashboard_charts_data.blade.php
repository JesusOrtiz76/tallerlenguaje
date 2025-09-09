@push('scripts')
    <!-- FusionCharts y dependencias -->
    <script src="https://cdn.fusioncharts.com/fusioncharts/latest/fusioncharts.js"></script>
    <script src="https://cdn.fusioncharts.com/fusioncharts/latest/themes/fusioncharts.theme.fusion.js"></script>
    <script src="https://cdn.fusioncharts.com/fusioncharts/latest/fusioncharts.timeseries.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const refreshIntervalSelect = document.getElementById('refresh-interval');
            const dateFilterForm = document.getElementById('dateFilterForm');
            const fechainicialInput = document.getElementById('fechainicial');
            const fechafinalInput   = document.getElementById('fechafinal');

            // Schema fijo 3 columnas
            const TIME_SCHEMA = [
                { name: 'Fecha',    type: 'date',   format: '%Y-%m-%d' },
                { name: 'Cantidad', type: 'number' },
                { name: 'Módulo',   type: 'string' }
            ];

            let interval = parseInt(refreshIntervalSelect.value);
            let timeSeriesChart = null, donutChart = null, heatmapChart = null;
            let intervalId;
            let inFlight = false;           // ✅ evita fetch/updates solapados
            let reqId = 0, lastHandled = 0; // ✅ ignora respuestas viejas

            // ---------- Utilidades ----------
            function normalizeTimeRows(rows) {
                return (Array.isArray(rows) ? rows : [])
                    .map(r => [String(r?.[0] ?? ''), Number(r?.[1]), String(r?.[2] ?? 'Sin módulo')])
                    .filter(r => r[0] && Number.isFinite(r[1]))
                    .map(r => r.slice(0, 3)); // fuerza 3 cols exactas
            }

            function hasYVariation(rows) {
                if (!rows.length) return false;
                let min = rows[0][1], max = rows[0][1];
                for (let i = 1; i < rows.length; i++) {
                    const v = rows[i][1];
                    if (v < min) min = v;
                    if (v > max) max = v;
                    if (max > min) return true;
                }
                return false;
            }

            function dispose(chartRef) {
                if (chartRef) { try { chartRef.dispose(); } catch(_) {} }
                return null;
            }

            function containerHasSize(el) {
                if (!el) return false;
                const r = el.getBoundingClientRect();
                return (r.width > 0 && r.height > 0);
            }

            // ---------- Render TS (siempre reconstruir) ----------
            function renderTimeSeries(rows) {
                const box = document.getElementById('chart-container');

                // 1) contenedor con tamaño
                if (!containerHasSize(box)) {
                    timeSeriesChart = dispose(timeSeriesChart);
                    box.innerHTML = 'El contenedor no tiene tamaño aún.';
                    return;
                }

                // 2) normaliza & valida
                const safe = normalizeTimeRows(rows);
                if (!safe.length) {
                    timeSeriesChart = dispose(timeSeriesChart);
                    box.innerHTML = 'Sin datos para el período seleccionado.';
                    return;
                }
                if (!hasYVariation(safe)) {
                    timeSeriesChart = dispose(timeSeriesChart);
                    box.innerHTML = 'No hay variación en los datos (todos los valores son iguales).';
                    return;
                }

                // 3) reconstruir (evita NaN por estados intermedios)
                timeSeriesChart = dispose(timeSeriesChart);
                box.innerHTML = '';

                const dataStore = new FusionCharts.DataStore();
                const dataTable = dataStore.createDataTable(safe, TIME_SCHEMA);

                const ds = {
                    chart: {
                        caption: "Resultados por Módulo",
                        subcaption: "Totales agrupados por módulo",
                        xAxisName: "Fecha",
                        yAxisName: "Cantidad",
                        theme: "fusion",
                        animation: "0" // evita NaN durante animaciones
                    },
                    series: "Módulo",
                    yAxis: [{ plot: "Cantidad", title: "Cantidad" }],
                    data: dataTable
                };

                timeSeriesChart = new FusionCharts({
                    type: 'timeseries',
                    renderAt: 'chart-container',
                    width: '100%',
                    height: 500, // número (no string)
                    dataSource: ds
                });
                timeSeriesChart.render();
            }

            // ---------- Donut / Heatmap ----------
            function renderOrUpdateDonut(donutData) {
                const ds = {
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
                };

                if (!donutChart) {
                    donutChart = new FusionCharts({
                        type: 'multilevelpie',
                        renderAt: 'donut-chart-container',
                        width: '100%',
                        height: 400,
                        dataFormat: 'json',
                        dataSource: ds
                    });
                    donutChart.render();
                } else {
                    donutChart.setJSONData(ds);
                }
            }

            function renderOrUpdateHeatmap(heatmapData) {
                if (!heatmapChart) {
                    heatmapChart = new FusionCharts({
                        type: 'heatmap',
                        renderAt: 'heatmap-chart-container',
                        width: '100%',
                        height: 400,
                        dataFormat: 'json',
                        dataSource: heatmapData
                    });
                    heatmapChart.render();
                } else {
                    heatmapChart.setJSONData(heatmapData);
                }
            }

            // ---------- Carga / actualización (anti-solapamiento) ----------
            function loadData() {
                if (inFlight) return;        // ✅ no solapar
                inFlight = true;
                const myId = ++reqId;

                const fi = fechainicialInput.value || '';
                const ff = fechafinalInput.value   || '';
                const url = `{{ route('dashboard.data') }}?fechainicial=${fi}&fechafinal=${ff}`;

                fetch(url)
                    .then(r => r.json())
                    .then(data => {
                        // ignora respuestas viejas si llegó otra después
                        if (myId < reqId) return;

                        // Contadores
                        document.getElementById('userCount').textContent = data.userCount;
                        document.getElementById('inscripcionesCount').textContent = data.inscripcionesCount;
                        document.getElementById('resultadosCount').textContent = data.resultadosCount;

                        // TS: reconstruir siempre
                        renderTimeSeries(data.chartData);

                        // Donut / Heatmap
                        renderOrUpdateDonut(data.donutData);
                        renderOrUpdateHeatmap(data.heatmapData);

                        lastHandled = myId;
                    })
                    .catch(err => console.error('Error al cargar los datos:', err))
                    .finally(() => { inFlight = false; });
            }

            function startAutoRefresh() {
                loadData();
                intervalId = setInterval(loadData, interval);
            }

            dateFilterForm.addEventListener('submit', function (e) {
                e.preventDefault();
                clearInterval(intervalId);
                loadData();
                intervalId = setInterval(loadData, interval);
            });

            refreshIntervalSelect.addEventListener('change', function () {
                clearInterval(intervalId);
                interval = parseInt(this.value);
                intervalId = setInterval(loadData, interval);
            });

            startAutoRefresh();
        });
    </script>
@endpush
