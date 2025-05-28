<?php
session_start();
if (!isset($_SESSION["usuario"])) {
    header("Location: login.php");
    exit();
}
$username = htmlspecialchars($_SESSION["usuario"]);

?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Dashboard - Casino Royale</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  
  <!-- Estilos en línea para el ejemplo -->
  <style>
    /* Reset y configuración básica */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }
    body {
      font-family: 'Montserrat', sans-serif;
      background: url('img/casino-bg.jpg') no-repeat center center/cover;
      color: #fff;
      min-height: 100vh;
    }
    /* Overlay para oscurecer el fondo y mejorar la legibilidad */
    .overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(15, 15, 15, 0.8);
      z-index: 1;
    }
    .container {
      position: relative;
      z-index: 2;
      padding: 20px;
      min-height: 100vh;
    }
    /* Header con título y botón de cerrar sesión */
    header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 30px;
    }
    header .titulo {
      font-size: 36px;
      font-weight: 600;
    }
    header form {
      margin: 0;
    }
    header .logout {
      background: #ffcc00;
      color: #333;
      border: none;
      padding: 10px 20px;
      border-radius: 4px;
      font-size: 16px;
      cursor: pointer;
      transition: background 0.3s;
    }
    header .logout:hover {
      background: #e6b800;
    }
    /* Mensaje de bienvenida */
    .welcome {
      margin-bottom: 20px;
      font-size: 20px;
    }
    /* Grid para los juegos */
    .games-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 20px;
    }
    .game-card {
      background: rgba(255, 255, 255, 0.1);
      border-radius: 8px;
      overflow: hidden;
      box-shadow: 0 0 20px rgba(0, 0, 0, 0.5);
      cursor: pointer;
      transition: transform 0.3s, box-shadow 0.3s;
    }
    .game-card:hover {
      transform: scale(1.05);
      box-shadow: 0 0 30px rgba(0, 0, 0, 0.7);
    }
    .game-card img {
      width: 100%;
      height: 150px;
      object-fit: cover;
    }
    .game-card .info {
      padding: 15px;
      text-align: center;
    }
    .game-card .info h3 {
      font-size: 24px;
      margin-bottom: 10px;
    }
    .game-card .info p {
      font-size: 14px;
      color: #ccc;
      margin-bottom: 15px;
    }
    .game-card .info .play-btn {
      padding: 10px 20px;
      background: #ffcc00;
      border: none;
      border-radius: 4px;
      color: #333;
      font-size: 16px;
      cursor: pointer;
      transition: background 0.3s;
    }
    .game-card .info .play-btn:hover {
      background: #e6b800;
    }
    /* Footer */
    footer {
      text-align: center;
      margin-top: 40px;
      font-size: 14px;
      color: #ccc;
    }
    /* Responsive en dispositivos móviles */
    @media (max-width: 480px) {
      header .titulo {
        font-size: 28px;
      }
      .welcome {
        font-size: 18px;
      }
    }
  </style>

  <!-- Fuentes de Google (opcional) -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600&display=swap" rel="stylesheet">
</head>
<body>
  <!-- Overlay de fondo -->
  <div class="overlay"></div>
  
  <!-- Contenedor principal -->
  <div class="container">
    <header>
      <div class="titulo">Mola Casino</div>
      <form action="logout.php" method="POST">
        <button type="submit" class="logout">Cerrar Sesión</button>
      </form>
    </header>
    
    <div class="welcome">Bienvenido, <?php echo $username; ?>. ¡Disfruta de nuestros juegos!</div>

    <!-- Sección de Juegos -->
    <div class="games-grid">
      <!-- Card Ruleta -->
      <div class="game-card" onclick="window.location.href='../ruleta/index.html';">
        <img src="https://bingopilar.com.ar/wp-content/uploads/2024/01/Ruleta-de-casino-1024x1024.jpg" alt="Ruleta">
        <div class="info">
          <h3>Ruleta</h3>
          <p>Gira la ruleta y apuesta por tu número de la suerte.</p>
          <button class="play-btn"><a href="../ruleta/index.html">Jugar</button></a>
        </div>
      </div>
      
      <!-- Card Tragaperras -->
      <div class="game-card" onclick="window.location.href='../tragaperras/index.html';">
        <img src="https://elperiodicodeyecla.com/wp-content/uploads/2019/03/trabaperras-1.jpg" alt="Tragaperras">
        <div class="info">
          <h3>Tragaperras</h3>
          <p>Prueba tu suerte en las máquinas tragaperras más emocionantes.</p>
          <button class="play-btn"><a href="../tragaperras/index.html">Jugar</button></a>
        </div>
      </div>
      
      <!-- Card para juegos próximos -->
      <div class="game-card">
        <img src="https://b2412870.smushcdn.com/2412870/wp-content/uploads/2023/07/Phone-English-Fotos-3-1024x512.jpg?lossy=1&strip=1&webp=1" alt="Próximamente">
        <div class="info">
          <h3>Próximamente</h3>
          <p>Más juegos emocionantes están en camino.</p>
          <button class="play-btn" disabled>En Desarrollo</button>
        </div>
      </div>
    </div>
    
    <footer>
      <p>&copy; <?php echo date("Y"); ?> Mola Casino. Todos los derechos reservados.</p>
    </footer>
  </div>
</body>
</html>
