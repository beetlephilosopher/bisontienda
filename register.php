<?php
session_start();
include 'conexion.php';

// Habilitar reporte de errores para debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

$db = new Conexion();
$conn = $db->conn;

// Variable para mensajes
$_SESSION['mensaje'] = '';
$_SESSION['error'] = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $usuario = trim($_POST['usuario']);
        $nombre_completo = trim($_POST['nombre_completo']);
        $correo = trim($_POST['correo']);
        $contrasena = $_POST['contra'];
        $confirm_contrasena = $_POST['confirmContra'];
        $fecha_nacimiento = $_POST['fecha_nacimiento'];
        $sexo = $_POST['sexo'];
        $avatar = null;

        // Validación de contraseñas
        if ($contrasena !== $confirm_contrasena) {
            throw new Exception("Las contraseñas no coinciden.");
        }

        // Verificar usuario/email existente
        $stmt = $conn->prepare("SELECT id_usuario FROM usuarios WHERE nombre_usuario = ? OR correo = ?");
        $stmt->bind_param("ss", $usuario, $correo);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            throw new Exception("El nombre de usuario o el correo electrónico ya están en uso.");
        }
        $stmt->close();

        // Manejo de avatar
        if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] == 0) {
            $upload_dir = 'uploads/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }
            $avatar = $upload_dir . basename($_FILES['avatar']['name']);
            if (!move_uploaded_file($_FILES['avatar']['tmp_name'], $avatar)) {
                throw new Exception("Error al subir la imagen.");
            }
        }

        // Registrar usuario
        $sql = "CALL RegistrarUsuario(?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssss", $correo, $usuario, password_hash($contrasena, PASSWORD_DEFAULT), $avatar, $nombre_completo, $fecha_nacimiento, $sexo);

        if ($stmt->execute()) {
            $_SESSION['mensaje'] = "Registro exitoso. Ahora puedes iniciar sesión.";
            $_SESSION['error'] = false;
            header("Location: login.php");
            exit();
        } else {
            throw new Exception("Error en el registro: " . $conn->error);
        }
    } catch (Exception $e) {
        $_SESSION['mensaje'] = $e->getMessage();
        $_SESSION['error'] = true;
        header("Location: register.php");
        exit();
    } finally {
        if (isset($stmt)) $stmt->close();
        $conn->close();
    }
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <link rel="stylesheet" href="styles.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuario</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="page_in.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Registro de Usuario</h2>
        <form id="registerForm" method="post" action="register.php" enctype="multipart/form-data" onsubmit="return validarRegistro()">
            <div class="form-group">
                <label for="username">Nombre de Usuario</label>
                <input type="text" class="form-control" id="username" name="usuario" placeholder="Nombre de usuario" required>
            </div>
            <div class="form-group">
                <label for="fullname">Nombre Completo</label>
                <input type="text" class="form-control" id="fullname" name="nombre_completo" placeholder="Nombre Completo" required>
            </div>
            <div class="form-group">
                <label for="email">Correo Electrónico</label>
                <input type="email" class="form-control" id="email" name="correo" placeholder="example@correo.com" required>
            </div>
            <div class="form-group">
                <label for="password">Contraseña</label>
                <input type="password" class="form-control" id="password" name="contra" placeholder="Contraseña" required>
                <small id="passwordHelp" class="form-text text-muted">
                    La contraseña debe contener al menos una letra mayúscula, una letra minúscula, un número y un carácter especial.
                </small>
            </div>
            <div class="form-group">
                <label for="confirmPassword">Confirmar Contraseña</label>
                <input type="password" class="form-control" id="confirmPassword" name="confirmContra" placeholder="Confirmar contraseña" required>
            </div>
            <div class="form-group">
                <label for="avatar">Foto de Perfil</label>
                <input type="file" class="form-control-file" id="avatar" name="avatar" accept="image/*">
            </div>
            <div class="form-group">
                <label for="birthdate">Fecha de Nacimiento</label>
                <input type="date" class="form-control" id="birthdate" name="fecha_nacimiento" required>
            </div>
            <div class="form-group">
                <label for="sexo">Sexo</label>
                <select class="form-control" id="sexo" name="sexo" required>
                    <option value="">Selecciona tu sexo</option>
                    <option value="masculino">Masculino</option>
                    <option value="femenino">Femenino</option>
                    <option value="otro">Otro</option>
                </select>
            </div>
            <div id="error-message" class="error text-danger"></div>
            <button type="submit" class="btn btn-primary">Registrarse</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="validaciones.js"></script> 
</body>
</html>