<?php
/**
 * =====================================================================
 * ARCHIVO: conexion.php
 * DESCRIPCIÓN: Script encargado de establecer la conexión con el servidor 
 * de base de datos MySQL utilizando la extensión nativa MySQLi.
 * PROYECTO: Sistema CRUD en PHP, MySQLi y AJAX (Fines educativos)
 * AUTOR: Juan Carlos Medina
 * =====================================================================
 */

// Definición de las credenciales de acceso al servidor de base de datos local
$servidor   = "localhost";      // Dirección del servidor (generalmente localhost o 127.0.0.1)
$usuario    = "root";           // Nombre de usuario de MySQL (por defecto en XAMPP/Laragon es root)
$password   = "";               // Contraseña del usuario (por defecto en entornos locales suele estar vacía)
$base_datos = "practica_db2";    // Nombre de la base de datos a la que nos conectaremos

// Intentar la conexión con el servidor MySQL utilizando la función mysqli_connect
$conexion = mysqli_connect($servidor, $usuario, $password, $base_datos);

// Establecer el conjunto de caracteres a UTF-8 para evitar problemas con tildes y eñes
mysqli_set_charset($conexion, "utf8mb4");

// Validación de seguridad: Si la conexión falla, se interrumpe el script y se muestra un mensaje de error
if (!$conexion) {
    die("Falló la conexión: " . mysqli_connect_error());
}
?>