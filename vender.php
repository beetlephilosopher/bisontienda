<?php
session_start();

if (!isset($_SESSION['nombre_usuario']) || !isset($_SESSION['correo']) || !isset($_SESSION['avatar'])) {

    header("Location: login.php");
    exit();
}


$username = $_SESSION['nombre_usuario'];
$correo = $_SESSION['correo'];
$avatar = $_SESSION['avatar'];


$username = $_SESSION['nombre_usuario'];
$correo = $_SESSION['correo'];
$avatar = $_SESSION['avatar'];
$rol = $_SESSION['rol'];


if ($rol === 'cliente') {
    
    echo "<script>alert('No tienes permiso para vender productos.'); window.location.href = 'home.php';</script>";
    exit();
}
?>



<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vender un Producto</title>
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
    <h1 class="text-center">Registrar Producto</h1>
    <form action="procesar_producto.php" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="nombre">Nombre del Producto</label>
            <input type="text" class="form-control" id="nombre" name="nombre" required>
        </div>
        <div class="form-group">
            <label for="descripcion">Descripción</label>
            <textarea class="form-control" id="descripcion" name="descripcion" rows="3" required></textarea>
        </div>
        <div class="form-group">
            <label for="imagen1">Imágenes del Producto (mínimo 1)</label>
            <input type="file" class="form-control-file" id="imagen1" name="imagenes[]" required>
            <input type="file" class="form-control-file mt-2" id="imagen2" name="imagenes[]">
            <input type="file" class="form-control-file mt-2" id="imagen3" name="imagenes[]">
        </div>
        <div class="form-group">
            <label for="video">Video del Producto (opcional)</label>
            <input type="file" class="form-control-file" id="video" name="video">
        </div>
        <div class="form-group">
            <label for="categoria">Categoría</label>
            <select class="form-control" id="categoria" name="categoria" required>
                <option value="1">Electrónica</option>
                <option value="2">Ropa</option>
                <option value="3">Videojuegos</option>
            </select>
        </div>
        <div class="form-group">
            <label for="tipo">Tipo</label>
            <select class="form-control" id="tipo" name="tipo" required>
                <option value="venta">Venta</option>
                <option value="cotizacion">Cotización</option>
            </select>
        </div>
        <div class="form-group" id="precio-group">
            <label for="precio">Precio ($)</label>
            <input type="number" class="form-control" id="precio" name="precio" step="0.01">
        </div>
        <div class="form-group">
            <label for="cantidad">Cantidad Disponible</label>
            <input type="number" class="form-control" id="cantidad" name="cantidad" required>
            <small id="disponibilidad-message" class="form-text text-danger" style="display: none;">No hay disponibilidad.</small>
        </div>
        <div class="form-group">
            <label>Valoración(del 1 al 10) </label>
            <input type="number" class="form-control" id="valoracion" name="valoracion" min="1" max="10" step="0.1" required>
        </div>
        <div class="form-group">
            <label for="comentarios">Comentarios</label>
            <textarea class="form-control" id="comentarios" name="comentarios" rows="3"></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Registrar Producto</button>
    </form>
</main>




<script>
   
    document.getElementById('tipo').addEventListener('change', function() {
        var tipo = this.value;
        var precioGroup = document.getElementById('precio-group');
        if (tipo === 'venta') {
            precioGroup.style.display = 'block';
        } else {
            precioGroup.style.display = 'none';
        }
    });

    
    document.getElementById('cantidad').addEventListener('input', function() {
        var cantidad = parseInt(this.value);
        var message = document.getElementById('disponibilidad-message');
        if (cantidad <= 0) {
            message.style.display = 'block';
        } else {
            message.style.display = 'none';
        }
    });
</script>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.4.0/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>