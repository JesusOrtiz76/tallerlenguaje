<nav id="sidebar" class="vh-100 shadow d-flex flex-column justify-content-between blur-bg">
    <div class="col">
        <div class="w-100 d-flex justify-content-end p-3">
            <button type="button" id="closeSidebar" class="btn btn-white">
                <i class="fa fa-x me-1"></i>
                <span class="sr-only">Close Sidebar</span>
            </button>
        </div>
        <!-- Contenido superior -->
        <div class="sidebar-content">
            <!-- Mostrar menús adicionales si el usuario es administrador -->
            @if(Auth::check() && Auth::user()->orol === 'admin')
                <div class="mb-4">
                    <div class="w-100 d-flex justify-content-center">
                        <h5 class="text-uppercase text-primary mb-2 text-center">Administración</h5>
                    </div>

                    <!-- Botón de Dashboard -->
                    <div class="btn-group d-flex justify-content-between my-2">
                        <a class="btn btn-sidebar btn-text-left w-100
                        {{ Request::is('dashboard') ? 'btn-primary' : 'btn-outline-primary' }}"
                           href="{{ route('admin.dashboard') }}">
                            <i class="fa-solid fa-tachometer-alt"></i> <!-- Icono de Dashboard -->
                            Dashboard
                        </a>
                    </div>

                    <!-- Botón de Gestionar Usuarios -->
                    <div class="btn-group d-flex justify-content-between my-2">
                        <a class="btn btn-sidebar btn-text-left w-100
                        {{ Request::is('users') ? 'btn-primary' : 'btn-outline-primary' }}"
                           href="{{ route('admin.users') }}">
                            <i class="fa-solid fa-users"></i> <!-- Icono de Gestionar Usuarios -->
                            Gestionar Usuarios
                        </a>
                    </div>
                </div>
            @endif

            @foreach ($cursos as $curso)
                <div class="mb-4">
                    <div class="w-100 d-flex justify-content-center">
                        <a class="btn-sidebar" href="{{ route('modulos.index', $curso->id) }}">
                            <h5 class="text-uppercase text-primary mb-2 text-center">
                                Módulos del curso
                            </h5>
                        </a>
                    </div>
                    @foreach ($curso->modulos as $modulo)
                        @php
                            // Verificar si el tema, evaluación o módulo está activo
                            $activoModulo = Request::is('modulos/' . $modulo->id);
                            $activo = false;
                            $temas_ids = collect($modulo->temas)->pluck('id');
                            $evaluaciones_ids = collect($modulo->evaluaciones)->pluck('id');
                            if (
                                (Request::is('temas/*') && $temas_ids->contains(Request::segment(2))) ||
                                (Request::is('evaluaciones/*') && $evaluaciones_ids->contains(Request::segment(2))) ||
                                (Request::is('evaluaciones/*/resultado') && $evaluaciones_ids->contains(Request::segment(2)))
                            ) {
                                $activo = true;
                            }
                        @endphp

                        <div class="btn-group d-flex justify-content-between my-2">
                            <a class="btn btn-sidebar btn-text-left w-100
                               {{ $activoModulo || $activo ? 'btn-primary' : 'btn-outline-primary' }}"
                               href="{{ route('modulos.show', $modulo->id) }}">
                                <i class="fa-regular fa-folder-closed"></i>
                                {{ Str::limit($modulo->onombre, 23, '...') }}
                            </a>
                            <button class="btn {{ $activoModulo || $activo ? 'btn-primary' : 'btn-outline-primary' }} dropdown-toggle"
                                    data-bs-toggle="collapse"
                                    data-bs-target="#collapse-{{ $modulo->id }}"
                                    aria-expanded="{{ $activoModulo || $activo ? 'true' : 'false' }}"
                                    aria-controls="collapse-{{ $modulo->id }}">
                                <span class="visually-hidden">Toggle Dropdown</span>
                            </button>
                        </div>

                        <div class="collapse{{ $activoModulo || $activo ? ' show' : '' }}" id="collapse-{{ $modulo->id }}">
                            @if (count($modulo->temas))
                                <div class="btn-group-vertical w-100">
                                    @foreach ($modulo->temas as $tema)
                                        <a class="btn btn-sidebar btn-text-left
                                           {{ Request::is('temas/'.$tema->id) ? 'btn-golden' : 'btn-outline-golden' }}"
                                           href="{{ route('temas.show', $tema->id) }}">
                                            <i class="fa-solid fa-chalkboard-user"></i>
                                            {{ Str::limit($tema->otitulo, 24, '...') }}
                                        </a>
                                    @endforeach
                                </div>
                            @endif

                            @if (count($modulo->evaluaciones))
                                <div class="btn-group-vertical w-100 mt-2">
                                    @foreach ($modulo->evaluaciones as $evaluacion)
                                        @php
                                            $completado = false;
                                            $link = route('evaluaciones.show', $evaluacion->id); // Enlace por defecto a la evaluación
                                            if (Auth::check()) {
                                                // Obtenemos el pivote de la evaluación del usuario
                                                $pivot = Auth::user()->evaluaciones()->where('evaluacion_id', $evaluacion->id)->first();
                                                $completado = $pivot && $pivot->pivot->ointentos >= 1;

                                                // Si la evaluación está completada, generar el enlace de resultados con el ID correcto
                                                if ($completado) {
                                                    $link = route('evaluaciones.resultado', [$evaluacion->id]);
                                                }
                                            }
                                        @endphp
                                        <a class="btn btn-sidebar btn-text-left
                                           {{ Request::is('evaluaciones/'.$evaluacion->id) || Request::is('evaluaciones/'.$evaluacion->id.'/resultado') ? 'btn-golden' : 'btn-outline-golden' }}"
                                           href="{{ $link }}">
                                            <i class="fa-solid {{ $completado ? 'fa-check-circle' : 'fa-hourglass-half' }}"></i>
                                            {{ $completado ? 'Ver Resultados: ' . Str::limit($evaluacion->onombre, 24, '...') : 'Pendiente: ' . Str::limit($evaluacion->onombre, 24, '...') }}
                                        </a>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>
    </div>

    <!-- Contenido inferior -->
    <div class="sidebar-footer d-flex flex-column align-items-end">
        <a href="{{ asset('storage/assets/docs/Manual de Usuario Curso Protocolo.pdf') }}" download>
            Manual de Usuario
            <i class="fa-solid fa-circle-info"></i>
        </a>
    </div>
</nav>
