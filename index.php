<?php
session_start();
require_once 'includes/config.php';

// Redirect if user is already logged in
if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit();
}

// Redirect if CTO is already logged in
if (isset($_SESSION['cto_logged_in'])) {
    header('Location: cto/dashboard.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Greenergy - Selección de Usuario</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <div class="logo-header">
            <img src="assets/greenergyLogo.jpeg" alt="Greenergy Logo" class="company-logo">
        </div>
        <div class="welcome-section">
            <h1>Greenergy</h1>
            <p>Bienvenido al sistema de evaluación ambiental de nuestra empresa. 
               Selecciona tu tipo de usuario para continuar.</p>
            
            <div class="user-type-selection">
                <div class="user-type-card">
                    <div class="user-type-icon">👨‍💼</div>
                    <h3>CTO</h3>
                    <p>Acceso administrativo para ver resultados y análisis de datos ambientales.</p>
                    <a href="cto/login.php" class="btn btn-primary">Acceder como CTO</a>
                </div>
                
                <div class="user-type-card">
                    <div class="user-type-icon">👥</div>
                    <h3>EMPLEADO</h3>
                    <p>Registro y evaluación ambiental para empleados de la empresa.</p>
                    <a href="employee/welcome.php" class="btn btn-secondary">Acceder como Empleado</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html> 