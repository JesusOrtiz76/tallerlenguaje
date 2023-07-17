@extends('layouts.app')

@section('title',"Resultado de la $evaluacion->nombre del $modulo->nombre")

@section('content')
    <div class="container">
        <div class="row d-flex justify-content-center align-items-center">
            <div class="col-md-10">
                <div class="card blur-bg shadow-sm border-0">
                    <div class="card-body p-lg-5">
                        <h1 class="text-gradient mb-4 text-center">
                            Resultado de la {{ $evaluacion->nombre }} del {{ $modulo->nombre }}
                        </h1>
                        <p class="text-justify">Tu puntaje es: {{ $puntaje }}/{{ $evaluacion->numero_preguntas }}</p>
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
