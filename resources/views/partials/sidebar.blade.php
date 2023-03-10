
<!-- Sidebar chill -->

<nav id="sidebar" class="vh-100 bg-white shadow">
    <div class="sidebar-content">
        <h3 class="text-uppercase text-muted mb-4">M&oacute;dulos del curso</h3>
        <!--<a href="#" class="img logo rounded-circle mb-5"></a>-->
        <ul class="list components mb-5">
            @foreach ($modulos as $modulo)
                <li ><!--class="active"-->
                    <a href="#collapase-{{ $modulo->id }}"
                       data-bs-toggle="collapse"
                       aria-expanded="false"
                       class="dropdown-toggle">
                        <b>{{ Str::limit($modulo->nombre, 20, '...') }}</b>
                    </a>
                    @if (count($modulo->temas))
                        <ul class="collapse list" id="collapase-{{ $modulo->id }}">
                            @foreach ($modulo->temas as $tema)
                                <li>
                                    <a href="{{ route('temas.show', $tema->id) }}">
                                        {{ Str::limit($tema->titulo, 20, '...') }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </li>
            @endforeach
        </ul>
    </div>
</nav>
