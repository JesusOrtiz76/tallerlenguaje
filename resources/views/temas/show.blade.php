@extends('layouts.app')

@section('title', "{$tema->otitulo} del {$modulo->onombre}")

@section('content')
    <div class="container">
        <div class="row d-flex justify-content-center align-items-center">
            <div class="col-md-9">
                <h1 class="text-gradient mb-4 text-center">{{ $tema->otitulo }}</h1>
                <div class="mb-3">
                    @php
                        $archivoPath = storage_path("app/public/{$tema->ohtml_file}");
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
