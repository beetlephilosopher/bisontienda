<?php
session_start();
include 'conexion.php';

if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit();
}

$id_usuario = $_SESSION['id_usuario'];
$id_pedido = $_POST['id_pedido']; 

$db = new Conexion();
$conn = $db->conn;

try {
    $stmt = $conn->prepare("SELECT id_pedido FROM pedidos WHERE id_pedido = ? AND id_comprador = ? AND estado = 'activo'");
    $stmt->bind_param("ii", $id_pedido, $id_usuario);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $stmt_cancel = $conn->prepare("UPDATE pedidos SET estado = 'cancelado' WHERE id_pedido = ?");
        $stmt_cancel->bind_param("i", $id_pedido);
        if ($stmt_cancel->execute()) {
            echo "Pedido cancelado exitosamente.";
        } else {
            echo "Error al cancelar el pedido.";
        }
        $stmt_cancel->close();
    } else {
        echo "Pedido no encontrado o ya fue cancelado.";
    }

    $stmt->close();
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

$conn->close();
?>
