@extends('layouts.app')

@section('title', 'Reportes')

@section('content')
    <div class="container-fluid">
        <h1 class="h3 mb-4 text-gray-800">Reportes</h1>

        <div class="card shadow-sm border-0">
            <div class="card-body">
                <form method="GET" action="{{ route('reportes.export') }}">
                    <div class="row">
                        {{-- Tipo de reporte --}}
                        <div class="col-md-4 mb-3">
                            <label for="tipo" class="form-label">Tipo de reporte</label>
                            <select name="tipo" id="tipo"
                                    class="form-select @error('tipo') is-invalid @enderror">
                                <option value="">Seleccione...</option>

                                <option value="centro_trabajo" {{ old('tipo') == 'centro_trabajo' ? 'selected' : '' }}>
                                    Por centro de trabajo
                                </option>

                                <option value="unidad_administrativa" {{ old('tipo') == 'unidad_administrativa' ? 'selected' : '' }}>
                                    Por unidad administrativa
                                </option>

                                <option value="aprobados" {{ old('tipo') == 'aprobados' ? 'selected' : '' }}>
                                    Aprobados (calificación ≥ 80%)
                                </option>

                                <option value="reprobados" {{ old('tipo') == 'reprobados' ? 'selected' : '' }}>
                                    Reprobados (calificación &lt; 80%)
                                </option>
                            </select>
                            @error('tipo')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                            <small class="text-muted">
                                Selecciona qué tipo de reporte quieres generar.
                            </small>
                        </div>

                        {{-- Centro de trabajo (por clave) --}}
                        <div class="col-md-4 mb-3">
                            <label for="oclave" class="form-label">Centro de trabajo (CCT)</label>
                            <input type="text"
                                   name="oclave"
                                   id="oclave"
                                   class="form-control @error('oclave') is-invalid @enderror"
                                   value="{{ old('oclave') }}"
                                   oninput="this.value = this.value.toUpperCase();">
                            @error('oclave')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                            <small class="text-muted">
                                Úsalo cuando el tipo de reporte sea "Por centro de trabajo".
                                Déjalo vacío para traer todos. Debe ser una CCT válida (10 caracteres).
                            </small>
                        </div>

                        {{-- Unidad administrativa --}}
                        <div class="col-md-4 mb-3">
                            <label for="unidadadministrativa_id" class="form-label">Unidad administrativa</label>
                            <select name="unidadadministrativa_id" id="unidadadministrativa_id"
                                    class="form-select @error('unidadadministrativa_id') is-invalid @enderror">
                                <option value="">Todas</option>
                                @foreach($unidades as $unidad)
                                    <option value="{{ $unidad->id }}"
                                        {{ old('unidadadministrativa_id') == $unidad->id ? 'selected' : '' }}>
                                        {{ $unidad->onombre }}
                                    </option>
                                @endforeach
                            </select>
                            @error('unidadadministrativa_id')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                            <small class="text-muted">
                                Úsalo cuando el tipo de reporte sea "Por unidad administrativa".
                            </small>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end mt-3">
                        <button type="submit" class="btn btn-outline-success">
                            <i class="fa-solid fa-file-excel me-1"></i>
                            Descargar Excel
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="mt-3">
            <p class="text-muted mb-0">
                El archivo se descargará en formato CSV compatible con Excel.
            </p>
        </div>
    </div>
@endsection
