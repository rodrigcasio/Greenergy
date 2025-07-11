<?php
session_start(); // Inicia la sesión para el usuario
require_once '../includes/config.php';

$error = '';
$success = '';

// --- PROCESAR EL FORMULARIO DE REGISTRO ---
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    // --- VALIDACIÓN DE CAMPOS DEL FORMULARIO ---
    if (empty($name) || empty($email) || empty($password)) {
        $error = 'Todos los campos son obligatorios.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Por favor, introduce un email válido.';
    } elseif (strlen($password) < 6) {
        $error = 'La contraseña debe tener al menos 6 caracteres.';
    } elseif ($password !== $confirm_password) {
        $error = 'Las contraseñas no coinciden.';
    } else {
        try {
            $pdo = getDBConnection();
            
            // Verificar si el email ya está registrado
            $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->execute([$email]);
            
            if ($stmt->rowCount() > 0) {
                $error = 'Este email ya está registrado.';
            } else {
                // --- HASHEAR LA CONTRASEÑA Y GUARDAR EL USUARIO ---
                $hashed_password = password_hash($password, PASSWORD_DEFAULT); // Cifra la contraseña de forma segura
                $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
                $stmt->execute([$name, $email, $hashed_password]);
                
                // Enviar email de bienvenida
                $email_sent = sendWelcomeEmail($name, $email);
                
                $success = 'Registro exitoso. Ahora puedes iniciar sesión.';
                if ($email_sent) {
                    $success .= ' Se ha enviado un email de bienvenida a tu correo.';
                }
            }
        } catch(PDOException $e) {
            $error = 'Error en el registro: ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - Evaluación Ambiental</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="container">
        <div class="logo-header">
            <img src="../assets/greenergyLogo.jpeg" alt="Greenergy Logo" class="company-logo">
        </div>
        <div class="form-container">
            <h2>Registro de Usuario</h2>
            
            <?php if ($error): ?>
                <div class="alert alert-error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <div class="form-group">
                    <label for="name">Nombre completo:</label>
                    <input type="text" id="name" name="name" value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="password">Contraseña:</label>
                    <input type="password" id="password" name="password" required>
                </div>
                
                <div class="form-group">
                    <label for="confirm_password">Confirmar contraseña:</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                </div>
                
                <button type="submit" class="btn btn-primary">Registrarse</button>
            </form>
            
            <div class="form-footer">
                <p>¿Ya tienes una cuenta? <a href="login.php">Iniciar sesión</a></p>
                <p><a href="../index.php">Volver al inicio</a></p>
            </div>
        </div>
    </div>
</body>
</html> 