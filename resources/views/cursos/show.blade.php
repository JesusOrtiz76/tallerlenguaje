<!-- resources/views/cursos/show.blade.php -->

@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row d-flex justify-content-center align-items-center h-100">
        <div class="col-md-8">
            @forelse ($curso->modulos as $modulo)
            <div class="card mb-4">
                <div class="card-header text-center text-uppercase">
                    <h5>{{ $modulo->nombre }}</h5>
                </div>
                <div class="card-body">
                    @include('partials.messages')
                    <h5 class="card-title">{{ __('Description') }}</h5>
                    <p class="card-text">{{ $modulo->descripcion }}</p>
                    <a href="{{ route('modulos.show', $modulo->id) }}" class="btn btn-primary">{{ __('Show') }}</a>
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