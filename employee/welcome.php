<?php
session_start(); // Inicia la sesión para el usuario
require_once '../includes/config.php';

// --- REDIRECCIONAR SI EL USUARIO YA INICIÓ SESIÓN ---
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
    <title>Empleado - Greenergy</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="container">
        <div class="logo-header">
            <img src="../assets/greenergyLogo.jpeg" alt="Greenergy Logo" class="company-logo">
        </div>
        <div class="welcome-section">
            <h1>👥 Área de Empleados</h1>
            <p>Bienvenido al sistema de evaluación ambiental para empleados. 
               Registra tu información y completa la evaluación ambiental.</p>
            
            <div class="action-buttons">
                <!-- Botones para iniciar sesión o registrarse -->
                <a href="login.php" class="btn btn-primary">Iniciar Sesión</a>
                <a href="register.php" class="btn btn-secondary">Registrarse</a>
            </div>
            
            <div class="form-footer">
                <p><a href="../index.php">← Volver al inicio</a></p>
            </div>
        </div>
    </div>
</body>
</html> 