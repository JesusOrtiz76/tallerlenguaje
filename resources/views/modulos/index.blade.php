@extends('layouts.app')

@section('title',"Módulos del curso '$curso->onombre'")

@section('content')
    <div class="container">
        <div class="row d-flex justify-content-center align-items-center">
            <div class="col-md-10">
                <div class="card blur-bg shadow-sm border-0 overflow-auto">
                    <div class="card-body p-lg-5">
                        <h1 class="text-gradient mb-4 text-center">Módulos del curso</h1>
                        <div class="table-responsive overflow-auto">
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Descripción</th>
                                    <th>Acciones</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($modulos as $modulo)
                                    <tr>
                                        <td class="text-nowrap align-middle">
                                            <strong>{{ $modulo->onombre }}</strong>
                                        </td>
                                        <td class="text-justify" style="min-width: 500px;">
                                            {{ $modulo->odescripcion }}
                                        </td>
                                        <td class="align-middle">
                                            <a href="{{ route('modulos.show', $modulo) }}"
                                               class="btn btn-primary">
                                                Ver módulo
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
