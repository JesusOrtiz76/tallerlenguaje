@extends('layouts.app')

@section('title', "{$tema->titulo} del {$modulo->nombre}")

@section('content')
    <div class="container">
        <div class="row d-flex justify-content-center align-items-center">
            <div class="col-md-9">
                <h1 class="text-primary mb-4">{{ $modulo->nombre }} - {{ $tema->titulo }}</h1>
                <p class="mb-4">{{ $tema->descripcion }}</p>
                <div class="mb-3">
                    @php
                        $archivoPath = storage_path("app/public/{$tema->archivo}");
                        if (is_file($archivoPath)) {
                            echo file_get_contents($archivoPath);
                        } else {
                            echo '<div class="alert alert-warning">No hay contenido disponible.</div>';
                        }
                    @endphp
                </div>
            </div>
        </div>
    </div>
@endsection
