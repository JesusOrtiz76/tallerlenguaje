@extends('layouts.app')

@section('title',"$evaluacion->nombre del $modulo->nombre")

@section('content')
    <div class="container">
        <div class="row d-flex justify-content-center align-items-center">
            <div class="col-md-8">
                <h1 class="text-primary mb-4">{{ $evaluacion->nombre }} del {{ $modulo->nombre }}</h1>

                <!--<p>{{ __('Esta evaluación tiene un límite de tiempo de 15 minutos. Usted tiene :tiempo_lim minutos restantes.',['tiempo_lim' => $evaluacion->tiempo_lim / 60]) }}</p>-->
                @if ($pivot ?? null)
                    @php
                        $intentosRestantes = $evaluacion->intentos_max - $pivot->pivot->intentos;
                        $intentoActual = $pivot->pivot->intentos + 1;
                    @endphp
                    <p>{{ __('Intento :intentoActual/:intentosTotales', ['intentoActual' => $intentoActual, 'intentosTotales' => $evaluacion->intentos_max]) }}</p>
                @else
                    <p>{{ __('Intento 1/:intentosTotales', ['intentosTotales' => $evaluacion->intentos_max]) }}</p>
                @endif

                <form method="POST" action="{{ route('evaluaciones.submit', ['modulo' => $modulo->id, 'evaluacion' => $evaluacion->id]) }}">
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
                        <button type="submit" class="btn btn-primary">{{ __('Enviar') }}</button>
                    </div>
                </form>
            </div>
        </div>
@endsection
