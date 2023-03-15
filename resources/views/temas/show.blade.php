@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row d-flex justify-content-center align-items-center">
            <div class="col-md-9">
                <h1 class="mb-4">{{ $tema->titulo }}</h1>
                <div class="mb3">
                {!! htmlspecialchars_decode($tema->contenido) !!}
                </div>

            </div>
        </div>
    </div>
@endsection
