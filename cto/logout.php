<?php
session_start(); // Inicia la sesión para poder manipularla

// --- DESTRUCCIÓN DE LA SESIÓN DEL CTO ---
// Elimina las variables de sesión específicas del CTO
unset($_SESSION['cto_logged_in']);
unset($_SESSION['cto_name']);

// Redirige al usuario a la página principal después de cerrar sesión
header('Location: ../index.php');
exit();
?> 