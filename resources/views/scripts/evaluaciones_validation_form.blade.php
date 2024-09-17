@push('scripts')
    <script>
        document.getElementById('evaluation_form').addEventListener('submit', function(e) {
            e.preventDefault(); // Evitar el envío accidental

            let selectedAnswers = document.querySelectorAll('input[type=radio]:checked');
            let totalQuestions = {{ count($preguntas) }};

            // Limpiar los estilos de las preguntas previamente resaltadas
            document.querySelectorAll('.question-container').forEach(function(question) {
                question.classList.remove('unanswered-question');
            });

            if (selectedAnswers.length !== totalQuestions) {
                // Resaltar las preguntas faltantes
                document.querySelectorAll('.question-container').forEach(function(question) {
                    let selectedOption = question.querySelectorAll('input[type=radio]:checked');
                    if (selectedOption.length === 0) {
                        question.classList.add('unanswered-question');
                    }
                });

                // Mostrar alerta personalizada
                showSweetAlert('warning', 'Por favor, completa todas las preguntas.');
                return; // Detener el flujo
            }

            // Mostrar confirmación antes de enviar
            Swal.fire({
                title: 'Confirmación',
                text: "¿Estás seguro de que deseas enviar las respuestas?",
                icon: 'warning',
                iconColor: '#FCCD00',
                showCancelButton: true,
                confirmButtonColor: '#FCCD00',
                cancelButtonColor: '#858796',
                confirmButtonText: 'Sí, enviar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Enviar el formulario si se confirma
                    e.target.submit();
                }
            });
        });

        // Evitar el envío del formulario al presionar Enter
        document.getElementById('evaluation_form').addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
            }
        });
    </script>
@endpush
