@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row d-flex justify-content-center align-items-center">
            <div class="col-md-8">
                <div class="card shadow-lg border-0">
                    <div class="card-body p-lg-5">
                        <h1 class="mb-4">Módulos del curso "{{ $curso->nombre }}"</h1>
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Descripción</th>
                                <th>Acciones</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($modulos as $modulo)
                                <tr>
                                    <td>{{ $modulo->nombre }}</td>
                                    <td>{{ $modulo->descripcion }}</td>
                                    <td>
                                        <a href="{{ route('modulos.show', $modulo) }}" class="btn btn-primary">Ver módulo</a>
                                    </td>
                                </tr>
                            @empty
                                <div class="card">
                                    <div class="card-body p-lg-5">
                                        {{ __('No Data') }}
                                    </div>
                                </div>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
