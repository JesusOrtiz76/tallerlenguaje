@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>Módulos del curso "{{ $curso->nombre }}"</h1>
            <table class="table">
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
                            <a href="{{ route('modulos.show', $modulo) }}" class="btn btn-primary">Ver Módulo</a>
                        </td>
                    </tr>
                    @empty
                    <div class="card">
                        <div class="card-body">
                            {{ __('No Data') }}
                        </div>
                    </div>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection