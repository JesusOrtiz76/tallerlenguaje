<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Laravel') }} | @yield('title')</title>

    <!-- Incluir la fuente Tilt Neon desde Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Tilt+Neon&display=swap" rel="stylesheet">

    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #010213; /* Fondo oscuro */
            background: radial-gradient(circle, rgb(0, 3, 58) 0%, rgb(0, 6, 24) 100%);
            color: #f8f9fa;
        }

        .error-container {
            text-align: center;
            margin: 3rem;
        }

        .error-code {
            font-size: 120px;
            font-weight: bold;
            margin: 0;
            position: relative;
            letter-spacing: -20px; /* Reduce el espacio entre los números */
            font-family: 'Tilt Neon', sans-serif; /* Aplica la fuente Tilt Neon a los números */
        }

        /* Todos los números en púrpura neón */
        .error-code .first-number,
        .error-code .second-number {
            color: #e9b4fc; /* Púrpura neón */
            animation: glowing-purple 3s infinite alternate, flicker 1.5s infinite alternate;
        }

        /* Último número más abajo */
        .error-code .last-number {
            color: #e9b4fc; /* Púrpura neón */
            animation: glowing-purple 3s infinite alternate, swing 2s infinite ease-in-out, flicker 2s infinite alternate;
            display: inline-block;
            transform-origin: top center;
            transform: translateY(30px); /* Desplaza el número 3 hacia abajo */
        }

        /* Efecto de resplandor púrpura */
        @keyframes glowing-purple {
            0% {
                text-shadow: 0 0 10px #c624ff, 0 0 20px #b666d2, 0 0 30px #bd00ff;
            }
            100% {
                text-shadow: 0 0 50px #b130ff, 0 0 100px #d48fff, 0 0 150px #9d00ff;
            }
        }

        /* Animación de balanceo para el último número (el 3) */
        @keyframes swing {
            0% { transform: translateY(30px) rotate(0deg); }
            25% { transform: translateY(30px) rotate(15deg); }
            50% { transform: translateY(30px) rotate(-15deg); }
            75% { transform: translateY(30px) rotate(10deg); }
            100% { transform: translateY(30px) rotate(0deg); }
        }

        /* Parpadeo abrupto para todos */
        @keyframes flicker {
            0%, 100% { opacity: 1; }
            40%, 60% { opacity: 0.1; }
            45%, 55% { opacity: 0.5; }
        }

        /* Efecto de parpadeo en el URL */
        a {
            font-size: 18px;
            color: #79ffff; /* Azul neón */
            text-decoration: none;
            font-weight: bold;
            position: relative;
            display: inline-block;
            animation: flicker-url 2s infinite alternate, glowing-blue 3s infinite alternate;
            font-family: 'Tilt Neon', sans-serif; /* Aplica la fuente Tilt Neon al URL */
        }

        /* Efecto de resplandor azul para el enlace */
        @keyframes glowing-blue {
            0% {
                text-shadow: 0 0 10px #00a9ff, 0 0 20px #66ccff, 0 0 30px #0bd3ff;
            }
            100% {
                text-shadow: 0 0 50px #3dd0ff, 0 0 100px #99e6ff, 0 0 150px #00c0ff;
            }
        }

        /* Parpadeo del URL */
        @keyframes flicker-url {
            0%, 100% { opacity: 1; }
            25%, 75% { opacity: 0.2; }
        }

        a:hover {
            text-decoration: underline;
        }

    </style>

</head>

<body>

@yield('content')

</body>

</html>
