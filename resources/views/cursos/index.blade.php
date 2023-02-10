<!-- resources/views/cursos/index.blade.php -->

@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                @forelse ($cursos as $curso)
                    <div class="card">
                        <div class="card-header text-center text-uppercase">
                            <h5>{{ $curso->nombre }}</h5>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">{{ __('Description') }}</h5>
                            <p class="card-text">{{ $curso->descripcion }}</p>
                            <a href="{{ route('cursos.show', $curso->id) }}" class="btn btn-primary">{{ __('Show modules') }}</a>
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
