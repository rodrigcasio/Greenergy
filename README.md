# Greenergy - Environmental Impact Assessment Application

---

## 🌐 Now Available Online! (First BETA Version)

Greenergy is now publicly available on the web in its first BETA version. You can access and test the application from anywhere with an internet connection. Help us improve by reporting any bugs or suggestions!

---

## 🌱 Main Features

- **Branded Experience:** Company logo on all pages
- **Dual User System:**
  - **Employee:** Registration, login, 10-question environmental assessment, and detailed results with recommendations
  - **CTO/Admin:** Secure (password-protected) access to a dashboard with aggregated analytics, CO2 impact charts, and employee results
- **Automatic Email Notifications:**
  - Welcome email upon registration
  - Results email upon completing the assessment
- **Real-Time Analytics:**
  - CO2 impact distribution
  - Employee score charts
  - Environmental impact analysis and recommendations
- **Modern, Responsive Interface:** Clean, mobile-friendly, and easy to use
- **Security:** Password hashing, input validation, and session management

---

## 🚀 How It Works

1. **Employees** register and log in to complete a 10-question environmental assessment.
2. **Results** are stored in a MySQL database and analyzed in real time.
3. **Employees** receive instant feedback, recommendations, and a summary by email.
4. **CTO/Admin** logs in with a secure password to view company-wide analytics, CO2 charts, and detailed results.
5. **Company logo** appears on all pages for a professional, branded experience.

---

## 🛠️ Technologies Used
- PHP 7.4+
- MySQL 5.7+
- HTML5, CSS3 (custom, responsive)
- Chart.js (for charts)

---

## 📦 Project Structure (Key Files)
- `index.php` — Welcome page and user type selection
- `employee/` — Registration, login, assessment, dashboard, and results for employees
- `cto/` — Login and dashboard for CTO
- `assets/greenergyLogo.jpeg` — Company logo (on all pages)
- `css/style.css` — Modern, responsive styles
- `includes/config.php` — Database and email configuration

---

## ⚡ Quick Start

1. **Clone the repository and set up your database** (see instructions below)
2. **Add your logo:** Place your logo as `assets/greenergyLogo.jpeg`
3. **Start MySQL and the PHP server:**
   ```bash
   php -S localhost:8000
   ```
4. **Visit** `http://localhost:8000` in your browser
5. **CTO password:** `GreenergyCTO2024!`

---

## 🔥 Recent Improvements
- Logo integrated on all pages (with rounded corners)
- Email notifications for registration and assessment completion
- Improved error handling and empty state management
- SQL compatibility with MySQL strict mode
- Enhanced analytics and charts in the dashboard
- Professional, clean interface

---

## Features

### For Employees
- **User registration** with name, email, and password
- **Secure login** with password hashing
- **Environmental assessment** with 10 daily habit questions
- **Automatic scoring system** (0-50 points)
- **Detailed results** with categories and recommendations
- **Modern, responsive interface**

### For Administrators
- **Admin panel** with complete statistics
- **View all employee results**
- **Score distribution by category**
- **Individual assessment details**
- **Assessment completion rate**

## Assessment Questions

1. **Main mode of transportation** - Sustainable mobility evaluation
2. **Air travel** - Impact of air transport
3. **People in the household** - Resource use efficiency
4. **Meat product consumption** - Dietary impact
5. **Electricity consumption habits** - Energy efficiency
6. **Heating energy source** - Use of renewable energy
7. **Recycling and composting** - Waste management
8. **Acquisition of new products** - Responsible consumption
9. **Purchase of local food** - Food sustainability
10. **Type of personal vehicle** - Personal mobility

## Scoring System

- **A (5 points):** Very sustainable
- **B (4 points):** Sustainable
- **C (3 points):** Moderate
- **D (2 points):** Less sustainable
- **E (1 point):** Not very sustainable
- **F (0 points):** Not sustainable

### Result Categories
- **Excellent (40-50 points):** Very low environmental impact
- **Good (30-39 points):** Moderate environmental impact
- **Fair (20-29 points):** Moderately high environmental impact
- **Needs Improvement (0-19 points):** High environmental impact

## System Requirements

- PHP 7.4 or higher
- MySQL 5.7 or higher
- Web server (Apache/Nginx)
- PHP PDO extension

## Installation

### 1. Database Setup

1. Create a MySQL database:
```sql
CREATE DATABASE greenergy CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

2. Create a user for the application:
```sql
CREATE USER 'greenergy_user'@'localhost' IDENTIFIED BY 'your_secure_password';
GRANT ALL PRIVILEGES ON greenergy.* TO 'greenergy_user'@'localhost';
FLUSH PRIVILEGES;
```

### 2. Application Configuration

1. Edit the `includes/config.php` file:
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'greenergy_user');
define('DB_PASS', 'your_secure_password');
define('DB_NAME', 'greenergy');
```

2. Ensure the web server has write permissions to the application directory.

### 3. Web Server Configuration

#### Apache
Create a `.htaccess` file in the root directory:
```apache
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php [QSA,L]
```

#### Nginx
Add this configuration to your server:
```nginx
location / {
    try_files $uri $uri/ /index.php?$query_string;
}
```

### 4. Accessing the Application

1. **Main access:** `http://your-domain.com/`
2. **Admin panel:** `http://your-domain.com/admin/`
   - Default password: `admin123`
   - **IMPORTANT:** Change this password in production

## File Structure

```
Greenergy/
├── index.php                 # Main page
├── register.php              # User registration
├── login.php                 # User login
├── dashboard.php             # User dashboard
├── assessment.php            # Assessment form
├── results.php               # Assessment results
├── logout.php                # Logout
├── includes/
│   └── config.php            # Database configuration
├── css/
│   └── style.css             # CSS styles
├── admin/
│   ├── index.php             # Admin panel
│   └── logout.php            # Admin logout
└── README.md                 # This file
```

## Database

### `users` Table
- `id`: Unique user ID
- `name`: Full name
- `email`: Email (unique)
- `password`: Hashed password
- `created_at`: Creation date
- `updated_at`: Update date

### `assessment_results` Table
- `id`: Unique result ID
- `user_id`: User ID (foreign key)
- `q1_answer` to `q10_answer`: Answers to the questions
- `total_score`: Total score
- `completed_at`: Completion date

## Security

### Implemented
- Password hashing with `password_hash()`
- Input data validation
- SQL injection protection with PDO
- HTML output escaping
- Secure sessions

### Production Recommendations
- Use HTTPS
- Implement rate limiting
- Add two-factor authentication
- Configure a firewall
- Perform regular backups
- Use environment variables for credentials

## Customization

### Change Questions
Edit the `$questions` array in `assessment.php` to modify the questions.

### Change Scoring System
Edit the `$scores` array in `assessment.php` to adjust the points.

### Change Categories
Edit the conditions in `results.php` to modify the category ranges.

### Change Styles
Edit `css/style.css` to customize the appearance.

---

## Author
Rodrigo Casio ([View my Github profile](https://github.com/rodrigcasio)) 