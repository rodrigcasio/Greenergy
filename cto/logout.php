<?php
session_start();

// Destroy CTO session
unset($_SESSION['cto_logged_in']);
unset($_SESSION['cto_name']);

// Redirect to home page
header('Location: ../index.php');
exit();
?> 