<script>
    document.addEventListener('DOMContentLoaded', function () {
        @if (session('warning'))
        Swal.fire({
            icon: 'warning',
            title: 'Mensaje',
            html: '{{ session('warning') }}',
            confirmButtonColor: '#FCCD00',
            iconColor: '#FCCD00',
        });
        @endif

        @if (session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Mensaje',
            html: '{{ session('error') }}',
            confirmButtonColor: '#E1143D',
            iconColor: '#E1143D',
        });
        @endif

        @if (session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Mensaje',
            html: '{{ session('success') }}',
            confirmButtonColor: '#9DC323',
            iconColor: '#9DC323',
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
