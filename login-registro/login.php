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
            <p class="subtitle">Inicia sesión para comenzar la diversión</p>

            <!-- Formulario de login -->
            <form action="process_login.php" method="POST" class="form-login">
                <div class="input-group">
                    <label for="usuario">Usuario</label>
                    <input 
                        type="text" 
                        id="usuario" 
                        name="usuario" 
                        placeholder="Ingresa tu nombre de usuario" 
                        required
                    >
                </div>
                <div class="input-group">
                    <label for="password">Contraseña</label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        placeholder="Ingresa tu contraseña" 
                        required
                    >
                </div>
                
                <div class="extra-options">
                    <label class="remember">
                        <input type="checkbox" name="remember">
                        Recuérdame
                    </label>
                    <a href="#" class="forgot-pass">¿Olvidaste tu contraseña?</a>
                </div>
                
                <button type="submit" class="btn-login">Iniciar Sesión</button>
            </form>

            <div class="social-login">
                <p>O inicia sesión con:</p>
                <button class="btn-social facebook">
                    <img src="https://cdn-icons-png.flaticon.com/512/124/124010.png" alt="Facebook">
                    Facebook
                </button>
                <button class="btn-social google">
                    <img src="https://img.icons8.com/?size=100&id=17949&format=png&color=000000" alt="Twitter">
                    Google
                </button>
            </div>

            <p class="register">
                ¿No tienes cuenta? 
                <a href="register.php">Regístrate aquí</a>
            </p>
        </div>
    </div>
</body>
</html>
