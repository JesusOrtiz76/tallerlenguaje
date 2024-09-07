@extends('layouts.errors')

@section('title', __('Server Error'))

@section('content')

    <div class="error-container">
        <div class="error-code">
            <span class="first-number">5</span>
            <span class="second-number">0</span>
            <span class="last-number">0</span>
        </div>
        <p class="error-message">{{ __('Server Error') }}</p>
        <p class="error-description">
            Ups! Algo malió sal, encontraste un fallo en la matrix.
        </p>
        <a href="{{ route('/') }}">&larr; Regresar al Inicio</a>
    </div>

@endsection
