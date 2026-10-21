<?php

$servidor = "localhost";
$usuario  = "root";
$password = "";
$base_datos = "practica_db";

$conexion = mysqli_connect($servidor, $usuario, $password, $base_datos);

mysqli_set_charset($conexion, "utf8mb4");

if(!$conexion){
    die("Falló la conexión: " . mysqli_connect_error());
}else{
    //echo "conectado";
}

?>