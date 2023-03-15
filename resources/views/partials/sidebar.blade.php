
<!-- Sidebar chill -->

<nav id="sidebar" class="vh-100 shadow blur-bg">
    <div class="sidebar-content">
        <h4 class="text-uppercase text-muted mb-4">M&oacute;dulos del curso</h4>
        <!--<a href="#" class="img logo rounded-circle mb-5"></a>-->
        <ul class="list components">
            @foreach ($modulos as $modulo)
                <li ><!--class="active"-->
                    <a class="rounded border-0 collapsed"
                       href="#"
                       data-bs-toggle="collapse"
                       data-bs-target="#collapse-{{ $modulo->id }}"
                       aria-expanded="false">
                        <i class="fa-regular fa-folder-closed"></i> {{ Str::limit($modulo->nombre, 20, '...') }}
                    </a>

                    @if (count($modulo->temas))
                        <div class="collapse" id="collapse-{{ $modulo->id }}">
                            <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small" style="padding-left: 1rem;">
                                @foreach ($modulo->temas as $tema)
                                    <li>
                                        <a class="btn-toggle-nav list-unstyled fw-normal pb-1 small"
                                           href="{{ route('temas.show', $tema->id) }}">
                                            <i class="fa-solid fa-chalkboard-user"></i> {{ Str::limit($tema->titulo, 20, '...') }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </li>
            @endforeach
        </ul>
    </div>
</nav>
