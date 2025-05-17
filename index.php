<?php
session_start();
if (isset($_SESSION['nombre_usuario'])) {header("Location: home.php"); exit();}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <link rel="stylesheet" href="styles.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido a Bisontienda</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        
    </style>
</head>

<body>
    <!-- Barra de navegación -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="home.html">
                <img src="bisontienda.png" alt="Bisontienda">
            </a>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            
            <h1 class="hero-title">BIENVENIDO A BISONTIENDA</h1>
            
            <p class="hero-subtitle">La plataforma para comprar, vender y cotizar productos de manera sencilla</p>

            <div>
                
                <a href="register.php" class="btn btn-hero btn-warning">Regístrate</a>
                <a href="login.php" class="btn btn-hero btn-secondary">Iniciar sesión</a>
            </div>
        </div>
        
    </section>

    <!-- Contenido principal -->
    <main class="main-content">
        <div class="container">
            <p>Encuentra los mejores productos, vende lo que ya no necesitas, y cotiza productos a precios increíbles. Únete a nuestra comunidad y descubre una nueva forma de comprar y vender.</p>
        </div>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <p class="mb-0">&copy; <?php echo date('Y'); ?> Bisontienda 2025. Todos los derechos reservados.</p>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>