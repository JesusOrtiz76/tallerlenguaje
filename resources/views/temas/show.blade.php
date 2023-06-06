@extends('layouts.app')

@section('title',"$tema->titulo del $modulo->nombre")

@section('content')
    <div class="container">
        <div class="row d-flex justify-content-center align-items-center">
            <div class="col-md-9">
                <h1 class="text-primary mb-4">{{ $tema->titulo }} del {{ $modulo->nombre }}</h1>
                <p class="mb-4">{{ $tema->descripcion }}</p>
                <div class="mb3">
                    {!! $tema->contenido_html !!}
                </div>
            </div>
        </div>
    </div>
@endsection
