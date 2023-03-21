
<!-- Sidebar chill -->

<nav id="sidebar" class="vh-100 shadow blur-bg">
    <div class="sidebar-content">
        <div class="w-100 d-flex justify-content-center">
            <a class="btn-sidebar" href="/cursos/1/modulo"><h4 class="text-uppercase text-muted mb-4">M&oacute;dulos del curso</h4></a>
        </div>

        <!--<a href="#" class="img logo rounded-circle mb-5"></a>-->

        @foreach ($modulos as $modulo)
            <!-- Split dropup button -->
            <div class="btn-group dropdown w-100 mb-2">

                <a type="button" class="btn btn-link btn-sidebar btn-text-left" href="{{ route('modulos.show', $modulo->id) }}">
                    <i class="fa-regular fa-folder-closed"></i> {{ Str::limit($modulo->nombre, 25, '...') }}
                </a>
                <button type="button"
                        class="btn btn-link dropdown-toggle"
                        data-bs-toggle="dropdown"
                        aria-expanded="false">
                    <span class="visually-hidden">Toggle Dropdown</span>
                </button>
                @if (count($modulo->temas))
                    <ul class="dropdown-menu animate swal-menu w-100">
                        @foreach ($modulo->temas as $tema)
                            <li>
                                <a class="dropdown-item"
                                   href="{{ route('temas.show', $tema->id) }}">
                                    <i class="fa-solid fa-chalkboard-user"></i>
                                    {{ Str::limit($tema->titulo, 30, '...') }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        @endforeach

    </div>
</nav>
