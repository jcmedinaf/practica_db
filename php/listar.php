<?php
/**
 * =====================================================================
 * ARCHIVO: listar.php
 * DESCRIPCIÓN: Script backend encargado de consultar todos los registros
 * de la tabla usuarios y retornar un arreglo JSON para la interfaz web.
 * PROYECTO: Sistema CRUD en PHP, MySQLi y AJAX (Fines educativos)
 * AUTOR: Juan Carlos Medina
 * =====================================================================
 */

// Carga la conexión usando la ruta absoluta de la carpeta actual mediante __DIR__
include_once(__DIR__ . '/conexion.php');

// Si por alguna razón el archivo de conexión falla, detenemos la ejecución y devolvemos un JSON de error
if (!isset($conexion) || !$conexion) {
    header('Content-Type: application/json');
    echo json_encode(array(array('estado' => 'error', 'mensaje' => 'Error crítico: No se pudo conectar a la base de datos.')));
    exit;
}

// Indicar estrictamente al navegador que la respuesta HTTP entregada será un JSON válido
header('Content-Type: application/json');

// Inicializa un arreglo vacío que contendrá todos los registros obtenidos
$json = array();

// Consulta SQL para seleccionar los campos requeridos de la tabla usuarios
$query = "SELECT id, nombre, apellido, correo FROM usuarios";
$result = mysqli_query($conexion, $query);

// Verifica si la consulta se ejecutó de forma exitosa
if ($result) {
    // Recorre cada fila devuelta por la base de datos como un arreglo asociativo
    while ($row = mysqli_fetch_assoc($result)) {
        // Almacena cada usuario mapeando sus campos a una estructura JSON clara
        $json[] = array(
            'id' => $row['id'],
            'nombre' => $row['nombre'],
            'apellido' => $row['apellido'],
            'correo' => $row['correo']
        );
    }
}

// Retornar siempre el arreglo codificado como JSON hacia la petición AJAX de JavaScript
echo json_encode($json);
?>