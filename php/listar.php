<?php

include_once(__DIR__ . '/conexion.php');

if(!isset($conexion) || !$conexion){
    header('Content-Type: application/json');
    echo json_encode(array(array('estado' => 'error', 'mensaje' => 'Error de conexión')));
    exit;
}

header('Content-Type: application/json');

$json = array();

$query = "SELECT id, nombre, apellido, correo FROM usuarios";
$result = mysqli_query($conexion, $query);

if($result){
    while($row = mysqli_fetch_assoc($result)){
        $json[] = array(
            'id' => $row['id'],
            'nombre' => $row['nombre'],
            'apellido' => $row['apellido'],
            'correo' => $row['correo']
        );

    }

}

echo json_encode($json);

?>