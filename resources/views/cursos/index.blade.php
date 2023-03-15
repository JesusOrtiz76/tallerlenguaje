<!-- resources/views/cursos/index.blade.php -->

@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row d-flex justify-content-center align-items-center">
            <div class="col-md-8">
                @forelse ($cursos as $curso)
                    <div class="card blur-bg shadow-lg border-0">
                        <div class="card-body">
                            <h5>{{ $curso->nombre }}</h5>
                            <p class="card-text text-muted">{{ $curso->descripcion }}</p>
                            @if(Auth::user()->cursos->contains($curso))
                                <a href="{{ route('modulos.index', $curso->id) }}" class="btn btn-primary">Continuar</a>
                            @else
                                <form method="POST" action="{{ route('cursos.inscribirse', $curso->id) }}">
                                    @csrf
                                    <button type="submit" class="btn btn-primary">Inscribirse</button>
                                </form>
                            @endif
                        </div>
                        <div class="card-footer bg-white text-muted">
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
