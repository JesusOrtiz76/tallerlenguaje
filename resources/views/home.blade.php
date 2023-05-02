@extends('layouts.app')

@section('title','Introducción')

@section('content')
    <div class="container">
        <div class="row d-flex justify-content-center align-items-center">
            <div class="col-lg-6 p-5">
                <p class="text-uppercase">{{ __('Welcom to the course') }}:</p>
                <h1 class="text-primary mb-4">{{ $cursos[0]->nombre }}</h1>
                <p class="text-muted">{{ $cursos[0]->descripcion }}</p>
                <a href="{{ route('cursos.index') }}" class="btn btn-lg btn-primary mt-5">{{ __('Go to course') }}</a>
            </div>
            <div class="col-lg-6 p-5">
                <img class="img-fluid mt-4" src="/assets/img/test.svg" alt="Image Brand">
            </div>

        </div>
@endsection
