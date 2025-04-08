<?php
$host = 'localhost';
$dbname = 'project_cars';
$user = 'suna';
$password = '';

// Crear conexión
$mysqli = new mysqli($host, $user, $password, $dbname);

// Verificar conexión
if ($mysqli->connect_error) {
    die('Error de conexión (' . $mysqli->connect_errno . ') '
        . $mysqli->connect_error);
}

// Establecer charset a UTF-8 MB4
if (!$mysqli->set_charset("utf8mb4")) {
    die("Error al establecer el charset utf8mb4: " . $mysqli->error);
}
?>