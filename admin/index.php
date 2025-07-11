<?php
session_start();
require_once '../includes/config.php';

// Simple admin authentication (in production, use proper authentication)
$admin_password = 'admin123'; // Change this in production

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($_POST['password'] === $admin_password) {
        $_SESSION['admin_logged_in'] = true;
    } else {
        $error = 'Contraseña incorrecta';
    }
}

if (!isset($_SESSION['admin_logged_in'])) {
    ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Admin - Greenergy</title>
        <link rel="stylesheet" href="../css/style.css">
    </head>
    <body>
        <div class="container">
            <div class="form-container">
                <h2>Acceso Administrador</h2>
                
                <?php if (isset($error)): ?>
                    <div class="alert alert-error"><?php echo $error; ?></div>
                <?php endif; ?>
                
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="password">Contraseña de administrador:</label>
                        <input type="password" id="password" name="password" required>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Acceder</button>
                </form>
                
                <div class="form-footer">
                    <p><a href="../index.php">Volver al inicio</a></p>
                </div>
            </div>
        </div>
    </body>
    </html>
    <?php
    exit();
}

// Get statistics
try {
    $pdo = getDBConnection();
    
    // Total users
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM users");
    $total_users = $stmt->fetch()['total'];
    
    // Completed assessments
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM assessment_results");
    $completed_assessments = $stmt->fetch()['total'];
    
    // Average score
    $stmt = $pdo->query("SELECT AVG(total_score) as avg_score FROM assessment_results");
    $avg_score = round($stmt->fetch()['avg_score'], 1);
    
    // Score distribution
    $stmt = $pdo->query("SELECT 
        CASE 
            WHEN total_score >= 40 THEN 'Excelente (40-50)'
            WHEN total_score >= 30 THEN 'Bueno (30-39)'
            WHEN total_score >= 20 THEN 'Regular (20-29)'
            ELSE 'Necesita Mejora (0-19)'
        END as category,
        COUNT(*) as count
        FROM assessment_results 
        GROUP BY category
        ORDER BY total_score DESC");
    $score_distribution = $stmt->fetchAll();
    
    // All results with user info
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
    
} catch(PDOException $e) {
    $error = 'Error al cargar estadísticas: ' . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Administrativo - Greenergy</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="container">
        <header class="dashboard-header">
            <h1>Panel Administrativo</h1>
            <div>
                <a href="../index.php" class="btn btn-secondary">Volver al Inicio</a>
                <a href="logout.php" class="btn btn-secondary">Cerrar Sesión</a>
            </div>
        </header>
        
        <?php if (isset($error)): ?>
            <div class="alert alert-error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <div class="stats-grid">
            <div class="stat-card">
                <h3>Total de Usuarios</h3>
                <div class="stat-number"><?php echo $total_users; ?></div>
            </div>
            
            <div class="stat-card">
                <h3>Evaluaciones Completadas</h3>
                <div class="stat-number"><?php echo $completed_assessments; ?></div>
            </div>
            
            <div class="stat-card">
                <h3>Puntuación Promedio</h3>
                <div class="stat-number"><?php echo $avg_score; ?>/50</div>
            </div>
            
            <div class="stat-card">
                <h3>Tasa de Completitud</h3>
                <div class="stat-number"><?php echo $total_users > 0 ? round(($completed_assessments / $total_users) * 100, 1) : 0; ?>%</div>
            </div>
        </div>
        
        <div class="score-distribution">
            <h3>Distribución de Puntuaciones</h3>
            <div class="distribution-grid">
                <?php foreach ($score_distribution as $dist): ?>
                    <div class="distribution-item">
                        <h4><?php echo $dist['category']; ?></h4>
                        <div class="distribution-count"><?php echo $dist['count']; ?> usuarios</div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        
        <div class="all-results">
            <h3>Todos los Resultados</h3>
            <div class="results-table-container">
                <table class="results-table">
                    <thead>
                        <tr>
                            <th>Usuario</th>
                            <th>Email</th>
                            <th>Puntuación</th>
                            <th>Fecha</th>
                            <th>Detalles</th>
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
                                <td><?php echo date('d/m/Y H:i', strtotime($result['completed_at'])); ?></td>
                                <td>
                                    <button class="btn btn-secondary btn-small" onclick="showDetails('<?php echo htmlspecialchars($result['name']); ?>', <?php echo htmlspecialchars(json_encode($result)); ?>)">
                                        Ver Detalles
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Modal for detailed results -->
    <div id="detailsModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2 id="modalTitle">Detalles del Usuario</h2>
            <div id="modalContent"></div>
        </div>
    </div>
    
    <script>
        function showDetails(userName, data) {
            const modal = document.getElementById('detailsModal');
            const modalTitle = document.getElementById('modalTitle');
            const modalContent = document.getElementById('modalContent');
            
            modalTitle.textContent = 'Detalles de ' + userName;
            
            const questions = [
                'Método principal de transporte',
                'Viajes en avión',
                'Personas en el hogar',
                'Consumo de productos cárnicos',
                'Hábitos de consumo eléctrico',
                'Fuente de energía para calefacción',
                'Reciclaje y compostaje',
                'Adquisición de productos nuevos',
                'Compra de alimentos locales',
                'Tipo de vehículo personal'
            ];
            
            const answerLabels = {
                'a': 'Muy sostenible',
                'b': 'Sostenible',
                'c': 'Moderado',
                'd': 'Menos sostenible',
                'e': 'Poco sostenible',
                'f': 'No sostenible'
            };
            
            let content = '<div class="user-details">';
            content += '<p><strong>Email:</strong> ' + data.email + '</p>';
            content += '<p><strong>Puntuación total:</strong> ' + data.total_score + '/50</p>';
            content += '<p><strong>Fecha de evaluación:</strong> ' + new Date(data.completed_at).toLocaleString('es-ES') + '</p>';
            
            content += '<h3>Respuestas Detalladas:</h3>';
            content += '<div class="answers-grid">';
            
            for (let i = 1; i <= 10; i++) {
                const answer = data['q' + i + '_answer'];
                const score = {'a': 5, 'b': 4, 'c': 3, 'd': 2, 'e': 1, 'f': 0}[answer];
                const scoreClass = score >= 4 ? 'high-score' : (score >= 2 ? 'medium-score' : 'low-score');
                
                content += '<div class="answer-item ' + scoreClass + '">';
                content += '<h4>Pregunta ' + i + '</h4>';
                content += '<p>' + questions[i-1] + '</p>';
                content += '<p><strong>Respuesta:</strong> ' + answerLabels[answer] + ' (' + score + '/5)</p>';
                content += '</div>';
            }
            
            content += '</div></div>';
            
            modalContent.innerHTML = content;
            modal.style.display = 'block';
        }
        
        // Close modal
        document.querySelector('.close').onclick = function() {
            document.getElementById('detailsModal').style.display = 'none';
        }
        
        window.onclick = function(event) {
            const modal = document.getElementById('detailsModal');
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        }
    </script>
</body>
</html>

<style>
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.stat-card {
    background: white;
    border-radius: 15px;
    padding: 25px;
    text-align: center;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
}

.stat-card h3 {
    color: #2c3e50;
    margin-bottom: 15px;
    font-size: 1.1em;
}

.stat-number {
    font-size: 2.5em;
    font-weight: bold;
    color: #667eea;
}

.score-distribution, .all-results {
    background: white;
    border-radius: 15px;
    padding: 30px;
    margin-bottom: 30px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
}

.distribution-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin-top: 20px;
}

.distribution-item {
    padding: 20px;
    border-radius: 10px;
    background: #f8f9fa;
    text-align: center;
}

.distribution-item h4 {
    color: #2c3e50;
    margin-bottom: 10px;
}

.distribution-count {
    font-size: 1.5em;
    font-weight: bold;
    color: #667eea;
}

.results-table-container {
    overflow-x: auto;
}

.results-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

.results-table th,
.results-table td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #e1e8ed;
}

.results-table th {
    background: #f8f9fa;
    font-weight: 600;
    color: #2c3e50;
}

.score-badge {
    padding: 5px 10px;
    border-radius: 15px;
    font-size: 0.9em;
    font-weight: bold;
    color: white;
}

.score-badge.excellent {
    background: #48bb78;
}

.score-badge.good {
    background: #4299e1;
}

.score-badge.regular {
    background: #ed8936;
}

.score-badge.needs-improvement {
    background: #e53e3e;
}

.btn-small {
    padding: 8px 15px;
    font-size: 0.9em;
}

/* Modal styles */
.modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
}

.modal-content {
    background-color: white;
    margin: 5% auto;
    padding: 30px;
    border-radius: 15px;
    width: 90%;
    max-width: 800px;
    max-height: 80vh;
    overflow-y: auto;
    position: relative;
}

.close {
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
    cursor: pointer;
    position: absolute;
    right: 20px;
    top: 15px;
}

.close:hover {
    color: #000;
}

.user-details {
    margin-top: 20px;
}

.answers-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 15px;
    margin-top: 20px;
}

.answer-item {
    padding: 15px;
    border-radius: 8px;
    border: 2px solid;
}

.answer-item.high-score {
    border-color: #48bb78;
    background: #f0fff4;
}

.answer-item.medium-score {
    border-color: #ed8936;
    background: #fffaf0;
}

.answer-item.low-score {
    border-color: #e53e3e;
    background: #fff5f5;
}

.answer-item h4 {
    color: #2c3e50;
    margin-bottom: 8px;
}

.answer-item p {
    color: #666;
    margin-bottom: 5px;
}

@media (max-width: 768px) {
    .stats-grid {
        grid-template-columns: 1fr;
    }
    
    .distribution-grid {
        grid-template-columns: 1fr;
    }
    
    .results-table {
        font-size: 0.9em;
    }
    
    .modal-content {
        width: 95%;
        margin: 10% auto;
    }
}
</style> 