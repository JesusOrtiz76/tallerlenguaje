
<div class="col-md-2 bg-light">
    <ul class="nav flex-column">
        @foreach ($cursos as $curso)
        <li class="nav-item">
            <a class="nav-link" href="{{ route('cursos.show', $curso->id) }}">{{ $curso->nombre }}</a>
            @if (count($curso->modulos))
            <ul class="nav flex-column ml-3">
                @foreach ($curso->modulos as $modulo)
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('modulos.show', $modulo->id) }}">{{ $modulo->nombre }}</a>
                </li>
                @endforeach
            </ul>
            @endif
        </li>
        @endforeach
    </ul>
</div>
