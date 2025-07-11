<?php
session_start();
require_once 'includes/config.php';

// Redirect if user is already logged in
if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Greenergy - Evaluación Ambiental</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <div class="welcome-section">
            <h1>Greenergy</h1>
            <p>Bienvenido a Greenergy, el sistema de evaluación ambiental de nuestra empresa. 
               Esta evaluación nos ayudará a entender mejor nuestro impacto ambiental colectivo.</p>
            
            <div class="action-buttons">
                <a href="login.php" class="btn btn-primary">Iniciar Sesión</a>
                <a href="register.php" class="btn btn-secondary">Registrarse</a>
            </div>
        </div>
    </div>
</body>
</html> 