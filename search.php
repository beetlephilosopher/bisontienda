<?php
session_start();


if (!isset($_SESSION['nombre_usuario']) || !isset($_SESSION['correo']) || !isset($_SESSION['avatar'])) {
    
    header("Location: login.php");
    exit();
}


$username = $_SESSION['nombre_usuario'];
$correo = $_SESSION['correo'];
$avatar = $_SESSION['avatar'];
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
    <h1 class="text-center mb-4">Búsqueda de Productos</h1>

    
    <form method="GET" action="results.php" class="mb-4">
        <div class="input-group">
            
            <div class="input-group-prepend">
                <select class="custom-select" id="categorySelect" name="category">
                    <option value="">Todas las categorías</option>
                    <option value="electronica">Electrónica</option>
                    <option value="ropa">Ropa</option>
                    <option value="hogar">Hogar</option>
                    <option value="deportes">Deportes</option>
                    <option value="juguetes">Juguetes</option>
                    <option value="libros">Libros</option>
                    
                </select>
            </div>
            <input type="text" name="query" class="form-control" placeholder="Buscar producto o usuario..." required>
            <div class="input-group-append">
                <button class="btn btn-primary" type="submit">Buscar</button>
            </div>
        </div>
    </form>

    
    <div class="filter-section">
        <h4>Filtros de Búsqueda</h4>
        <div class="form-row">
            <div class="col-md-4 mb-3">
                <label for="priceFilter" class="filter-label">Filtrar por Precio</label>
                <select id="priceFilter" class="form-control">
                    <option value="">Seleccionar...</option>
                    <option value="asc">Menor a Mayor</option>
                    <option value="desc">Mayor a Menor</option>
                </select>
            </div>
            <div class="col-md-4 mb-3">
                <label for="ratingFilter" class="filter-label">Filtrar por Calificación</label>
                <select id="ratingFilter" class="form-control">
                    <option value="">Seleccionar...</option>
                    <option value="high">Mejor Calificados</option>
                    <option value="low">Peor Calificados</option>
                </select>
            </div>
            <div class="col-md-4 mb-3">
                <label for="salesFilter" class="filter-label">Filtrar por vendedor</label>
                <select id="salesFilter" class="form-control">
                    <option value="">Seleccionar...</option>
                    <option value="most">Más Vendidos</option>
                    <option value="least">Menos Vendidos</option>
                </select>
            </div>
        </div>
        <button type="button" class="btn filter-btn">Aplicar Filtros</button>
    </div>
</main>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
