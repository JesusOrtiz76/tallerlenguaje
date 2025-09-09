@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var editUserModalEl = document.getElementById('editUserModal');
            var editUserModal = new bootstrap.Modal(editUserModalEl);
            var editUserForm = document.getElementById('editUserForm');
            var currentRfc = '';

            // Funcionalidad: al hacer clic en "Editar"
            document.querySelectorAll('.editUserBtn').forEach(function(button) {
                button.addEventListener('click', function() {
                    var id    = this.getAttribute('data-id');
                    var name  = this.getAttribute('data-name');
                    var email = this.getAttribute('data-email');
                    var rfc   = this.getAttribute('data-rfc');

                    // Establece la URL del formulario
                    var updateUserUrlTemplate = "{{ route('admin.users.update', ['user' => ':id']) }}";
                    editUserForm.action = updateUserUrlTemplate.replace(':id', id);

                    // Asigna los valores a los campos
                    document.getElementById('userName').value  = name;
                    document.getElementById('userEmail').value = email;
                    document.getElementById('userPassword').value = '';
                    document.getElementById('userPasswordConfirmation').value = '';

                    // Guarda el RFC para usarlo en la función de copiar
                    currentRfc = rfc;

                    // Abre el modal
                    editUserModal.show();
                });
            });

            // Funcionalidad: usar el RFC como contraseña
            var useRfcPasswordBtn = document.getElementById('useRfcPasswordBtn');
            useRfcPasswordBtn.addEventListener('click', function() {
                document.getElementById('userPassword').value = currentRfc;
                document.getElementById('userPasswordConfirmation').value = currentRfc;
            });

            // Si existen errores de validación dentro del modal, lo mostramos automáticamente
            if (editUserModalEl.querySelector('.invalid-feedback')) {
                editUserModal.show();
            }
        });
    </script>
@endpush
