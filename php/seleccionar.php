<?php
/**
 * =====================================================================
 * ARCHIVO: seleccionar.php
 * DESCRIPCIÓN: Script backend encargado de consultar un registro 
 * específico de la tabla usuarios a partir de su ID único recibido por POST,
 * retornando los datos en un objeto JSON seguro para cargarlos en el formulario.
 * PROYECTO: Sistema CRUD en PHP, MySQLi y AJAX (Fines educativos)
 * AUTOR: Juan Carlos Medina
 * =====================================================================
 */

// Carga la conexión utilizando la ruta absoluta del directorio actual
include_once(__DIR__ . '/conexion.php');

// Indicar estrictamente que la respuesta HTTP será en formato JSON
header('Content-Type: application/json');

// Inicializa la variable que contendrá la respuesta a retornar
$json = array();

// 1. Verificar que el parámetro 'id' exista en la petición POST
if (isset($_POST['id'])) {

    // 2. Sanitizar y convertir estrictamente el ID a un número entero por seguridad
    $id = (int)$_POST['id'];

    // Valida que el ID sea un número positivo mayor a cero
    if ($id <= 0) {
        $json = array(
            'estado' => 'error',
            'mensaje' => 'El ID proporcionado no es válido.'
        );
        echo json_encode($json);
        exit;
    }

    // 3. Ejecutar la consulta buscando únicamente los campos necesarios (sin exponer contraseñas) limitando a 1 resultado
    $query = "SELECT id, nombre, apellido, correo FROM usuarios WHERE id = $id LIMIT 1";
    $result = mysqli_query($conexion, $query);

    // Control de errores en caso de que falle la consulta SQL
    if (!$result) {
        $json = array(
            'estado' => 'error',
            'mensaje' => 'Error al consultar la base de datos: ' . mysqli_error($conexion)
        );
        echo json_encode($json);
        exit;
    }

    // 4. Verificar si se encontró el registro en la base de datos
    if ($row = mysqli_fetch_assoc($result)) {
        // Devolvemos el objeto plano listo para usar en JavaScript (ej. rellenar los inputs del formulario)
        $json = array(
            'estado' => 'exito',
            'id' => $row['id'],
            'nombre' => $row['nombre'],
            'apellido' => $row['apellido'],
            'email' => $row['correo']
        );
    } else {
        // Si la consulta fue exitosa pero no existe ningún usuario con ese ID
        $json = array(
            'estado' => 'error',
            'mensaje' => 'No se encontró ningún usuario con ese ID.'
        );
    }

    // Codifica y envía el resultado final en formato JSON
    echo json_encode($json);

} else {
    // Si no se recibió el parámetro ID en la solicitud POST
    $json = array(
        'estado' => 'error',
        'mensaje' => 'Falta el parámetro ID en la solicitud.'
    );
    echo json_encode($json);
}
?>