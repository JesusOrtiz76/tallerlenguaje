<!-- Modal de Edición de Usuario -->
<div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editUserForm" method="POST" action="">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="editUserModalLabel">Editar Usuario</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <!-- Campo Nombre -->
                    <div class="mb-3">
                        <label for="userName" class="form-label">Nombre</label>
                        <input type="text"
                               class="form-control @error('name') is-invalid @enderror"
                               id="userName"
                               name="name"
                               value="{{ old('name') }}"
                               required>
                        @error('name')
                        <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <!-- Campo Email -->
                    <div class="mb-3">
                        <label for="userEmail" class="form-label">Email</label>
                        <input type="email"
                               class="form-control @error('email') is-invalid @enderror"
                               id="userEmail"
                               name="email"
                               value="{{ old('email') }}"
                               required>
                        @error('email')
                        <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <!-- Campo Nueva Contraseña con botón para usar RFC -->
                    <div class="mb-3">
                        <label for="userPassword" class="form-label">Nueva Contraseña</label>
                        <div class="input-group">
                            <input type="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   id="userPassword"
                                   name="password">
                            <button type="button" class="btn btn-outline-secondary" id="useRfcPasswordBtn" title="Usar RFC como contraseña">
                                <i class="fa-solid fa-key"></i>
                            </button>
                        </div>
                        @error('password')
                        <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <!-- Campo Confirmar Contraseña -->
                    <div class="mb-3">
                        <label for="userPasswordConfirmation" class="form-label">Confirmar Contraseña</label>
                        <input type="password"
                               class="form-control @error('password_confirmation') is-invalid @enderror"
                               id="userPasswordConfirmation"
                               name="password_confirmation">
                        @error('password_confirmation')
                        <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>
