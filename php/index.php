<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../devoluciones.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="../img/icono2.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">
    <title>Almacen Serva</title>
    <style>
        .welcome-text {
            flex: 1;
            text-align: center;
            display: none;
        }

        @media (min-width: 769px) {
            .welcome-text {
                display: block;
            }
        }

        .form-group.hidden {
            display: none;
        }

        .form-group .error-message {
            color: red;
            display: none;
        }

        /* Spinner de carga */
        #loading-spinner {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            z-index: 1000;
            width: 4rem;
            height: 4rem;
            border-width: 0.25rem;
        }

        body.loading #loading-spinner {
            display: block;
        }

        body.loading * {
            pointer-events: none;
        }

        .drop-zone {
            border: 2px dashed #007bff;
            padding: 20px;
            text-align: center;
            cursor: pointer;
            color: #007bff;
            border-radius: 5px;
            background-color: #f8f9fa;
        }

        .drop-zone.dragover {
            background-color: #e9ecef;
        }

        .file-item {
            display: flex;
            align-items: center;
            margin: 5px 0;
        }

        .remove-file {
            background-color: #ff5c5c;
            border: none;
            color: white;
            padding: 3px 7px;
            margin-left: 10px;
            cursor: pointer;
            border-radius: 3px;
        }

        .remove-file:hover {
            background-color: #ff2b2b;
        }
    </style>
</head>

<body>
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top" style="background-color: #081856!important;">
            <a class="navbar-brand" href="../php/index.php">
                <img src="../img/loguito2.png" alt="" height="45" class="d-inline-block align-text-top">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <span class="nav-link text-white">
                            Bienvenido de nuevo <?php echo $_SESSION['usuario']; ?>
                        </span>
                    </li>
                </ul>
                <div class="modo me-2" id="modo">
                    <i class="fas fa-toggle-off fa-1x"></i>
                </div>
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Cerrar Sesión</a>
                    </li>
                </ul>
            </div>
        </nav>
    </header>

    <div style="padding-top: 30px;"></div>

    <div class="container mt-5">
        <h1 class="display-5 text-center">Subir Video aclaración</h1><br><br>
        <form action="subir.php" method="post" enctype="multipart/form-data" id="uploadForm">
            <div class="form-group">
                <label for="verificar" class="form-label">Seleccione una opción: </label><br>
                <div class="btn-group" role="group">
                    <input type="radio" class="btn-check" name="cliente" id="cliente1" value="cliente1"
                        autocomplete="off">
                    <label class="btn btn-outline-primary" for="cliente1">Verificar</label>

                    <input type="radio" class="btn-check" name="cliente" id="cliente2" value="cliente2"
                        autocomplete="off">
                    <label class="btn btn-outline-primary" for="cliente2">Nuevo Cliente</label>
                </div>
            </div>

            <div class="form-group mb-3 hidden" id="verificar-group">
                <label for="verificar" class="form-label">Verificar Numero de Cliente: </label>
                <input type="text" name="verificar" class="form-control" id="verificar" aria-describedby="nameHelp"
                    placeholder="Ingrese el numero de cliente" />
                <div class="error-message">Número de cliente no registrado.</div>
            </div>

            <div class="form-group hidden" id="num_cliente-group">
                <label for="num_cliente" class="form-label">Numero de Cliente: </label>
                <input type="text" name="num_cliente" id="num_cliente" aria-describedby="nameHelp"
                    placeholder="Numero de Cliente" />
            </div>

            <div class="form-group mb-3">
                <label for="motivo" class="form-label">Motivo: </label>
                <input type="text" name="motivo" class="form-control" id="motivo" aria-describedby="nameHelp"
                    placeholder="Ingrese el Motivo" />
            </div>

            <div class="form-group">
                <label for="description">Descripción (opcional):</label>
                <textarea name="description" class="form-control" placeholder="Descripcion del video"></textarea>
            </div><br>

            <div id="drop-zone" class="drop-zone">
                <label for="video">Seleccione un archivo multimedia:</label>
                <p>Arrastre sus archivos aquí o haga clic para seleccionar</p>
                <input type="file" id="media" name="media[]" class="form-control" accept=".mp4, .jpg, .jpeg, .png"
                    multiple required hidden>
            </div>
            <div id="file-list" class="file-list"></div><br>

            <div class="form-group mb-3">
                <label for="exampleInputEmail1" class="form-label">Enviar correo a: </label>
                <div id="correos-container">
                    <div class="input-group mb-2">
                        <input type="text" name="correos[]" class="form-control" aria-describedby="nameHelp"
                            placeholder="Ingrese un correo" required />
                        <div class="input-group-append">
                            <button type="button" class="btn btn-outline-secondary add-email-btn">
                                <i class="bi bi-plus-square-fill"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Subir</button>
        </form>
        <a href="ver.php" class="btn btn-secondary mt-3">Ver Videos subidos</a>
    </div><br><br><br>

    <!-- Spinner de carga -->
    <div id="loading-spinner" class="spinner-border text-primary" role="status">
        <span class="sr-only">Cargando...</span>
    </div>

    <script>
        document.getElementById('uploadForm').addEventListener('submit', function (e) {
            // Mostrar spinner y deshabilitar elementos de la página
            document.body.classList.add('loading');

            // Deshabilitar el botón de envío
            const submitButton = e.target.querySelector('button[type="submit"]');
            submitButton.disabled = true;

            // Mantener el spinner mientras se envía el formulario
            e.target.submit();
        });

        // Código JavaScript
        document.addEventListener('DOMContentLoaded', function () {
            const verificarRadio = document.getElementById('cliente1');
            const añadirRadio = document.getElementById('cliente2');
            const verificarGroup = document.getElementById('verificar-group');
            const numClienteGroup = document.getElementById('num_cliente-group');
            const verificarInput = document.getElementById('verificar');
            const errorMessage = document.querySelector('.error-message');

            verificarRadio.addEventListener('change', function () {
                if (verificarRadio.checked) {
                    verificarGroup.classList.remove('hidden');
                    numClienteGroup.classList.add('hidden');
                }
            });

            añadirRadio.addEventListener('change', function () {
                if (añadirRadio.checked) {
                    numClienteGroup.classList.remove('hidden');
                    verificarGroup.classList.add('hidden');
                }
            });

            verificarInput.addEventListener('input', function () {
                const numCliente = verificarInput.value.trim();
                if (numCliente !== '') {
                    fetch('verificar_cliente.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: 'num_cliente=' + encodeURIComponent(numCliente)
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.exists) {
                                errorMessage.style.display = 'none';
                            } else {
                                errorMessage.style.display = 'block';
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                        });
                }
            });
        });
    </script>

    <script> //Codigo script para campo añadir correo
        document.addEventListener('click', function (event) {
            if (event.target.closest('.add-email-btn')) {
                const container = document.getElementById('correos-container');

                // Crear un nuevo input group
                const newInputGroup = document.createElement('div');
                newInputGroup.className = 'input-group mb-2';

                // Crear el nuevo campo de entrada
                const newInput = document.createElement('input');
                newInput.type = 'text';
                newInput.name = 'correos[]';
                newInput.className = 'form-control';
                newInput.placeholder = 'Ingrese otro correo';
                newInput.required = true;

                // Crear el botón para eliminar el campo
                const removeButton = document.createElement('button');
                removeButton.type = 'button';
                removeButton.className = 'btn btn-outline-danger remove-email-btn';
                removeButton.innerHTML = '<i class="bi bi-x-square-fill"></i>';

                // Crear un div para el botón de eliminación
                const removeDiv = document.createElement('div');
                removeDiv.className = 'input-group-append';
                removeDiv.appendChild(removeButton);

                // Agregar el campo de entrada y el botón de eliminación al nuevo input group
                newInputGroup.appendChild(newInput);
                newInputGroup.appendChild(removeDiv);

                // Añadir el nuevo input group al contenedor
                container.appendChild(newInputGroup);
            }

            if (event.target.closest('.remove-email-btn')) {
                const emailField = event.target.closest('.input-group');
                emailField.remove();
            }
        });

        // Validar que ningún campo de correo esté vacío antes de enviar el formulario
        document.getElementById('uploadForm').addEventListener('submit', function (e) {
            const emailInputs = document.querySelectorAll('input[name="correos[]"]');
            let valid = true;

            emailInputs.forEach(input => {
                if (input.value.trim() === '') {
                    valid = false;
                    input.classList.add('is-invalid');
                } else {
                    input.classList.remove('is-invalid');
                }
            });

            if (!valid) {
                e.preventDefault();
                alert('Por favor, complete todos los campos de correo.');
            }
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const dropZone = document.getElementById('drop-zone');
            const fileInput = document.getElementById('media');
            let fileList = [];

            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                dropZone.addEventListener(eventName, preventDefaults, false);
                document.body.addEventListener(eventName, preventDefaults, false);
            });

            function preventDefaults(e) {
                e.preventDefault();
                e.stopPropagation();
            }

            ['dragenter', 'dragover'].forEach(eventName => {
                dropZone.addEventListener(eventName, () => dropZone.classList.add('dragover'), false);
            });

            ['dragleave', 'drop'].forEach(eventName => {
                dropZone.addEventListener(eventName, () => dropZone.classList.remove('dragover'), false);
            });

            dropZone.addEventListener('drop', (e) => {
                handleFiles(e.dataTransfer.files);
            });

            dropZone.addEventListener('click', () => fileInput.click());

            fileInput.addEventListener('change', () => {
                handleFiles(fileInput.files);
            });

            function handleFiles(files) {
                for (let i = 0; i < files.length; i++) {
                    fileList.push(files[i]);
                }
                updateFileInput();
                displayFiles();
            }

            function updateFileInput() {
                const dataTransfer = new DataTransfer();
                fileList.forEach(file => {
                    dataTransfer.items.add(file);
                });
                fileInput.files = dataTransfer.files;
            }

            function displayFiles() {
                const fileListContainer = document.getElementById('file-list');
                fileListContainer.innerHTML = '';

                fileList.forEach((file, index) => {
                    const fileItem = document.createElement('div');
                    fileItem.classList.add('file-item');

                    const fileName = document.createElement('span');
                    fileName.textContent = file.name;

                    const removeButton = document.createElement('button');
                    removeButton.textContent = 'X';
                    removeButton.classList.add('remove-file');
                    removeButton.addEventListener('click', () => {
                        removeFile(index);
                    });

                    fileItem.appendChild(fileName);
                    fileItem.appendChild(removeButton);
                    fileListContainer.appendChild(fileItem);
                });
            }

            function removeFile(index) {
                fileList.splice(index, 1);
                updateFileInput();
                displayFiles();
            }
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"
        integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9JvLu4Bc5jn60ck69tannE2a5lWCbu2E1O1xT"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.min.js"
        integrity="sha384-Vw8uCM8B8h7z0Ah+HqKoPQsOMxRW0XOKNvbD2UszI4Jvcty9WKiJjU49vxgKwrp4"
        crossorigin="anonymous"></script>


    <script>
        document.addEventListener('click', function (event) {
            const navbarCollapse = document.getElementById('navbarNav');
            const isClickInside = navbarCollapse.contains(event.target);
            const isNavbarToggler = event.target.closest('.navbar-toggler');

            if (!isClickInside && navbarCollapse.classList.contains('show')) {
                new bootstrap.Collapse(navbarCollapse).hide();
            }

            if (isNavbarToggler) {
                new bootstrap.Collapse(navbarCollapse).toggle();
            }
        });
    </script>


</body>

<?php include '../css/footer.php'; ?>

</html>