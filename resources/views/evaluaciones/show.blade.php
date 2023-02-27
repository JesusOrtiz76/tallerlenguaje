@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row d-flex justify-content-center align-items-center h-100">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Evaluación</div>

                    <div class="card-body">
                        @if ($evaluacion->attempts_left <= 0)
                            <div class="alert alert-danger" role="alert">
                                {{ __('No tiene más intentos para esta evaluación.') }}
                            </div>
                        @else
                            <p>{{ __('Esta evaluación tiene un límite de tiempo de 15 minutos. Usted tiene :time_left minutos restantes.', ['time_left' => $evaluacion->time_left]) }}</p>
                            <p>{{ __('Usted tiene :attempts_left intentos restantes para esta evaluación.', ['attempts_left' => $evaluacion->attempts_left]) }}</p>
                            <form method="POST" action="{{ route('evaluations.submit', ['evaluation' => $evaluacion->id]) }}">
                                @csrf

                                @foreach ($evaluacion->questions as $question)
                                    <div class="form-group">
                                        <p>{{ $question->text }}</p>
                                        @foreach ($question->options as $option)
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="answers[{{ $question->id }}]" id="answer_{{ $option->id }}" value="{{ $option->id }}">
                                                <label class="form-check-label" for="answer_{{ $option->id }}">
                                                    {{ $option->text }}
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
