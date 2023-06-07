<nav id="sidebar" class="vh-100 shadow blur-bg">
    <div class="sidebar-content">
        @foreach ($cursos as $curso)
            <div class="mb-4">
                <div class="w-100 d-flex justify-content-center">
                    <a class="btn-sidebar" href="{{ route('modulos.index', $curso->id) }}">
                        <h5 class="text-uppercase text-primary mb-2 text-center">Módulos del curso<br>"{{ $curso->nombre }}"</h5>
                    </a>
                </div>
                @foreach ($curso->modulos as $modulo)
                    @php
                        $activo = false;
                        $temas_ids = collect($modulo->temas)->pluck('id');
                        if (Request::is('temas/*') && $temas_ids->contains(Request::segment(2))) {
                            $activo = true;
                        }
                    @endphp
                    <div class="btn-group d-flex justify-content-between my-2">
                        <a class="btn btn-outline-primary btn-sidebar btn-text-left w-100
                        {{ Request::is('modulos/'.$modulo->id) ? ' active' : '' }}"
                           href="{{ route('modulos.show', $modulo->id) }}">
                            <i class="fa-regular fa-folder-closed"></i>
                            {{ Str::limit($modulo->nombre, 25, '...') }}
                        </a>
                        <button class="btn btn-outline-primary dropdown-toggle"
                                data-bs-toggle="collapse"
                                data-bs-target="#collapse-{{ $modulo->id }}"
                                aria-expanded="{{ $activo ? 'true' : 'false' }}"
                                aria-controls="collapse-{{ $modulo->id }}">
                            <span class="visually-hidden">Toggle Dropdown</span>
                        </button>
                    </div>

                    <div class="collapse{{ $activo ? ' show' : '' }}" id="collapse-{{ $modulo->id }}">
                        @if (count($modulo->temas))
                            <div class="btn-group-vertical w-100">
                                @foreach ($modulo->temas as $tema)
                                    <a class="btn btn-outline-secondary btn-sidebar btn-text-left
                                    {{ Request::is('temas/'.$tema->id) ? ' active' : '' }}"
                                       href="{{ route('temas.show', $tema->id) }}">
                                        <i class="fa-solid fa-chalkboard-user"></i>
                                        {{ Str::limit($tema->titulo, 25, '...') }}
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endforeach
    </div>
</nav>
