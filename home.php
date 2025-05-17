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
    <link rel="stylesheet" href="styles.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página Principal</title>
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
            background-color: var(--crema);
            color: var(--texto-oscuro);
            font-family: 'Segoe UI', system-ui, sans-serif;
        }
        .navbar {
            background-color: var(--cafe-oscuro);
        }
        .navbar-brand, .nav-link {
            color: var(--cafe-claro) !important;
        }
        .nav-link:hover {
            color: white !important;
        }
        .card {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(93, 64, 55, 0.1);
            margin-bottom: 20px;
            padding: 20px;
            border: 1px solid var(--cafe-claro);
        }
        
        .icon-carrito {
            width: 24px;
            height: 24px;
            margin-right: 5px;
            filter: brightness(0) invert(0.8);
        }

        .btn-yellow {
            background-color: var(--dorado);
            color: var(--texto-oscuro);
            border: none;
        }
        .btn-yellow:hover {
            background-color: #FFC107;
        }
        
        footer {
            background-color: var(--cafe-oscuro);
            color: var(--cafe-claro);
            padding: 15px 0;
            margin-top: 30px;
        }
        
        .dropdown-menu {
            border: 1px solid var(--cafe-claro);
        }
        
        .dropdown-item:hover {
            background-color: var(--crema);
        }
        
        .form-control, .custom-select {
            border: 1px solid var(--cafe-claro);
        }
        
        .form-control:focus, .custom-select:focus {
            border-color: var(--cafe-medio);
            box-shadow: 0 0 0 0.2rem rgba(141, 110, 99, 0.25);
        }
    </style>
</head>
<body>
<header>
    <nav class="navbar navbar-expand-lg navbar-dark">
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
                        <img src="chat.png" alt="Mensajes" class="icon-carrito">
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

<main class="container text-center mt-5">
    
    <form class="form-inline justify-content-center mb-4">
        <div class="input-group" style="width: 70%;">
            <div class="input-group-prepend">
                <select class="custom-select" id="categorySelect">
                    <option value="">Todas las categorías</option>
                    <option value="1">Electrónica</option>
                    <option value="2">Ropa</option>
                    <option value="3">Videojuegos</option>
                </select>
            </div>
            <input type="text" id="searchQuery" class="form-control" placeholder="Buscar producto o usuario...">
        </div>
    </form>

   
    <section id="searchResults" class="mt-4"></section>

    
</main>

<footer class="text-center py-3">
    <p>&copy; 2024 Bisontienda. Todos los derechos reservados.</p>
</footer>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.4.0/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
    
    document.getElementById('searchQuery').addEventListener('input', function() {
        const query = this.value;
        const category = document.getElementById('categorySelect').value;

        if (query.length > 0) {
            const xhr = new XMLHttpRequest();
            xhr.open('GET', `buscar_producto.php?query=${encodeURIComponent(query)}&category=${encodeURIComponent(category)}`, true);
            xhr.onload = function() {
                if (xhr.status === 200) {
                    document.getElementById('searchResults').innerHTML = xhr.responseText;
                } else {
                    console.error('Error en la solicitud AJAX.');
                }
            };
            xhr.send();
        } else {
            document.getElementById('searchResults').innerHTML = '';
        }
    });

    
    document.addEventListener('click', function(event) {
        if (event.target.classList.contains('add-to-cart')) {
            const productId = event.target.getAttribute('data-id');
            
            fetch('agregar_al_carrito.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ id_producto: productId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert(data.message); 
                } else {
                    alert('Error: ' + data.message); 
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al agregar el producto al carrito. Inténtalo nuevamente.');
            });
        }
    });

    
    document.addEventListener("DOMContentLoaded", function() {
        fetch("fetch_orders.php")
            .then(response => response.json())
            .then(data => {
                const leftColumn = document.getElementById("recentOrdersLeft");
                const rightColumn = document.getElementById("recentOrdersRight");
                
                data.forEach((order, index) => {
                    const orderElement = document.createElement("div");
                    orderElement.className = "order-item mb-3 p-3 border rounded";
                    orderElement.innerHTML = `
                        <h5>Orden #${order.id}</h5>
                        <p><strong>Producto:</strong> ${order.product}</p>
                        <p><strong>Cantidad:</strong> ${order.quantity}</p>
                        <p><strong>Fecha:</strong> ${order.date}</p>
                    `;
                    
                    if (index % 2 === 0) {
                        leftColumn.appendChild(orderElement);
                    } else {
                        rightColumn.appendChild(orderElement);
                    }
                });
            })
            .catch(error => console.error("Error al cargar órdenes:", error));
    });
</script>
</body>
</html>