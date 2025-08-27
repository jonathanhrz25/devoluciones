<?php include '../css/header.php'; ?>

<?php
require 'connect.php';

// Inicializamos las variables de sesión
session_start();

$message1 = '';
$message2 = '';

// Verificar si se envió el formulario de administrador
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['admin_submit'])) {
    $adminUsuario = $_POST['admin_usuario'];
    $adminPassword = $_POST['admin_password'];

    // Validar las credenciales del administrador
    $sql = "SELECT id, usuario, password FROM users WHERE usuario = :usuario";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':usuario', $adminUsuario);
    $stmt->execute();
    $adminCredentials = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($adminCredentials && $adminCredentials['id'] == 1) {
        // Verificar las credenciales del administrador
        if (password_verify($adminPassword, $adminCredentials['password'])) {
            // Credenciales de administrador válidas, configurar variable de sesión
            $_SESSION['admin_verified'] = true;
            $message1 = 'Credenciales de administrador verificadas correctamente.';
        } else {
            $message2 = 'Credenciales de administrador incorrectas';
        }
    } else {
        $message2 = 'No se encontró el usuario administrador';
    }
}

// Verificar si el administrador ya ha sido verificado
if (isset($_SESSION['admin_verified']) && $_SESSION['admin_verified']) {
    // Verificar si se envió el formulario de registro de usuario
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_submit'])) {
        if (!empty($_POST['usuario']) && !empty($_POST['password']) && !empty($_POST['confirm_password'])) {
            if ($_POST['password'] === $_POST['confirm_password']) {
                $sql = "INSERT INTO users (usuario, password) VALUES (:usuario, :password)";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':usuario', $_POST['usuario']);
                $pass = password_hash($_POST['password'], PASSWORD_BCRYPT);
                $stmt->bindParam(':password', $pass);

                if ($stmt->execute()) {
                    $message1 = 'Nuevo usuario creado correctamente';
                } else {
                    $message2 = 'Lo sentimos, debe haber habido un problema al crear tu cuenta';
                }
            } else {
                $message2 = 'Las contraseñas no coinciden';
            }
        } else {
            $message2 = 'Por favor complete todos los campos';
        }
    }
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="shortcut icon" href="./img/icono2.png" type="image/x-icon">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="./css/footer.css">
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
    <link rel="stylesheet" href="./css/style.css">
    <title>Devoluciones</title>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
        }

        .container {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 70vh;
            position: relative;
            padding-top: 120px;
            /* Ajusta este valor para bajar el formulario */
        }

        .alert {
            position: absolute;
            top: 0%;
            width: 40%;
            text-align: center;
        }

        .login-box,
        .registro-box {
            width: 100%;
            max-width: 400px;
            /* Ajusta el tamaño aquí para que coincida */
            padding: 5px;
            margin: 0 auto;
            border: 1px solid #ccc;
            border-radius: 10px;
            background-color: #29cece;
            margin-top: -10px;
        }

        .custom-btn-color {
            background-color: #003eaf !important;
            color: white !important;
            border-color: #003eaf !important;
        }

        .custom-btn-color:hover {
            background-color: #14d6e0 !important;
            border-color: #14d6e0 !important;
        }

        .custom-btn-color:active {
            background-color: #04123b !important;
            border-color: #04123b !important;
        }

        input[type="submit"].custom-btn-color {
            width: 80%;
            /* botón de enviar ocupe todo el ancho */
            margin-top: 10px;
            /* Agrega algo de espacio encima del botón de enviar */
        }
    </style>
</head>

<body>

    <div class="container">

        <?php if (!empty($message1)): ?>
            <div id="success-alert" class="alert alert-success" role="alert">
                <?php echo $message1; ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($message2)): ?>
            <div id="error-alert" class="alert alert-danger" role="alert">
                <?php echo $message2; ?>
            </div>
        <?php endif; ?>

        <div class="registro-box">
            <h5 class="display-6">Registro</h5>
            <span><a href="../devoluciones.php" class="btn btn-info custom-btn-color">ó Iniciar sesión</a></span>

            <?php if (!isset($_SESSION['admin_verified']) || !$_SESSION['admin_verified']): ?>
                <!-- Formulario de administrador -->
                <form action="./registro.php" method="POST">
                    <div class="mb-3">
                        <input type="hidden" name="admin_submit" value="1">
                        <input name="admin_usuario" type="text" placeholder="Usuario de administrador" required>
                    </div>
                    <div class="mb-3">
                        <input name="admin_password" type="password" placeholder="Contraseña de administrador" required>
                    </div>
                    <input type="submit" value="Verificar Administrador" class="btn btn-primary custom-btn-color">
                </form>
            </div>
        </div>
    <?php else: ?>
        <div class="registro-box">
            <!-- Formulario de registro de usuario -->
            <form action="./registro.php" method="POST">
                <input type="hidden" name="user_submit" value="1">
                <input name="usuario" type="text" placeholder="Ingresa tu Usuario" required>
                <input name="password" type="password" placeholder="Ingrese su contraseña" required>
                <input name="confirm_password" type="password" placeholder="Confirmar contraseña" required>
                <input type="submit" value="Registrar Usuario" class="btn btn-primary custom-btn-color">
            </form>
        </div>
    <?php endif; ?>

    <script>
        // JavaScript para ocultar la alerta después de 5 segundos
        setTimeout(function () {
            var successAlert = document.getElementById('success-alert');
            if (successAlert) {
                successAlert.style.display = 'none';
            }
            var errorAlert = document.getElementById('error-alert');
            if (errorAlert) {
                errorAlert.style.display = 'none';
            }
        }, 5000); // 5000 ms = 5 segundos
    </script>

</body>

</html>

<?php include '../css/footer.php'; ?>