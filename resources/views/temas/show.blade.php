@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row d-flex justify-content-center align-items-center">
            <div class="col-md-9">
                <h1 class="mb-2">{{ $tema->titulo }} del {{ $modulo->nombre }}</h1>
                <p class="mb-4">{{ $tema->descripcion }}</p>
                <div class="mb3">
                {!! htmlspecialchars_decode($tema->contenido) !!}
                </div>

            </div>
        </div>
    </div>
@endsection
