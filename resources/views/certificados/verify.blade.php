<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SEIEM | Verificación de Certificado</title>
    <style>
        * {
            box-sizing: border-box;
        }
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
            overflow: hidden; /* Evitar desbordamiento horizontal */
        }
        .table-container {
            max-width: 100%;
            max-height: 100%;
            overflow: auto;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }
        th, td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
            white-space: nowrap;
        }
        th {
            background-color: #9f2241;
            color: white;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        td {
            max-width: 300px;
            overflow: hidden;
            text-overflow: ellipsis;
        }
    </style>
</head>
<body>
<div class="table-container">
    <table>
        <thead>
        <tr>
            <th>Campo</th>
            <th>Descripción</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>Nombre</td>
            <td>{{ $userScore->user->name }}</td>
        </tr>
        <tr>
            <td>RFC</td>
            <td>{{ $userScore->user->orfc }}</td>
        </tr>
        <tr>
            <td>Email</td>
            <td>{{ $userScore->user->email }}</td>
        </tr>
        <tr>
            <td>Centro de Trabajo</td>
            <td>{{ $userScore->user->centroTrabajo->oclave }}</td>
        </tr>
        <tr>
            <td>Curso</td>
            <td>{{ $userScore->curso->onombre }}</td>
        </tr>
        <tr>
            <td>Total de Respuestas</td>
            <td>{{ $userScore->total_answers }}</td>
        </tr>
        <tr>
            <td>Respuestas Correctas</td>
            <td>{{ $userScore->correct_answers }}</td>
        </tr>
        <tr>
            <td>Porcentaje de Puntuación</td>
            <td>{{ number_format($userScore->score_percentage, 2) }}%</td>
        </tr>
        </tbody>
    </table>
</div>
</body>
</html>
