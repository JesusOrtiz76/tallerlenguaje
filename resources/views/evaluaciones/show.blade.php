@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row d-flex justify-content-center align-items-center">
            <div class="col-md-8">

                @if (($pivot ?? null) && $pivot->pivot->intentos <= 0)
                    <div class="alert alert-danger" role="alert">
                        {{ __('No tiene más intentos para esta evaluación.') }}
                    </div>
                @else
                    <p>{{ __('Esta evaluación tiene un límite de tiempo de 15 minutos. Usted tiene :tiempo_lim minutos restantes.',['tiempo_lim' => $evaluacion->tiempo_lim / 60]) }}</p>
                    @if ($pivot ?? null)
                        <p>{{ __('Usted tiene :intentos intentos restantes para esta evaluación.', ['intentos' => $evaluacion->intentos_max - $pivot->pivot->intentos]) }}</p>
                    @else
                        <p>{{ __('Usted tiene :intentos intentos restantes para esta evaluación.', ['intentos' => $evaluacion->intentos_max - 1]) }}</p>
                    @endif
                    <form method="POST" action="{{ route('evaluaciones.submit', ['id_modulo' => $modulo->id, 'id_evaluacion' => $evaluacion->id]) }}">
                        @csrf
                        @foreach ($preguntas as $pregunta)
                            <div class="card mb-4 border-0 shadow-sm">
                                <div class="card-body">
                                    <h5 class="mb-3">{{ $pregunta->enunciado }}</h5>
                                    <div class="w-100 d-flex justify-content-center row ps-4">
                                        @foreach ($pregunta->opciones as $opcion)
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
                            <button type="submit" class="btn btn-lg btn-primary">{{ __('Enviar') }}</button>
                        </div>
                    </form>
                @endif
            </div>
        </div>
@endsection
