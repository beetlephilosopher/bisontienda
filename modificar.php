<?php
session_start();
include 'conexion.php'; 


if (!isset($_SESSION['id_usuario']) || !isset($_SESSION['nombre_usuario']) || !isset($_SESSION['correo'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['nombre_usuario'];
$correo = $_SESSION['correo'];
$avatar = $_SESSION['avatar'];
$id_usuario = $_SESSION['id_usuario'];


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $uploadDir = 'uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

   
    if (!empty($_FILES['avatar']['name'])) {
        $avatarName = basename($_FILES['avatar']['name']);
        $avatarPath = $uploadDir . $avatarName;

        if (move_uploaded_file($_FILES['avatar']['tmp_name'], $avatarPath)) {
            
            $db = new Conexion();
            $conn = $db->conn;
            $stmt = $conn->prepare("UPDATE usuarios SET avatar = ? WHERE id_usuario = ?");
            $stmt->bind_param("si", $avatarPath, $id_usuario);

            if ($stmt->execute()) {
                $_SESSION['avatar'] = $avatarPath; 
                header("Location: profile.php");
                exit();
            } else {
                echo "Error al actualizar el avatar: " . $stmt->error;
            }

            $stmt->close();
            $conn->close();
        } else {
            echo "Error al subir el archivo.";
        }
    } else {
        echo "Por favor, selecciona un archivo para subir.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificar Perfil</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <style>
        body {
            background-color: #f0f8ff;
            color: #333;
        }
        .navbar {
            background-color: #007bff;
        }
        .navbar-brand, .nav-link {
            color: #fff !important;
        }
        .card {
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            padding: 20px;
        }
       
        .icon-carrito {
            width: 24px;
            height: 24px;
            margin-right: 5px;
        }
    </style>
</head>
<body>
<header>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <a class="navbar-brand" href="home.php">
        <img src="bisontienda.png" alt="Logo TiendaOnline" style="width: 180px; height: 40px;">
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item"><a class="nav-link" href="search.php">Buscar</a></li>

            <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] !== 'cliente'): ?>
                <li class="nav-item"><a class="nav-link" href="vender.php">Vender</a></li>
            <?php endif; ?>

            <li class="nav-item"><a class="nav-link" href="mis_listas.php">Mis listas</a></li>

            <li class="nav-item"><a class="nav-link" href="pedidos.php" id="btn_pedidos">Pedidos</a></li>
        </ul>

        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link d-flex align-items-center" href="carrito.php">
                    <img src="carrito.png" alt="Carrito" class="icon-carrito">
                    <span>Carrito</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link d-flex align-items-center" href="chat_vendedor.php">
                    <img src="chat.png" alt="Mensajes" class="icono-chat" style="width: 24px; height: 24px; margin-right: 5px;">
                    <span>Mensajes</span>
                </a>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="btn_perfil" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <img src="<?php echo htmlspecialchars($avatar); ?>" alt="Usuario" class="rounded-circle" width="30" height="30">
                    <span class="ml-2"><?php echo htmlspecialchars($username); ?></span>
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="btn_perfil">
                    <a class="dropdown-item" href="profile.php">Ver Perfil</a>
                    <a class="dropdown-item" href="settings.php">Configuración</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="logout.php">Cerrar Sesión</a>
                </div>
            </li>
        </ul>
    </div>
</nav>


</header>

<main class="container mt-5">
    <div class="card text-center mx-auto p-4" style="max-width: 400px;">
        <h2>Modificar Avatar</h2>
        <form action="modificar.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="avatar">Selecciona un nuevo avatar:</label>
                <input type="file" class="form-control-file" id="avatar" name="avatar" required>
            </div>
            <button type="submit" class="btn btn-primary">Actualizar Avatar</button>
        </form>
    </div>
</main>

<footer class="text-center mt-5">
    <p>&copy; 2024 Bisontienda. Todos los derechos reservados.</p>
</footer>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
