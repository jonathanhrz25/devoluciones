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
            width: 100%;
            /* botón de enviar ocupe todo el ancho */
            margin-top: 10px;
            /* Agrega algo de espacio encima del botón de enviar */
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-left" style="background-color: rgb(4, 32, 145)!important; text-align: left;"
        role="navigation">
        <div class="container-fluid">
            <a class="navbar-brand d-flex flex-row">
                <img src="./img/loguito2.png" alt="" height="45" class="d-inline-block align-text-top">
            </a>
        </div>
    </nav>

    <div class="container">
        <?php
        session_start();
        if (!empty($_SESSION['login_error'])) {
            echo '<div class="alert alert-danger" role="alert" id="login-error-alert">' . $_SESSION['login_error'] . '</div>';
            unset($_SESSION['login_error']); // Borrar el mensaje después de mostrarlo
        }
        ?>
        <div class="login-box">
        <h5 class="display-6">Iniciar Sesión</h5>
            <span><a href="./php/registro.php" class="btn btn-info custom-btn-color">ó Registrate</a></span>
            <form action="php/login.php" method="POST">
                <div class="mb-3">
                    <input name="usuario" type="text" class="form-control" placeholder="Ingresa tu usuario" required>
                </div>
                <div class="mb-3">
                    <input name="password" type="password" class="form-control" placeholder="Ingrese su contraseña" required>
                </div>
                <input type="submit" value="Entrar" class="btn btn-primary custom-btn-color">
            </form>
        </div>
    </div>

    <?php include './css/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"
        integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"
        integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF"
        crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.0/jquery.min.js"
        integrity="sha512-suUtSPkqYmFd5Ls30Nz6bjDX+TCcfEzhFfqjijfdggsaFZoylvTj+2odBzshs0TCwYrYZhQeCgHgJEkncb2YVQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="js/jquery-3.6.4.js"></script>
    <script>
        // JavaScript para ocultar la alerta después de 5 segundos
        setTimeout(function () {
            var alert = document.getElementById('login-error-alert');
            if (alert) {
                alert.style.display = 'none';
            }
        }, 5000); // 5000 ms = 5 segundos
    </script>
</body>

</html>