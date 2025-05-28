<?php
// db.php

$host = "localhost";
$usuario = "root";        // Cambia a tu usuario de la base de datos
$password = "";      // Cambia a tu contraseña
$nombreDB = "casino";

// Crear conexión
$conn = new mysqli($host, $usuario, $password, $nombreDB);

// Verificar conexión
if ($conn->connect_error) {
    die("La conexión falló: " . $conn->connect_error);
}

// Para forzar que el contenido se guarde como UTF-8
$conn->set_charset("utf8");
?>
