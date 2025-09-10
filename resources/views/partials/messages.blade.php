<script>
    document.addEventListener('DOMContentLoaded', function () {
        @if (session('warning'))
        Swal.fire({
            icon: 'warning',
            title: 'Mensaje',
            html: '{{ session('warning') }}',
            confirmButtonColor: '#FFC107'
        }).then(() => {
            {{ session()->forget('warning') }} // Eliminar el mensaje de la sesión
        });
        @endif

        @if (session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Mensaje',
            html: '{{ session('error') }}',
            confirmButtonColor: '#DC3545'
        }).then(() => {
            {{ session()->forget('error') }} // Eliminar el mensaje de la sesión
        });
        @endif

        @if (session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Mensaje',
            html: '{{ session('success') }}',
            confirmButtonColor: '#28A745'
        }).then(() => {
            {{ session()->forget('success') }} // Eliminar el mensaje de la sesión
        });
        @endif
    });

    // SweetAlert para mensajes de alerta AJAX
    function showSweetAlert(type, message) {
        Swal.fire({
            icon: type,
            title: 'Mensaje',
            text: message,
            confirmButtonColor: type === 'success' ? '#28A745' : (type === 'error' ? '#DC3545' : '#FFC107'),
        });
    }
</script>
