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
                <li class="nav-item"><a class="nav-link" href="#modulos">M&oacute;dulos</a></li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('home') }}">{{ __('Go to course') }}</a>
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
        <div class="masthead-subheading"> {{ __('Welcome to the course') }} </div>
        <div class="masthead-heading text-uppercase"> {{ $cursos[0]->nombre }} </div>
        <a class="btn btn-lg btn-outline-light text-uppercase fst-italic rounded-pill text-nowrap"
           href="{{ route('home') }}">
            {{ __('Go to course') }}
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
            <h3 class="section-subheading text-muted text-justify">El curso tiene como propósito primordial, hacer énfasis en
                la correcta aplicación del “Protocolo para la prevención, detección y actuación en casos de Abuso Sexual
                e                Infantil, Acoso Escolar y Maltrato, en las escuelas de Educación Básica de SEIEM”, con la finalidad de
                que todo el personal adscrito a Servicios Educativos Integrados al Estado de México
                (SEIEM), es decir, Autoridades Escolares (Supervisores Generales, Supervisores y
                Directores), Docentes frente a grupo, Personal Administrativo y de Apoyo a la
                Educación, tengan mayores herramientas para la aplicabilidad de los procedimientos
                en los casos que se puedan presentar en los diferentes niveles educativos.</h3>
        </div>
        <div class="row text-center">
            <div class="col-md-6 mb-3 px-5">
                    <span class="fa-stack fa-4x">
                        <i class="fa fa-circle fa-stack-2x text-primary"></i>
                        <i class="fa fa-flag-checkered fa-stack-1x fa-inverse"></i>
                    </span>
                <h4 class="my-3">Objetivo</h4>
                <p class="text-muted text-justify">
                    Comprender los conceptos básicos necesarios que, de manera significativa, permitan a la comunidad educativa, conocer las responsabilidades
                    y acciones inmediatas a realizar para la prevención, detección y actuación en los casos de abuso sexual infantil, acoso escolar y maltrato
                    de los alumnos dentro de los planteles de educación básica, públicos y privados, pertenecientes al Subsistema Educativo Federalizado a cargo
                    de Servicios Educativos Integrados al Estado de México (SEIEM), con la finalidad de salvaguardar sus derechos fundamentales, así como su integridad
                    física, psicológica y sexual.
                </p>
            </div>
            <div class="col-md-6 mb-3 px-5">
                    <span class="fa-stack fa-4x">
                        <i class="fa fa-circle fa-stack-2x text-primary"></i>
                        <i class="fa fa-person-chalkboard fa-stack-1x fa-inverse"></i>
                    </span>
                <h4 class="my-3">Instrucciones</h4>
                <p class="text-muted text-justify">Para realizar su registro, deberá ser servidor público activo adscrito a
                    Servicios Educativos Integrados al Estado de México (SEIEM), deberá llenar el
                    <a href="{{ route('register') }}"><strong>Formulario de Registro</strong></a>
                    , ingresando su nombre, RFC y correo electrónico, posteriormente, podrá crear una contraseña de 8 caracteres, utilizando mayúsculas,
                    minúsculas, números o símbolos. Al finalizar, recibirá un correo electrónico de verificación para validar sus datos y así poder acceder
                    al contenido del curso y evaluaciones.
                </p>
            </div>
            <div class="col-md-6 mb-3 px-5">
                    <span class="fa-stack fa-4x">
                        <i class="fa fa-circle fa-stack-2x text-primary"></i>
                        <i class="fa fa-file-contract fa-stack-1x fa-inverse"></i>
                    </span>
                <h4 class="my-3">Acreditaci&oacute;n</h4>
                <p class="text-muted text-justify">Para poder acreditar el curso en línea
                    “Protocolo de Actuación para la Prevención, Detección y
                    Actuación en caso de Abuso Sexual Infantil, Acoso Escolar y Maltrato en las Escuelas de Educación Básica de SEIEM”,
                    al final de cada uno de los Módulos deberá realizar la
                    evaluación que se presenta, contando con 3 oportunidades para acreditar cada uno de los módulos,
                    las preguntas contienen respuestas de opción múltiple. Al acreditar las evaluaciones de los
                    {{ count($cursos[0]->modulos) }} módulos, estará en posibilidades de descargar la CONSTANCIA DE
                    ACREDITACIÓN, misma que contará con valor curricular.
                </p>
            </div>
            <div class="col-md-6 mb-3 px-5">
                    <span class="fa-stack fa-4x">
                        <i class="fa fa-circle fa-stack-2x text-primary"></i>
                        <i class="fa fa-stopwatch fa-stack-1x fa-inverse"></i>
                    </span>
                <h4 class="my-3">Duración</h4>
                <p class="text-muted text-justify">La duración del curso en línea del "Protocolo de Actuación para la Prevención, Detección y
                    Actuación en caso de Abuso Sexual Infantil, Acoso Escolar y Maltrato en las Escuelas de Educación Básica de SEIEM", no será
                    mayor a seis meses, contados a partir de la fecha de su registro. La fecha para registro al curso será del
                    será
                    <strong>{{ $formattedStartDate }}</strong>, finalizando el día
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
            <h2 class="section-heading text-uppercase text-gradient">M&oacute;dulos</h2>
            <h3 class="section-subheading text-muted">
                Este curso cuenta con un total de {{ count($cursos[0]->modulos) }} módulos, que se describen a
                continuación.
            </h3>
        </div>
        <ul class="modulo">
            @foreach($cursos[0]->modulos as $modulo)
                <li class="{{ $loop->iteration % 2 == 0 ? 'modulo-inverted' : '' }}">
                    <div class="modulo-image">
                        <img class="rounded-circle"
                             src="{{ asset('storage/' . $modulo->img_path) }}"
                             alt="Imagen del {{ $modulo->nombre }}">
                    </div>
                    <div class="modulo-panel">
                        <div class="modulo-heading">
                            <h4>{{ $modulo->nombre }}</h4>
                        </div>
                        <div class="modulo-body">
                            <p class="text-muted text-justify">{{ $modulo->descripcion }}</p>
                        </div>
                    </div>
                </li>
            @endforeach
            <li class="modulo-inverted">
                <div class="modulo-image">
                    <h4>
                        Fin
                        <br />
                        del
                        <br />
                        Curso!
                    </h4>
                </div>
            </li>
        </ul>
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

<script
    src="https://code.jquery.com/jquery-3.6.4.min.js"
    integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8="
    crossorigin="anonymous">
</script>
</body>

</html>
