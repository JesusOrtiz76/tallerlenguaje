@extends('layouts.app')

@section('title',"$modulo->onombre")

@section('content')
    <div class="container">
        <div class="row d-flex justify-content-center align-items-center">
            <div class="col-md-8">
                <div class="card blur-bg shadow-sm border-0">
                    <div class="card-body p-lg-5">
                        <h1 class="text-gradient mb-4 text-center">{{ $modulo->onombre }}</h1>
                        <p class="text-justify">{{ $modulo->odescripcion }}</p>

                        @php
                            // Módulo especial: el 9 es el de evaluación final
                            $esModuloEvaluacion = ($modulo->id === 9);
                        @endphp

                        <h3>Contenido del módulo</h3>

                        @forelse ($temas as $tema)
                            <div class="mb-4 pb-3 border-bottom">
                                <h5 class="mb-1">
                                    <a href="{{ route('temas.show', $tema->id) }}">
                                        {{ $tema->otitulo }}
                                    </a>
                                </h5>

                                @php
                                    $evaluacionesTema = $evaluaciones->where('tema_id', $tema->id);
                                @endphp

                                @if ($evaluacionesTema->count())
                                    <div class="mt-2 ms-3">
                                        @foreach ($evaluacionesTema as $evaluacion)
                                            <div class="mb-2">
                                                <div class="row g-2 align-items-center">
                                                    <div class="col-6">
                                                        @if($evaluacion->sinIntentos())
                                                            <button class="btn btn-primary btn-sm w-100" disabled>
                                                                {{ $evaluacion->tituloConTipo() }}
                                                            </button>
                                                        @else
                                                            <a href="{{ route('evaluaciones.show', $evaluacion->id) }}"
                                                               class="btn btn-primary btn-sm w-100">
                                                                {{ $evaluacion->tituloConTipo() }}
                                                            </a>
                                                        @endif
                                                    </div>

                                                    @if($user->resultados()->where('evaluacion_id', $evaluacion->id)->exists())
                                                        <div class="col-6">
                                                            <a href="{{ route('evaluaciones.resultado', $evaluacion->id) }}"
                                                               class="btn btn-outline-primary btn-sm w-100">
                                                                Resultado de {{ strtolower($evaluacion->etiquetaTipoSingular()) }}
                                                            </a>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @empty
                            <span>No hay temas para mostrar</span>
                        @endforelse

                        {{-- 2) Evaluaciones generales del módulo (sin tema asignado) --}}
                        @php
                            $evaluacionesSueltas = $evaluaciones->whereNull('tema_id');
                        @endphp

                        @if($evaluacionesSueltas->count())
                            <hr>
                            @php
                                // En módulo 9 se llama "EVALUACIÓN", en los demás "EJERCICIOS"
                                $tituloBloqueSueltas = $esModuloEvaluacion ? 'EVALUACIÓN' : 'EJERCICIOS';
                            @endphp

                            <h3 class="text-uppercase">{{ $tituloBloqueSueltas }}</h3>

                            @foreach ($evaluacionesSueltas as $evaluacion)
                                <div class="mb-3">
                                    <div class="row g-2">
                                        <div class="col-6">
                                            @if($evaluacion->sinIntentos())
                                                <button class="btn btn-primary btn-sm w-100" disabled>
                                                    {{ $evaluacion->tituloConTipo() }}
                                                </button>
                                            @else
                                                <a href="{{ route('evaluaciones.show', $evaluacion->id) }}"
                                                   class="btn btn-primary btn-sm w-100">
                                                    {{ $evaluacion->tituloConTipo() }}
                                                </a>
                                            @endif
                                        </div>

                                        @if($user->resultados()->where('evaluacion_id', $evaluacion->id)->exists())
                                            <div class="col-6">
                                                <a href="{{ route('evaluaciones.resultado', $evaluacion->id) }}"
                                                   class="btn btn-primary btn-sm w-100">
                                                    Resultado de {{ strtolower($evaluacion->etiquetaTipoSingular()) }}
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
