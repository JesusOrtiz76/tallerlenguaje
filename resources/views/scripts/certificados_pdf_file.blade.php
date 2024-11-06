@push('scripts')
    <script>
        async function fetchAndDownloadPDF(url) {
            // Mostrar el loader antes de iniciar la solicitud
            document.getElementById('loader-container').style.display = 'block';

            try {
                const response = await fetch(url);
                const result = await response.json();

                if (result.status === 'success') {
                    // Convertir el base64 en un Blob
                    const byteCharacters = atob(result.data);
                    const byteNumbers = new Array(byteCharacters.length);
                    for (let i = 0; i < byteCharacters.length; i++) {
                        byteNumbers[i] = byteCharacters.charCodeAt(i);
                    }
                    const byteArray = new Uint8Array(byteNumbers);
                    const blob = new Blob([byteArray], { type: 'application/pdf' });

                    // Crear una data URL a partir del Blob
                    const dataUrl = URL.createObjectURL(blob);

                    // Crear un enlace temporal para iniciar la descarga
                    const a = document.createElement('a');
                    a.href = dataUrl;
                    a.download = '{{ Auth::user()->orfc }}.pdf'; // Nombre del archivo

                    document.body.appendChild(a);
                    a.click();
                    document.body.removeChild(a);

                    // Revocar el objeto URL para liberar memoria
                    URL.revokeObjectURL(dataUrl);
                } else {
                    showSweetAlert(result.status, result.message || 'No se puede descargar el certificado');
                }
            } catch (error) {
                console.error('Error:', error);
                showSweetAlert('error', 'Ocurrió un error al descargar el certificado');
            } finally {
                // Ocultar el loader una vez que la solicitud haya terminado
                document.getElementById('loader-container').style.display = 'none';
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Obtener todos los botones con la clase 'download-constancia'
            const buttons = document.querySelectorAll('.download-constancia');

            buttons.forEach(function(button) {
                button.addEventListener('click', function(event) {
                    event.preventDefault(); // Prevenir la acción por defecto del enlace

                    const url = this.getAttribute('href'); // Obtener la ruta del botón

                    fetchAndDownloadPDF(url);
                });
            });
        });
    </script>
@endpush
