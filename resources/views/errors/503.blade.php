@extends('layouts.errors')

@section('title', __('Service Unavailable'))

@section('content')

    <div class="error-container">
        <div class="error error-code">
            <span class="first-number">5</span>
            <span class="second-number">0</span>
            <span class="last-number">3</span>
        </div>
        <p class="error-message">{{ __('Service Unavailable') }}</p>
        <p class="error-description">
            Actualmente estamos realizando mantenimiento programado. Por favor, vuelve más tarde.
        </p>
    </div>

@endsection
