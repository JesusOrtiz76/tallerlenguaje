@extends('layouts.app')

@section('title','Cursos')

@section('content')
    <div class="container">
        @forelse ($cursos as $curso)
            <div class="row d-flex justify-content-center align-items-center flex-lg-row flex-column">
                @if($loop->iteration % 2 == 0)

                    <div class="col-lg-6 p-5 order-2 order-lg-1">
                        <img class="img-fluid mt-4"
                             src="{{ asset('storage/' . $curso->oimg_path) }}"
                             alt="Imagen del curso {{ $curso->onombre }}">
                    </div>

                    <div class="col-lg-6 p-5 order-1 order-lg-2">

                        <p class="text-uppercase">{{ __('Welcome to the writing workshop') }}:</p>
                        <h1 class="text-gradient mb-4">{{ $curso->onombre }}</h1>
                        <p class="text-muted text-justify">{{ $curso->odescripcion }}</p>

                        @if(Auth::check())
                            <div class="row">

                                <!-- Continuar o inscribirse al curso -->
                                <div class="col-auto">
                                    @if(Auth::user()->cursos->contains($curso))
                                        <a href="{{ route('modulos.index', $curso->id) }}"
                                           class="btn btn-primary">Continuar
                                        </a>
                                    @else
                                        <form class="form"
                                              method="POST"
                                              action="{{ route('cursos.inscribirse', $curso->id) }}">
                                            @csrf
                                            <button type="submit" class="btn btn-primary">Inscribirse</button>
                                        </form>
                                    @endif
                                </div>

                                <!-- Anillo de progreso -->
                                <div class="col-auto">
                                    <div class="progress-ring d-flex align-items-center">
                                        <small>
                                            <svg width="38" height="38" viewBox="0 0 38 38">
                                                <circle class="progress-ring__circle-bg" r="16" cx="19" cy="19"/>
                                                <circle class="progress-ring__circle" r="{{ $radio }}" cx="19" cy="19"
                                                        stroke-dasharray="{{ $circunferencia }}"
                                                        stroke-dashoffset="{{ $llenados[$curso->id] }}"/>
                                                <text x="19" y="22"
                                                      class="percentage-label"
                                                      text-anchor="middle">{{ $porcentajes[$curso->id] }} %
                                                </text>
                                            </svg>
                                        </small>
                                        <small class="text-nowrap text-primary ms-2">
                                            Completado
                                        </small>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                @else
                    <div class="col-lg-6 p-5 order-1">

                        <p class="text-uppercase">{{ __('Welcome to the writing workshop') }}:</p>
                        <h1 class="text-gradient mb-4">{{ $curso->onombre }}</h1>
                        <p class="text-muted text-justify">{{ $curso->odescripcion }}</p>

                        @if(Auth::check())
                            <div class="row">

                                <!-- Continuar o inscribirse al curso -->
                                <div class="col-auto">
                                    @if(Auth::user()->cursos->contains($curso))
                                        <a href="{{ route('modulos.index', $curso->id) }}"
                                           class="btn btn-primary">Continuar
                                        </a>
                                    @else
                                        <form class="form"
                                              method="POST"
                                              action="{{ route('cursos.inscribirse', $curso->id) }}">
                                            @csrf
                                            <button type="submit" class="btn btn-primary">Inscribirse</button>
                                        </form>
                                    @endif
                                </div>

                                <!-- Anillo de progreso -->
                                <div class="col-auto">
                                    <div class="progress-ring d-flex align-items-center">
                                        <small>
                                            <svg width="38" height="38" viewBox="0 0 38 38">
                                                <circle class="progress-ring__circle-bg" r="16" cx="19" cy="19"/>
                                                <circle class="progress-ring__circle" r="{{ $radio }}" cx="19" cy="19"
                                                        stroke-dasharray="{{ $circunferencia }}"
                                                        stroke-dashoffset="{{ $llenados[$curso->id] }}"/>
                                                <text x="19" y="22"
                                                      class="percentage-label"
                                                      text-anchor="middle">{{ $porcentajes[$curso->id] }} %
                                                </text>
                                            </svg>
                                        </small>
                                        <small class="text-nowrap text-primary ms-2">
                                            Completado
                                        </small>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

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
@endsection
