@extends('layouts.app')

@section('title',"$evaluacion->onombre del $modulo->onombre")

@section('content')
    <div class="container">
        <div class="row d-flex justify-content-center align-items-center">
            <div class="col-md-8">
                <h1 class="text-gradient mb-4 text-center">{{ $evaluacion->onombre }} del {{ $modulo->onombre }}</h1>

                @if ($intentoActual)
                    <p class="text-justify">
                        {{ __('Intento :intentoActual/:intentosTotales', ['intentoActual' => $intentoActual, 'intentosTotales' => $evaluacion->ointentos_max]) }}
                    </p>
                @else
                    <p class="text-justify">
                        {{ __('Intento 1/:intentosTotales', ['intentosTotales' => $evaluacion->ointentos_max]) }}
                    </p>
                @endif

                <form id="formulario_evaluacion" method="POST" action="{{ route('evaluaciones.submit', ['modulo' => $modulo->id, 'evaluacion' => $evaluacion->id]) }}">
                    @csrf

                    @foreach ($preguntas->shuffle() as $pregunta)
                    <div class="card mb-4 shadow-sm border-0 blur-bg">
                        <div class="card-body px-lg-3">
                            <h5 class="mb-3">{{ $pregunta->oenunciado }}</h5>
                            <div class="w-100 d-flex justify-content-center row ps-4">
                                @foreach ($pregunta->opciones->shuffle() as $opcion)
                                <input type="radio" class="btn-check" name="respuestas[{{ $pregunta->id }}]" id="respuesta_{{ $opcion->id }}" value="{{ $opcion->id }}" @if(old('respuestas.'.$pregunta->id, '') == $opcion->id) checked @endif>
                                <label class="btn col btn-outline-primary py-1 m-1 text-justify" for="respuesta_{{ $opcion->id }}">
                                    {!! nl2br(e($opcion->otexto)) !!}
                                </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endforeach

                    <div class="w-100 d-flex justify-content-center">
                        <button type="submit" class="btn btn-lg btn-primary col-4">{{ __('Enviar') }}</button>
                    </div>
                </form>
            </div>
        </div>
        <script>
            document.getElementById('formulario_evaluacion').addEventListener('submit', function(e) {
                let respuestas = document.querySelectorAll('input[type=radio]:checked');

                if (respuestas.length !== {{ count($preguntas) }}) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Mensaje',
                        text: "Completa las preguntas.",
                        icon: 'warning',
                        confirmButtonColor: '#FCCD00',
                        iconColor: '#FCCD00',
                    });
                }
            });
        </script>
@endsection
