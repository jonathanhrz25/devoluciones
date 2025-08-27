<?php
session_start();
require 'connect.php';
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';
require '../PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $uploadDir = 'uploads/';

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $uploadedFiles = [];
    $errors = [];

    // Procesar múltiples archivos
    if (isset($_FILES['media']) && count($_FILES['media']['name']) > 0) {
        foreach ($_FILES['media']['name'] as $key => $filename) {
            $fileTmpName = $_FILES['media']['tmp_name'][$key];
            $fileType = $_FILES['media']['type'][$key];
            $fileError = $_FILES['media']['error'][$key];

            // Validar que el archivo sea una imagen o un video
            if ((strpos($fileType, 'image') !== false || strpos($fileType, 'video') !== false) && $fileError == 0) {
                $uploadFileName = uniqid() . '-' . basename($filename);
                $uploadFile = $uploadDir . $uploadFileName;

                if (move_uploaded_file($fileTmpName, $uploadFile)) {
                    $uploadedFiles[] = $uploadFileName;
                } else {
                    $errors[] = "Error al mover el archivo: $filename";
                }
            } else {
                $errors[] = "El archivo $filename no es una imagen o video válido, o hubo un error al cargarlo.";
            }
        }
    } else {
        $errors[] = "No se ha seleccionado ningún archivo.";
    }

    if (empty($errors) && !empty($uploadedFiles)) {
        // Procesar datos de formulario
        $cliente_opcion = isset($_POST['cliente']) ? $_POST['cliente'] : '';
        $num_cliente = '';

        if ($cliente_opcion === 'cliente1') {
            $verificar = isset($_POST['verificar']) ? $_POST['verificar'] : '';
            $stmt = $conn->prepare("SELECT * FROM videodb WHERE num_cliente = :num_cliente");
            $stmt->bindParam(':num_cliente', $verificar);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $num_cliente = $verificar;
            } else {
                echo "<script>
                        alert('Número de cliente no registrado.');
                        window.location.href = '../php/index.php';
                      </script>";
                exit();
            }
        } else if ($cliente_opcion === 'cliente2') {
            $num_cliente = isset($_POST['num_cliente']) ? $_POST['num_cliente'] : '';
        }

        $motivo = $_POST['motivo'];
        $description = isset($_POST['description']) ? $_POST['description'] : '';
        $correos = isset($_POST['correos']) ? $_POST['correos'] : [];
        $correos_str = implode(',', array_map('trim', $correos));

        // Guardar detalles de los archivos subidos en la base de datos
        foreach ($uploadedFiles as $file) {
            $stmt = $conn->prepare("INSERT INTO videodb (num_cliente, motivo, filename, description, correo) VALUES (:num_cliente, :motivo, :filename, :description, :correo)");
            $stmt->bindParam(':num_cliente', $num_cliente);
            $stmt->bindParam(':motivo', $motivo);
            $stmt->bindParam(':filename', $file);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':correo', $correos_str);
            $stmt->execute();
        }

        echo "Archivos subidos y datos guardados correctamente.<br>";

        // Enviar correos
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'mail.smtp2go.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'ticket@serva.com.mx';
            $mail->Password = 'Serva123.*';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 2525;
            $mail->SMTPDebug = 0;
            $mail->Debugoutput = 'html';
            $mail->setFrom('ticket@serva.com.mx', 'Automotriz Serva');

            $token = bin2hex(random_bytes(16));
            $expiration = date('Y-m-d H:i:s', strtotime('+60 minutes'));
            $stmt = $conn->prepare("INSERT INTO tokens (token, expiration) VALUES (:token, :expiration)");
            $stmt->bindParam(':token', $token);
            $stmt->bindParam(':expiration', $expiration);
            $stmt->execute();

            $link = "http://localhost/almacen/clientes.php?token=" . $token;

            $body = "
                <html>
                    <body>
                        Estimado Cliente.
                        <p>Un gusto saludarle, compartimos con usted la siguiente información de su video o imagen aclaratoria sobre devolución o garantía.</p>
                        <p>Motivo: " . htmlspecialchars($motivo) . "</p>
                        <p>Descripción: " . htmlspecialchars($description) . "</p>
                        <p><a href='" . $link . "'>Acceder al portal de clientes para ver video o imagen aclaratoria</a></p>
                        <p>Saludos cordiales.</p>
                        <p>Automotriz SERVA S.A de C.V.</p>
                        <p>AVISO DE PRIVACIDAD.</p>
                        <p>Este mensaje puede contener informaci&oacute;n confidencial y privilegiada. Si se ha enviado a usted por un error, por favor, responda o notifique al remitente del error e inmediatamente eliminarlo. Si usted no es el destinatario, no leer, copiar, divulgar o utilizar este mensaje. El remitente se exime de cualquier responsabilidad por el uso no autorizado. Recuerde que todos los correos electr&oacute;nicos entrantes enviados a las cuentas de correo electr&oacute;nico de AUTOMOTRIZ SERVA  ser&aacute;n archivados y podr&aacute;n ser escaneados por nosotros y/o por los proveedores de servicios externos para detectar y prevenir las amenazas a nuestros sistemas, investigar el comportamiento ilegal o inapropiado y/o eliminar e-mails  promocionales no solicitados (spam). Este proceso podr&iacute;a resultar la eliminaci&oacute;n de un correo electr&oacute;nico leg&iacute;timo antes de que sea le&iacute;do por su destinatario en nuestra organizaci&oacute;n. Por otra parte, con base en los resultados del an&aacute;lisis, el texto completo de los correos electr&oacute;nicos y archivos adjuntos pueden ser puestos a disposici&oacute;n del personal de seguridad y otros para su revisi&oacute;n y acci&oacute;n correspondiente. Si tiene alguna pregunta sobre este proceso, por favor p&oacute;ngase en contacto con nosotros en  contacto@serva.com.mx</p>
                    </body>
                </html>
            ";

            $mail->isHTML(true);
            $mail->Subject = 'IMAGENES O VIDEO ACLARACION';
            $mail->Body = $body;

            foreach ($correos as $correo) {
                $mail->clearAddresses();
                $mail->addAddress($correo, 'Avisos Automotriz Serva');

                if (!$mail->send()) {
                    echo "<script>
                            alert('Los archivos fueron subidos pero hubo un error al enviar el correo a {$correo}: {$mail->ErrorInfo}');
                          </script>";
                }
            }

            echo "<script>
                    alert('Los archivos fueron subidos exitosamente y los correos fueron enviados.');
                    window.location.href = '../php/index.php';
                  </script>";

        } catch (Exception $e) {
            echo "<script>
                    alert('Los archivos fueron subidos exitosamente, pero hubo un error al enviar los correos: {$mail->ErrorInfo}');
                    window.location.href = '../php/index.php';
                  </script>";
        }

    } else {
        echo "Errores:<br>";
        foreach ($errors as $error) {
            echo $error . "<br>";
        }
        echo "<script>
                alert('Hubo un problema con la subida de archivos.');
                window.location.href = '../php/index.php';
              </script>";
    }
}
$conn = null;
?>