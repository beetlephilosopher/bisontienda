<?php
session_start();
include 'conexion.php';


if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit();
}


$id_usuario = $_SESSION['id_usuario'];
$username = $_SESSION['nombre_usuario'];
$correo = $_SESSION['correo'];


$db = new Conexion();
$conn = $db->conn;


$stmt = $conn->prepare("SELECT cd.id_producto, cd.cantidad, p.nombre, p.precio FROM carrito_detalle cd JOIN productos p ON cd.id_producto = p.id_producto WHERE cd.id_carrito = (SELECT id_carrito FROM carrito WHERE id_usuario = ?)");
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$result = $stmt->get_result();
$items = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

$total = 0;
foreach ($items as $item) {
    $total += $item['precio'] * $item['cantidad'];
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pago del Pedido</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2 class="text-center">Procesar Pago</h2>
    <p class="text-center">Total a pagar: <strong>$<?php echo number_format($total, 2); ?></strong></p>
    
    <div class="row">
        <div class="col-md-6">
            <h4>Pagar con Tarjeta de Crédito</h4>
            <form action="procesar_tarjeta.php" method="POST">
                <div class="form-group">
                    <label for="nombre_tarjeta">Nombre en la Tarjeta</label>
                    <input type="text" class="form-control" id="nombre_tarjeta" name="nombre_tarjeta" required>
                </div>
                <div class="form-group">
                    <label for="numero_tarjeta">Número de la Tarjeta</label>
                    <input type="text" class="form-control" id="numero_tarjeta" name="numero_tarjeta" required>
                </div>
                <div class="form-group">
                    <label for="expiracion">Fecha de Expiración</label>
                    <input type="text" class="form-control" id="expiracion" name="expiracion" placeholder="MM/AA" required>
                </div>
                <div class="form-group">
                    <label for="cvv">CVV</label>
                    <input type="text" class="form-control" id="cvv" name="cvv" required>
                </div>
                <button type="submit" class="btn btn-primary">Pagar con Tarjeta</button>
            </form>
        </div>

        <div class="col-md-6">
            <h4>Pagar con PayPal</h4>
            <form action="procesar_paypal.php" method="POST">
                <input type="hidden" name="total" value="<?php echo $total; ?>">
                <button type="submit" class="btn btn-warning">Pagar con PayPal</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>
