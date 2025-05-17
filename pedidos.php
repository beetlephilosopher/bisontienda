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
$id_usuario = $_SESSION['id_usuario'];


$db = new Conexion();
$conn = $db->conn;

$fechaInicio = $_GET['fechaInicio'] ?? null;
$fechaFin = $_GET['fechaFin'] ?? null;
$categoria = $_GET['categoria'] ?? 'todas';


$query = "
    SELECT p.id_pedido, p.fecha_hora, c.nombre AS categoria, pr.nombre AS producto, dv.calificacion, p.estado
    FROM pedidos p
    JOIN detalles_pedido dp ON p.id_pedido = dp.id_pedido
    JOIN productos pr ON dp.id_producto = pr.id_producto
    JOIN categorias c ON pr.id_categoria = c.id_categoria
    LEFT JOIN detalles_venta dv ON dv.id_producto = pr.id_producto
    WHERE p.id_comprador = ? AND p.estado = 'activo'";


$params = [$id_usuario];
$types = "i";

if ($fechaInicio) {
    $query .= " AND p.fecha_hora >= ?";
    $params[] = $fechaInicio;
    $types .= "s";
}

if ($fechaFin) {
    $query .= " AND p.fecha_hora <= ?";
    $params[] = $fechaFin;
    $types .= "s";
}

if ($categoria && $categoria != 'todas') {
    $query .= " AND c.nombre = ?";
    $params[] = $categoria;
    $types .= "s";
}

$query .= " ORDER BY p.fecha_hora DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulta de Pedidos</title>
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
                        <a class="nav-link d-flex align-items-center" href="mensajes.php">
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
        <h1 class="text-center">Consulta de Pedidos</h1>
        <section class="mt-4">
            <form class="form-inline justify-content-center" method="GET" action="pedidos.php">
                <label for="fechaInicio" class="mr-2">Fecha Inicio:</label>
                <input type="date" id="fechaInicio" name="fechaInicio" class="form-control mr-4">
                <label for="fechaFin" class="mr-2">Fecha Fin:</label>
                <input type="date" id="fechaFin" name="fechaFin" class="form-control mr-4">
                <label for="categoria" class="mr-2">Categoría:</label>
                <select id="categoria" name="categoria" class="form-control">
                    <option value="todas">Todas</option>
                    <option value="videojuegos">Videojuegos</option>
                    <option value="ropa">Ropa</option>
                    <option value="arte">Arte</option>
                </select>
                <button type="submit" class="btn btn-primary ml-4">Buscar</button>
            </form>
        </section>

        <section class="mt-5">
            <h2 class="text-center">Resultados de la Consulta</h2>
            <table class="table table-bordered mt-3">
                <thead>
                    <tr>
                        <th>Fecha y Hora de Compra</th>
                        <th>Categoría</th>
                        <th>Producto</th>
                        <th>Calificación</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['fecha_hora']); ?></td>
                                <td><?php echo htmlspecialchars($row['categoria']); ?></td>
                                <td><?php echo htmlspecialchars($row['producto']); ?></td>
                                <td><?php echo htmlspecialchars($row['calificacion'] ?? 'Sin calificar'); ?></td>
                                <td>
                                    <form method="POST" action="cancelar_pedido.php" style="display: inline;">
                                        <input type="hidden" name="id_pedido" value="<?php echo $row['id_pedido']; ?>">
                                        <button type="submit" class="btn btn-danger btn-sm">Cancelar Pedido</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center">No se encontraron pedidos.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>
    </main>

    <footer class="text-center mt-5">
        <p>&copy; 2024 Bisontienda. Todos los derechos reservados.</p>
    </footer>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
