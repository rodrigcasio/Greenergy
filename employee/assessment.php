<?php
session_start();
require_once '../includes/config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Check if user has already completed the assessment
try {
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("SELECT id FROM assessment_results WHERE user_id = ?");
    $stmt->execute([$user_id]);
    if ($stmt->fetch()) {
        header('Location: dashboard.php');
        exit();
    }
} catch(PDOException $e) {
    $error = 'Error al verificar el estado de la evaluación: ' . $e->getMessage();
}

// Process form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $answers = [];
    $total_score = 0;
    
    // Scoring system: a=5, b=4, c=3, d=2, e=1, f=0 (environmentally friendly to less friendly)
    $scores = [
        'a' => 5, 'b' => 4, 'c' => 3, 'd' => 2, 'e' => 1, 'f' => 0
    ];
    
    for ($i = 1; $i <= 10; $i++) {
        $answer = $_POST["q{$i}"] ?? '';
        if (empty($answer)) {
            $error = "Por favor, responde todas las preguntas.";
            break;
        }
        $answers["q{$i}_answer"] = $answer;
        $total_score += $scores[$answer] ?? 0;
    }
    
    if (!isset($error)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO assessment_results (user_id, q1_answer, q2_answer, q3_answer, q4_answer, q5_answer, q6_answer, q7_answer, q8_answer, q9_answer, q10_answer, total_score) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $user_id,
                $answers['q1_answer'],
                $answers['q2_answer'],
                $answers['q3_answer'],
                $answers['q4_answer'],
                $answers['q5_answer'],
                $answers['q6_answer'],
                $answers['q7_answer'],
                $answers['q8_answer'],
                $answers['q9_answer'],
                $answers['q10_answer'],
                $total_score
            ]);
            
            // Get user info for email
            $stmt = $pdo->prepare("SELECT name, email FROM users WHERE id = ?");
            $stmt->execute([$user_id]);
            $user = $stmt->fetch();
            
            // Calculate CO2 impact
            $co2_impact = calculateIndividualCO2($total_score);
            
            // Send completion email
            if ($user) {
                sendAssessmentCompletionEmail($user['name'], $user['email'], $total_score, $co2_impact);
            }
            
            header('Location: results.php');
            exit();
        } catch(PDOException $e) {
            $error = 'Error al guardar los resultados: ' . $e->getMessage();
        }
    }
}

$questions = [
    1 => [
        'question' => '¿Cuál es su método principal de transporte para sus desplazamientos diarios (por ejemplo, al trabajo, estudio u otras actividades recurrentes)?',
        'options' => [
            'a' => 'Caminar, bicicleta o patinete eléctrico',
            'b' => 'Transporte público (autobús, tren, metro)',
            'c' => 'Coche eléctrico o híbrido (uso personal)',
            'd' => 'Coche de gasolina/diésel (uso personal, generalmente solo/a)',
            'e' => 'Coche de gasolina/diésel (compartiendo coche/transporte colaborativo)',
            'f' => 'Motocicleta'
        ]
    ],
    2 => [
        'question' => 'En promedio, ¿cuántas horas semanales dedica a viajes personales en avión?',
        'options' => [
            'a' => '0 horas',
            'b' => 'Menos de 2 horas',
            'c' => 'Entre 2 y 5 horas',
            'd' => 'Entre 5 y 10 horas',
            'e' => 'Más de 10 horas (viajes de larga distancia muy frecuentes)'
        ]
    ],
    3 => [
        'question' => '¿Cuántas personas, incluido usted, residen habitualmente en su hogar (compartiendo espacio y servicios)?',
        'options' => [
            'a' => '1 persona',
            'b' => '2 personas',
            'c' => '3-4 personas',
            'd' => '5 o más personas'
        ]
    ],
    4 => [
        'question' => '¿Con qué frecuencia consume productos cárnicos (carne de res, cerdo, cordero, aves de corral)?',
        'options' => [
            'a' => 'Nunca (Dieta vegana o vegetariana)',
            'b' => 'Rara vez (1-2 veces por semana)',
            'c' => 'Algunas veces a la semana (3-4 veces)',
            'd' => 'Diariamente (en la mayoría de las comidas)',
            'e' => 'Varias veces al día'
        ]
    ],
    5 => [
        'question' => '¿Cómo describiría sus hábitos de consumo de electricidad en su hogar?',
        'options' => [
            'a' => 'Muy eficiente (uso de iluminación LED, desconexión de aparatos, uso mínimo de aire acondicionado/calefacción)',
            'b' => 'Consciente (apago luces, algunos electrodomésticos eficientes)',
            'c' => 'Promedio (no siempre presto atención al consumo, algunos electrodomésticos antiguos)',
            'd' => 'Alto consumo (dejo luces/electrónicos encendidos, uso intensivo de aire acondicionado/calefacción)'
        ]
    ],
    6 => [
        'question' => '¿Cuál es la fuente principal de energía utilizada para calentar su hogar durante los meses fríos (si aplica)?',
        'options' => [
            'a' => 'Energía renovable (solar, geotérmica)',
            'b' => 'Calefacción eléctrica (de la red)',
            'c' => 'Gas natural',
            'd' => 'Gasóleo/Propano',
            'e' => 'Leña/Carbón',
            'f' => 'No aplica / No uso calefacción'
        ]
    ],
    7 => [
        'question' => '¿Qué porcentaje de los residuos generados en su hogar suele reciclar o compostar?',
        'options' => [
            'a' => 'Casi todos (80-100%)',
            'b' => 'La mayoría (50-79%)',
            'c' => 'Algo (20-49%)',
            'd' => 'Muy poco o nada (0-19%)'
        ]
    ],
    8 => [
        'question' => '¿Con qué frecuencia adquiere prendas de vestir o dispositivos electrónicos nuevos?',
        'options' => [
            'a' => 'Rara vez (pocas veces al año, priorizando la durabilidad)',
            'b' => 'Ocasionalmente (cada pocos meses)',
            'c' => 'Regularmente (mensual, siguiendo tendencias o realizando actualizaciones frecuentes)',
            'd' => 'Muy frecuentemente (semanalmente/quincenalmente, adquiriendo siempre lo último)'
        ]
    ],
    9 => [
        'question' => '¿Prioriza la compra de alimentos de origen local o de temporada siempre que es posible?',
        'options' => [
            'a' => 'Siempre/La mayor parte del tiempo',
            'b' => 'A veces',
            'c' => 'Rara vez/Nunca',
            'd' => 'Cultivo mis propios alimentos'
        ]
    ],
    10 => [
        'question' => '¿Qué tipo de vehículo utiliza principalmente para su transporte personal (si posee uno)?',
        'options' => [
            'a' => 'No tengo/conduzco un vehículo personal',
            'b' => 'Vehículo Eléctrico (VE)',
            'c' => 'Vehículo Híbrido',
            'd' => 'Coche de gasolina pequeño/compacto (ej. sedán de 4 cilindros)',
            'e' => 'Coche de gasolina de tamaño medio (ej. SUV, sedán más grande)',
            'f' => 'Camioneta/SUV de gasolina grande'
        ]
    ]
];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Greenergy - Evaluación Ambiental</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="container">
        <header class="assessment-header">
            <h1>Greenergy</h1>
            <p>Responde las siguientes 10 preguntas sobre tus hábitos diarios y su impacto ambiental.</p>
        </header>
        
        <?php if (isset($error)): ?>
            <div class="alert alert-error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="POST" action="" class="assessment-form">
            <?php foreach ($questions as $q_num => $question_data): ?>
                <div class="question-container">
                    <h3>Pregunta <?php echo $q_num; ?></h3>
                    <p class="question-text"><?php echo $question_data['question']; ?></p>
                    
                    <div class="options-container">
                        <?php foreach ($question_data['options'] as $option => $text): ?>
                            <div class="option-item">
                                <input type="radio" id="q<?php echo $q_num; ?>_<?php echo $option; ?>" 
                                       name="q<?php echo $q_num; ?>" value="<?php echo $option; ?>" required>
                                <label for="q<?php echo $q_num; ?>_<?php echo $option; ?>">
                                    <span class="option-letter"><?php echo strtoupper($option); ?></span>
                                    <?php echo $text; ?>
                                </label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Enviar Evaluación</button>
                <a href="dashboard.php" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</body>
</html> 