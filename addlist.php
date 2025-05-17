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
                        <a class="nav-link" href="home.php" id="btn_inicio">Inicio</a>
                    </li>
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
                    <li class="nav-item">
                        <a class="nav-link" href="cotizacion.php" id="btn_pedidos">Cotizacion</a>
                    </li>
                </ul>
            </div>
        </nav>
    </header>

    <main class="container mt-5">
        <h1 class="text-center">Crear Nueva Lista</h1>


        <section class="mt-4">
            <form action="guardar_lista.php" method="POST" class="mt-4">
                <div class="form-group">
                    <label for="nombreLista">Nombre de la Lista</label>
                    <input type="text" class="form-control" id="nombreLista" name="nombreLista" placeholder="Escribe el nombre de tu lista" required>
                </div>
                <div class="form-group">
                    <label for="descripcionLista">Descripción</label>
                    <textarea class="form-control" id="descripcionLista" name="descripcionLista" placeholder="Añade una descripción a tu lista" rows="3" required></textarea>
                </div>
                <div class="form-group">
                    <label for="imagenLista">Imágenes (opcional)</label>
                    <input type="file" class="form-control-file" id="imagenLista" name="imagenLista" accept="image/*">
                </div>
                <div class="form-group">
                    <label>Tipo de Lista</label><br>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="tipoLista" id="publica" value="publica" checked>
                        <label class="form-check-label" for="publica">Pública</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="tipoLista" id="privada" value="privada">
                        <label class="form-check-label" for="privada">Privada</label>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary mt-3">Guardar Lista</button>
            
        </section>

        
        
    </main>

 
</body>
</html>
