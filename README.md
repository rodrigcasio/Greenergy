# Greenergy - Aplicación de Evaluación de Impacto Ambiental

Greenergy es una aplicación web moderna en PHP para empresas, diseñada para empoderar tanto a empleados como a líderes en el seguimiento, análisis y mejora de su impacto ambiental. La app cuenta con un sistema dual de usuarios (Empleado y CTO/Admin), una evaluación ambiental de 10 preguntas, analíticas en tiempo real y branding profesional con el logo de tu empresa.

---

## 🌱 Funcionalidades Principales

- **Experiencia con Branding:** Logo de la empresa visible en todas las páginas
- **Sistema Dual de Usuarios:**
  - **Empleado:** Registro, inicio de sesión, evaluación ambiental de 10 preguntas y resultados detallados con recomendaciones
  - **CTO/Admin:** Acceso seguro (protegido por contraseña) a un panel con analíticas agregadas, gráficos de impacto de CO2 y resultados de empleados
- **Notificaciones Automáticas por Email:**
  - Email de bienvenida al registrarse
  - Email de resultados tras completar la evaluación
- **Analíticas en Tiempo Real:**
  - Distribución del impacto de CO2
  - Gráficos de puntuaciones de empleados
  - Análisis de impacto ambiental y recomendaciones
- **Interfaz Moderna y Responsive:** Diseño limpio, adaptable a dispositivos móviles y fácil de usar
- **Seguridad:** Hash de contraseñas, validación de entradas y gestión de sesiones

---

## 🚀 ¿Cómo Funciona?

1. **Los empleados** se registran e inician sesión para realizar una evaluación ambiental de 10 preguntas.
2. **Los resultados** se almacenan en una base de datos MySQL y se analizan en tiempo real.
3. **Los empleados** reciben feedback instantáneo, recomendaciones y un resumen por email.
4. **El CTO/Admin** accede con contraseña segura a un panel con analíticas, gráficos de CO2 y resultados detallados.
5. **El logo de la empresa** se muestra en todas las páginas para una experiencia profesional y coherente.

---

## 🛠️ Tecnologías Utilizadas
- PHP 7.4+
- MySQL 5.7+
- HTML5, CSS3 (personalizado y responsive)
- Chart.js (para gráficos)

---

## 📦 Estructura del Proyecto (Archivos Clave)
- `index.php` — Página de bienvenida y selección de tipo de usuario
- `employee/` — Registro, login, evaluación, dashboard y resultados para empleados
- `cto/` — Login y dashboard para CTO
- `assets/greenergyLogo.jpeg` — Logo de la empresa (visible en todas las páginas)
- `css/style.css` — Estilos modernos y responsive
- `includes/config.php` — Configuración de base de datos y email

---

## ⚡ Inicio Rápido

1. **Clona el repositorio y configura la base de datos** (ver instrucciones más abajo)
2. **Agrega tu logo:** Coloca tu logo como `assets/greenergyLogo.jpeg`
3. **Inicia MySQL y el servidor PHP:**
   ```bash
   php -S localhost:8000
   ```
4. **Abre** `http://localhost:8000` en tu navegador
5. **Contraseña de CTO:** `GreenergyCTO2024!`

---

## 🔥 Mejoras Recientes
- Integración del logo en todas las páginas (con esquinas redondeadas)
- Notificaciones por email en registro y al completar la evaluación
- Mejor manejo de errores y mensajes cuando no hay datos
- Compatibilidad SQL con modo estricto de MySQL
- Analíticas y gráficos mejorados en el dashboard
- Interfaz profesional y limpia

---

## Resumen del Proyecto (para presentación)

**Objetivo:**  
Desarrollar una plataforma web profesional y personalizada para que las empresas evalúen y mejoren el impacto ambiental de sus empleados, con analíticas para la dirección.

**Logros Clave:**
- Branding completo con logo en todas las páginas
- Sistema dual de usuarios (Empleado y CTO/Admin)
- Evaluación ambiental automatizada y recomendaciones personalizadas
- Panel de analíticas en tiempo real para el CTO
- Notificaciones automáticas por email
- Seguridad y experiencia de usuario mejoradas
- Documentación y README actualizados

**Resultado:**  
Una plataforma robusta, fácil de usar y visualmente atractiva para la evaluación ambiental, con analíticas accionables para la dirección y una experiencia fluida para los empleados.

---

## Características Técnicas y Detalles

### Para Empleados
- **Registro de usuarios** con nombre, email y contraseña
- **Sistema de login** seguro con hash de contraseñas
- **Evaluación ambiental** con 10 preguntas sobre hábitos diarios
- **Sistema de puntuación** automático (0-50 puntos)
- **Resultados detallados** con categorización y recomendaciones
- **Interfaz responsive** y moderna

### Para CTO/Admin
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
2. **Panel CTO**: `http://tu-dominio.com/cto/`
   - Contraseña por defecto: `GreenergyCTO2024!`
   - **IMPORTANTE**: Cambia esta contraseña en producción si lo deseas

## Estructura de Archivos

```
Greenergy/
├── index.php                 # Página principal
├── employee/                 # Módulo de empleados
│   ├── welcome.php           # Bienvenida empleados
│   ├── login.php             # Login empleados
│   ├── register.php          # Registro empleados
│   ├── dashboard.php         # Dashboard empleados
│   ├── assessment.php        # Evaluación ambiental
│   └── results.php           # Resultados de la evaluación
├── cto/                      # Módulo CTO/Admin
│   ├── login.php             # Login CTO
│   └── dashboard.php         # Dashboard CTO
├── assets/
│   └── greenergyLogo.jpeg    # Logo de la empresa
├── css/
│   └── style.css             # Estilos CSS
├── includes/
│   └── config.php            # Configuración
└── README.md                 # Este archivo
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