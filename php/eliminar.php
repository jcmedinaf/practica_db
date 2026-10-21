<?php
/**
 * =====================================================================
 * ARCHIVO: eliminar.php
 * DESCRIPCIÓN: Script backend encargado de recibir el ID por POST,
 * sanitizarlo, validar su integridad, ejecutar la sentencia SQL DELETE
 * y verificar mediante filas afectadas si el usuario realmente existía.
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

// 1. Verificar que el parámetro 'id' exista en la petición POST
if (isset($_POST['id'])) {

    // 2. Sanitizar y convertir estrictamente el ID a un número entero para prevenir Inyección SQL
    $id = (int)$_POST['id'];

    // Validar que sea un ID positivo válido (mayor a cero)
    if ($id <= 0) {
        $json[] = array(
            'estado' => 'error',
            'mensaje' => 'El ID proporcionado no es válido.'
        );
        echo json_encode($json);
        exit;
    }

    // 3. Ejecutar la consulta de eliminación directa utilizando la conexión activa
    $query = "DELETE FROM usuarios WHERE id = $id";
    $result = mysqli_query($conexion, $query);

    // Control de errores en caso de que falle la sentencia SQL contra la base de datos
    if (!$result) {
        $json[] = array(
            'estado' => 'error',
            'mensaje' => 'Error al eliminar el registro: ' . mysqli_error($conexion)
        );
        echo json_encode($json);
        exit;
    }

    // 4. Verificar mediante mysqli_affected_rows si realmente se eliminó algún registro (si el ID existía en la BD)
    if (mysqli_affected_rows($conexion) > 0) {
        $json[] = array(
            'estado' => 'exito',
            'mensaje' => 'Registro eliminado correctamente',
            'id' => $id
        );
    } else {
        // La consulta se ejecutó bien, pero el ID no correspondía a ningún usuario existente
        $json[] = array(
            'estado' => 'error',
            'mensaje' => 'No se encontró ningún usuario con ese ID para eliminar.'
        );
    }

    // Codifica y envía el resultado final en formato JSON hacia la petición AJAX
    echo json_encode($json);

} else {
    // Si no se recibió el parámetro ID en la solicitud POST
    $json[] = array(
        'estado' => 'error',
        'mensaje' => 'Falta el parámetro ID en la solicitud.'
    );
    echo json_encode($json);
}
?>