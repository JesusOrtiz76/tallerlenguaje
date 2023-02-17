<!-- resources/views/cursos/index.blade.php -->

@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row d-flex justify-content-center align-items-center h-100">
        <div class="col-md-8">
            @forelse ($cursos as $curso)
            <div class="card">
                <div class="card-header text-center text-uppercase">
                    <h5>{{ $curso->nombre }}</h5>
                </div>
                <div class="card-body">
                    @include('partials.messages')
                    <h5 class="card-title">{{ __('Description') }}</h5>
                    <p class="card-text">{{ $curso->descripcion }}</p>
                    @if(Auth::user()->InscritoEnCurso($curso))
                    <a href="{{ route('modulos.index', $curso->id) }}" class="btn btn-primary">Continuar</a>
                    @else
                    <form method="POST" action="{{ route('cursos.inscribirse', $curso->id) }}">
                        @csrf
                        <button type="submit" class="btn btn-primary">Inscribirse</button>
                    </form>
                    @endif
                </div>
                <div class="card-footer text-muted">
                    {{ $curso->created_at->diffForHumans() }}
                </div>
            </div>
            @empty
            <div class="card">
                <div class="card-body">
                    {{ __('No Data') }}
                </div>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection