<?php
session_start();
include 'conexion.php'; 


if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'administrador') {
    echo "<p class='text-danger'>No tienes permisos para acceder a esta página.</p>";
    exit;
}


$db = new Conexion();
$conn = $db->conn;


$sql = "SELECT id_producto, nombre, descripcion, precio, cantidad_disponible FROM productos WHERE aprobado = 0";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aprobar Productos</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #000;
            color: #00ff00;
            font-family: 'Courier New', Courier, monospace; 
        }
        .container {
            margin-top: 20px;
        }
        .btn-autorizar {
            background-color: #00ff00;
            color: #000;
            border: none;
        }
        .btn-autorizar:hover {
            background-color: #00cc00;
        }
        .table {
            color: #00ff00;
        }
        .table th, .table td {
            border-color: #00ff00;
        }
    </style>
                <a class="navbar-brand" href="home.php"><img src="bisontienda.png" alt="Logo TiendaOnline" style="width: 180px; height: 40px;"> </a>

</head>
<body>
    <div class="container">
        <h1>Modo Admin - Aprobar Productos</h1>
        <form id="aprobarProductosForm" method="POST" action="aprobar_productos.php">
            <table class="table table-dark table-bordered">
                <thead>
                    <tr>
                        <th>Seleccionar</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Precio</th>
                        <th>Cantidad</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><input type="checkbox" name="productos[]" value="<?php echo $row['id_producto']; ?>"></td>
                                <td><?php echo htmlspecialchars($row['nombre']); ?></td>
                                <td><?php echo htmlspecialchars($row['descripcion']); ?></td>
                                <td>$<?php echo number_format($row['precio'], 2); ?></td>
                                <td><?php echo $row['cantidad_disponible']; ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5">No hay productos pendientes de aprobación.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
            <button type="submit" class="btn btn-autorizar">Autorizar Seleccionados</button>
        </form>
    </div>

    <script>
        document.getElementById('aprobarProductosForm').addEventListener('submit', function(event) {
            if (!document.querySelector('input[name="productos[]"]:checked')) {
                event.preventDefault();
                alert('Por favor, selecciona al menos un producto para autorizar.');
            }
        });
    </script>
</body>
</html>
