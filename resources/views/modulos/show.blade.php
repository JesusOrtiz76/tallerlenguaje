@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h2>{{ $modulo->nombre }}</h2>
                <p>{{ $modulo->descripcion }}</p>
                @forelse ($modulo->bloques as $bloque)
                    <div class="card mb-4">
                        <div class="card-header text-center text-uppercase">
                            <h5>{{ $bloque->nombre }}</h5>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">{{ __('Description') }}</h5>
                            <p class="card-text">{{ $bloque->descripcion }}</p>
                            <a href="#" class="btn btn-primary">{{ __('Go') }}</a>
                        </div>
                        <div class="card-footer text-muted">
                            {{ $bloque->created_at->diffForHumans() }}
                        </div>
                    </div>
                @empty
                    <div class="card">
                        <div class="card-body">
                            {{ __('No Data') }}
                        </div>
                    </div>
                @endforelse
                <a href="{{ route('cursos.index') }}" class="btn btn-secondary">{{ __('Back to top') }}</a>
            </div>
        </div>
    </div>
@endsection
