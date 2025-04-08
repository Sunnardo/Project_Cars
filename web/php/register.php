<?php
require_once 'db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST['usuari'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $verify_password = $_POST['verify_password'];
    $first_name = $_POST['fstname'];
    $last_name = $_POST['lstname'];

    if ($password !== $verify_password) {
        die("Les contrasenyes no coincideixen.");
    }
    if (empty($email) || empty($username) || empty($password)) {
        die("Tots els camps obligatoris han d'estar omplerts.");
    }


    // Comprovar si ja existeix el username o el mail
$stmt = $mysqli->prepare("SELECT iduser FROM users WHERE username = ? OR mail = ?");
$stmt->bind_param("ss", $username, $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    // Ja existeix usuari o email
    $stmt->close();
    echo "Aquest nom d'usuari o correu electrònic ja està registrat. Torna enrere i prova amb un altre.";
    exit;
}
$stmt->close();

// Hash de la contrasenya
$passHash = password_hash($password, PASSWORD_BCRYPT);

// Inserir nou usuari
$stmt = $mysqli->prepare("
    INSERT INTO users (mail, username, passHash, userFirstName, userLastName, creationDate, active)
    VALUES (?, ?, ?, ?, ?, NOW(), 1)
");

$stmt->bind_param("sssss", $email, $username, $passHash, $userFirstName, $userLastName);

if ($stmt->execute()) {
    //  Registre completat
    $stmt->close();
    $mysqli->close();
    header("Location: ../html/index.html"); // Pots afegir un missatge a index.html que es mostri si 'registre=ok'
    exit;
} else {
    echo "Error al registrar l'usuari: " . $stmt->error;
    $stmt->close();
    $mysqli->close();
}
}
?>