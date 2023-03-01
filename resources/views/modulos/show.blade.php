@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row d-flex justify-content-center align-items-center h-100">
            <div class="col-md-8">
                <h2>{{ $modulo->nombre }}</h2>
                <p>{{ $modulo->descripcion }}</p>
            </div>
        </div>
        <div class="row d-flex justify-content-center align-items-center h-100">
            <div class="col-md-8">
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
            </div>
        </div>
        <div class="row d-flex justify-content-center align-items-center h-100">
            <div class="col-md-8">
                <h3>Evaluación</h3>
                <p>
                    Completa la evaluación para pasar al siguiente módulo.
                </p>
                <form action="{{ route('evaluaciones.show') }}" method="POST">
                    @csrf
                    <input type="hidden" name="modulo_id" value="{{ $modulo->id }}">
                    <button type="submit" class="btn btn-primary">
                        Comenzar evaluación
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
