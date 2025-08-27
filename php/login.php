<?php
session_start();
require 'connect.php';

$message = '';

if (isset($_SESSION['user_id'])) {
    header("Location: ./devoluciones.php");
    exit();
}

if (!empty($_POST['usuario']) && !empty($_POST['password'])) {
    $records = $conn->prepare('SELECT id, usuario, password FROM users WHERE usuario = :usuario');
    $records->bindParam(':usuario', $_POST['usuario']);
    $records->execute();
    $results = $records->fetch(PDO::FETCH_ASSOC);

    if ($results && password_verify($_POST['password'], $results['password'])) {
        $_SESSION['user_id'] = $results['id'];
        $_SESSION['usuario'] = $results['usuario'];
        header("Location: ../php/index.php");
        exit();
    } else {
        $_SESSION['login_error'] = 'Lo sentimos, esas credenciales no coinciden.';
        header("Location: ../devoluciones.php");
        exit();
    }
} else {
    $_SESSION['login_error'] = 'Por favor ingrese su usuario y contraseña.';
    header("Location: ../devoluciones.php");
    exit();
}
?>