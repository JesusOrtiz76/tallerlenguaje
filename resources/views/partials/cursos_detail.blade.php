<!-- Detalles del curso -->
<p class="text-uppercase">{{ __('Welcome to the course') }}:</p>
<h1 class="text-gradient mb-4">{{ $curso->onombre }}</h1>
<p class="text-muted text-justify">{{ $curso->odescripcion }}</p>

@if(Auth::check())
    <div class="row align-items-center">
        <div class="col-12 col-md-auto mb-3 mb-md-0">
            @if(Auth::user()->cursos->contains($curso))
                @if(isset($scores[$curso->id]) && $scores[$curso->id] > 0)
                    @if($scores[$curso->id] >= 80)
                        @if(!Auth::user()->ochange_name)
                            <button class="btn btn-primary w-100 w-md-auto"
                                    data-bs-toggle="modal"
                                    data-bs-target="#nameConfirmationModal">
                                Descarga tu constancia
                            </button>
                        @else
                            <a href="{{ route('certificados.show', ['curso' => $curso->id]) }}"
                               class="btn btn-primary w-100 w-md-auto download-constancia">
                                Descarga tu constancia
                            </a>
                        @endif
                    @else
                        <button class="btn btn-outline-secondary w-100 w-md-auto" disabled>
                            Gracias por participar
                        </button>
                    @endif
                @else
                    <a href="{{ route('modulos.index', $curso->id) }}" class="btn btn-primary w-100 w-md-auto">
                        Continuar
                    </a>
                @endif
            @else
                <form class="form" method="POST" action="{{ route('cursos.inscribirse', $curso->id) }}">
                    @csrf
                    <button type="submit" class="btn btn-primary w-100 w-md-auto">Inscribirse</button>
                </form>
            @endif
        </div>

        <!-- Anillo de progreso -->
        <div class="col-12 col-md-auto">
            <div class="progress-ring d-flex align-items-center">
                <small>
                    <svg width="38" height="38" viewBox="0 0 38 38">
                        <circle class="progress-ring__circle-bg" r="16" cx="19" cy="19"/>
                        <circle class="progress-ring__circle" r="{{ $radio }}" cx="19" cy="19"
                                stroke-dasharray="{{ $circunferencia }}"
                                stroke-dashoffset="{{ $llenados[$curso->id] }}"/>
                        <text x="19" y="22" class="percentage-label" text-anchor="middle">
                            {{ $porcentajes[$curso->id] }} %
                        </text>
                    </svg>
                </small>
                <small class="text-nowrap text-primary ms-2">
                    Completado
                </small>
            </div>
        </div>
    </div>
@endif
