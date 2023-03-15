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
<div class="wrapper d-flex align-items-stretch dashboard-bg">
    @if (!in_array(request()->path(), ['login', 'register']))
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
                <span class="text-muted px-1">
                    Copyright &copy; Servicios Integrados al Estado de M&eacute;xico {{ now()->year }}.
                </span>
                <hr>
                <span class="text-muted">
                    Direcci&oacute;n de Inform&aacute;tica y Telecomunicaciones.
                </span>
            </div>
        </footer>
    </div>
</div>

<script
    src="https://code.jquery.com/jquery-3.6.4.min.js"
    integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8="
    crossorigin="anonymous">
</script>

</body>

</html>
