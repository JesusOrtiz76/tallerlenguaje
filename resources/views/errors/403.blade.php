@extends('layouts.errors')

@section('title', '403 - Prohibido')

@section('content')

    <div class="error-container">
        <div class="error-code">
            <span class="first-number">4</span>
            <span class="second-number">0</span>
            <span class="last-number">3</span>
        </div>
        <p class="error-message">{{ __('Forbidden') }}</p>
        <p class="error-description">
            Lo sentimos, no tienes permiso para acceder a esta sección.
        </p>
        <a href="/">&larr; Regresar al Inicio</a>
    </div>

@endsection
