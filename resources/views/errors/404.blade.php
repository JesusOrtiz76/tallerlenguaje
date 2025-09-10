@extends('layouts.errors')

@section('title', __('Not Found'))

@section('content')

    <div class="error-container">
        <div class="error-code">
            <span class="first-number">4</span>
            <span class="second-number">0</span>
            <span class="last-number">4</span>
        </div>
        <p class="error-message">{{ __('Not Found') }}</p>
        <p class="error-description">
            Parece que estás perdido.
        </p>
        <a href="/">&larr; Regresar al Inicio</a>
    </div>

@endsection
