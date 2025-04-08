<?php
session_start();

require_once 'db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    echo "Solicitud POST recibida.<br>";
// Verificar si el usuario tiene la sesión abierta
if (!isset($_SESSION['iduser'])) {
    // Si no hay sesión, redirigir a la página de login
    header("Location: ../html/index.html");
    exit;
}

    $usernameOrEmail  = $_POST['usuari'] ?? '';
    $password = $_POST['password'] ?? '';

    if (empty($usernameOrEmail) || empty($password)) {
        echo "Faltan datos.<br>";
        exit();
    }
    // Preparar consulta per verificar usuari actiu (per username o email)
$stmt = $mysqli->prepare("
SELECT iduser, username, mail, passHash, userFirstName, userLastName 
FROM users 
WHERE (username = ? OR mail = ?) 
  AND active = 1
LIMIT 1
");

$stmt->bind_param("ss", $usernameOrEmail, $usernameOrEmail);
$stmt->execute();
$result = $stmt->get_result();
if ($result && $result->num_rows === 1) {
    $user = $result->fetch_assoc();

    // Verificar contrasenya
    if (password_verify($password, $user['passHash'])) {

        //  Actualitzar lastSignIn
        $update = $mysqli->prepare("UPDATE users SET lastSignIn = NOW() WHERE iduser = ?");
        $update->bind_param("i", $user['iduser']);
        $update->execute();
        $update->close();

        //  Crear sessió i cookies
        $_SESSION['iduser'] = $user['iduser'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['userFirstName'] = $user['userFirstName'];
        $_SESSION['userLastName'] = $user['userLastName'];

        // Cookie bàsica 
        setcookie("lastLogin", date("Y-m-d H:i:s"), time() + (86400 * 30), "/");

        //  Redirigir a home
        header("Location: ../html/home.html");
        exit;
    }
}

} else {
    echo "Método no permitido: " . $_SERVER["REQUEST_METHOD"];
}
?>
