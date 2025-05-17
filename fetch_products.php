<?php
include 'conexion.php';

$sql = "SELECT * FROM Productos";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo '<div class="col-md-4">';
        echo '<div class="card">';
        echo '<img src="' . $row['imagen_url'] . '" class="card-img-top" alt="' . $row['nombre'] . '">';
        echo '<div class="card-body">';
        echo '<h5 class="card-title">' . $row['nombre'] . '</h5>';
        echo '<p class="card-text">' . $row['descripcion'] . '</p>';
        if ($row['para_cotizar']) {
            echo '<p class="card-text"><strong>Cotizable</strong></p>';
        } else {
            echo '<p class="card-text"><strong>Precio: $' . number_format($row['precio'], 2) . '</strong></p>';
        }
        echo '<p class="card-text">Cantidad disponible: ' . ($row['cantidad'] > 0 ? $row['cantidad'] : '<span class="text-danger">(Agotado)</span>') . '</p>';
        echo '<p class="card-text">Valoración: ' . str_repeat('⭐', $row['calificacion']) . '</p>';
        if ($row['para_cotizar']) {
            echo '<button class="btn btn-primary" onclick="openChat(\'' . $row['nombre'] . '\')">Solicitar Cotización</button>';
        } else {
            echo '<a href="producto' . $row['producto_id'] . '.html" class="btn btn-primary">Ver Detalles</a>';
        }
        echo '</div></div></div>';
    }
} else {
    echo '<p class="text-center">No hay productos disponibles.</p>';
}

$conn->close();
?>
