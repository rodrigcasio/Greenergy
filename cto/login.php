<?php
session_start(); // Inicia la sesión para el CTO
require_once '../includes/config.php';

$error = '';

// --- CONTRASEÑA DEL CTO ---
// (En producción, almacena esto en una base de datos segura)
$cto_password = 'GreenergyCTO2024!';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $password = $_POST['password'];
    
    if (empty($password)) {
        $error = 'Por favor, introduce la contraseña de CTO.';
    } elseif ($password === $cto_password) {
        // Si la contraseña es correcta, inicia la sesión del CTO
        $_SESSION['cto_logged_in'] = true;
        $_SESSION['cto_name'] = 'CTO';
        header('Location: dashboard.php'); // Redirige al dashboard del CTO
        exit();
    } else {
        $error = 'Contraseña de CTO incorrecta.';
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CTO Login - Greenergy</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="container">
        <div class="logo-header">
            <img src="../assets/greenergyLogo.jpeg" alt="Greenergy Logo" class="company-logo">
        </div>
        <div class="form-container">
            <h2>👨‍💼 Acceso CTO</h2>
            <p class="cto-info">Acceso exclusivo para el Chief Technology Officer</p>
            
            <?php if ($error): ?>
                <div class="alert alert-error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <div class="form-group">
                    <label for="password">Contraseña de CTO:</label>
                    <input type="password" id="password" name="password" required>
                </div>
                
                <button type="submit" class="btn btn-primary">Acceder como CTO</button>
            </form>
            
            <div class="form-footer">
                <p><a href="../index.php">← Volver al inicio</a></p>
            </div>
        </div>
    </div>
</body>
</html> 