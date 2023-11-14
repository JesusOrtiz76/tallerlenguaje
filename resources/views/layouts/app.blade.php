<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} | @yield('title')</title>

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>

<body>

<!-- Pantalla de carga -->
<div class="loader-container" id="loader-container">
    <div class="loader-content">
        <div class="spinner"></div>
    </div>
</div>

<div class="wrapper d-flex align-items-stretch dashboard-bg">
    @if (!in_array(request()->path(), ['login', 'register', 'password/reset', 'password/email', 'password/reset/*', 'email/verify']))
        @include('partials.sidebar')
    @endif
    <!-- Page Content  -->
    <div id="content" class="d-flex flex-column h-screen justify-content-between">
        @include('partials.nav')

        <main class="d-flex flex-column min-vh-0">
            @yield('content')
        </main>

        <footer class="py-lg-2 mt-5 blur-bg border-top">
            <div class="container py-1 d-flex justify-content-center">
                <span class="text-muted px-1 me-2 text-end" style="font-size: 9pt">
                    Servicios Educativos Integrados al Estado de M&eacute;xico.<br>
                    Copyright &copy; {{ now()->year }}.
                </span>
                <span class="text-muted px-1 ps-2 separador-footer" style="font-size: 9pt">
                    Unidad de Asuntos Jurídicos e Igualdad de Género.<br>
                    Dirección de Informática y Telecomunicaciones.
                </span>
            </div>
        </footer>
    </div>
</div>

@include('partials.messages')

<script
    src="https://code.jquery.com/jquery-3.6.4.min.js"
    integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8="
    crossorigin="anonymous">
</script>

</body>

</html>
