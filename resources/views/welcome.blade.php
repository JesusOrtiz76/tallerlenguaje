<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>

<body id="page-top">
    <!-- Navigation-->
    <nav class="navbar navbar-expand-lg fixed-top" id="mainNav">
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
                    <li class="nav-item"><a class="nav-link" href="#services">Objetivos</a></li>
                    <li class="nav-item"><a class="nav-link" href="#about">M&oacute;dulos</a></li>
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
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}">{{ __('Go to course') }}</a>
                    </li>
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

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
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
            <div class="masthead-subheading"> {{ __('Welcom to the course') }} </div>
            <div class="masthead-heading text-uppercase"> Prevenci&oacute;n de la violencia </div>
            <a class="btn btn-primary btn-lg text-uppercase" href="#services"> {{ __('Info') }} </a>
            <br><br><br>
        </div>
    </header>
    <!-- Services-->
    <section class="page-section" id="services">
        <div class="container">
            <div class="text-center">
                <h2 class="section-heading text-uppercase">Objetivos del curso</h2>
                <h3 class="section-subheading text-muted">Lorem ipsum dolor sit amet consectetur.</h3>
            </div>
            <div class="row text-center">
                <div class="col-md-6 mb-3">
                    <span class="fa-stack fa-4x">
                        <i class="fa fa-circle fa-stack-2x text-primary"></i>
                        <i class="fa fa-flag-checkered fa-stack-1x fa-inverse"></i>
                    </span>
                    <h4 class="my-3">Objetivo</h4>
                    <p class="text-muted">
                        Lorem ipsum dolor sit amet, consectetur adipisicing elit.
                        Minima ómaxime quam architecto quo inventore harum ex magni, dicta impedit.
                    </p>
                </div>
                <div class="col-md-6 mb-3">
                    <span class="fa-stack fa-4x">
                        <i class="fa fa-circle fa-stack-2x text-primary"></i>
                        <i class="fa fa-person-chalkboard fa-stack-1x fa-inverse"></i>
                    </span>
                    <h4 class="my-3">Instrucciones</h4>
                    <p class="text-muted">Lorem ipsum dolor sit amet, consectetur adipisicing elit.
                        Minima maxime quam architecto quo inventore harum ex magni, dicta impedit.
                    </p>
                </div>
                <div class="col-md-6 mb-3">
                    <span class="fa-stack fa-4x">
                        <i class="fa fa-circle fa-stack-2x text-primary"></i>
                        <i class="fa fa-file-contract fa-stack-1x fa-inverse"></i>
                    </span>
                    <h4 class="my-3">Acreditaci&oacute;n</h4>
                    <p class="text-muted">Lorem ipsum dolor sit amet, consectetur adipisicing elit.
                        Minima maxime quam architecto quo inventore harum ex magni, dicta impedit.
                    </p>
                </div>
                <div class="col-md-6 mb-3">
                    <span class="fa-stack fa-4x">
                        <i class="fa fa-circle fa-stack-2x text-primary"></i>
                        <i class="fa fa-stopwatch fa-stack-1x fa-inverse"></i>
                    </span>
                    <h4 class="my-3">Duraci&oacute;n</h4>
                    <p class="text-muted">Lorem ipsum dolor sit amet, consectetur adipisicing elit.
                        Minima maxime quam architecto quo inventore harum ex magni, dicta impedit.
                    </p>
                </div>
            </div>
        </div>
    </section>
    <!-- About-->
    <section class="page-section" id="about">
        <div class="container">
            <div class="text-center">
                <h2 class="section-heading text-uppercase">M&oacute;dulos</h2>
                <h3 class="section-subheading text-muted">Lorem ipsum dolor sit amet consectetur.</h3>
            </div>
            <ul class="modulo">
                <li>
                    <div class="modulo-image">
                        <img class="rounded-circle img-fluid" src="assets/img/about/1.jpg" alt="..." />
                    </div>
                    <div class="modulo-panel">
                        <div class="modulo-heading">
                            <h4>M&Oacute;DULO I</h4>
                        </div>
                        <div class="modulo-body">
                            <p class="text-muted">Lorem ipsum dolor sit amet, consectetur adipisicing elit.
                                Sunt ut voluptatum eius sapiente, totam reiciendis temporibus qui quibusdam,
                                recusandae sit vero unde, sed, incidunt et ea quo dolore laudantium consectetur!
                            </p>
                        </div>
                    </div>
                </li>
                <li class="modulo-inverted">
                    <div class="modulo-image">
                        <img class="rounded-circle img-fluid" src="assets/img/about/2.jpg" alt="..." />
                    </div>
                    <div class="modulo-panel">
                        <div class="modulo-heading">
                            <h4>M&Oacute;DULO II</h4>
                        </div>
                        <div class="modulo-body">
                            <p class="text-muted">Lorem ipsum dolor sit amet, consectetur adipisicing elit.
                                Sunt ut voluptatum eius sapiente, totam reiciendis temporibus qui quibusdam,
                                recusandae sit vero unde, sed, incidunt et ea quo dolore laudantium consectetur!
                            </p>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="modulo-image">
                        <img class="rounded-circle img-fluid" src="assets/img/about/3.jpg" alt="..." />
                    </div>
                    <div class="modulo-panel">
                        <div class="modulo-heading">
                            <h4>M&Oacute;DULO III</h4>
                        </div>
                        <div class="modulo-body">
                            <p class="text-muted">Lorem ipsum dolor sit amet, consectetur adipisicing elit.
                                Sunt ut voluptatum eius sapiente, totam reiciendis temporibus qui quibusdam,
                                recusandae sit vero unde, sed, incidunt et ea quo dolore laudantium consectetur!
                            </p>
                        </div>
                    </div>
                </li>
                <li class="modulo-inverted">
                    <div class="modulo-image">
                        <img class="rounded-circle img-fluid" src="assets/img/about/4.jpg" alt="..." />
                    </div>
                    <div class="modulo-panel">
                        <div class="modulo-heading">
                            <h4>M&Oacute;DULO IV</h4>
                        </div>
                        <div class="modulo-body">
                            <p class="text-muted">Lorem ipsum dolor sit amet, consectetur adipisicing elit.
                                Sunt ut voluptatum eius sapiente, totam reiciendis temporibus qui quibusdam,
                                recusandae sit vero unde, sed, incidunt et ea quo dolore laudantium consectetur!
                            </p>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="modulo-image">
                        <img class="rounded-circle img-fluid" src="assets/img/about/3.jpg" alt="..." />
                    </div>
                    <div class="modulo-panel">
                        <div class="modulo-heading">
                            <h4>M&Oacute;DULO V</h4>
                        </div>
                        <div class="modulo-body">
                            <p class="text-muted">Lorem ipsum dolor sit amet, consectetur adipisicing elit.
                                Sunt ut voluptatum eius sapiente, totam reiciendis temporibus qui quibusdam,
                                recusandae sit vero unde, sed, incidunt et ea quo dolore laudantium consectetur!
                            </p>
                        </div>
                    </div>
                </li>
                <li class="modulo-inverted">
                    <div class="modulo-image">
                        <img class="rounded-circle img-fluid" src="assets/img/about/4.jpg" alt="..." />
                    </div>
                    <div class="modulo-panel">
                        <div class="modulo-heading">
                            <h4>M&Oacute;DULO VI</h4>
                        </div>
                        <div class="modulo-body">
                            <p class="text-muted">Lorem ipsum dolor sit amet, consectetur adipisicing elit.
                                Sunt ut voluptatum eius sapiente, totam reiciendis temporibus qui quibusdam,
                                recusandae sit vero unde, sed, incidunt et ea quo dolore laudantium consectetur!
                            </p>
                        </div>
                    </div>
                </li>
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
    <br><br>
    <!-- Footer-->
    <footer class="footer bg-dark">
            <div class="container py-4 d-flex justify-content-center">
                <span class="text-light px-1">
                    Copyright &copy; Servicios Integrados al Estado de M&eacute;xico {{ now()->year }}.
                </span>
                <hr>
                <span class="text-light">
                    Direcci&oacute;n de Inform&aacute;tica y Telecomunicaciones.
                </span>
            </div>
        </footer>
</body>

</html>
