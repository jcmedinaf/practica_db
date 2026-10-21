<?php

include_once(__DIR__ . '/conexion.php');

if(!isset($conexion) || !$conexion){
    header('Content-Type: application/json');
    echo json_encode(array(array('estado' => 'error', 'mensaje' => 'Error de conexión')));
    exit;
}

header('Content-Type: application/json');

$json = array();

if(isset($_GET['id'])){

    $id = (int)$_GET['id'];

    if($id <= 0){
        $json = array(
            'estado'  => 'error',
            'mensaje' => 'error id no válido'
        );
        echo json_encode($json);
        exit;
    }

    $query  = "SELECT id, nombre, apellido, correo FROM usuarios WHERE id=$id";
    $result = mysqli_query($conexion, $query);

    if(!$result){
        $json = array(
            'estado'  => 'error',
            'mensaje' => 'error al consultar a la base de datos' . mysqli_error($conexion)
        );
        echo json_encode($json);
        exit;
    }


    if($row = mysqli_fetch_assoc($result)){

        $json = array(
            'estado'   => 'success',
            'id'       => $row['id'],
            'nombre'   => $row['nombre'],
            'apellido' => $row['apellido'],
            'correo'   => $row['correo']

        );

    }else{
        $json = array(
            'estado' => 'error',
            'mensaje' => 'Error no encontró el registro'
    );
    }
    echo json_encode($json);

}else{
    $json = array(
        'estado' => 'error',
        'mensaje' => 'Error en el id'
    );

    echo json_encode($json);
}


?>