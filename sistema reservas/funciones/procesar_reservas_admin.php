<?php
// procesar_reserva.php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require '../email/PHPMailer/src/Exception.php';
require '../email/PHPMailer/src/SMTP.php';
require '../email/PHPMailer/src/PHPMailer.php';
require_once('../conexion/config.php');
require_once('../funciones/database.php');

session_start();

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
    $sede_seleccionada = $_POST['sede_seleccionada'];
    $espacio_seleccionado = $_POST['espacio_seleccionada'];
    $hora_inicio = $_POST['horaInicio']; 
    $Horas_reservas = $_POST['Horas_reservas'] ?? 0;
    $minutos_reservas = $_POST['minutos_reservas'] ?? 0;
    $semestre = $_POST['semestre'];
    $programa = $_POST['programa'];
    $num_asistentes = $_POST['num_asistentes'];

    // Inicializar variable para mensajes
    $mensaje = '';

    // Validar que no haya campos vacíos
    if (empty($id) || empty($nombre) || empty($apellido) || empty($telefono) || empty($email) || 
        empty($tipo_usuario) || empty($fecha_reserva) || empty($sede_seleccionada) || 
        empty($espacio_seleccionado) || empty($hora_inicio) || empty($semestre) || 
        empty($programa) || empty($num_asistentes)) {
        $mensaje = 'Por favor, completa todos los campos.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $mensaje = 'El formato del correo electrónico no es válido.';
    } else {
        // Validar que la hora de inicio esté entre 6:30 AM y 7:59 PM
        $hora_inicio_timestamp = strtotime($hora_inicio);
        $hora_inicio_6am = strtotime("06:30");
        $hora_inicio_8pm = strtotime("19:59");

        echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
        if ($hora_inicio_timestamp < $hora_inicio_6am || $hora_inicio_timestamp > $hora_inicio_8pm) {
                        echo "
                        <script>
                            window.onload = function() {
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'Hora no permitida',
                                    text: 'La hora de inicio debe estar entre las 6:30 AM y las 7:59 PM.',
                                    confirmButtonText: 'Volver'
                                }).then(() => {
                                    window.history.back();
                                });
                            };
                        </script>";
                    } elseif ($Horas_reservas < 0 || $minutos_reservas < 0) {
                        echo "
                        <script>
                            window.onload = function() {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Duración inválida',
                                    text: 'La duración de la reserva no puede ser negativa.',
                                    confirmButtonText: 'Corregir'
                                }).then(() => {
                                    window.history.back();
                                });
                            };
                        </script>";
                    } elseif (empty($Horas_reservas) && empty($minutos_reservas)) {
                        echo "
                        <script>
                            window.onload = function() {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Duración nula',
                                    text: 'La duración de la reserva no puede ser 0 horas y 0 minutos.',
                                    confirmButtonText: 'Modificar'
                                }).then(() => {
                                    window.history.back();
                                });
                            };
                        </script>";
                    } elseif ($Horas_reservas > 4 || ($Horas_reservas == 4 && $minutos_reservas > 0)) {
                        echo "
                        <script>
                            window.onload = function() {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Duración excedida',
                                    text: 'La duración máxima de la reserva es de 4 horas.',
                                    confirmButtonText: 'Entendido'
                                }).then(() => {
                                    window.history.back();
                                });
                            };
                        </script>";
                    } else {

            // Obtener la duración total de la nueva reserva en minutos
            $total_minutos_reserva = (!empty($Horas_reservas) ? $Horas_reservas * 60 : 0) + (!empty($minutos_reservas) ? $minutos_reservas : 0);
            $hora_fin_nueva_reserva = date("H:i", strtotime($hora_inicio) + ($total_minutos_reserva * 60));
            $hora_fin_9pm = strtotime("21:00");
            $hora_fin_nueva_reserva_timestamp = strtotime($hora_fin_nueva_reserva);

            if ($hora_fin_nueva_reserva_timestamp > $hora_fin_9pm) {
                $mensaje = 'La reserva no puede exceder las 9:00 PM.';
            } else {
                // Primero obtener el ID de la ubicación
                $query_location = "SELECT id_location FROM locations WHERE name_location = ?";
                $stmt = $conexion->prepare($query_location);
                $stmt->bind_param("s", $sede_seleccionada);
                $stmt->execute();
                $result_location = $stmt->get_result();
                $location_row = $result_location->fetch_assoc();

                if (!$location_row) {
                    $mensaje = 'La sede seleccionada no existe en la base de datos.';
                } else {
                    $id_location = $location_row['id_location'];

                    // Obtener el ID del espacio usando tanto el nombre como la ubicación
                    $query_space = "SELECT id_space, capacity FROM spaces WHERE name_space = ? AND id_location = ?";
                    $stmt = $conexion->prepare($query_space);
                    $stmt->bind_param("si", $espacio_seleccionado, $id_location);
                    $stmt->execute();
                    $result_space = $stmt->get_result();
                    $space_row = $result_space->fetch_assoc();

                    if (!$space_row) {
                        $mensaje = 'El espacio seleccionado no existe en esta sede.';
                    } else {
                        $id_space = $space_row['id_space'];
                        $capacidad_espacio = $space_row['capacity'];

                        // Validar capacidad del espacio
                        if ($num_asistentes > $capacidad_espacio) {
                        echo "
                        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
                        <script>
                            window.onload = function() {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Capacidad Excedida',
                                    html: 'El número de asistentes (<strong>$num_asistentes</strong>) supera la capacidad del espacio (<strong>$capacidad_espacio</strong>).',
                                    confirmButtonText: 'Volver'
                                }).then(() => {
                                    window.history.back(); // Vuelve atrás para corregir el número
                                });
                            };
                        </script>";
                    } else {
                             // Verificar si hay reservas existentes que se superpongan, ignorando las canceladas y las inactivas
                             $query = "SELECT COUNT(*) as total_reservas 
                             FROM reservations r
                             JOIN spaces s ON r.id_space = s.id_space
                             WHERE r.date_reserv = ?
                             AND s.name_space = ?
                             AND s.id_location = ?
                             AND r.cancel_reserv = 0  -- Ignora reservas canceladas
                             AND r.state_reservation = 1  -- Ignora reservas inactivas
                             AND (
                                 (r.start_time <= ? AND ADDTIME(r.start_time, CONCAT(r.hours_reserv, ':', r.minuts_reserv)) > ?)
                                 OR
                                 (r.start_time >= ? AND r.start_time < ?)
                             )";

                             $stmt = $conexion->prepare($query);
                             $stmt->bind_param("ssissii", 
                                 $fecha_reserva,
                                 $espacio_seleccionado,
                                 $id_location,
                                 $hora_fin_nueva_reserva,
                                 $hora_inicio,
                                 $hora_inicio,
                                 $hora_fin_nueva_reserva);

                             $stmt->execute();
                             $result = $stmt->get_result();
                             $row = $result->fetch_assoc();

                            // Verificación después de la consulta
                             if ($row['total_reservas'] > 0) {
                                    echo "
                                    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
                                    <script>
                                        window.onload = function() {
                                            Swal.fire({
                                                icon: 'warning',
                                                title: 'Reserva Duplicada',
                                                text: 'Ya existe una reserva activa para este espacio en el horario seleccionado.',
                                                confirmButtonText: 'Volver'
                                            }).then(() => {
                                                window.history.back(); // Opcional: vuelve atrás
                                            });
                                        };
                                    </script>";
                                } else {

                                // Preparar los datos para insertar en la base de datos
                                $data = [
                                    'id_user' => $id,
                                    'name_user' => $nombre,
                                    'surname_user' => $apellido,
                                    'phone_user' => $telefono,
                                    'email_user' => $email,
                                    'user_type' => $tipo_usuario,
                                    'observation' => $observacion,
                                    'date_reserv' => $fecha_reserva,
                                    'date_current' => date('Y-m-d'),
                                    'space_select' => $espacio_seleccionado,
                                    'location_select' => $sede_seleccionada,
                                    'start_time' => $hora_inicio,
                                    'hours_reserv' => $Horas_reservas,
                                    'minuts_reserv' => $minutos_reservas,
                                    'semester' => $semestre,
                                    'program' => $programa,
                                    'number_attendees' => $num_asistentes,
                                    'state_reservation' => 1,
                                    'id_location' => $id_location,
                                    'id_space' => $id_space,
                                    'cancel_reserv' => 0
                                ];

                                // Insertar en la tabla 'reservations'
                                if (insert($conexion, 'reservations', $data)) {
                                    // Crear una instancia de PHPMailer
                                    $mail = new PHPMailer(true);
                                    try {
                                        // Configuración del servidor SMTP
                                        $mail->isSMTP(); // (modificado) Configurar para usar SMTP
                                        $mail->Host = 'smtp.gmail.com'; // Servidor SMTP
                                        $mail->SMTPAuth = true; // Habilitar autenticación SMTP
                                        $mail->Username = 'referencistabiblioteca@unisinucartagena.edu.co'; // Correo del remitente
                                        $mail->Password = 'egng uqta eudg fqgz'; // (modificado) Contraseña del correo (considera usar un método más seguro para manejar contraseñas)
                                        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Habilitar encriptación TLS
                                        $mail->Port = 587; // Puerto TCP para la conexión

                                        // Configuración del correo
                                        $mail->setFrom('referencistabiblioteca@unisinucartagena.edu.co', 'Biblioteca');
                                        $mail->addAddress($email);

                                        // Obtener el nombre del programa desde el ID
                                        $query_programa = "SELECT nombre_programa FROM programas WHERE id_programa = ?";
                                        $stmt_programa = $conexion->prepare($query_programa);
                                        $stmt_programa->bind_param("i", $programa);
                                        $stmt_programa->execute();
                                        $result_programa = $stmt_programa->get_result();
                                        $row_programa = $result_programa->fetch_assoc();
                                        $nombre_programa = $row_programa ? $row_programa['nombre_programa'] : 'Desconocido';


                                        // Contenido del correo
                                        $mail->isHTML(true);
                                        $mail->Subject = 'Confirmación de reserva';
                                        $mail->Body = "Estimado $nombre $apellido, <br>has realizado una reserva en nuestra biblioteca exitosamente.

                                        <h3>Detalles de la Reserva</h3>
                                        <p><strong>Fecha de Reserva:</strong> $fecha_reserva</p>
                                        <p><strong>Hora de Inicio:</strong> $hora_inicio</p>
                                        <p><strong>Duración:</strong> $Horas_reservas horas y $minutos_reservas minutos</p>
                                        <p><strong>Sede:</strong> $sede_seleccionada</p>
                                        <p><strong>Espacio:</strong> $espacio_seleccionado</p>
                                        <p>Reserva para el programa de $nombre_programa del semestre $semestre asistiendo $num_asistentes personas.</p>

                                        <p>En caso de no poder asistir a la reserva solicitada, por favor, acercarse a la oficina o enviar un correo a la dirección de la biblioteca para cancelar su reserva.</p>";

                                        // Enviar el correo
                                        $mail->send();

                                      // Mostrar un mensaje de éxito y redirigir
                                        echo "
                                                <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
                                                <script>
                                                    window.onload = function() {
                                                        Swal.fire({
                                                            title: '¡Reserva Exitosa!',
                                                            text: 'Serás redirigido al inicio.',
                                                            icon: 'success',
                                                            timer: 3000,
                                                            timerProgressBar: true,
                                                            showConfirmButton: false,
                                                            willClose: () => {
                                                                window.location.href = '../Visualizaciones/administrador.php';
                                                            }
                                                        });
                                                    }
                                                </script>";

                                        exit();
                                    } catch (Exception $e) {
                                        echo "<script>
                                                alert('Error al enviar el correo de confirmación: {$mail->ErrorInfo}');
                                                window.history.back();
                                              </script>";
                                        exit();
                                    }
                                } else {
                                    echo "<script>
                                            alert('Error al crear la reserva. Por favor, intenta nuevamente.');
                                            window.history.back();
                                          </script>";
                                    exit();
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    // Si hay un mensaje de error, mostrarlo
    if ($mensaje) {
        echo "<script>alert('$mensaje'); window.history.back();</script>";
        exit();
    }
}
?>