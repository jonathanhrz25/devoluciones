<?php
session_start();
require '../php/connect.php';

$message = '';

if (isset($_SESSION['user_id'])) {
    header("Location: ../php/inicio.php");
    exit();
}

if (!empty($_POST['usuario'])) {
    // Ajusta la consulta para buscar por num_cliente en la tabla videodb
    $records = $conn->prepare('SELECT id, num_cliente FROM videodb WHERE num_cliente = :num_cliente');
    $records->bindParam(':num_cliente', $_POST['usuario']);
    $records->execute();
    $results = $records->fetch(PDO::FETCH_ASSOC);

    if ($results) {
        $_SESSION['user_id'] = $results['id'];
        $_SESSION['num_cliente'] = $results['num_cliente'];
        header("Location: ../php/inicio.php");
        exit();
    } else {
        $_SESSION['login_error'] = 'Lo sentimos, ese número de cliente no existe.';
        header("Location: ../clientes.php");
        exit();
    }
} else {
    $_SESSION['login_error'] = 'Por favor ingrese su número de cliente.';
    header("Location: ../clientes.php");
    exit();
}
?>
