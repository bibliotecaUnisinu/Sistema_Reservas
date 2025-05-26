document.getElementById('programa').addEventListener('change', function() {
    if (this.value === 'agregar') {
        // Cierra cualquier modal Bootstrap abierto
        $('.modal').modal('hide');
        // Abre el SweetAlert2
        Swal.fire({
            title: 'Agregar Nuevo Programa',
            html: `
                <div style="margin-top:10px;">
                    <input id="nuevoProgramaInput"
                           class="form-control"
                           placeholder="Escribe el nombre del nuevo programa"
                           style="font-size: 1.2em; padding: 10px; width: 100%; box-sizing: border-box;"
                           autocomplete="off"
                           autocapitalize="off"
                    >
                    <div style="margin-top: 10px;">
                        <input id="nuevoSemestreInput"
                               class="form-control"
                               type="number"
                               placeholder="Cantidad de maxima de semestres"
                               min="1"
                               max="12"
                               style="font-size: 1.2em; padding: 10px; width: 100%; box-sizing: border-box;"
                               required
                        >
                    </div>
                </div>
            `,
            customClass: {
                popup: 'animated fadeInDown faster'
            },
            showCancelButton: true,
            confirmButtonText: 'Guardar',
            cancelButtonText: 'Cancelar',
            focusConfirm: false,
            preConfirm: () => {
                const nombrePrograma = document.getElementById('nuevoProgramaInput').value.trim();
                const semestres = parseInt(document.getElementById('nuevoSemestreInput').value);
                
                if (!nombrePrograma) {
                    Swal.showValidationMessage('¡Debes escribir un nombre!');
                }
                if (!semestres || semestres < 1 || semestres > 12) {
                    Swal.showValidationMessage('¡Debes ingresar una cantidad válida de semestres (1-12)!');
                }
                return nombrePrograma && semestres && semestres >= 1 && semestres <= 12;
            },
            didOpen: () => {
                const input = document.getElementById('nuevoProgramaInput');
                const semestreInput = document.getElementById('nuevoSemestreInput');
                
                input.removeAttribute('readonly');
                input.disabled = false;
                input.focus();
                
                semestreInput.removeAttribute('readonly');
                semestreInput.disabled = false;
            }
        }).then((result) => {
            if (result.isConfirmed) {
                const nombrePrograma = document.getElementById('nuevoProgramaInput').value.trim();
                const semestres = parseInt(document.getElementById('nuevoSemestreInput').value);
                
                // Envía a PHP para guardar
                fetch('../funciones/agregarPrograma.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `nombre_programa=${encodeURIComponent(nombrePrograma)}&semestre=${encodeURIComponent(semestres)}`
                })
                .then(response => response.text())
                .then(data => {
                    if (!isNaN(data) && parseInt(data) > 0) {
                        const select = document.getElementById('programa');
                        const nuevaOpcion = document.createElement('option');
                        nuevaOpcion.value = data; // El nuevo ID
                        nuevaOpcion.textContent = nombrePrograma;
                        // Insertarlo antes de la opción "agregar"
                        const opcionAgregar = select.querySelector('option[value="agregar"]');
                        select.insertBefore(nuevaOpcion, opcionAgregar);
                        // Seleccionar automáticamente el nuevo
                        nuevaOpcion.selected = true;
                        Swal.fire(
                            '¡Programa agregado!',
                            'El programa fue creado exitosamente.',
                            'success'
                        );
                    } else {
                        Swal.fire(
                            'Error',
                            'No se pudo guardar el programa. Intenta de nuevo.',
                            'error'
                        );
                    }
                })
                .catch(error => {
                    console.error('Error de conexión:', error);
                    Swal.fire(
                        'Error de conexión',
                        'No pudimos conectarnos al servidor.',
                        'error'
                    );
                });
            } else {
                // Si cancelan, regresar a opción por defecto
                this.value = '';
            }
        });
    }
});