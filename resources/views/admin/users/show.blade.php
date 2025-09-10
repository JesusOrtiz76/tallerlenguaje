@extends('layouts.app')

@section('title',"Detalle de usuario")

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center align-items-center">
            <div class="col-md-12">
                <div class="card blur-bg shadow-sm border-0">
                    <div class="card-body p-lg-5">
                        <h1 class="text-gradient mb-4 text-center">{{ $user->name }}</h1>

                        <div class="d-flex justify-content-end mb-3">
                            <!-- Botón para abrir el modal de edición -->
                            <button type="button" class="btn btn-primary editUserBtn"
                                    data-id="{{ $user->id }}"
                                    data-name="{{ $user->name }}"
                                    data-email="{{ $user->email }}"
                                    data-rfc="{{ $user->orfc }}">
                                <i class="fas fa-pencil-alt"></i> Editar
                            </button>
                        </div>

                        <!-- Listado de cursos con cards -->
                        @foreach($cursos as $curso)
                            <div class="card mb-4 border-primary">
                                <div class="card-header bg-primary text-white">
                                    Curso: {{ $curso->onombre }}
                                </div>
                                <div class="card-body">
                                    <div class="d-flex justify-content-end">
                                        @if($curso->userScore)
                                            <span class="badge rounded-pill bg-primary">
                                                <strong>Puntuación total:</strong>
                                                {{ number_format($curso->userScore->score_percentage, 2) }}%
                                            </span>
                                        @endif
                                    </div>

                                    @if($curso->inscripcionDetails->isNotEmpty())
                                        <div class="table-responsive mt-3">
                                            <table class="table table-hover">
                                                <thead>
                                                <tr>
                                                    <th>Módulo</th>
                                                    <th>Evaluación</th>
                                                    <th class="text-center">Intentos Máximos</th>
                                                    <th class="text-center">Intentos</th>
                                                    <th class="text-center text-nowrap">Aciertos/No. Preguntas</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($curso->inscripcionDetails as $detail)
                                                    <tr>
                                                        <td class="text-nowrap">{{ $detail->modulo_nombre }}</td>
                                                        <td class="text-nowrap">{{ $detail->evaluacion_nombre }}</td>
                                                        <td class="text-center">{{ $detail->intentos_max }}</td>
                                                        <td class="text-center">{{ $detail->intentos }}</td>
                                                        <td class="text-center">
                                                            {{ $detail->aciertos }}/{{ $detail->num_preguntas }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <p class="text-muted text-center">Aún no se ha inscrito a este curso.</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('partials.users_modal_edit_user')
    @include('scripts.users_open_modal_edit_user')
@endsection
