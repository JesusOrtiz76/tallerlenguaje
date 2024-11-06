@extends('layouts.app')

@section('title', 'Cursos')

@section('content')
    <div class="container">
        @forelse ($cursos as $curso)
            <div class="row d-flex justify-content-center align-items-center flex-lg-row flex-column">
                @if($loop->iteration % 2 == 0)
                    <!-- Imagen del curso -->
                    <div class="col-lg-6 p-5 order-2 order-lg-1">
                        <img class="img-fluid mt-4"
                             src="{{ asset('storage/' . $curso->oimg_path) }}"
                             alt="Imagen del curso {{ $curso->onombre }}">
                    </div>

                    <!-- Contenido del curso -->
                    <div class="col-lg-6 p-5 order-1 order-lg-2">
                        @include('partials.cursos_detail', [
                            'curso' => $curso,
                            'radio' => $radio,
                            'circunferencia' => $circunferencia,
                            'llenados' => $llenados,
                            'porcentajes' => $porcentajes,
                            'scores' => $scores
                        ])
                    </div>
                @else
                    <!-- Contenido del curso -->
                    <div class="col-lg-6 p-5 order-1">
                        @include('partials.cursos_detail', [
                            'curso' => $curso,
                            'radio' => $radio,
                            'circunferencia' => $circunferencia,
                            'llenados' => $llenados,
                            'porcentajes' => $porcentajes,
                            'scores' => $scores
                        ])
                    </div>

                    <!-- Imagen del curso -->
                    <div class="col-lg-6 p-5 order-2">
                        <img class="img-fluid mt-4"
                             src="{{ asset('storage/' . $curso->oimg_path) }}"
                             alt="Imagen del curso {{ $curso->onombre }}">
                    </div>
                @endif
            </div>
        @empty
            <div class="row">
                <div class="card">
                    <div class="card-body p-lg-5">
                        {{ __('No Data') }}
                    </div>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Incluir el modal -->
    @auth
        @include('scripts.open_modal_change_name')
        @include('partials.certificado_modal_change_name')
        @include('scripts.certificados_pdf_file')
    @endauth
@endsection
