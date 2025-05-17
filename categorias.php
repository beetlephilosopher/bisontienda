<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categorías de Productos</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
            <a class="navbar-brand" href="home.php" id="btn_inicio">TiendaOnline</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mr-auto">
                    
                    <li class="nav-item">
                        <a class="nav-link" href="search.php" id="btn_cursos">Buscar</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="profile.php" id="btn_perfil">Mi Perfil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="categorias.php" id="btn_categorias">Categorías</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="mis_listas.php" id="btn_listas">Mis listas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="carrito.php" id="btn_carrito">Carrito</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="ventas.php" id="btn_ventas">Venta</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="pedidos.php" id="btn_pedidos">Pedidos</a>
                    </li>
                    
                </ul>
                <!-- Barra de búsqueda -->
                <form class="form-inline my-2 my-lg-0">
                    <input class="form-control mr-sm-2" type="search" placeholder="Buscar productos..." aria-label="Buscar">
                    <button class="btn btn-outline-light my-2 my-sm-0" type="submit">Buscar</button>
                </form>
            </div>
        </nav>
    </header>
    <div class="container ">
    <h2>Categorías de Productos</h2>
    
    <ul id="listaCategorias">
        <div class="order-list">
        <li class="order"><a href="categoria.php?categoria=electronica">Electrónica</a></li>
        <li class="order"><a href="categoria.php?categoria=ropa">Ropa</a></li>
        <li class="order"><a href="categoria.php?categoria=hogar">Hogar</a></li>
        <li class="order"><a href="categoria.php?categoria=deportes">Deportes</a></li>
    </ul>
</div>
    </div>
    <script src="app.js"></script>
</body>
</html>