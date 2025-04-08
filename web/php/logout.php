<?php
// Inicia la sesión
session_start();

// Eliminar todas las variables de sesión
session_unset();

// Destruir la sesión
session_destroy();

setcookie("lastLogin", "", time() - 3600, "/"); // Caduca la cookie
setcookie(session_name(), "", time() - 3600, "/"); // Elimina la cookie de sessió

// Redirigir al usuario al formulario de inicio de sesión
header("Location: ../html/index.html");
exit;
?>