<?php require_once __DIR__ . '/../../config/app_config.php'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Clínica</title>
  <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
  <div class="container">
    <div class="left">
      <img src="../../assets/img/login.jpg" alt="Ilustración médica" class="medical-image">
    </div>

    <div class="login-box">
      <h2>¡Bienvenido!</h2>

      <?php
      session_start();
      if (isset($_SESSION['login_error'])) {
          echo '<p style="color: #fff; background:#e74c3c; padding:8px; border-radius:5px;">' . $_SESSION['login_error'] . '</p>';
          unset($_SESSION['login_error']);
      }
      ?>

      <form method="POST" action="../../app/controllers/login_action.php">
        <div class="form-group">
          <label for="usuario">Ingresa tu usuario:</label>
          <input type="text" id="usuario" name="usuario" placeholder="Usuario" required>
        </div>
        <div class="form-group">
          <label for="password">Ingresa tu contraseña:</label>
          <input type="password" id="password" name="password" placeholder="Contraseña" required>
        </div>
        <button type="submit" class="btn">Login</button>
      </form>

      <div class="forgot-password">
        <a href="#">¿Olvidaste tu contraseña?</a>
      </div>
    </div>
  </div>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const particlesContainer = document.getElementById('particles');
      const particleCount = 12;
      
      for (let i = 0; i < particleCount; i++) {
        const particle = document.createElement('div');
        particle.className = 'particle';
        const size = Math.random() * 6 + 2;
        particle.style.width = `${size}px`;
        particle.style.height = `${size}px`;
        particle.style.left = `${Math.random() * 100}%`;
        particle.style.top = `${Math.random() * 100}%`;
        particle.style.animationDelay = `${Math.random() * 5}s`;
        particlesContainer.appendChild(particle);
      }
    });
  </script>
</body>
</html>
