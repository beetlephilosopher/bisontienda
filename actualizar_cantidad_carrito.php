<?php
include 'conexion.php';

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['cart_detail_id']) && isset($data['cantidad'])) {
    $cart_detail_id = $data['cart_detail_id'];
    $cantidad = $data['cantidad'];

    $db = new Conexion();
    $conn = $db->conn;

    $stmt = $conn->prepare("UPDATE carrito_detalle SET cantidad = ? WHERE id_detalle = ?");
    $stmt->bind_param("ii", $cantidad, $cart_detail_id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Cantidad actualizada correctamente.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al actualizar la cantidad.']);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Datos no válidos.']);
}
?>
