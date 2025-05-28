<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Casino Login</title>
    <!-- Enlazar la hoja de estilos -->
    <link rel="stylesheet" href="../CSS/login.css">
    <!-- Fuente de Google Fonts (opcional) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link 
        href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600&display=swap" 
        rel="stylesheet"
    >
</head>
<body>
<div class="background-overlay"></div>
    <div class="container">
        <!-- Sección para la imagen de fondo y el overlay -->
        
        
        <!-- Contenedor del formulario -->
        <div class="login-box">
            <h1>Bienvenido a <span><br>Mola Casino</span></h1>
            <?php
// process_register.php
include("db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $conn->real_escape_string($_POST["usuario"]);
    $email   = $conn->real_escape_string($_POST["email"]);
    $password = $_POST["password"];
    $confirmar = $_POST["confirmar"];

    // Verificar que las contraseñas sean iguales
    if ($password !== $confirmar) {
        die("Las contraseñas no coinciden. <a href='register.php'>Volver</a>");
    }

    // Verificar si el usuario ya existe
    $sql = "SELECT * FROM usuarios WHERE usuario='$usuario' OR email='$email' LIMIT 1";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        die("El usuario o email ya existe. <a href='register.php'>Volver</a>");
    }

    // Encriptar la contraseña (usar password_hash() es recomendable en producción)
    $password_hashed = md5($password);

    // Insertar el nuevo usuario en la base de datos
    $sql = "INSERT INTO usuarios (usuario, email, password) VALUES ('$usuario', '$email', '$password_hashed')";
    if ($conn->query($sql) === TRUE) {
        echo "Registro exitoso. <br><a href='login.php'>Inicia sesión</a>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
} else {
    header("Location: register.php");
    exit();
}
?>
        </div>
    </div>
</body>
</html>
