document.addEventListener("DOMContentLoaded", function() {
    const fechaReserva = flatpickr("#fecha_reserva", {
        enableTime: false,
        dateFormat: "Y-m-d",
        minDate: "today"
    });

    document.getElementById('iconoCalendario').addEventListener('click', function() {
        fechaReserva.open();
    });

    const fechaActual = new Date().toISOString().split('T')[0];
    document.getElementById("fecha_actual").value = fechaActual;

    function ocultarMensaje(mensajeId) {
        document.getElementById(mensajeId).style.display = 'none';
    }

    function mostrarError(idCampo, mensaje) {
        const campo = document.getElementById(idCampo);
        if (!campo) return;

        const contenedor = campo.closest('.form-group');
        if (!contenedor) return;

        let mensajeError = contenedor.querySelector('.error-message');
        if (!mensajeError) {
            mensajeError = document.createElement('div');
            mensajeError.className = 'error-message';
            contenedor.appendChild(mensajeError);
        }

        mensajeError.textContent = mensaje;
        mensajeError.style.color = 'red';
        campo.classList.add('is-invalid');
        campo.classList.remove('is-valid');
    }

    function ocultarError(idCampo) {
        const campo = document.getElementById(idCampo);
        if (!campo) return;

        const contenedor = campo.closest('.form-group');
        if (!contenedor) return;

        const mensajeError = contenedor.querySelector('.error-message');
        if (mensajeError) mensajeError.remove();

        campo.classList.remove('is-invalid');
        campo.classList.add('is-valid');
    }

    function validarCampo(idCampo) {
        const campo = document.getElementById(idCampo);
        if (!campo) return true;

        const valor = campo.value.trim();
        const esSelect = campo.tagName === 'SELECT';

        if (esSelect && valor === "Seleccione una opción") {
            mostrarError(idCampo, "Por favor, selecciona una opción.");
            return false;
        } else if (!esSelect && !valor) {
            mostrarError(idCampo, "Este campo es obligatorio.");
            return false;
        }

        ocultarError(idCampo);
        return true;
    }

    function validarFormulario() {
        let isValid = true;
        
        const horas = document.getElementById("Horas_reservas");
        const minutos = document.getElementById("minutos_reservas");
        const mensajeHoras = document.getElementById("mensajeDuracion");
        const mensajeMinutos = document.getElementById("mensajeMinutos");
    
        const valorHoras = parseInt(horas.value, 10) || 0;
        const valorMinutos = parseInt(minutos.value, 10) || 0;
    
        // Validación: al menos uno de los dos debe ser mayor que 0
        if (valorHoras === 0 && valorMinutos === 0) {
            mensajeHoras.style.display = 'block';
            mensajeHoras.textContent = 'Debes elegir al menos una duración en horas o minutos.';
            mensajeMinutos.style.display = 'block';
            mensajeMinutos.textContent = 'El tiempo no puede ser menor a 15 minutos.';
            isValid = false;
        } else {
            mensajeHoras.style.display = 'none';
            mensajeMinutos.style.display = 'none';
        }
    
        // Validar otros campos obligatorios
        const otrosCampos = [
            { id: "programa", mensaje: "mensajePrograma" },
            { id: "semestre", mensaje: "mensajeSemestre" },
            { id: "num_asistentes", mensaje: "mensajeAsistentes" }
        ];
    
        otrosCampos.forEach(({ id, mensaje }) => {
            const campo = document.getElementById(id);
            if (!campo.value || campo.value === "0" || campo.value === "Programa" || campo.value === "Semestre") {
                document.getElementById(mensaje).style.display = 'block';
                isValid = false;
            } else {
                document.getElementById(mensaje).style.display = 'none';
            }
        });
    
       // Validación de Email para dominios válidos
       const email = document.getElementById("email").value.trim();
       const emailRegex = /^[a-zA-Z0-9._%+-]+@(?:gmail\.com|outlook\.com|hotmail\.com|yahoo\.com|edu\.pe)$/i;
       
       if (!emailRegex.test(email)) {
           document.getElementById('mensajeEmail').style.display = 'block';
           document.getElementById('mensajeEmail').textContent = 'El correo debe ser de dominio válido: @gmail.com, @outlook.com, @hotmail.com, @yahoo.com o @edu.pe';
           isValid = false;
       } else {
           document.getElementById('mensajeEmail').style.display = 'none';
       }
       
    
    
        actualizarEstadoBoton(isValid);
        return isValid;
    }    

    function actualizarEstadoBoton(esValido) {
        const btnCrearReserva = document.getElementById('btnCrearReserva');
        if (!btnCrearReserva) return;

        if (esValido) {
            btnCrearReserva.classList.remove('btn-danger');
            btnCrearReserva.classList.add('btn-primary');
        } else {
            btnCrearReserva.classList.remove('btn-primary');
            btnCrearReserva.classList.add('btn-danger');
        }
        // El botón siempre estará habilitado
        btnCrearReserva.disabled = false;
    }

    function validarCampos() {
        const campos = ["Horas_reservas", "minutos_reservas", "programa", "semestre", "num_asistentes"];
        let valido = campos.every(campo => {
            const elemento = document.getElementById(campo);
            return elemento && elemento.value.trim() !== '' && elemento.value !== "0";
        });

        actualizarEstadoBoton(valido);
    }

    document.querySelectorAll('input, select').forEach(elemento => {
        elemento.addEventListener('change', validarCampos);
        elemento.addEventListener('input', validarCampos);
    });

    document.getElementById("formulario").addEventListener("submit", function(event) {
        if (!validarFormulario()) {
            event.preventDefault();
            const mensajeFormulario = document.getElementById('mensajeFormulario');
            mensajeFormulario.style.display = 'block';
            mensajeFormulario.style.color = 'red';
            mensajeFormulario.textContent = "Por favor, completa todos los campos antes de enviar.";
        }
    });

    const camposValidar = [
        { id: "Horas_reservas", mensaje: "mensajeDuracion" },
        { id: "minutos_reservas", mensaje: "mensajeMinutos" },
        { id: "programa", mensaje: "mensajePrograma" },
        { id: "semestre", mensaje: "mensajeSemestre" },
        { id: "num_asistentes", mensaje: "mensajeAsistentes" }
    ];

    camposValidar.forEach(({ id, mensaje }) => {
        const campo = document.getElementById(id);
        if (campo) {
            campo.addEventListener('focus', () => {
                ocultarMensaje(mensaje);
            });
        }
    });

    // Inicializar el botón como activo
    actualizarEstadoBoton(false);
});

// Función para obtener la hora actual en formato HH:mm
function obtenerHoraActual() {
    const ahora = new Date();
    const horas = String(ahora.getHours()).padStart(2, '0');
    const minutos = String(ahora.getMinutes()).padStart(2, '0');
    return `${horas}:${minutos}`;
}

// Establecer la hora actual al cargar el modal
document.addEventListener('DOMContentLoaded', function() {
    const horaInicio = document.getElementById('hora_inicio');
    horaInicio.value = obtenerHoraActual();
});

// Actualizar la hora cada minuto para mantenerla sincronizada
setInterval(function() {
    const horaInicio = document.getElementById('hora_inicio');
    horaInicio.value = obtenerHoraActual();
}, 60000); // 60000 milisegundos = 1 minuto

    
document.getElementById('programa').addEventListener('change', function () {
    const programaId = this.value;

    fetch(`../funciones/obtener_semestres.php?id_programa=${programaId}`)
        .then(response => response.json())
        .then(data => {
            const semestreSelect = document.getElementById('semestre');
            semestreSelect.innerHTML = '<option disabled selected>Semestre</option>';

            data.forEach(sem => {
                const option = document.createElement('option');
                option.value = sem;
                option.textContent = sem;
                semestreSelect.appendChild(option);
            });
        })
        .catch(error => {
            console.error('Error al cargar semestres:', error);
        });
});


document.addEventListener("DOMContentLoaded", function () {
    const tipoUsuario = document.getElementById("tipo_usuario");
    const identificacion = document.getElementById("identificacion");
    const mensajeTipoUsuarioAntes = document.getElementById("mensajeTipoUsuarioAntes");

    tipoUsuario.addEventListener("change", function () {
        const tipo = tipoUsuario.value;

        mensajeTipoUsuarioAntes.style.display = "none"; // Oculta el mensaje si ya se eligió

        identificacion.value = ""; // Limpia el campo cada vez que se cambia de tipo
        identificacion.removeAttribute("pattern");
        identificacion.removeAttribute("title");

        if (tipo === "Docente") {
            identificacion.setAttribute("pattern", "\\d{5,10}");
            identificacion.setAttribute("maxlength", "10");
            identificacion.setAttribute("title", "Debe tener entre 5 y 10 números(Documento de identidad)");
        } else if (tipo === "Estudiante") {
            identificacion.setAttribute("pattern", "(0000\\d{6}|00000\\d{5})");
            identificacion.setAttribute("maxlength", "10");
            identificacion.setAttribute("title", "Debe comenzar con '0000' seguido de 6 números o '00000' seguido de 5 números(Carnet Estudiantil)");
        } else {
            identificacion.removeAttribute("maxlength");
        }
    });



    identificacion.addEventListener("focus", function () {
        if (!tipoUsuario.value || tipoUsuario.selectedIndex === 0) {
            mensajeTipoUsuarioAntes.style.display = "block";
            tipoUsuario.focus(); // Redirige el foco al selector de tipo de usuario
        } else {
            mensajeTipoUsuarioAntes.style.display = "none";
        }
    });
});

    
