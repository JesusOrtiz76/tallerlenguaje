@extends('layouts.app')

@section('title',"$evaluacion->nombre del $modulo->nombre")

@section('content')
    <div class="container">
        <div class="row d-flex justify-content-center align-items-center">
            <div class="col-md-8">
                <h1 class="text-gradient mb-4 text-center">{{ $evaluacion->nombre }} del {{ $modulo->nombre }}</h1>

                <!--<p class="text-justify">{{ __('Esta evaluación tiene un límite de tiempo de 15 minutos. Usted tiene :tiempo_lim minutos restantes.',['tiempo_lim' => $evaluacion->tiempo_lim / 60]) }}</p>-->

                @if ($pivot ?? null)
                    @php
                        $intentosRestantes = $evaluacion->intentos_max - $pivot->pivot->intentos;
                        $intentoActual = $pivot->pivot->intentos + 1;
                    @endphp
                    <p class="text-justify">{{ __('Intento :intentoActual/:intentosTotales', ['intentoActual' => $intentoActual, 'intentosTotales' => $evaluacion->intentos_max]) }}</p>
                @else
                    <p class="text-justify">{{ __('Intento 1/:intentosTotales', ['intentosTotales' => $evaluacion->intentos_max]) }}</p>
                @endif

                <form id="formulario_evaluacion"
                      method="POST"
                      action="{{ route('evaluaciones.submit', ['modulo' => $modulo->id, 'evaluacion' => $evaluacion->id]) }}">
                @csrf
                    @foreach ($preguntas as $pregunta)
                        <div class="card mb-4 shadow-sm border-0 blur-bg">
                            <div class="card-body px-lg-3">
                                <h5 class="mb-3">{{ $pregunta->enunciado }}</h5>
                                <div class="w-100 d-flex justify-content-center row ps-4">
                                    @foreach ($pregunta->opciones->shuffle() as $opcion)
                                        <input type="radio"
                                               class="btn-check"
                                               name="respuestas[{{ $pregunta->id }}]"
                                               id="respuesta_{{ $opcion->id }}"
                                               value="{{ $opcion->id }}"
                                               @if(old('respuestas.'.$pregunta->id, '') == $opcion->id)
                                                   checked
                                            @endif>
                                        <label class="btn col btn-outline-primary py-1 m-1"
                                               for="respuesta_{{ $opcion->id }}">
                                            {{ $opcion->texto }}
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

                // Verificar si el número de respuestas es igual al número de preguntas
                if (respuestas.length !== {{ count($preguntas) }}) {
                    e.preventDefault(); // Previene el envío del formulario si no todas las preguntas han sido contestadas

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
