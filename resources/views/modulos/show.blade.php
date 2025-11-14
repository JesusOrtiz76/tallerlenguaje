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

                        <h3>Contenido del módulo</h3>

                        {{-- 1) Temas y sus evaluaciones asociadas --}}
                        @forelse ($temas as $tema)
                            <div class="mb-4">
                                <h5 class="mb-1">
                                    <a href="{{ route('temas.show', $tema->id) }}">
                                        {{ $tema->otitulo }}
                                    </a>
                                </h5>

                                @php
                                    // Evaluaciones que se ligaron a este tema (tema_id)
                                    $evaluacionesTema = $evaluaciones->where('tema_id', $tema->id);
                                @endphp

                                @if ($evaluacionesTema->count())
                                    <p class="mb-2 text-muted small">
                                        Completa la(s) evaluación(es) de este tema antes de continuar.
                                    </p>

                                    @foreach ($evaluacionesTema as $evaluacion)
                                        <div class="mb-2">
                                            <div class="row g-2 align-items-center">
                                                <div class="col-6">
                                                    @if($evaluacion->sinIntentos())
                                                        <button class="btn btn-primary btn-sm w-100" disabled>
                                                            {{ $evaluacion->onombre }}
                                                        </button>
                                                    @else
                                                        <a href="{{ route('evaluaciones.show', $evaluacion->id) }}"
                                                           class="btn btn-primary btn-sm w-100">
                                                            {{ $evaluacion->onombre }}
                                                        </a>
                                                    @endif
                                                </div>

                                                @if($user->resultados()->where('evaluacion_id', $evaluacion->id)->exists())
                                                    <div class="col-6">
                                                        <a href="{{ route('evaluaciones.resultado', $evaluacion->id) }}"
                                                           class="btn btn-outline-primary btn-sm w-100">
                                                            Resultado de {{ $evaluacion->onombre }}
                                                        </a>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
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
                            <h3>Evaluaciones del módulo</h3>
                            <p class="text-justify">
                                Completa estas evaluaciones para pasar al siguiente bloque.
                            </p>

                            @foreach ($evaluacionesSueltas as $evaluacion)
                                <div class="mb-3">
                                    <div class="row g-2">
                                        <div class="col-6">
                                            @if($evaluacion->sinIntentos())
                                                <button class="btn btn-primary btn-sm w-100" disabled>
                                                    {{ $evaluacion->onombre }}
                                                </button>
                                            @else
                                                <a href="{{ route('evaluaciones.show', $evaluacion->id) }}"
                                                   class="btn btn-primary btn-sm w-100">
                                                    {{ $evaluacion->onombre }}
                                                </a>
                                            @endif
                                        </div>

                                        @if($user->resultados()->where('evaluacion_id', $evaluacion->id)->exists())
                                            <div class="col-6">
                                                <a href="{{ route('evaluaciones.resultado', $evaluacion->id) }}"
                                                   class="btn btn-primary btn-sm w-100">
                                                    Resultado de {{ $evaluacion->onombre }}
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        @else
                            {{-- si quieres, puedes mantener el mensaje de "no hay evaluaciones" cuando no haya ni ligadas ni sueltas --}}
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
