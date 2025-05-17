<?php
session_start();

if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_SESSION['nombre_usuario']) || !isset($_SESSION['correo']) || !isset($_SESSION['avatar'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['nombre_usuario'];
$correo = $_SESSION['correo'];
$avatar = $_SESSION['avatar'];
include 'conexion.php';

$id_usuario = $_SESSION['id_usuario'];

try {
    $db = new Conexion();
    $conn = $db->conn;

    $stmt = $conn->prepare("SELECT id_carrito FROM carrito WHERE id_usuario = ?");
    $stmt->bind_param("i", $id_usuario);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $id_carrito = $row['id_carrito'];
        
        $stmt_detalle = $conn->prepare("
            SELECT 
                cd.id_detalle, 
                p.nombre AS prod_name, 
                p.descripcion AS prod_description, 
                p.precio AS prod_price, 
                cd.cantidad, 
                COALESCE(p.imagen1, p.imagen2, p.imagen3, 'noimagen.png') AS prod_image
            FROM carrito_detalle cd
            JOIN productos p ON cd.id_producto = p.id_producto
            WHERE cd.id_carrito = ?
        ");
        $stmt_detalle->bind_param("i", $id_carrito);
        $stmt_detalle->execute();
        $result_detalle = $stmt_detalle->get_result();
        $items = $result_detalle->fetch_all(MYSQLI_ASSOC);

        $total_carrito = 0;
        foreach ($items as $item) {
            $total_carrito += $item['prod_price'] * $item['cantidad'];
        }
    } else {
        $items = [];
        $total_carrito = 0;
    }

    $stmt->close();
    if (isset($stmt_detalle)) {
        $stmt_detalle->close();
    }
    $conn->close();
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito de Compras</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    
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
    <h2>Tu Carrito de Compras</h2>
    <?php if (!empty($items) && count($items) > 0): ?>
        <div class="row">
            <?php foreach ($items as $item): ?>
                <div class="col-md-4">
                    <div class="card">
                        <img src="<?php echo htmlspecialchars($item['prod_image']); ?>" alt="<?php echo htmlspecialchars($item['prod_name']); ?>" class="card-img-top" style="width: 100%; height: 200px; object-fit: cover;">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($item['prod_name']); ?></h5>
                            <p class="card-text"><?php echo htmlspecialchars($item['prod_description']); ?></p>
                            <p class="card-text"><strong>Precio: </strong>$<?php echo number_format($item['prod_price'], 2); ?></p>
                            <div class="d-flex align-items-center mb-3">
                                <strong>Cantidad: </strong>
                                <input type="number" class="form-control input-cantidad ml-2 mr-2" value="<?php echo htmlspecialchars($item['cantidad']); ?>" min="1" data-id="<?php echo $item['id_detalle']; ?>">
                                <button class="btn btn-primary btn-sm update-quantity" data-id="<?php echo $item['id_detalle']; ?>">Actualizar</button>
                            </div>
                            <button class="btn btn-danger btn-sm remove-item" data-id="<?php echo $item['id_detalle']; ?>">Eliminar</button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="mt-4">
            <h4>Total del Carrito: $<span id="totalCarrito"><?php echo number_format($total_carrito, 2); ?></span></h4>
        </div>
        <button class="btn btn-success mt-4" id="procesarPedido">Procesar Pedido</button>
    <?php else: ?>
        <p class="text-muted">Tu carrito está vacío.</p>
    <?php endif; ?>
</main>

<script>
    function actualizarTotalCarrito() {
        let total = 0;
        document.querySelectorAll('.card').forEach(function(card) {
            const precio = parseFloat(card.querySelector('.card-text strong').nextSibling.nodeValue.trim().replace('$', ''));
            const cantidad = parseInt(card.querySelector('.input-cantidad').value);
            total += precio * cantidad;
        });
        document.getElementById('totalCarrito').innerText = total.toFixed(2);
    }

    document.addEventListener('click', function(event) {
        if (event.target.classList.contains('remove-item')) {
            const cartDetailId = event.target.getAttribute('data-id');

            fetch('eliminar_del_carrito.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ cart_detail_id: cartDetailId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al eliminar el producto del carrito. Inténtalo nuevamente.');
            });
        }
    });

    document.addEventListener('click', function(event) {
        if (event.target.classList.contains('update-quantity')) {
            const cartDetailId = event.target.getAttribute('data-id');
            const inputElement = document.querySelector(`.input-cantidad[data-id='${cartDetailId}']`);
            const newQuantity = inputElement.value;

            fetch('actualizar_cantidad_carrito.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ cart_detail_id: cartDetailId, cantidad: newQuantity })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    actualizarTotalCarrito();
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al actualizar la cantidad del producto. Inténtalo nuevamente.');
            });
        }
    });

    document.addEventListener('input', function(event) {
        if (event.target.classList.contains('input-cantidad')) {
            actualizarTotalCarrito();
        }
    });

    document.getElementById('procesarPedido').addEventListener('click', function() {
        window.location.href = 'metpago.php';
    });
    
</script>
</body>
</html>
