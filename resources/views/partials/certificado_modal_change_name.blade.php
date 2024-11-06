<!-- Modal para confirmar el nombre -->
<div class="modal fade"
     id="nameConfirmationModal"
     tabindex="-1"
     aria-labelledby="exampleModalLabel"
     aria-hidden="true">

    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"
                    id="exampleModalLabel">
                    Confirma que tu nombre esté completo, ya que este es el que se mostrará en tu constancia.
                </h5>
                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close">

                </button>
            </div>
            <div class="modal-body">
                <form id="editNameForm"
                      action="{{ route('user.update_name') }}"
                      method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="name">Nombre:</label>
                        <input type="text"
                               name="name"
                               id="name"
                               class="form-control"
                               value="{{ Auth::user()->name }}">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal">Cerrar
                </button>
                <!-- Botón para enviar el formulario y actualizar el nombre -->
                <button type="submit"
                        class="btn btn-primary"
                        form="editNameForm">Actualizar Nombre
                </button>
            </div>
        </div>
    </div>
</div>
