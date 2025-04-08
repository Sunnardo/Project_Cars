<?php
session_start();

// Verificar si el usuario tiene la sesión abierta
if (!isset($_SESSION['iduser'])) {
    // Si no hay sesión, redirigir a la página de login
    header("Location: ../html/index.html");
    exit;
}
?>
