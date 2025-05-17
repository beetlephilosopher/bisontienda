<?php
include 'conexion.php';
session_start(); 


if (!isset($_SESSION['id_usuario'])) {
    echo "<p class='text-danger'>Por favor, inicia sesión para buscar y agregar productos al carrito.</p>";
    exit;
}

if (isset($_GET['query'])) {
    $query = $_GET['query'];
    $category = $_GET['category'] ?? '';

    $db = new Conexion();
    $conn = $db->conn;

    
    $sql = "SELECT p.id_producto, p.nombre, p.descripcion, p.precio, p.imagen1, p.imagen2, p.imagen3, p.creado_por
            FROM productos p
            WHERE p.aprobado = 1 AND p.nombre LIKE ?"; 

    $params = ["%$query%"];
    if (!empty($category)) {
        $sql .= " AND p.id_categoria = ?";
        $params[] = $category;
    }

    $stmt = $conn->prepare($sql);
    $stmt->bind_param(str_repeat('s', count($params)), ...$params);
    $stmt->execute();
    $result = $stmt->get_result();

  
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<div class='card mb-2 p-3 d-flex flex-row align-items-center'>";
            
         
            $imagen = !empty($row['imagen1']) ? $row['imagen1'] : (!empty($row['imagen2']) ? $row['imagen2'] : (!empty($row['imagen3']) ? $row['imagen3'] : 'noimagen.png'));
            echo "<img src='" . htmlspecialchars($imagen) . "' alt='" . htmlspecialchars($row['nombre']) . "' class='img-thumbnail mr-3' style='width: 50px; height: 50px; object-fit: cover;'>";
            
            echo "<div class='flex-grow-1'>";
            echo "<h5 class='mb-1'>" . htmlspecialchars($row['nombre']) . "</h5>";
            echo "<p class='mb-1 text-muted'>" . htmlspecialchars($row['descripcion']) . "</p>";
            
            if (!empty($row['precio'])) {
                echo "<p class='mb-1'><strong>Precio:</strong> $" . number_format($row['precio'], 2) . "</p>";
            }
            echo "</div>";
            echo "<button class='btn btn-success btn-sm ml-auto add-to-cart' data-id='" . $row['id_producto'] . "'>Agregar al carrito</button>";
         
            echo "<button class='btn btn-warning btn-sm ml-2 cotizar' onclick=\"window.location.href='chat_vendedor.php?id_producto=" . $row['id_producto'] . "&id_vendedor=" . $row['creado_por'] . "'\">Cotizar con el vendedor</button>";
            echo "</div>";
        }
    } else {
        echo "<p class='text-muted'>No se encontraron resultados.</p>";
    }

    $stmt->close();
    $conn->close();
}
?>
