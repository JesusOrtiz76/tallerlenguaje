@extends('layouts.errors')

@section('title', __('Page Expired'))

@section('content')

    <div class="error-container">
        <div class="error-code">
            <span class="first-number">4</span>
            <span class="second-number">1</span>
            <span class="last-number">9</span>
        </div>
        <p class="error-message">{{ __('Page Expired') }}</p>
        <p class="error-description">
            Notamos tu ausencia y por tu seguridad cerramos la sesión.
        </p>
        <a href="{{ route('/') }}">&larr; Volver a Iniciar sesión</a>
    </div>

@endsection
