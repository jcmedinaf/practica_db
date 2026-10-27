<?php
/**
 * =====================================================================
 * ARCHIVO: editar.php
 * DESCRIPCIÓN: Script backend encargado de recibir los datos actualizados
 * por POST, validar campos y formato de correo, verificar si se modificó 
 * la contraseña (aplicando hash si aplica) y actualizar el registro 
 * correspondiente en la tabla usuarios de la base de datos.
 * PROYECTO: Sistema CRUD en PHP, MySQLi y AJAX (Fines educativos)
 * AUTOR: Juan Carlos Medina
 * =====================================================================
 */

// Carga la conexión a la base de datos utilizando la ruta absoluta del directorio actual
include_once(__DIR__ . '/conexion.php');

// Indicar estrictamente que la respuesta HTTP entregada será en formato JSON
header('Content-Type: application/json');

// Inicializa el arreglo que contendrá la respuesta a retornar
$json = array();

// 1. Verificar que los campos obligatorios requeridos existan en la petición POST (incluyendo el ID)
if (isset($_POST['id'], $_POST['nombre'], $_POST['apellido'], $_POST['correo'])) {

    // Limpiar espacios en blanco y escapar los datos de texto para prevenir Inyección SQL
    $id = (int)$_POST['id']; // Convierte y sanitiza el ID estrictamente a un número entero
    $nombre = mysqli_real_escape_string($conexion, trim($_POST['nombre']));
    $apellido = mysqli_real_escape_string($conexion, trim($_POST['apellido']));
    $correo = mysqli_real_escape_string($conexion, trim($_POST['correo']));

    // 2. Validar que los datos obligatorios no estén vacíos y que el ID sea válido
    if ($id <= 0 || $nombre === '' || $apellido === '' || $correo === '') {
        $json[] = array(
            'estado' => 'error',
            'mensaje' => 'Por favor, completa todos los campos obligatorios y proporciona un ID válido.'
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

    // 4. Determinar dinámicamente si el usuario ingresó una nueva contraseña para actualizarla o mantener la anterior
    if (isset($_POST['clave']) && trim($_POST['clave']) !== '') {
        // Se envió una nueva contraseña: se aplica cifrado hash seguro y se incluye la clave en la sentencia UPDATE
        $clave_original = trim($_POST['clave']);
        $clave_hash = password_hash($clave_original, PASSWORD_DEFAULT);
        $clave_segura = mysqli_real_escape_string($conexion, $clave_hash);

        $query = "UPDATE usuarios SET nombre = '$nombre', apellido = '$apellido', correo = '$correo', clave = '$clave_segura' WHERE id = $id";
    } else {
        // No se envió contraseña: se actualizan nombre, apellido y correo conservando intacta la clave anterior en la BD
        $query = "UPDATE usuarios SET nombre = '$nombre', apellido = '$apellido', correo = '$correo' WHERE id = $id";
    }

    // 5. Ejecutar la consulta SQL de actualización contra la base de datos
    $result = mysqli_query($conexion, $query);

    // Control de errores en caso de que la sentencia SQL falle (ej. correo duplicado)
    if (!$result) {
        $json[] = array(
            'estado' => 'error',
            'mensaje' => 'Error al actualizar el usuario: ' . mysqli_error($conexion)
        );
        echo json_encode($json);
        exit;
    }

    // 6. Construir la respuesta exitosa con los datos actualizados para reflejarlos en la interfaz
    $json[] = array(
        'estado' => 'exito',
        'mensaje' => 'Datos actualizados correctamente',
        'id' => $id,
        'nombre' => $_POST['nombre'],
        'apellido' => $_POST['apellido'],
        'correo' => $_POST['correo']
    );

    // Codifica y envía el resultado final en formato JSON
    echo json_encode($json);

} else {
    // Si faltan parámetros obligatorios en la solicitud POST
    $json[] = array(
        'estado' => 'error',
        'mensaje' => 'Faltan datos requeridos en la solicitud.'
    );
    echo json_encode($json);
}
?>