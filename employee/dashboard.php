<?php
session_start();
require_once '../includes/config.php';

// --- VERIFICAR SI EL USUARIO ESTÁ LOGUEADO ---
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];

// --- CONSULTAR SI EL USUARIO YA COMPLETÓ LA EVALUACIÓN ---
try {
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("SELECT * FROM assessment_results WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $assessment = $stmt->fetch();
} catch(PDOException $e) {
    $error = 'Error al verificar el estado de la evaluación: ' . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Greenergy</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="container">
        <div class="logo-header">
            <img src="../assets/greenergyLogo.jpeg" alt="Greenergy Logo" class="company-logo">
        </div>
        <header class="dashboard-header">
            <h1>Bienvenido, <?php echo htmlspecialchars($user_name); ?></h1>
            <a href="logout.php" class="btn btn-secondary">Cerrar Sesión</a>
        </header>
        
        <div class="dashboard-content">
            <?php if (isset($error)): ?>
                <div class="alert alert-error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <div class="assessment-status">
                <?php if ($assessment): ?>
                    <!-- Si el usuario ya completó la evaluación, muestra el resumen y acceso a resultados -->
                    <div class="completed-assessment">
                        <h3>✅ Evaluación Completada</h3>
                        <p>Has completado la evaluación ambiental el <?php echo date('d/m/Y H:i', strtotime($assessment['completed_at'])); ?></p>
                        <p>Tu puntuación total: <strong><?php echo $assessment['total_score']; ?> puntos</strong></p>
                        <a href="results.php" class="btn btn-primary">Ver Resultados Detallados</a>
                    </div>
                <?php else: ?>
                    <!-- Si el usuario no ha completado la evaluación, invita a realizarla -->
                    <div class="pending-assessment">
                        <h3>📋 Evaluación Pendiente</h3>
                        <p>La evaluación ambiental consta de 10 preguntas sobre tus hábitos diarios y su impacto ambiental.</p>
                        <p>Esta evaluación nos ayudará a entender mejor nuestro impacto ambiental colectivo como empresa.</p>
                        <a href="assessment.php" class="btn btn-primary">Comenzar Evaluación</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html> 