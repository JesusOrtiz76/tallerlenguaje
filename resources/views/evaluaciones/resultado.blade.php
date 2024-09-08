@extends('layouts.app')

@section('title',"Resultado de la $evaluacion->onombre del $modulo->onombre")

@section('content')
    <div class="container">
        <div class="row d-flex justify-content-center align-items-center">
            <div class="col-md-10">
                <div class="card blur-bg shadow-sm border-0">
                    <div class="card-body p-lg-5">
                        <h1 class="text-gradient mb-4 text-center">
                            Resultado de {{ $evaluacion->onombre }} del {{ $modulo->onombre }}
                        </h1>

                        <div class="d-flex justify-content-between align-items-center mb-4">

                            <button class="btn btn-outline-primary">
                                Tu puntaje es: <strong>{{ $puntaje }}/{{ $evaluacion->onumero_preguntas }}</strong>
                            </button>


                            @if($evaluacion->sinIntentos())
                                <button class="btn btn-primary" disabled>Intentos agotados</button>
                            @else
                                <a href="{{ route('evaluaciones.show', $evaluacion->id) }}" class="btn btn-primary">
                                    Realizar otro intento
                                </a>
                            @endif
                        </div>

                        <div class="table-responsive overflow-auto">
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <th>Pregunta</th>
                                    <th>Tu respuesta</th>
                                    <th>Resultado</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($preguntas as $pregunta)
                                    <tr>
                                        <td style="min-width: 300px;">{{ $pregunta['enunciado'] }}</td>
                                        <td style="min-width: 200px;">{{ $pregunta['opcion'] }}</td>
                                        <td>
                                            @if ($pregunta['es_correcta'])
                                                <span class="badge bg-success">Correcta</span>
                                            @else
                                                <span class="badge bg-warning text-dark">Incorrecta</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
