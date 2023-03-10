@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row d-flex justify-content-center align-items-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Evaluación</div>

                    <div class="card-body">

                        @if (($pivot ?? null) && $pivot->pivot->intentos <= 0)
                            <div class="alert alert-danger" role="alert">
                                {{ __('No tiene más intentos para esta evaluación.') }}
                            </div>
                        @else
                            <p>{{ __('Esta evaluación tiene un límite de tiempo de 15 minutos. Usted tiene :tiempo_lim minutos restantes.', ['tiempo_lim' => $evaluacion->tiempo_lim / 60]) }}</p>
                            @if ($pivot ?? null)
                                <p>{{ __('Usted tiene :intentos intentos restantes para esta evaluación.', ['intentos' => $evaluacion->intentos_max - $pivot->pivot->intentos]) }}</p>
                            @else
                                <p>{{ __('Usted tiene :intentos intentos restantes para esta evaluación.', ['intentos' => $evaluacion->intentos_max - 1]) }}</p>
                            @endif
                            <form method="POST" action="{{ route('evaluaciones.submit', ['id_modulo' => $modulo->id, 'id_evaluacion' => $evaluacion->id]) }}">

                                @csrf

                                @foreach ($evaluacion->preguntas->shuffle() as $pregunta)
                                    <div class="form-group">
                                        <p>{{ $pregunta->enunciado }}</p>
                                        @foreach ($pregunta->opciones->shuffle() as $opcion)
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="respuestas[{{ $pregunta->id }}]" id="respuesta_{{ $opcion->id }}" value="{{ $opcion->id }}">
                                                <label class="form-check-label" for="respuesta_{{ $opcion->id }}">
                                                    {{ $opcion->texto }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                @endforeach

                                <button type="submit" class="btn btn-primary">{{ __('Enviar') }}</button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
