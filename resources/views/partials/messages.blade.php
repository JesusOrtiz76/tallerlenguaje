<script>
    document.addEventListener('DOMContentLoaded', function () {
        @if (session('warning'))
        Swal.fire({
            icon: 'warning',
            title: 'Mensaje',
            text: '{{ session('warning') }}',
            confirmButtonColor: '#FFC107',
        });
        @endif

        @if (session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Mensaje',
            text: '{{ session('error') }}',
            confirmButtonColor: '#DC3545',
        });
        @endif

        @if (session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Mensaje',
            text: '{{ session('success') }}',
            confirmButtonColor: '#28A745',
        });
        @endif
    });
</script>
