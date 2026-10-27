<?php
/**
 * =====================================================================
 * ARCHIVO: agregar.php
 * DESCRIPCIÓN: Script backend encargado de recibir los datos por POST,
 * validar campos y formato de correo, cifrar la contraseña de forma segura
 * con password_hash e insertar un nuevo registro en la tabla usuarios.
 * PROYECTO: Sistema CRUD en PHP, MySQLi y AJAX (Fines educativos)
 * AUTOR: Juan Carlos Medina
 * =====================================================================
 */

// Carga la conexión a la base de datos utilizando la ruta absoluta de la carpeta actual
include_once(__DIR__ . '/conexion.php');

// Indicar estrictamente que la respuesta HTTP será en formato JSON
header('Content-Type: application/json');

// Inicializa el arreglo que contendrá la respuesta a retornar
$json = array();

// 1. Verificar que todos los campos requeridos existan en la petición POST
if (isset($_POST['nombre'], $_POST['apellido'], $_POST['correo'], $_POST['clave'])) {
    
    // Limpiar espacios en blanco al inicio/final y escapar los datos de texto para prevenir Inyección SQL
    $nombre = mysqli_real_escape_string($conexion, trim($_POST['nombre']));
    $apellido = mysqli_real_escape_string($conexion, trim($_POST['apellido']));
    $correo = mysqli_real_escape_string($conexion, trim($_POST['correo']));
    $clave_original = trim($_POST['clave']);

    // 2. Validar que ninguno de los campos obligatorios esté vacío
    if ($nombre === '' || $apellido === '' || $correo === '' || $clave_original === '') {
        $json[] = array(
            'estado' => 'error',
            'mensaje' => 'Por favor, completa todos los campos obligatorios.'
        );
        echo json_encode($json);
        exit;
    }

    // 3. Validar que el correo electrónico tenga un formato estructural válido
    if (!filter_var($_POST['correo'], FILTER_VALIDATE_EMAIL)) {
        $json[] = array(
            'estado' => 'error',
            'mensaje' => 'El formato del correo electrónico no es válido.'
        );
        echo json_encode($json);
        exit;
    }

    // 4. Aplicar un hash seguro y unidireccional a la contraseña utilizando el algoritmo por defecto de PHP
    $clave_hash = password_hash($clave_original, PASSWORD_DEFAULT);
    $clave_segura = mysqli_real_escape_string($conexion, $clave_hash);

    // 5. Construir y ejecutar la consulta SQL de inserción con los datos protegidos
    $query = "INSERT INTO usuarios (nombre, apellido, correo, clave) VALUES ('$nombre', '$apellido', '$correo', '$clave_segura')";
    $result = mysqli_query($conexion, $query);

    // Control de errores en caso de que falle la sentencia SQL (ej. correo duplicado)
    if (!$result) {
        $json[] = array(
            'estado' => 'error',
            'mensaje' => 'Error al registrar en la base de datos: ' . mysqli_error($conexion)
        );
        echo json_encode($json);
        exit;
    }

    // Capturar el ID único generado automáticamente por el autoincrementable de la base de datos
    $ultimo_id = mysqli_insert_id($conexion);

    // 6. Construir la respuesta exitosa (por estricta seguridad, nunca se retorna la clave cifrada en el JSON)
    $json[] = array(
        'estado' => 'exito',
        'mensaje' => 'Datos agregados correctamente',
        'id' => $ultimo_id,
        'nombre' => $_POST['nombre'],
        'apellido' => $_POST['apellido'],
        'correo' => $_POST['correo']
    );

    // Codifica y envía el resultado final en formato JSON
    echo json_encode($json);

} else {
    // Si faltan datos obligatorios en la solicitud POST
    $json[] = array(
        'estado' => 'error',
        'mensaje' => 'Faltan datos requeridos en la solicitud.'
    );
    echo json_encode($json);
}
?>