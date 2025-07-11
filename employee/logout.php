<?php
session_start(); // Inicia la sesión para poder manipularla

// --- DESTRUCCIÓN DE TODA LA SESIÓN DEL USUARIO ---
session_destroy(); // Elimina todos los datos de sesión del usuario

// Redirige al usuario a la página principal después de cerrar sesión
header('Location: ../index.php');
exit();
?> 