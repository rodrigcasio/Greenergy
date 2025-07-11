<?php
session_start(); // Inicia la sesión para el usuario
require_once '../includes/config.php';

// --- VERIFICAR SI EL USUARIO ESTÁ LOGUEADO ---
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];

// --- OBTENER LOS RESULTADOS DE LA EVALUACIÓN DEL USUARIO ---
try {
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("SELECT * FROM assessment_results WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $assessment = $stmt->fetch();
    
    if (!$assessment) {
        header('Location: dashboard.php');
        exit();
    }
} catch(PDOException $e) {
    $error = 'Error al cargar los resultados: ' . $e->getMessage();
}

// --- CALCULAR CATEGORÍA Y MENSAJE SEGÚN EL PUNTAJE ---
$max_score = 50; // Máximo puntaje posible (10 preguntas * 5 puntos)
$score_percentage = ($assessment['total_score'] / $max_score) * 100;

if ($score_percentage >= 80) {
    $category = 'Excelente';
    $category_class = 'excellent';
    $message = '¡Felicitaciones! Tu impacto ambiental es muy bajo. Continúa con estas excelentes prácticas.';
} elseif ($score_percentage >= 60) {
    $category = 'Bueno';
    $category_class = 'good';
    $message = 'Buen trabajo. Tu impacto ambiental es moderado. Hay algunas áreas donde puedes mejorar.';
} elseif ($score_percentage >= 40) {
    $category = 'Regular';
    $category_class = 'regular';
    $message = 'Tu impacto ambiental es moderado-alto. Considera implementar más prácticas sostenibles.';
} else {
    $category = 'Necesita Mejora';
    $category_class = 'needs-improvement';
    $message = 'Tu impacto ambiental es alto. Te recomendamos revisar tus hábitos y considerar cambios más sostenibles.';
}

$questions = [
    1 => 'Método principal de transporte',
    2 => 'Viajes en avión',
    3 => 'Personas en el hogar',
    4 => 'Consumo de productos cárnicos',
    5 => 'Hábitos de consumo eléctrico',
    6 => 'Fuente de energía para calefacción',
    7 => 'Reciclaje y compostaje',
    8 => 'Adquisición de productos nuevos',
    9 => 'Compra de alimentos locales',
    10 => 'Tipo de vehículo personal'
];

$answer_labels = [
    'a' => 'Muy sostenible',
    'b' => 'Sostenible',
    'c' => 'Moderado',
    'd' => 'Menos sostenible',
    'e' => 'Poco sostenible',
    'f' => 'No sostenible'
];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultados - Greenergy</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="container">
        <div class="logo-header">
            <img src="../assets/greenergyLogo.jpeg" alt="Greenergy Logo" class="company-logo">
        </div>
        <header class="results-header">
            <h1>Resultados de Greenergy</h1>
            <p>Usuario: <?php echo htmlspecialchars($user_name); ?></p>
            <p>Fecha: <?php echo date('d/m/Y H:i', strtotime($assessment['completed_at'])); ?></p>
        </header>
        
        <?php if (isset($error)): ?>
            <div class="alert alert-error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <div class="results-summary">
            <div class="score-card <?php echo $category_class; ?>">
                <h2>Puntuación Total</h2>
                <div class="score-display">
                    <span class="score-number"><?php echo $assessment['total_score']; ?></span>
                    <span class="score-max">/ <?php echo $max_score; ?></span>
                </div>
                <div class="score-percentage"><?php echo round($score_percentage, 1); ?>%</div>
                <div class="category"><?php echo $category; ?></div>
            </div>
            
            <div class="message-card">
                <h3>Evaluación</h3>
                <p><?php echo $message; ?></p>
            </div>
        </div>
        
        <div class="detailed-results">
            <h3>Resultados Detallados por Pregunta</h3>
            
            <div class="questions-breakdown">
                <?php for ($i = 1; $i <= 10; $i++): ?>
                    <?php 
                    $answer = $assessment["q{$i}_answer"];
                    $score = ['a' => 5, 'b' => 4, 'c' => 3, 'd' => 2, 'e' => 1, 'f' => 0][$answer];
                    $score_class = $score >= 4 ? 'high-score' : ($score >= 2 ? 'medium-score' : 'low-score');
                    ?>
                    <div class="question-result <?php echo $score_class; ?>">
                        <div class="question-info">
                            <h4>Pregunta <?php echo $i; ?></h4>
                            <p><?php echo $questions[$i]; ?></p>
                        </div>
                        <div class="answer-info">
                            <span class="answer-label"><?php echo $answer_labels[$answer]; ?></span>
                            <span class="answer-score"><?php echo $score; ?>/5</span>
                        </div>
                    </div>
                <?php endfor; ?>
            </div>
        </div>
        
        <div class="recommendations">
            <h3>Recomendaciones</h3>
            <div class="recommendations-content">
                <div class="recommendation-item">
                    <h4>🌱 Transporte Sostenible</h4>
                    <p>Considera usar transporte público, bicicleta o caminar para desplazamientos cortos.</p>
                </div>
                <div class="recommendation-item">
                    <h4>💡 Eficiencia Energética</h4>
                    <p>Apaga luces y electrodomésticos cuando no los uses, y considera cambiar a LED.</p>
                </div>
                <div class="recommendation-item">
                    <h4>♻️ Reducir, Reutilizar, Reciclar</h4>
                    <p>Separa tus residuos y considera el compostaje para residuos orgánicos.</p>
                </div>
                <div class="recommendation-item">
                    <h4>🥗 Alimentación Sostenible</h4>
                    <p>Reduce el consumo de carne y prioriza productos locales y de temporada.</p>
                </div>
                <div class="recommendation-item">
                    <h4>🛒 Consumo Responsable</h4>
                    <p>Compra solo lo necesario y prioriza productos duraderos y sostenibles.</p>
                </div>
            </div>
        </div>
        
        <div class="actions">
            <a href="dashboard.php" class="btn btn-primary">Volver al Dashboard</a>
            <a href="logout.php" class="btn btn-secondary">Cerrar Sesión</a>
        </div>
    </div>
</body>
</html> 