<!-- resources/views/cursos/index.blade.php -->

@extends('layouts.app')

@section('title','Cursos')

@section('content')
    <div class="container">
        <div class="row d-flex justify-content-center align-items-center">
            <div class="col-md-8">
                @forelse ($cursos as $curso)
                    <div class="card blur-bg shadow-sm border-0 mb-3">
                        <div class="card-body p-lg-5">
                            <h1 class="text-primary mb-4">{{ $curso->nombre }}</h1>
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
                        <div class="card-footer text-muted">
                            {{ $curso->created_at->diffForHumans() }}
                        </div>
                    </div>
                @empty
                    <div class="card">
                        <div class="card-body p-lg-5">
                            {{ __('No Data') }}
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection
