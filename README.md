# Greenergy - Aplicación PHP

Una aplicación web completa para evaluar el impacto ambiental de los empleados de una empresa mediante un cuestionario de 10 preguntas.

## Características

### Para Empleados
- **Registro de usuarios** con nombre, email y contraseña
- **Sistema de login** seguro con hash de contraseñas
- **Evaluación ambiental** con 10 preguntas sobre hábitos diarios
- **Sistema de puntuación** automático (0-50 puntos)
- **Resultados detallados** con categorización y recomendaciones
- **Interfaz responsive** y moderna

### Para Administradores
- **Panel administrativo** con estadísticas completas
- **Vista de todos los resultados** de empleados
- **Distribución de puntuaciones** por categorías
- **Detalles individuales** de cada evaluación
- **Tasa de completitud** de evaluaciones

## Preguntas de la Evaluación

1. **Método principal de transporte** - Evaluación de movilidad sostenible
2. **Viajes en avión** - Impacto del transporte aéreo
3. **Personas en el hogar** - Eficiencia del uso de recursos
4. **Consumo de productos cárnicos** - Impacto de la alimentación
5. **Hábitos de consumo eléctrico** - Eficiencia energética
6. **Fuente de energía para calefacción** - Uso de energías renovables
7. **Reciclaje y compostaje** - Gestión de residuos
8. **Adquisición de productos nuevos** - Consumo responsable
9. **Compra de alimentos locales** - Sostenibilidad alimentaria
10. **Tipo de vehículo personal** - Movilidad personal

## Sistema de Puntuación

- **A (5 puntos)**: Muy sostenible
- **B (4 puntos)**: Sostenible
- **C (3 puntos)**: Moderado
- **D (2 puntos)**: Menos sostenible
- **E (1 punto)**: Poco sostenible
- **F (0 puntos)**: No sostenible

### Categorías de Resultados
- **Excelente (40-50 puntos)**: Impacto ambiental muy bajo
- **Bueno (30-39 puntos)**: Impacto ambiental moderado
- **Regular (20-29 puntos)**: Impacto ambiental moderado-alto
- **Necesita Mejora (0-19 puntos)**: Impacto ambiental alto

## Requisitos del Sistema

- PHP 7.4 o superior
- MySQL 5.7 o superior
- Servidor web (Apache/Nginx)
- Extensión PDO para PHP

## Instalación

### 1. Configuración de la Base de Datos

1. Crea una base de datos MySQL:
```sql
CREATE DATABASE greenergy CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

2. Crea un usuario para la aplicación:
```sql
CREATE USER 'greenergy_user'@'localhost' IDENTIFIED BY 'tu_contraseña_segura';
GRANT ALL PRIVILEGES ON greenergy.* TO 'greenergy_user'@'localhost';
FLUSH PRIVILEGES;
```

### 2. Configuración de la Aplicación

1. Edita el archivo `includes/config.php`:
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'greenergy_user');
define('DB_PASS', 'tu_contraseña_segura');
define('DB_NAME', 'greenergy');
```

2. Asegúrate de que el servidor web tenga permisos de escritura en el directorio de la aplicación.

### 3. Configuración del Servidor Web

#### Apache
Crea un archivo `.htaccess` en el directorio raíz:
```apache
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php [QSA,L]
```

#### Nginx
Añade esta configuración a tu servidor:
```nginx
location / {
    try_files $uri $uri/ /index.php?$query_string;
}
```

### 4. Acceso a la Aplicación

1. **Acceso principal**: `http://tu-dominio.com/`
2. **Panel administrativo**: `http://tu-dominio.com/admin/`
   - Contraseña por defecto: `admin123`
   - **IMPORTANTE**: Cambia esta contraseña en producción

## Estructura de Archivos

```
Greenergy/
├── index.php                 # Página principal
├── register.php             # Registro de usuarios
├── login.php               # Login de usuarios
├── dashboard.php           # Dashboard del usuario
├── assessment.php          # Formulario de evaluación
├── results.php             # Resultados de la evaluación
├── logout.php              # Cerrar sesión
├── includes/
│   └── config.php          # Configuración de base de datos
├── css/
│   └── style.css           # Estilos CSS
├── admin/
│   ├── index.php           # Panel administrativo
│   └── logout.php          # Logout de administrador
└── README.md               # Este archivo
```

## Base de Datos

### Tabla `users`
- `id`: ID único del usuario
- `name`: Nombre completo
- `email`: Email (único)
- `password`: Contraseña hasheada
- `created_at`: Fecha de creación
- `updated_at`: Fecha de actualización

### Tabla `assessment_results`
- `id`: ID único del resultado
- `user_id`: ID del usuario (clave foránea)
- `q1_answer` a `q10_answer`: Respuestas a las preguntas
- `total_score`: Puntuación total
- `completed_at`: Fecha de completitud

## Seguridad

### Implementadas
- Hash de contraseñas con `password_hash()`
- Validación de entrada de datos
- Protección contra SQL injection con PDO
- Escape de salida HTML
- Sesiones seguras

### Recomendaciones para Producción
- Usar HTTPS
- Implementar rate limiting
- Añadir autenticación de dos factores
- Configurar firewall
- Realizar backups regulares
- Usar variables de entorno para credenciales

## Personalización

### Cambiar Preguntas
Edita el array `$questions` en `assessment.php` para modificar las preguntas.

### Cambiar Sistema de Puntuación
Modifica el array `$scores` en `assessment.php` para ajustar los puntos.

### Cambiar Categorías
Edita las condiciones en `results.php` para modificar los rangos de categorías.

### Cambiar Estilos
Modifica `css/style.css` para personalizar la apariencia.

## Soporte

Para reportar problemas o solicitar características adicionales, contacta al equipo de desarrollo.

## Licencia

Este proyecto está bajo la Licencia MIT. Ver el archivo LICENSE para más detalles.

---

**Nota**: Esta aplicación está diseñada para uso interno de empresas. Asegúrate de cumplir con las regulaciones de privacidad y protección de datos de tu jurisdicción. 