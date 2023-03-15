@extends('layouts.app')

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-10 col-lg-6 p-5">
                <p class="text-uppercase">{{ __('Welcom to the course') }}:</p>
                <h2 class="text-primary">{{ $cursos[0]->nombre }}</h2>
                <p class="text-muted">{{ $cursos[0]->descripcion }}</p>
                <a href="{{ route('cursos.index') }}" class="btn btn-lg btn-primary mt-5">{{ __('Go to course') }}</a>
            </div>
            <div class="col-10 col-lg-6 p-5">
                <img class="img-fluid mt-4" src="/assets/img/test.svg" alt="Image Brand">
            </div>

        </div>

@endsection
