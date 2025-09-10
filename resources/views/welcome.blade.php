<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>SEIEM | {{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>

<body id="page-top">

<!-- Pantalla de carga -->
<div class="loader-container" id="loader-container">
    <div class="loader-content">
        <div class="spinner"></div>
    </div>
</div>

<!-- Navigation-->
<nav class="navbar navbar-expand-lg fixed-top border-bottom" id="mainNav">
    <div class="container">
        <a class="navbar-brand" href="#page-top"></a>
        <button class="btn btn-white d-inline-block d-lg-none ml-auto"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#navbarResponsive"
                aria-controls="navbarResponsive"
                aria-expanded="false"
                aria-label="Toggle navigation">
            <i class="fa fa-ellipsis-v ms-1"></i>
            <span class="sr-only">Toggle Menu</span>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
            <ul class="navbar-nav text-uppercase ms-auto py-4 py-lg-0">
                <li class="nav-item"><a class="nav-link" href="#page-top">Inicio</a></li>
                <li class="nav-item"><a class="nav-link" href="#objetivos">Objetivos</a></li>
                <li class="nav-item"><a class="nav-link" href="#modulos">Bloques</a></li>
                <li class="nav-item"><a class="nav-link" href="#ayuda">Preguntas <F></F>recuentes</a></li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('home') }}">{{ __('Go to workshop') }}</a>
                </li>
                <!-- Authentication Links -->
                @guest
                    @if (Route::has('login'))
                        <li class="nav-item">
                            <a class="nav-link  text-uppercase" href="{{ route('login') }}">{{ __('Login') }}</a>
                        </li>
                    @endif

                    @if (Route::has('register'))
                        <li class="nav-item">
                            <a class="nav-link  text-uppercase" href="{{ route('register') }}">{{ __('Register') }}</a>
                        </li>
                    @endif
                @else
                    <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle  text-uppercase"
                           href="#"
                           role="button"
                           data-bs-toggle="dropdown"
                           aria-haspopup="true"
                           aria-expanded="false"
                           v-pre>
                            {{ Auth::user()->name }}
                        </a>

                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                document.getElementById('logout-form').submit();">
                                {{ __('Logout') }}
                            </a>

                            <form class="form"
                                  id="logout-form"
                                  action="{{ route('logout') }}"
                                  method="POST"
                                  class="d-none">
                                @csrf
                            </form>
                        </div>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>

<!-- Masthead-->
<header class="masthead">
    <div class="container">
        <div class="masthead-subheading"> {{ __('Welcome to the writing workshop') }} </div>
        <div class="masthead-heading text-uppercase"> {{ $cursos[0]->onombre }} </div>
        <a class="btn btn-lg btn-outline-light text-uppercase fst-italic rounded-pill text-nowrap"
           href="{{ route('home') }}">
            {{ __('Go to workshop') }}
        </a>

        <!-- Animation Mousey-->
        <a href="#objetivos">
            <div class="scroll-downs">
                <div class="mousey">
                    <div class="scroller"></div>
                </div>
            </div>
        </a>
    </div>
</header>

<!-- Objetivos -->
<section class="page-section" id="objetivos">
    <div class="container">
        <div class="text-center">
            <h2 class="section-heading text-uppercase text-gradient">Introducción</h2>
            <h3 class="section-subheading text-muted text-justify">
                <p class="text-muted text-justify">
                El lenguaje es la herramienta con la cual una persona o una sociedad comparte su ideología, costumbres
                y valores. En este sentido, el lenguaje de las sociedades con un sistema patriarcal, como es nuestro
                país, se centra en lo masculino, dando total visibilidad al hombre e invisibilidad a las mujeres.
                </p>
                <p class="text-muted text-justify">
                    Es justo el lenguaje del patriarcado, el que determina el papel del hombre y la mujer en lo
                    personal, familiar y social: estereotipos y roles.
                </p>
                <p class="text-muted text-justify">
                    Ahora bien, el lenguaje no es estático, continuamente evoluciona con base al espacio y el tiempo en
                    el que se desarrolla. Es este dinamismo el que permite hacer cambios que hagan visible lo femenino y
                    lo masculino por igual en la sociedad.
                </p>
                <p class="text-muted text-justify">
                    En una sociedad democrática, la igualdad de género es imperante, y es por ello que los gobiernos
                    han establecido políticas públicas con perspectiva de género, y el Estado de México no es la
                    excepción y más específicamente Servicios Educativos Integrados al Estado de México, que desarrolla
                    e impulsa material para desarrollar el lenguaje igualitario.
                </p>
                <p class="text-muted text-justify">
                    Para lo anterior, se ha desarrollado este taller “Lenguaje incluyente y no sexista”, con el fin de
                    que todas las personas públicas de SEIEM sean parte de la evolución del lenguaje igualitario. Para
                    ello, vamos a comenzar con algunos conceptos esenciales que den contexto al taller, después
                    expondremos la importancia y los fundamentos del lenguaje no sexista, seguido de la definición del
                    “lenguaje sexista” y “no sexista”, para terminar con ejercicios que refuercen la teoría.
                </p>
            </h3>
        </div>
        <div class="row text-center">
            <div class="col-md-6 mb-3 px-5">
                    <span class="fa-stack fa-4x">
                        <i class="fa fa-circle fa-stack-2x text-primary"></i>
                        <i class="fa fa-flag-checkered fa-stack-1x fa-inverse"></i>
                    </span>
                <h4 class="my-3">Objetivo</h4>
                <p class="text-muted text-justify">
                    Incorporar el uso del lenguaje incluyente y no sexista en las personas servidoras públicas de los
                    Servicios Educativos Integrados al Estado de México, así como concientizar y sensibilizar sobre la
                    comunicación con perspectiva de género, con el fin de hacer un uso adecuado del lenguaje y
                    contribuir a la erradicación de la violencia.
                </p>
            </div>
            <div class="col-md-6 mb-3 px-5">
                    <span class="fa-stack fa-4x">
                        <i class="fa fa-circle fa-stack-2x text-primary"></i>
                        <i class="fa-solid fa-bullseye fa-stack-1x fa-inverse"></i>
                    </span>
                <h4 class="my-3">Objetivos Especificos</h4>
                <p class="text-muted text-justify">
                    Erradicar el uso sexista del lenguaje en la expresión oral y escrita (en las conversaciones
                    informales y en los documentos oficiales del Organismo) ya que transmite y refuerza la desigualdad
                    de género.
                </p>
                <p class="text-muted text-justify">
                    Proporcionar las técnicas y herramientas necesarias para implementar el uso del lenguaje incluyente
                    y no sexista en las prácticas escritas y orales dentro del Organismo.
                </p>
            </div>
            <div class="col-md-6 mb-3 px-5">
                    <span class="fa-stack fa-4x">
                        <i class="fa fa-circle fa-stack-2x text-primary"></i>
                        <i class="fa-solid fa-gavel fa-stack-1x fa-inverse"></i> <!-- Ícono de martillo de juez -->
                    </span>
                <h4 class="my-3">Fundamento Legal</h4>
                <p class="text-muted text-justify">
                    <b>Fracción XII del artículo 17, de la Ley General para la Igualdad entre Mujeres y Hombres</b>
                    establece: “Promover que, en las prácticas de comunicación social de las dependencias de la
                    Administración Pública Federal, así como en los medios de comunicación masiva electrónicos e
                    impresos, se eliminen el uso de estereotipos sexistas y discriminatorios e incorporen un lenguaje
                    incluyente”.
                </p>
            </div>
            <div class="col-md-6 mb-3 px-5">
                    <span class="fa-stack fa-4x">
                        <i class="fa fa-circle fa-stack-2x text-primary"></i>
                        <i class="fa fa-person-chalkboard fa-stack-1x fa-inverse"></i>
                    </span>
                <h4 class="my-3">Instrucciones</h4>
                <p class="text-muted text-justify">
                    Para realizar su registro, deberá ser servidor público activo adscrito a Servicios Educativos
                    Integrados al Estado de México (SEIEM), deberá llenar el
                    <a href="{{ route('register') }}"><i class="fa fa-address-card"></i> <strong>Formulario de
                            Registro</strong></a>, ingresando su
                    nombre, RFC, clave del centro de trabajo y correo electrónico, posteriormente, podrá crear una
                    contraseña
                    de 8 caracteres,
                    utilizando mayúsculas, minúsculas, números o símbolos. Al finalizar, recibirá un correo
                    electrónico de verificación para validar sus datos y así poder acceder al contenido del curso y
                    evaluaciones.
                </p>
                <p class="text-muted text-justify">
                    Descargue el
                    <a href="{{ asset('storage/assets/docs/Manual de Usuario Curso Protocolo.pdf') }}" download>
                        <i class="fa fa-book"></i> <strong>Manual de Usuario</strong>
                    </a> para más información.
                </p>
            </div>
            <div class="col-md-6 mb-3 px-5">
                    <span class="fa-stack fa-4x">
                        <i class="fa fa-circle fa-stack-2x text-primary"></i>
                        <i class="fa fa-file-contract fa-stack-1x fa-inverse"></i>
                    </span>
                <h4 class="my-3">Acreditaci&oacute;n</h4>
                <p class="text-muted text-justify">
                    Para poder acreditar el taller en línea “{{ $cursos[0]->onombre }}”, es necesario completar todos
                    los ejercicios del curso, así como realizar la evaluación final. Contará con 2 oportunidades para
                    acreditar la evaluacion final. Al completar todos los ejercicios y acreditar la evaluación final de
                    los {{ count($cursos[0]->modulos) }} módulos, estará en posibilidades de descargar la
                    <span class="text-primary">
                            <i class="fa fa-file-contract"></i> <strong>Constancia de Acreditación</strong></span>,
                    misma que contará con valor curricular.
                </p>
            </div>
            <div class="col-md-6 mb-3 px-5">
                    <span class="fa-stack fa-4x">
                        <i class="fa fa-circle fa-stack-2x text-primary"></i>
                        <i class="fa fa-stopwatch fa-stack-1x fa-inverse"></i>
                    </span>
                <h4 class="my-3">Duración</h4>
                <p class="text-muted text-justify">
                    El registro para el curso "{{ $cursos[0]->onombre }}" estará disponible del
                    <strong>{{ $formattedStartRegisterDate }}</strong> al
                    <strong>{{ $formattedEndRegisterDate }}</strong>. Una vez registrado, tendrá acceso al curso
                    desde
                    su registro hasta el
                    <strong>{{ $formattedEndDate }}</strong>.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Modulos -->
<section class="page-section" id="modulos">
    <div class="container">
        <div class="text-center">
            <h2 class="section-heading text-uppercase text-gradient mb-2">BLOQUES</h2>
            <p class="text-muted mb-5">
                Este curso cuenta con un total de {{ count($cursos[0]->modulos) }} bloques, que se describen a continuación.
            </p>
        </div>

        <ul class="modulo">
            @foreach($cursos[0]->modulos as $modulo)
                @php $isRight = $loop->iteration % 2 === 0; @endphp

                <li class="{{ $isRight ? 'modulo-inverted' : '' }}">
                    <div class="modulo-image">
                        <img class="rounded-circle" src="{{ asset('storage/' . $modulo->oimg_path) }}"
                             alt="Imagen del {{ $modulo->onombre }}">
                    </div>

                    <div class="modulo-panel p-3 p-lg-4 {{ $isRight ? 'text-lg-start' : 'text-lg-end' }}">
                        <h3 class="h5 fw-bold mb-2">{{ $modulo->onombre }}</h3>
                        <p class="text-muted mb-0 modulo-desc">
                            {{ $modulo->odescripcion }}
                        </p>
                    </div>
                </li>
            @endforeach

            <li class="modulo-inverted">
                <div class="modulo-image">
                    <h4 class="m-0 text-center">¡Fin<br>del<br>Taller!</h4>
                </div>
            </li>
        </ul>
    </div>
</section>

<!-- Preguntas Frecuentes -->
<section class="page-section" id="ayuda">
    <div class="container">
        <div class="text-center">
            <h2 class="section-heading text-uppercase text-gradient">Preguntas Frecuentes</h2>
            <h3 class="section-subheading text-muted">Encuentre respuestas a las preguntas más comunes sobre el curso.
            </h3>
        </div>
        <div class="accordion" id="accordionAyuda">

            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse1"
                            aria-expanded="true" aria-controls="collapse1">
                        ¿Cómo me registro en el curso?
                    </button>
                </h2>
                <div id="collapse1" class="accordion-collapse collapse show" data-bs-parent="#accordionAyuda">
                    <div class="accordion-body">
                        Llene el <a href="{{ route('register') }}"><strong>Formulario de Registro</strong></a> en las
                        fechas de apertura.
                    </div>
                </div>
            </div>

            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapse2" aria-expanded="false" aria-controls="collapse2">
                        ¿Qué sucede si no me registré al curso a tiempo o en las fechas establecidas?
                    </button>
                </h2>
                <div id="collapse2" class="accordion-collapse collapse" data-bs-parent="#accordionAyuda">
                    <div class="accordion-body">
                        Recuerde que el registro solo estará disponible del
                        <strong>{{ $formattedStartRegisterDate }}</strong> al
                        <strong>{{ $formattedEndRegisterDate }}</strong>.
                        Fuera de los periodos establecidos, lamentablemente no habrá forma de registrarse.
                        Para dudas o aclaraciones, favor de comunicarse al teléfono <strong>(722) 279 77 00, Ext.
                            0727.</strong>
                        Horario de atención: Lunes a Viernes en días hábiles de 9:00 AM a 1:00 PM.
                    </div>
                </div>
            </div>

            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapseReset" aria-expanded="false" aria-controls="collapseReset">
                        ¿Cómo puedo restablecer mi contraseña?
                    </button>
                </h2>
                <div id="collapseReset" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                        Puede restablecer su contraseña accediendo al siguiente enlace:
                        <a href="{{ route('password.request') }}"><strong>Restablecer Contraseña</strong></a>.
                        De presentar problemas con el enlace anterior consulte la siguiente sección.
                    </div>
                </div>
            </div>

            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapse3" aria-expanded="false" aria-controls="collapse3">
                        ¿Cómo puedo recuperar mi cuenta si no recuerdo el correo o la contraseña con la que me registré?
                    </button>
                </h2>
                <div id="collapse3" class="accordion-collapse collapse" data-bs-parent="#accordionAyuda">
                    <div class="accordion-body">
                        Para restablecer su correo o contraseña, favor de comunicarse al teléfono (722) 279 77 00,
                        <strong> Ext. 1150 o Ext. 1142.</strong>
                        Horario de atención: Lunes a Viernes en días hábiles de 9:00 AM a 1:00 PM.
                    </div>
                </div>
            </div>

            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapse4" aria-expanded="false" aria-controls="collapse4">
                        ¿Cómo obtengo mi constancia de acreditación?
                    </button>
                </h2>
                <div id="collapse4" class="accordion-collapse collapse" data-bs-parent="#accordionAyuda">
                    <div class="accordion-body">
                        Debe <strong>APROBAR TODAS</strong> las evaluaciones de los módulos.
                        Una vez logrado, podrá descargar su constancia desde la plataforma.
                    </div>
                </div>
            </div>

            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapse5" aria-expanded="false" aria-controls="collapse5">
                        ¿Cómo puedo cambiar mi nombre si me equivoqué al momento de registrarme?
                    </button>
                </h2>
                <div id="collapse5" class="accordion-collapse collapse" data-bs-parent="#accordionAyuda">
                    <div class="accordion-body">
                        Al finalizar el curso y al intentar descargar la constancia, aparecerá una ventana para
                        verificar su nombre.
                        Es importante asegurarse de que esté correcto, ya que será el nombre que aparecerá en la
                        constancia.
                    </div>
                </div>
            </div>

            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapse6" aria-expanded="false" aria-controls="collapse6">
                        ¿Por qué no me aparece la opción de descargar mi constancia si terminé mi curso al 100%?
                    </button>
                </h2>
                <div id="collapse6" class="accordion-collapse collapse" data-bs-parent="#accordionAyuda">
                    <div class="accordion-body">
                        Si el sistema no le muestra la opción de descargar la constancia, lamentablemente <strong>no
                            aprobó el curso.</strong>
                        Aunque haya concluido el curso, esto significa que no obtuvo una calificación aprobatoria.
                        <strong>Si tiene intentos disponibles en sus evaluaciones, podría intentar mejorar su
                            calificación.</strong>
                    </div>
                </div>
            </div>

            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapse7" aria-expanded="false" aria-controls="collapse7">
                        ¿Habrá nuevas fechas de apertura para acreditar el curso?
                    </button>
                </h2>
                <div id="collapse7" class="accordion-collapse collapse" data-bs-parent="#accordionAyuda">
                    <div class="accordion-body">
                        Por el momento, no existen otras fechas para registros nuevos ni para intentos de acreditar el
                        curso.
                        Para dudas o aclaraciones, favor de comunicarse al teléfono <strong>(722) 279 77 00, Ext.
                            0727.</strong>
                        Horario de atención: Lunes a Viernes en días hábiles de 9:00 AM a 1:00 PM.
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Footer-->
<footer class="py-lg-2 mt-5 bg-dark border-top">
    <div class="container py-1 d-flex justify-content-center">
        <span class="text-white px-1 me-2 text-end" style="font-size: 9pt">
            Servicios Educativos Integrados al Estado de M&eacute;xico.<br>
            Copyright &copy; {{ now()->year }}.
        </span>
        <span class="text-white px-1 ps-2 separador-footer" style="font-size: 9pt">
            Unidad de Asuntos Jurídicos e Igualdad de Género.<br>
            Dirección de Informática y Telecomunicaciones.
        </span>
    </div>
</footer>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"
        integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8="
        crossorigin="anonymous">
</script>
<!-- Bootstrap JS (con Popper) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

@include('partials.messages')

</body>

</html>
