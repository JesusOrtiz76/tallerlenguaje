<!-- resources/views/cursos/show.blade.php -->

@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row d-flex justify-content-center align-items-center">
            <div class="col-md-8">
                @forelse ($curso->modulos as $modulo)
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">{{ $modulo->nombre }}</h5>
                            <h5 class="card-title">Descripción</h5>
                            <p class="card-text">{{ $modulo->descripcion }}</p>
                            <a href="{{ route('modulos.index', $modulo->id) }}" class="btn btn-primary">Ver</a>
                        </div>
                        <div class="card-footer text-muted">
                            {{ $modulo->created_at->diffForHumans() }}
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
