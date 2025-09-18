@extends('layouts.app')

@section('title',"Listado de usuarios")

@section('content')
    <div class="container-fluid">
        <div class="row d-flex justify-content-center align-items-center">
            <div class="col-md-12">
                <div class="card blur-bg shadow-sm border-0">
                    <div class="card-body p-lg-5">
                        <h1 class="text-gradient mb-4 text-center">
                            Listado de usuarios
                        </h1>
                        <div class="row">
                            <div class="col-lg-6 ms-auto">
                                <!-- Formulario de búsqueda en Laravel con Bootstrap 5 -->
                                <form action="{{ route('admin.users') }}" method="GET" class="form-inline">
                                    <div class="input-group">
                                        <!-- Campo de búsqueda -->
                                        <input type="text"
                                               name="search"
                                               class="form-control"
                                               placeholder="Buscar..."
                                               aria-label="Buscar..."
                                               value="{{ request('search') }}">

                                        <!-- Botón de buscar -->
                                        <button class="btn btn-outline-primary"
                                                type="submit"
                                                data-bs-toggle="tooltip"
                                                title="Buscar">
                                            <i class="fa-solid fa-magnifying-glass"></i>
                                        </button>

                                        <!-- Botón para limpiar la búsqueda -->
                                        <a class="btn btn-outline-primary"
                                           href="{{ route('admin.users') }}"
                                           role="button" data-bs-toggle="tooltip"
                                           title="Limpiar búsqueda">
                                            <i class="fa-solid fa-eraser"></i>
                                        </a>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="table-responsive overflow-auto mt-3">
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <th class="text-center">Centro de Trabajo</th>
                                    <th class="text-center">Nombre</th>
                                    <th class="text-center">RFC</th>
                                    <th class="text-center">Email</th>
                                    <th class="text-center">Fecha de Creación</th>
                                    <th class="text-center">Acciones</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($users as $user)
                                    <tr>
                                        <td>
                                            {{ $user->centroTrabajo->oclave ?? 'N/A' }}
                                        </td>
                                        <td class="text-nowrap">
                                            {{ $user->name }}
                                        </td>
                                        <td class="text-center">
                                            {{ $user->orfc }}
                                        </td>
                                        <td>
                                            {{ $user->email }}
                                        </td>
                                        <td class="text-center text-nowrap">
                                            {{ $user->created_at->format('d/m/Y h:i A') }}
                                        </td>
                                        <td class="text-center text-nowrap">
                                            <!-- Botón para ver el detalle del usuario -->
                                            <a href="{{ route('admin.users.show', $user) }}"
                                               class="btn btn-sm btn-golden text-white">
                                                <i class="fas fa-eye"></i> Ver
                                            </a>
                                            <!-- Botón para abrir el modal de edición -->
                                            <button type="button" class="btn btn-sm btn-primary editUserBtn"
                                                    data-id="{{ $user->id }}"
                                                    data-name="{{ $user->name }}"
                                                    data-email="{{ $user->email }}"
                                                    data-rfc="{{ $user->orfc }}">
                                                <i class="fas fa-pencil-alt"></i> Editar
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{ $users->appends(['search' => request('search')])->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('partials.users_modal_edit_user')

    @include('scripts.users_open_modal_edit_user')
@endsection
