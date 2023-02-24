<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title')</title>

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>

<body>
@include('partials.messages')
<div id="app" class="d-flex flex-column h-screen justify-content-between">
    <header>
        @include('partials.nav')
    </header>

    <main class="py-4">
        @yield('content')
    </main>

    <footer class="bg-dark shadow-lg py-1">
        <div class="container py-2 d-flex justify-content-center">
            <span class="text-light px-1">Copyright &copy; Servicios Integrados al Estado de M&eacute;xico {{ now()->year }}.</span>
            <hr>
            <span class="text-light">Direcci&oacute;n de Inform&aacute;tica y Telecomunicaciones.</span>
        </div>
    </footer>
</div>

</body>

</html>
