<?php
session_start();
include 'conexion.php';
$error_message = "";

$db = new Conexion();
$conn = $db->conn;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST['usuario'] ?? '';
    $contrasena = $_POST['contra'] ?? '';

    $sql = "CALL IniciarSesion(?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $usuario, $contrasena);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        $_SESSION['id_usuario'] = $user['id_usuario'];
        $_SESSION['correo'] = $user['correo'];
        $_SESSION['nombre_usuario'] = $user['nombre_usuario'];
        $_SESSION['avatar'] = $user['avatar'];
        $_SESSION['rol'] = $user['rol'];
        
        header("Location: home.php"); 
        exit();
    } else {
        $error_message = "Usuario o contraseña incorrecta.";
    }
    
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de sesión | Bisontienda</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        :root {
            --cafe-oscuro: #5D4037;
            --cafe-medio: #8D6E63;
            --cafe-claro: #D7CCC8;
            --crema: #F5F5F0;
            --texto-oscuro: #3E2723;
            --dorado: #FFD700;
        }
        
        body {
            font-family: 'Segoe UI', system-ui, sans-serif;
            background-color: var(--crema);
            color: var(--texto-oscuro);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background-image: linear-gradient(rgba(245, 245, 240, 0.9), rgba(245, 245, 240, 0.9)), url('img/fondo-login.jpg');
            background-size: cover;
        }
        
        .login-container {
            width: 100%;
            max-width: 400px;
            padding: 30px;
            background-color: white;
            box-shadow: 0 5px 15px rgba(93, 64, 55, 0.1);
            border-radius: 10px;
            border: 1px solid var(--cafe-claro);
        }
        
        .login-container h2 {
            color: var(--cafe-oscuro);
            text-align: center;
            margin-bottom: 25px;
        }
        
        .login-container input[type="text"],
        .login-container input[type="password"] {
            width: 100%;
            padding: 12px 15px;
            margin: 10px 0;
            border: 2px solid var(--cafe-claro);
            border-radius: 6px;
            font-size: 16px;
        }
        
        .login-container input[type="submit"] {
            width: 100%;
            padding: 12px;
            background-color: var(--cafe-medio);
            border: none;
            color: white;
            font-weight: 500;
            border-radius: 6px;
            cursor: pointer;
            transition: background-color 0.3s;
            margin-top: 10px;
        }
        
        .login-container input[type="submit"]:hover {
            background-color: var(--cafe-oscuro);
        }
        
        .login-container a {
            display: block;
            text-align: center;
            margin-top: 15px;
            color: var(--cafe-medio);
            text-decoration: none;
            transition: color 0.3s;
        }
        
        .login-container a:hover {
            color: var(--cafe-oscuro);
        }
        
        .error-message {
            color: #dc3545;
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            padding: 10px;
            border-radius: 5px;
            text-align: center;
            margin-bottom: 15px;
        }
        
        .logo {
            text-align: center;
            margin-bottom: 20px;
        }
        
        .logo img {
            width: 180px;
            height: auto;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="logo">
            <img src="bisontienda.png" alt="Bisontienda">
        </div>
        
        <h2>Inicio de sesión</h2>
        
        <?php if (!empty($error_message)) : ?>
            <p class="error-message"><?php echo $error_message; ?></p>
        <?php endif; ?>

        <form name="loginForm" method="post" action="login.php" onsubmit="return validarLogin()">
            <input name="usuario" id="usuario" type="text" placeholder="Usuario" required>
            <input name="contra" id="contra" type="password" placeholder="Contraseña" required>
            <input type="submit" value="Ingresar">
            <a href="register.php">¿No tienes una cuenta? Regístrate</a>
        </form>
    </div>
    <script src="validaciones.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.4.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>