<?php
session_start();
include 'conexion.php';


if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit();
}


$nombre_tarjeta = $_POST['nombre_tarjeta'];
$numero_tarjeta = $_POST['numero_tarjeta'];
$expiracion = $_POST['expiracion'];
$cvv = $_POST['cvv'];



$id_usuario = $_SESSION['id_usuario'];


$db = new Conexion();
$conn = $db->conn;

try {
    
    $conn->begin_transaction();

    
    $stmt = $conn->prepare("INSERT INTO pedidos (id_comprador) VALUES (?)");
    $stmt->bind_param("i", $id_usuario);
    $stmt->execute();
    $id_pedido = $conn->insert_id;
    $stmt->close();

    
    $stmt_detalle = $conn->prepare("INSERT INTO detalles_pedido (id_pedido, id_producto, cantidad, precio) VALUES (?, ?, ?, ?)");
    foreach ($_SESSION['carrito'] as $producto) {
        $stmt_detalle->bind_param("iiid", $id_pedido, $producto['id_producto'], $producto['cantidad'], $producto['precio']);
        $stmt_detalle->execute();
    }
    $stmt_detalle->close();

    
    $conn->commit();
    echo "Pago con tarjeta procesado exitosamente. Pedido registrado.";
} catch (Exception $e) {
    $conn->rollback();
    echo "Error al procesar el pago: " . $e->getMessage();
}

$conn->close();
?>
