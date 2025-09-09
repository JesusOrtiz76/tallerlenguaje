@extends('layouts.app')

@section('title', "Reportes")

@section('content')
    <div class="container-fluid">
        <!-- Título del Dashboard -->
        <h1 class="h3 mb-4 text-gray-800">Dashboard</h1>

        <form id="dateFilterForm">
            <div class="row mb-4">
                <!-- Columna 1: Frecuencia de actualización -->
                <div class="col-12 col-lg-4 mb-3 mb-lg-0">
                    <div class="form-group">
                        <label for="refresh-interval">Frecuencia de actualización:</label>
                        <select id="refresh-interval" class="form-control">
                            <option value="30000">30 segundos</option>
                            <option value="60000">1 minuto</option>
                            <option value="300000">5 minutos</option>
                            <option value="900000">15 minutos</option>
                            <option value="1800000">30 minutos</option>
                            <option value="3600000">1 hora</option>
                        </select>
                    </div>
                </div>

                <!-- Columna 2: Campos de periodo y botón -->
                <div class="col-12 col-lg-8">
                    <div class="d-lg-flex">
                        <div class="form-group me-2 mb-3 flex-fill">
                            <label for="fechainicial">Fecha Inicial</label>
                            <input type="date" id="fechainicial" name="fechainicial" class="form-control" value="{{ \Carbon\Carbon::now()->subWeek()->format('Y-m-d') }}">
                        </div>
                        <div class="form-group me-2 mb-3 flex-fill">
                            <label for="fechafinal">Fecha Final</label>
                            <input type="date" id="fechafinal" name="fechafinal" class="form-control" value="">
                        </div>
                        <div class="form-group">
                            <!-- Se usa un label oculto en lg para alinear el botón con los campos -->
                            <label class="d-none d-lg-block">&nbsp;</label>
                            <button type="submit" class="btn btn-primary btn-block">Filtrar</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <!-- Contadores -->
        <div class="row mb-4">
            <div class="col-xl-4 mb-4">
                <div class="card border-left-success shadow-sm h-100 py-2">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Usuarios Registrados
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-muted" id="userCount">0</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-users fa-2x text-success"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 mb-4">
                <div class="card border-left-info shadow-sm h-100 py-2">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                    Inscripciones
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-muted" id="inscripcionesCount">0</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-file-signature fa-2x text-info"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 mb-4">
                <div class="card border-left-warning shadow-sm h-100 py-2">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                    Resultados
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-muted" id="resultadosCount">0</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-poll fa-2x text-warning"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gráficas -->
        <div class="row">
            <!-- Time Series Chart -->
            <div class="col-12 mb-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h4 class="text-primary">Resultados por módulo y fecha</h4>
                        <div id="chart-container" style="height: 500px;">Gráfica aparecerá aquí.</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gráfica de calor y anillos (donut) -->
        <div class="row">
            <div class="col-xl-8 col-lg-7 mb-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h4 class="text-primary">Concurrencia de resultados por día y hora</h4>
                        <div id="heatmap-chart-container" style="height: 400px;">Gráfica aparecerá aquí.</div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-lg-5 mb-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h4 class="text-primary">Resultados por Módulo y Evaluación</h4>
                        <div id="donut-chart-container" style="height: 400px;">Gráfica aparecerá aquí.</div>
                    </div>
                </div>
            </div>
        </div>

        @include('scripts.dashboard_charts_data')
    </div>
@endsection
