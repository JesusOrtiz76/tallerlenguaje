@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row d-flex justify-content-center align-items-center">
            <div class="col-md-10">
                <div class="card shadow-lg border-0">
                    <div class="card-body p-lg-5">
                        <h1 class="mb-4">Resultados de la {{ $evaluacion->nombre }} del {{ $modulo->nombre }}</h1>
                        <p>Tu puntaje es: {{ $resultado->resultados }}/{{ $evaluacion->preguntas->count() }}</p>
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th>Pregunta</th>
                                <th>Tu respuesta</th>
                                <th>Resultado</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($evaluacion->preguntas as $pregunta)
                                <tr>
                                    <td>{{ $pregunta->enunciado }}</td>
                                    <td>{{ $pregunta->opciones()->find($respuestas[strval($pregunta->id)])->texto }}</td>
                                    <td>
                                        @if ($pregunta->opciones()->find($respuestas[strval($pregunta->id)])->es_correcta)
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
@endsection
