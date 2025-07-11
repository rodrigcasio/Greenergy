<?php
session_start();

// Destroy admin session
unset($_SESSION['admin_logged_in']);

// Redirect to home page
header('Location: ../index.php');
exit();
?> 