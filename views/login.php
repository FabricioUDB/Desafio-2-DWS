<?php
session_start();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar Sesión - Finanzas</title>
    <link rel="stylesheet" href="../public/css/login.css">
</head>
<body>
    <div class="container">
        <div class="left-panel">
            <h1>Sistema de control de finanzas</h1>
            <p>Inicia sesión para acceder al sistema</p>
            
        </div>

        <div class="right-panel">
            <h2>¡Hola!</h2>
            <h3><span>Buenos día</span></h3>
            <p><strong>Inicia sesión en tu cuenta</strong></p>

            <form action="../controllers/AuthController.php" method="post">
                <input type="text" name="username" placeholder="Correo o nombre de usuario" required>
                <input type="password" name="password" placeholder="Contraseña" required>

                <div class="options">
                    <label><input type="checkbox"> Recordarme</label>
                    <a href="#">¿Olvidaste tu contraseña?</a>
                </div>

                <button type="submit" name="login">Iniciar sesión</button>
            </form>

            <p class="create">¿No tienes cuenta? <a href="#">Crear cuenta</a></p>
        </div>
    </div>
</body>
</html>
