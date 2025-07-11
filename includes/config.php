<?php
// Database configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'greenergy');
define('DB_USER', 'root');
define('DB_PASS', '');

// Email configuration
define('COMPANY_NAME', 'Greenergy');
define('COMPANY_EMAIL', 'noreply@greenergy.com');
define('COMPANY_WEBSITE', 'http://localhost:8000');

function getDBConnection() {
    try {
        $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch(PDOException $e) {
        die("Error de conexión: " . $e->getMessage());
    }
}

function sendWelcomeEmail($name, $email) {
    $subject = "¡Bienvenido a " . COMPANY_NAME . " - Evaluación Ambiental!";
    
    $message = "
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
            .header { background: linear-gradient(135deg, #667eea 0%, green 100%); color: white; padding: 30px; text-align: center; border-radius: 10px 10px 0 0; }
            .content { background: #f9f9f9; padding: 30px; border-radius: 0 0 10px 10px; }
            .btn { display: inline-block; background: #667eea; color: white; padding: 12px 25px; text-decoration: none; border-radius: 5px; margin: 20px 0; }
            .footer { text-align: center; margin-top: 30px; color: #666; font-size: 0.9em; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h1>🌱 ¡Bienvenido a " . COMPANY_NAME . "!</h1>
                <p>Evaluación de Impacto Ambiental</p>
            </div>
            <div class='content'>
                <h2>¡Hola " . htmlspecialchars($name) . "!</h2>
                <p>Te damos la bienvenida a nuestra plataforma de evaluación ambiental. Tu registro ha sido exitoso y ahora puedes participar en nuestra evaluación de impacto ambiental.</p>
                
                <h3>🎯 ¿Qué puedes hacer ahora?</h3>
                <ul>
                    <li><strong>Completar la evaluación:</strong> Accede a tu cuenta y realiza la evaluación de 10 preguntas sobre tus hábitos ambientales.</li>
                    <li><strong>Ver tus resultados:</strong> Recibe un análisis detallado de tu impacto ambiental y recomendaciones personalizadas.</li>
                    <li><strong>Contribuir al cambio:</strong> Ayuda a nuestra empresa a reducir su huella de carbono colectiva.</li>
                </ul>
                
                <p><strong>Próximos pasos:</strong></p>
                <ol>
                    <li>Inicia sesión en tu cuenta</li>
                    <li>Completa la evaluación ambiental</li>
                    <li>Revisa tus resultados y recomendaciones</li>
                </ol>
                
                <div style='text-align: center; margin: 30px 0;'>
                    <a href='" . COMPANY_WEBSITE . "/employee/login.php' class='btn'>Iniciar Sesión</a>
                </div>
                
                <p><strong>¿Necesitas ayuda?</strong></p>
                <p>Si tienes alguna pregunta sobre la evaluación o la plataforma, no dudes en contactar a nuestro equipo de soporte.</p>
            </div>
            <div class='footer'>
                <p>Este es un mensaje automático de " . COMPANY_NAME . ".</p>
                <p>Si no solicitaste este registro, puedes ignorar este mensaje.</p>
            </div>
        </div>
    </body>
    </html>";
    
    // Email headers
    $headers = array(
        'MIME-Version: 1.0',
        'Content-type: text/html; charset=UTF-8',
        'From: ' . COMPANY_NAME . ' <' . COMPANY_EMAIL . '>',
        'Reply-To: ' . COMPANY_EMAIL,
        'X-Mailer: PHP/' . phpversion()
    );
    
    // Send email
    return mail($email, $subject, $message, implode("\r\n", $headers));
}

function sendAssessmentCompletionEmail($name, $email, $score, $co2_impact) {
    $subject = "¡Evaluación Completada - " . COMPANY_NAME . "!";
    
    $score_category = '';
    $recommendations = '';
    
    if ($score >= 40) {
        $score_category = 'Excelente';
        $recommendations = '¡Felicidades! Tienes excelentes hábitos ambientales. Continúa así y comparte tus buenas prácticas con tus compañeros.';
    } elseif ($score >= 30) {
        $score_category = 'Bueno';
        $recommendations = 'Tienes buenos hábitos ambientales. Hay algunas áreas donde puedes mejorar para reducir aún más tu impacto.';
    } elseif ($score >= 20) {
        $score_category = 'Regular';
        $recommendations = 'Hay varias oportunidades para mejorar tus hábitos ambientales. Revisa las recomendaciones para hacer cambios positivos.';
    } else {
        $score_category = 'Necesita Mejora';
        $recommendations = 'Hay muchas oportunidades para mejorar tu impacto ambiental. Las recomendaciones te ayudarán a hacer cambios significativos.';
    }
    
    $message = "
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
            .header { background: linear-gradient(135deg, #667eea 0%, green 100%); color: white; padding: 30px; text-align: center; border-radius: 10px 10px 0 0; }
            .content { background: #f9f9f9; padding: 30px; border-radius: 0 0 10px 10px; }
            .score-card { background: white; padding: 20px; border-radius: 10px; margin: 20px 0; text-align: center; }
            .btn { display: inline-block; background: #667eea; color: white; padding: 12px 25px; text-decoration: none; border-radius: 5px; margin: 20px 0; }
            .footer { text-align: center; margin-top: 30px; color: #666; font-size: 0.9em; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h1>🎉 ¡Evaluación Completada!</h1>
                <p>" . COMPANY_NAME . " - Impacto Ambiental</p>
            </div>
            <div class='content'>
                <h2>¡Hola " . htmlspecialchars($name) . "!</h2>
                <p>Has completado exitosamente tu evaluación ambiental. Aquí están tus resultados:</p>
                
                <div class='score-card'>
                    <h3>Tu Puntuación: " . $score . "/50</h3>
                    <p><strong>Categoría: " . $score_category . "</strong></p>
                    <p><strong>Impacto CO2 estimado: " . $co2_impact . " ton/año</strong></p>
                </div>
                
                <h3>📊 Análisis de tus Resultados</h3>
                <p>" . $recommendations . "</p>
                
                <h3>🎯 Próximos Pasos</h3>
                <ul>
                    <li>Revisa las recomendaciones detalladas en tu perfil</li>
                    <li>Implementa los cambios sugeridos en tu rutina diaria</li>
                    <li>Comparte tus experiencias con tus compañeros</li>
                    <li>Participa en las iniciativas ambientales de la empresa</li>
                </ul>
                
                <div style='text-align: center; margin: 30px 0;'>
                    <a href='" . COMPANY_WEBSITE . "/employee/dashboard.php' class='btn'>Ver Resultados Detallados</a>
                </div>
                
                <p><strong>¡Gracias por tu participación!</strong></p>
                <p>Tu contribución nos ayuda a crear un futuro más sostenible para todos.</p>
            </div>
            <div class='footer'>
                <p>Este es un mensaje automático de " . COMPANY_NAME . ".</p>
                <p>Si tienes preguntas, contacta a nuestro equipo de soporte.</p>
            </div>
        </div>
    </body>
    </html>";
    
    // Email headers
    $headers = array(
        'MIME-Version: 1.0',
        'Content-type: text/html; charset=UTF-8',
        'From: ' . COMPANY_NAME . ' <' . COMPANY_EMAIL . '>',
        'Reply-To: ' . COMPANY_EMAIL,
        'X-Mailer: PHP/' . phpversion()
    );
    
    // Send email
    return mail($email, $subject, $message, implode("\r\n", $headers));
}
?> 
