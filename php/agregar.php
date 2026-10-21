<?php

include_once(__DIR__ . '/conexion.php');

if(!isset($conexion) || !$conexion){
    header('Content-Type: application/json');
    echo json_encode(array(array('estado' => 'error', 'mensaje' => 'Error de conexión')));
    exit;
}

header('Content-Type: application/json');

$json = array();


if(isset($_POST['nombre'], $_POST['apellido'], $_POST['correo'], $_POST['clave'])){

    $nombre   = mysqli_real_escape_string($conexion, trim($_POST['nombre']));
    $apellido = mysqli_real_escape_string($conexion, trim($_POST['apellido'])); 
    $correo   = mysqli_real_escape_string($conexion, trim($_POST['correo']));
    $clave_original = mysqli_real_escape_string($conexion, trim($_POST['clave']));

    if(empty($nombre) || empty($apellido) || empty($correo) || empty($clave_original)){
        $json[] = array(
            'estado'  => 'error',
            'mensaje' => 'datos incompletos'
        );
        echo json_encode($json);
        exit;
    }

    $clave_hash = password_hash($clave_original, PASSWORD_DEFAULT);
    $clave_segura = mysqli_real_escape_string($conexion, $clave_hash);

    $query = "INSERT INTO usuarios (nombre, apellido, correo, clave) 
              VALUES ('$nombre', '$apellido', '$correo', '$clave_segura')";
    $result = mysqli_query($conexion, $query); 

    if(!$result){
        $json[] = array(
            'estado'  => 'error',
            'mensaje' => 'Error al guardar datos: ' . mysqli_error($conexion)
        );
        echo json_encode($json);
        exit;
    }else{
        $ultimo_id = mysqli_insert_id($conexion);

        $json[] = array(
            'estado'  => 'success',
            'mensaje' => 'datos guardados',
            'id'      => $ultimo_id,
            'nombre'  => $nombre,
            'apellido' => $apellido,
            'correo'   => $correo,
            'clave_original' => $clave_original,
            'clave_segura'   => $clave_segura

        );
        echo json_encode($json);
        exit;    

    }

}else{
    $json[] = array(
        'estado'  => 'error',
        'mensaje' => 'datos faltante en la solicitud',
            'nombre'  => $_POST['nombre'],
            'apellido' => $_POST['apellido'],
            'correo'   => $_POST['correo'],
            'clave_original' => $_POST['clave']
    );
    echo json_encode($json);
    exit;
}

?>