<?php
session_start();
include 'conexion.php'; 


if (!isset($_SESSION['nombre_usuario']) || !isset($_SESSION['correo']) || !isset($_SESSION['avatar'])) {
    
    header("Location: login.php");
    exit();
}


$username = $_SESSION['nombre_usuario'];
$correo = $_SESSION['correo'];
$avatar = $_SESSION['avatar'];


$db = new Conexion();
$conn = $db->conn;


$id_usuario = $_SESSION['id_usuario'];
$sql = "SELECT nombre, descripcion, precio, imagen1, imagen2, imagen3 FROM productos WHERE creado_por = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página Principal</title>
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

        .btn-yellow {
            background-color: #FFC107; 
            color: #fff; 
            border: none; 
        }
        .btn-yellow:hover {
            background-color: #FFB300; 
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

            <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'administrador'): ?>
                <li class="nav-item"><a class="nav-link" href="modo_admin.php">Modo admin</a></li>
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
        <div class="d-flex flex-column align-items-center">
            <img src="<?php echo htmlspecialchars($avatar); ?>" alt="Foto de Perfil" class="rounded-circle mb-3" style="width: 150px; height: 150px;">
            <h2 class="mb-1"><?php echo htmlspecialchars($username); ?></h2>
            <p class="text-muted"><?php echo htmlspecialchars($correo); ?></p>
        </div>
        <p>Órdenes Completadas: 5</p>
        <button class="btn btn-primary" onclick="window.location.href='modificar.php'">Modificar</button>
    </div>

    <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] !== 'cliente'): ?>
        <div class="card mt-4">
            <h3 class="card-header">Productos Publicados</h3>
            <div class="row">
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<div class='col-md-4 mb-3'>";
                        echo "<div class='card'>";

                        
                        $imagen = !empty($row['imagen1']) ? $row['imagen1'] : (!empty($row['imagen2']) ? $row['imagen2'] : (!empty($row['imagen3']) ? $row['imagen3'] : 'noimagen.png'));
                        echo "<img src='" . htmlspecialchars($imagen) . "' class='card-img-top' alt='" . htmlspecialchars($row['nombre']) . "'>";

                        echo "<div class='card-body'>";
                        echo "<h5 class='card-title'>" . htmlspecialchars($row['nombre']) . "</h5>";
                        echo "<p class='card-text'><strong>Precio: $" . number_format($row['precio'], 2) . "</strong></p>";
                        echo "</div>";
                        echo "</div>";
                        echo "</div>";
                    }
                } else {
                    echo "<p class='text-muted mx-auto'>No has publicado ningún producto.</p>";
                }
                ?>
            </div>
        </div>
    <?php endif; ?>
</main>

<footer class="text-center mt-5">
    <p>&copy; 2024 Bisontienda. Todos los derechos reservados.</p>
</footer>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
