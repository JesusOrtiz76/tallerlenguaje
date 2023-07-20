<script>
    document.addEventListener('DOMContentLoaded', function () {
        @if (session('warning'))
        Swal.fire({
            icon: 'warning',
            title: 'Mensaje',
            text: '{{ session('warning') }}',
            confirmButtonColor: '#FCCD00',
            iconColor: '#FCCD00',
        });
        @endif

        @if (session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Mensaje',
            text: '{{ session('error') }}',
            confirmButtonColor: '#E1143D',
            iconColor: '#E1143D',
        });
        @endif

        @if (session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Mensaje',
            text: '{{ session('success') }}',
            confirmButtonColor: '#9DC323',
            iconColor: '#9DC323',
        });
        @endif
    });
</script>
