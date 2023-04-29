@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row d-flex justify-content-center align-items-center">
            <div class="col-md-8">
                <div class="card shadow-lg border-0">
                    <div class="card-body p-lg-5">
                        <h1 class="mb-4">{{ $modulo->nombre }}</h1>
                        <p>{{ $modulo->descripcion }}</p>

                        <h3>Temas</h3>
                        <ul>
                            @foreach($modulo->temas as $tema)
                                <li>
                                    <a href="{{ route('temas.show', $tema->id) }}">
                                        {{ $tema->titulo }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>

                        <h3>Evaluación</h3>
                        <p>
                            Completa la evaluación para pasar al siguiente módulo.
                        </p>
                        @foreach ($modulo->evaluaciones as $evaluacion)
                            <div class="mb-3">
                                <a href="{{ route('evaluaciones.show', $evaluacion->id) }}" class="btn btn-primary">{{ $evaluacion->nombre }} del {{ $modulo->nombre }}</a>
                                @if($user->resultados()->where('evaluacion_id', $evaluacion->id)->exists())
                                    <a href="{{ route('evaluaciones.resultado', [$evaluacion->modulo_id, $evaluacion->id]) }}" class="btn btn-primary ms-2">Ver resultado</a>
                                @endif
                            </div>
                        @endforeach

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
