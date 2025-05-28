<!-- register.php -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro - Casino</title>
    <!-- Enlazar la hoja de estilos -->
    <link rel="stylesheet" href="../CSS/login.css">
    <!-- Fuente de Google Fonts (opcional) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link 
        href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600&display=swap" 
        rel="stylesheet"
    >
    <script>
        // Validación básica del formulario de registro
        function validarRegistro() {
            var usuario = document.getElementById("usuario").value;
            var email = document.getElementById("email").value;
            var password = document.getElementById("password").value;
            var confirmar = document.getElementById("confirmar").value;
            
            if(usuario === "" || email === "" || password === "" || confirmar === ""){
                alert("Todos los campos son obligatorios.");
                return false;
            }
            if(password !== confirmar){
                alert("Las contraseñas no coinciden.");
                return false;
            }
            return true;
        }
    </script>
</head>
<body>
<div class="background-overlay"></div>
<div class="container">
    <div class="login-box">
        <h1><span>Registro</span></h1>
        <form action="process_register.php" method="POST" class="form-login">
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
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" placeholder="Ingresa tu email" required>
                </div>

                <div class="input-group">
                <label for="password">Contraseña:</label>
                <input type="password" id="password" name="password" placeholder="Ingresa tu contraseña" required>
                </div>

                <div class="input-group">
                <label for="confirmar">Confirmar Contraseña:</label>
                <input type="password" id="confirmar" name="confirmar" placeholder="Vuleve a ingresar la contraseña" required>
                </div>


            <input class="btn-login" type="submit" value="Registrarse">
        </form>
        <div class="social-login">
                <p>O registrate con:</p>
                <button class="btn-social facebook">
                    <img src="https://cdn-icons-png.flaticon.com/512/124/124010.png" alt="Facebook">
                    Facebook
                </button>
                <button class="btn-social google">
                    <img src="https://img.icons8.com/?size=100&id=17949&format=png&color=000000" alt="Twitter">
                    Google
                </button>
            </div>
        <p class="register">¿Ya tienes cuenta? <a href="login.php">Inicia Sesión</a></p>
    </div>
    </div>
</body>
</html>
