<?php
include 'conexion.php';
session_start(); 


if (!isset($_SESSION['id_usuario'])) {
    echo "<p class='text-danger'>Por favor, inicia sesión para enviar mensajes.</p>";
    exit;
}

$id_producto = $_GET['id_producto'] ?? null;
$id_vendedor = $_GET['id_vendedor'] ?? null;
$id_comprador = $_SESSION['id_usuario'];

if (!$id_producto || !$id_vendedor) {
    echo "<p class='text-danger'>Datos insuficientes para iniciar la conversación.</p>";
    exit;
}

$db = new Conexion();
$conn = $db->conn;


$sql = "SELECT nombre FROM productos WHERE id_producto = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_producto);
$stmt->execute();
$result = $stmt->get_result();
$producto = $result->fetch_assoc();
$stmt->close();


$sql_cotizacion = "INSERT INTO cotizaciones (id_producto, id_comprador, id_vendedor)
                   VALUES (?, ?, ?)
                   ON DUPLICATE KEY UPDATE id_cotizacion = LAST_INSERT_ID(id_cotizacion)";
$stmt = $conn->prepare($sql_cotizacion);
$stmt->bind_param("iii", $id_producto, $id_comprador, $id_vendedor);
$stmt->execute();
$id_cotizacion = $conn->insert_id;
$stmt->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat con el Vendedor</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2>Chat con el Vendedor - Producto: <?php echo htmlspecialchars($producto['nombre']); ?></h2>
    <div class="chat-box border rounded p-3 mb-3" style="height: 300px; overflow-y: scroll;">
        <!-- Aquí se mostrarán los mensajes -->
        <?php
        $sql_mensajes = "SELECT m.mensaje, m.fecha_envio, u.nombre_usuario
                         FROM mensajes_cotizacion m
                         JOIN usuarios u ON m.id_emisor = u.id_usuario
                         WHERE m.id_cotizacion = ?
                         ORDER BY m.fecha_envio ASC";
        $stmt = $conn->prepare($sql_mensajes);
        $stmt->bind_param("i", $id_cotizacion);
        $stmt->execute();
        $result_mensajes = $stmt->get_result();
        
        while ($mensaje = $result_mensajes->fetch_assoc()) {
            echo "<p><strong>" . htmlspecialchars($mensaje['nombre_usuario']) . ":</strong> " . htmlspecialchars($mensaje['mensaje']) . " <small class='text-muted'>" . $mensaje['fecha_envio'] . "</small></p>";
        }
        $stmt->close();
        ?>
    </div>
    <form id="form-mensaje">
        <div class="input-group">
            <input type="text" id="mensaje" class="form-control" placeholder="Escribe tu mensaje...">
            <div class="input-group-append">
                <button type="button" id="enviar-mensaje" class="btn btn-primary">Enviar</button>
            </div>
        </div>
    </form>
</div>

<script>
    document.getElementById('enviar-mensaje').addEventListener('click', function() {
        const mensaje = document.getElementById('mensaje').value;
        if (mensaje.trim() === '') {
            alert('El mensaje no puede estar vacío.');
            return;
        }

        fetch('enviar_mensaje.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                id_cotizacion: <?php echo $id_cotizacion; ?>,
                mensaje: mensaje
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload(); 
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al enviar el mensaje. Inténtalo nuevamente.');
        });
    });
</script>
</body>
</html>
