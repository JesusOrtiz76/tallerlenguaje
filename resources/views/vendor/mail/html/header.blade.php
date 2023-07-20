@props(['url'])
<tr>
    <td class="header">
        <a href="{{ $url }}" style="display: inline-block;">
            @if (trim($slot) === 'Laravel')
                <img src="https://laravel.com/img/notification-logo.png" class="logo" alt="Laravel Logo">
            @else
                <img src="https://i.imgur.com/hZYrMha.jpg" class="logo" alt="Curso 'Protocolo de Actuación de SEIEM'">
            @endif
        </a>
    </td>
</tr>
