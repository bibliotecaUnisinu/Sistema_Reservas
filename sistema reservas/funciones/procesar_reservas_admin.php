<?php
// Incluir PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require '../email/PHPMailer/src/Exception.php'; // (modificado) Incluir la clase Exception de PHPMailer
require '../email/PHPMailer/src/SMTP.php'; // (modificado) Incluir la clase SMTP de PHPMailer
require '../email/PHPMailer/src/PHPMailer.php'; // (modificado) Incluir la clase PHPMailer

// Incluir el archivo de configuración para la conexión
require_once('../conexion/config.php'); // (modificado) Incluir el archivo de configuración de la base de datos
require_once('../funciones/database.php'); // (modificado) Incluir el archivo de funciones de base de datos

session_start(); // Iniciar sesión

// Verificar si el formulario fue enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recibir los datos del formulario
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $telefono = $_POST['telefono'];
    $email = $_POST['email'];
    $tipo_usuario = $_POST['tipo_usuario'];
    $observacion = $_POST['observacion'];
    $fecha_reserva = $_POST['fecha_reserva'];
    $fecha_actual = $_POST['fecha_actual'];
    $sede_seleccionada = $_POST['sede_seleccionada'];
    $espacio_seleccionado = $_POST['espacio_seleccionada'];
    $hora_inicio = $_POST['horaInicio']; 
    $Horas_reservas = $_POST['Horas_reservas'];
    $minutos_reservas = $_POST['minutos_reservas'];
    $semestre = $_POST['semestre'];
    $programa = $_POST['programa'];
    $num_asistentes = $_POST['num_asistentes'];

    // Validar que no haya campos vacíos
    if (empty($id) || empty($nombre) || empty($apellido) || empty($telefono) || empty($email) || empty($tipo_usuario) || empty($fecha_reserva) || empty($fecha_actual) || empty($sede_seleccionada) || empty($espacio_seleccionado) || empty($hora_inicio) || empty($Horas_reservas) || empty($minutos_reservas) || empty($semestre) || empty($programa) || empty($num_asistentes)) {
        echo "Por favor, completa todos los campos."; // (modificado) Mensaje de error si hay campos vacíos
        exit; // Termina la ejecución
    }

    // Verificar si ya existe una reserva en el mismo espacio y horario
    $sql_verificar = "SELECT * FROM reservations 
                    WHERE date_reserv = ? 
                    AND start_time = ? 
                    AND id_space = ? 
                    AND state_reservation = 1"; // (modificado) Consulta SQL para verificar superposiciones
    $stmt = $conexion->prepare($sql_verificar); // (modificado) Preparar la consulta
    $stmt->bind_param("ssi", $fecha_reserva, $hora_inicio, $espacio_seleccionado); // (modificado) Vincular parámetros
    $stmt->execute(); // (modificado) Ejecutar la consulta
    $resultado = $stmt->get_result(); // (modificado) Obtener el resultado

    if ($resultado->num_rows > 0) {
        header('Location: ../Visualizaciones/inicio.php?error=El espacio ya está reservado en el horario seleccionado'); // (modificado) Redirigir con mensaje de error si hay superposición
        exit; // Termina la ejecución si hay una superposición
    }

    // Preparar los datos para insertar en la base de datos
    $data = [
        'id_user' => $id, // ID del usuario
        'name_user' => $nombre, // Nombre del usuario
        'surname_user' => $apellido, // Apellido del usuario
        'phone_user' => $telefono, // Teléfono del usuario
        'email_user' => $email, // Correo del usuario
        'user_type' => $tipo_usuario, // Tipo de usuario
        'observation' => $observacion, // Observaciones
        'date_reserv' => $fecha_reserva, // Fecha de la reserva
        'date_current' => $fecha_actual, // Fecha actual
        'space_select' => $espacio_seleccionado, // Espacio seleccionado
        'location_select' => $sede_seleccionada, // Sede seleccionada
        'start_time' => $hora_inicio, // Hora de inicio
        'hours_reserv' => $Horas_reservas, // Horas de reserva
        'minuts_reserv' => $minutos_reservas, // Minutos de reserva
        'semester' => $semestre, // Semestre
        'program' => $programa, // Programa
        'number_attendees' => $num_asistentes, // Número de asistentes
        'state_reservation' => 1 // Estado de la reserva habilitada por defecto
    ];

    // Insertar en la tabla 'reservations'
    if (insert($conexion, 'reservations', $data)) { // (modificado) Llama a la función insert
        // Instancia de PHPMailer
        $mail = new PHPMailer(true); // (modificado) Crear una nueva instancia de PHPMailer
        try {
            // Configuración del servidor SMTP
            $mail->isSMTP(); // (modificado) Configurar para usar SMTP
            $mail->Host = 'smtp.gmail.com'; // Servidor SMTP
            $mail->SMTPAuth = true; // Habilitar autenticación SMTP
            $mail->Username = 'referencistabiblioteca@unisinucartagena.edu.co'; // Correo del remitente
            $mail->Password = 'Unisinu2023*'; // (modificado) Contraseña del correo (considera usar un método más seguro para manejar contraseñas)
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Habilitar encriptación TLS
            $mail->Port = 587; // Puerto TCP para la conexión

            // Configuración del correo
            $mail->setFrom('referencistabiblioteca@unisinucartagena.edu.co', 'Biblioteca'); // Remitente
            $mail->addAddress($email); // Destinatario

            // Contenido del correo
            $mail->isHTML(true); // Habilitar contenido HTML
            $mail->Subject = 'Confirmación de reserva'; // Asunto del correo
            $mail->Body = "Estimado $nombre $apellido, <br>has realizado una reserva en nuestra biblioteca exitosamente.

            <h3>Detalles de la Reserva</h3>
            <p><strong>Fecha de Reserva:</strong> $fecha_reserva</p>
            <p><strong>Hora de Inicio:</strong> $hora_inicio</p>
            <p><strong>Duración:</strong> $Horas_reservas horas y $minutos_reservas minutos</p>
            <p><strong>Sede:</strong> $sede_seleccionada</p>
            <p><strong>Espacio:</strong> $espacio_seleccionado</p>
            <p>Reserva para el programa de $programa del semestre $semestre asistiendo $num_asistentes personas.</p>

            <p>En caso de no poder asistir a la reserva solicitada, por favor, acercarse a la oficina o enviar un correo a la dirección de la biblioteca para cancelar su reserva.</p>";

            // Enviar el correo
            $mail->send(); // (modificado) Enviar el correo

            // Redirigir a la página de inicio con un mensaje de éxito
            header('Location: ../Visualizaciones/inicio.php?success=reserva creada exitosamente'); // (modificado) Redirigir con mensaje de éxito
        } catch (Exception $e) {
            // Redirigir a la página de inicio con un mensaje de error si falla el envío
            header('Location: ../Visualizaciones/administrador.php?error=Error al crear la reserva'); // (modificado) Mensaje de error si falla el envío
        }
    } else {
        // Redirigir a la página de inicio con un mensaje de error si no se pudo insertar
        header('Location: ../Visualizaciones/inicio.php?error=Error al crear la reserva'); // (modificado) Mensaje de error si no se pudo insertar
    }
    exit; // Terminar la ejecución
}
?>