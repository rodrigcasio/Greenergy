<?php
session_start();
require_once '../includes/config.php';

// Check if CTO is logged in
if (!isset($_SESSION['cto_logged_in'])) {
    header('Location: login.php');
    exit();
}

$cto_name = $_SESSION['cto_name'];

// Initialize default values
$total_employees = 0;
$completed_assessments = 0;
$avg_score = 0;
$total_co2_impact = 0;
$co2_distribution = [];
$all_results = [];

// Get statistics and data for graphs
try {
    $pdo = getDBConnection();
    
    // Total employees
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM users");
    $total_employees = $stmt->fetch()['total'] ?? 0;
    
    // Completed assessments
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM assessment_results");
    $completed_assessments = $stmt->fetch()['total'] ?? 0;
    
    // Average score
    $stmt = $pdo->query("SELECT AVG(total_score) as avg_score FROM assessment_results");
    $avg_score_result = $stmt->fetch();
    $avg_score = $avg_score_result['avg_score'] ? round($avg_score_result['avg_score'], 1) : 0;
    
    // CO2 impact calculation (based on scores)
    $stmt = $pdo->query("SELECT 
        CASE 
            WHEN total_score >= 40 THEN 'Bajo (0-2 ton CO2/año)'
            WHEN total_score >= 30 THEN 'Moderado (2-5 ton CO2/año)'
            WHEN total_score >= 20 THEN 'Alto (5-8 ton CO2/año)'
            ELSE 'Muy Alto (8+ ton CO2/año)'
        END as co2_category,
        COUNT(*) as count
        FROM assessment_results 
        GROUP BY co2_category
        ORDER BY 
            CASE co2_category
                WHEN 'Bajo (0-2 ton CO2/año)' THEN 1
                WHEN 'Moderado (2-5 ton CO2/año)' THEN 2
                WHEN 'Alto (5-8 ton CO2/año)' THEN 3
                WHEN 'Muy Alto (8+ ton CO2/año)' THEN 4
            END");
    $co2_distribution = $stmt->fetchAll();
    
    // Get all employee results for detailed analysis
    $stmt = $pdo->query("SELECT 
        u.name, 
        u.email, 
        ar.total_score, 
        ar.completed_at,
        ar.q1_answer, ar.q2_answer, ar.q3_answer, ar.q4_answer, ar.q5_answer,
        ar.q6_answer, ar.q7_answer, ar.q8_answer, ar.q9_answer, ar.q10_answer
        FROM assessment_results ar
        JOIN users u ON ar.user_id = u.id
        ORDER BY ar.completed_at DESC");
    $all_results = $stmt->fetchAll();
    
    // Calculate total CO2 impact
    $total_co2_impact = 0;
    foreach ($all_results as $result) {
        if ($result['total_score'] >= 40) {
            $total_co2_impact += 1; // Low impact
        } elseif ($result['total_score'] >= 30) {
            $total_co2_impact += 3.5; // Moderate impact
        } elseif ($result['total_score'] >= 20) {
            $total_co2_impact += 6.5; // High impact
        } else {
            $total_co2_impact += 10; // Very high impact
        }
    }
    
} catch(PDOException $e) {
    $error = 'Error al cargar datos: ' . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CTO Dashboard - Greenergy</title>
    <link rel="stylesheet" href="../css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="container">
        <header class="dashboard-header">
            <h1>👨‍💼 Dashboard CTO - Greenergy</h1>
            <div>
                <span class="cto-name">Bienvenido, <?php echo htmlspecialchars($cto_name); ?></span>
                <a href="logout.php" class="btn btn-secondary">Cerrar Sesión</a>
            </div>
        </header>
        
        <?php if (isset($error)): ?>
            <div class="alert alert-error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <div class="cto-stats-grid">
            <div class="stat-card">
                <h3>Total Empleados</h3>
                <div class="stat-number"><?php echo $total_employees; ?></div>
            </div>
            
            <div class="stat-card">
                <h3>Evaluaciones Completadas</h3>
                <div class="stat-number"><?php echo $completed_assessments; ?></div>
            </div>
            
            <div class="stat-card">
                <h3>Puntuación Promedio</h3>
                <div class="stat-number"><?php echo $avg_score; ?>/50</div>
            </div>
            
            <div class="stat-card co2-impact">
                <h3>Impacto CO2 Total</h3>
                <div class="stat-number"><?php echo round($total_co2_impact, 1); ?> ton/año</div>
            </div>
        </div>
        
        <?php if (!empty($all_results)): ?>
        <div class="graphs-section">
            <div class="graph-container">
                <h3>Distribución de Impacto CO2</h3>
                <canvas id="co2Chart" width="400" height="200"></canvas>
            </div>
            
            <div class="graph-container">
                <h3>Puntuaciones por Empleado</h3>
                <canvas id="scoresChart" width="400" height="200"></canvas>
            </div>
        </div>
        
        <div class="co2-analysis">
            <h3>📊 Análisis de Impacto Ambiental</h3>
            <div class="analysis-grid">
                <div class="analysis-card">
                    <h4>🚗 Transporte</h4>
                    <p>Impacto principal: <?php echo calculateTransportImpact($all_results); ?>% de empleados usan transporte no sostenible</p>
                </div>
                <div class="analysis-card">
                    <h4>⚡ Energía</h4>
                    <p>Consumo eléctrico: <?php echo calculateEnergyImpact($all_results); ?>% tienen hábitos de alto consumo</p>
                </div>
                <div class="analysis-card">
                    <h4>🥗 Alimentación</h4>
                    <p>Consumo de carne: <?php echo calculateFoodImpact($all_results); ?>% tienen dieta alta en productos cárnicos</p>
                </div>
                <div class="analysis-card">
                    <h4>♻️ Reciclaje</h4>
                    <p>Prácticas de reciclaje: <?php echo calculateRecyclingImpact($all_results); ?>% reciclan menos del 50%</p>
                </div>
            </div>
        </div>
        
        <div class="employee-results">
            <h3>📋 Resultados Detallados por Empleado</h3>
            <div class="results-table-container">
                <table class="results-table">
                    <thead>
                        <tr>
                            <th>Empleado</th>
                            <th>Email</th>
                            <th>Puntuación</th>
                            <th>Impacto CO2</th>
                            <th>Fecha</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($all_results as $result): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($result['name']); ?></td>
                                <td><?php echo htmlspecialchars($result['email']); ?></td>
                                <td>
                                    <span class="score-badge <?php echo $result['total_score'] >= 40 ? 'excellent' : ($result['total_score'] >= 30 ? 'good' : ($result['total_score'] >= 20 ? 'regular' : 'needs-improvement')); ?>">
                                        <?php echo $result['total_score']; ?>/50
                                    </span>
                                </td>
                                <td><?php echo calculateIndividualCO2($result['total_score']); ?> ton/año</td>
                                <td><?php echo date('d/m/Y H:i', strtotime($result['completed_at'])); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <script>
            // CO2 Distribution Chart
            const co2Ctx = document.getElementById('co2Chart').getContext('2d');
            new Chart(co2Ctx, {
                type: 'doughnut',
                data: {
                    labels: <?php echo json_encode(array_column($co2_distribution, 'co2_category')); ?>,
                    datasets: [{
                        data: <?php echo json_encode(array_column($co2_distribution, 'count')); ?>,
                        backgroundColor: ['#48bb78', '#4299e1', '#ed8936', '#e53e3e']
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
            
            // Employee Scores Chart
            const scoresCtx = document.getElementById('scoresChart').getContext('2d');
            new Chart(scoresCtx, {
                type: 'bar',
                data: {
                    labels: <?php echo json_encode(array_column($all_results, 'name')); ?>,
                    datasets: [{
                        label: 'Puntuación',
                        data: <?php echo json_encode(array_column($all_results, 'total_score')); ?>,
                        backgroundColor: '#667eea'
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 50
                        }
                    }
                }
            });
        </script>
        <?php else: ?>
        <div class="no-data-section">
            <div class="no-data-card">
                <h3>📊 No hay datos disponibles</h3>
                <p>Actualmente no hay empleados registrados o evaluaciones completadas.</p>
                <p>Los gráficos y análisis aparecerán automáticamente cuando los empleados completen sus evaluaciones ambientales.</p>
            </div>
        </div>
        <?php endif; ?>
        
        <div class="recommendations-section">
            <h3>🎯 Recomendaciones para Reducir Impacto CO2</h3>
            <div class="recommendations-grid">
                <div class="recommendation-card">
                    <h4>1. Transporte Sostenible</h4>
                    <p>Implementar programa de carpooling y subsidios para transporte público.</p>
                    <span class="impact-reduction">Reducción estimada: 2.5 ton CO2/año</span>
                </div>
                <div class="recommendation-card">
                    <h4>2. Eficiencia Energética</h4>
                    <p>Cambiar a iluminación LED y electrodomésticos eficientes en oficinas.</p>
                    <span class="impact-reduction">Reducción estimada: 1.8 ton CO2/año</span>
                </div>
                <div class="recommendation-card">
                    <h4>3. Alimentación Sostenible</h4>
                    <p>Ofrecer opciones vegetarianas en cafetería y promover días sin carne.</p>
                    <span class="impact-reduction">Reducción estimada: 1.2 ton CO2/año</span>
                </div>
                <div class="recommendation-card">
                    <h4>4. Programa de Reciclaje</h4>
                    <p>Implementar sistema de reciclaje integral y compostaje.</p>
                    <span class="impact-reduction">Reducción estimada: 0.8 ton CO2/año</span>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

<?php
function calculateTransportImpact($results) {
    if (empty($results)) return 0;
    $high_impact = 0;
    foreach ($results as $result) {
        if (in_array($result['q1_answer'], ['d', 'e', 'f'])) {
            $high_impact++;
        }
    }
    return round(($high_impact / count($results)) * 100, 1);
}

function calculateEnergyImpact($results) {
    if (empty($results)) return 0;
    $high_impact = 0;
    foreach ($results as $result) {
        if (in_array($result['q5_answer'], ['c', 'd'])) {
            $high_impact++;
        }
    }
    return round(($high_impact / count($results)) * 100, 1);
}

function calculateFoodImpact($results) {
    if (empty($results)) return 0;
    $high_impact = 0;
    foreach ($results as $result) {
        if (in_array($result['q4_answer'], ['d', 'e'])) {
            $high_impact++;
        }
    }
    return round(($high_impact / count($results)) * 100, 1);
}

function calculateRecyclingImpact($results) {
    if (empty($results)) return 0;
    $low_recycling = 0;
    foreach ($results as $result) {
        if (in_array($result['q7_answer'], ['c', 'd'])) {
            $low_recycling++;
        }
    }
    return round(($low_recycling / count($results)) * 100, 1);
}

function calculateIndividualCO2($score) {
    if ($score >= 40) return 1.0;
    elseif ($score >= 30) return 3.5;
    elseif ($score >= 20) return 6.5;
    else return 10.0;
}
?> 