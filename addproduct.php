<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Listas</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
            <a class="navbar-brand" href="#">TiendaOnline</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item active">
                        <a class="nav-link" href="home.html" id="btn_inicio">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="search.html" id="btn_cursos">Buscar</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="profile.html" id="btn_perfil">Mi Perfil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="categorias.html" id="btn_categorias">Categorías</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="mis_listas.html" id="btn_listas">Mis listas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="carrito.html" id="btn_carrito">Carrito</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="ventas.html" id="btn_ventas">Venta</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="pedidos.html" id="btn_pedidos">Pedidos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="cotizacion.html" id="btn_pedidos">Cotizacion</a>
                    </li>
                </ul>
            </div>
        </nav>
    </header>

    <main class="container mt-5">
        <h1 class="text-center">Agregar Productos a la Lista</h1>


        <section class="mt-4">
            <h3 class="mt-4"></h3>
            <div id="productosContainer">
                <div class="producto mb-3">
                    <div class="form-group">
                        <label for="nombreProducto1">Nombre del Objeto</label>
                        <input type="text" class="form-control" id="nombreProducto1" name="productos[0][nombre]" placeholder="Nombre del producto" required>
                    </div>
                    <div class="form-group">
                        <label for="descripcionProducto1">Descripción</label>
                        <textarea class="form-control" id="descripcionProducto1" name="productos[0][descripcion]" placeholder="Descripción del producto" rows="2" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="precioProducto1">Precio (opcional)</label>
                        <input type="number" class="form-control" id="precioProducto1" name="productos[0][precio]" placeholder="Precio del producto">
                    </div>
                </div>
            </div>

            <button type="button" class="btn btn-secondary" id="agregarProducto">Agregar Producto</button>
            
        </section>

        
        
    </main>

 
</body>
</html>
