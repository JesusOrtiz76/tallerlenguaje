@extends('layouts.app')

@section('title',"$modulo->nombre")

@section('content')
    <div class="container">
        <div class="row d-flex justify-content-center align-items-center">
            <div class="col-md-8">
                <div class="card blur-bg shadow-sm border-0">
                    <div class="card-body p-lg-5">
                        <h1 class="text-gradient mb-4 text-center">{{ $modulo->nombre }}</h1>
                        <p class="text-justify">{{ $modulo->descripcion }}</p>

                        <h3>Temas</h3>
                        <ul>
                            @forelse($modulo->temas as $tema)
                                <li>
                                    <a href="{{ route('temas.show', $tema->id) }}">
                                        {{ $tema->titulo }}
                                    </a>
                                </li>
                            @empty
                                <span>No hay temas para mostrar</span>
                            @endforelse
                        </ul>

                        <h3>Evaluación</h3>
                        <p class="text-justify">
                            Completa la evaluación para pasar al siguiente módulo.
                        </p>
                        @forelse ($modulo->evaluaciones as $evaluacion)
                            <div class="mb-3">
                                <a href="{{ route('evaluaciones.show', $evaluacion->id) }}" class="btn btn-primary">{{ $evaluacion->nombre }} del {{ $modulo->nombre }}</a>
                                @if($user->resultados()->where('evaluacion_id', $evaluacion->id)->exists())
                                    <a href="{{ route('evaluaciones.resultado', $evaluacion->id) }}" class="btn btn-primary ms-2">Ver resultado</a>
                                @endif
                            </div>
                        @empty
                            <span>No hay evaluaciones para mostrar</span>
                        @endforelse

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
