<?php
// process_login.php
session_start();
include("db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recoger los datos del formulario
    $usuario = $conn->real_escape_string($_POST["usuario"]);
    $password = $_POST["password"];

    // Por seguridad, se recomienda utilizar password_hash() y password_verify()
    // En este ejemplo se asume que el campo password se guardó con MD5 (no recomendado para producción)
    $password_hashed = md5($password);

    // Consulta para buscar el usuario
    $sql = "SELECT * FROM usuarios WHERE usuario='$usuario' AND password='$password_hashed' LIMIT 1";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        // Usuario encontrado, establecer variables de sesión
        $_SESSION["usuario"] = $usuario;
        header("Location: dashboard.php");
        exit();
    } else {
        echo "Usuario o contraseña incorrectos. <a href='login.php'>Volver</a>";
    }
} else {
    header("Location: login.php");
    exit();
}
?>
