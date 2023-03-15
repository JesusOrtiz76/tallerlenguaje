@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row d-flex justify-content-center align-items-center">
            <div class="col-md-8">
                <h2>{{ $modulo->nombre }}</h2>
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
                    <a href="{{ route('evaluaciones.show', $evaluacion->id) }}" class="btn btn-primary">{{ $evaluacion->nombre }}</a>
                @endforeach
            </div>
        </div>
    </div>
@endsection
