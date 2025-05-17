<?php
include 'conexion.php';


$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['cart_detail_id'])) {
    $cart_detail_id = $data['cart_detail_id'];

    try {
        
        $db = new Conexion();
        $conn = $db->conn;

        $stmt = $conn->prepare("DELETE FROM carrito_detalle WHERE id_detalle = ?");
        $stmt->bind_param("i", $cart_detail_id);

        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                echo json_encode(['success' => true, 'message' => 'Producto eliminado del carrito correctamente.']);
            } else {
                echo json_encode(['success' => false, 'message' => 'No se encontró el producto en el carrito.']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al ejecutar la consulta para eliminar el producto.']);
        }

        $stmt->close();
        $conn->close();

    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Excepción: ' . $e->getMessage()]);
    }

} else {
    echo json_encode(['success' => false, 'message' => 'Datos no válidos para eliminar el producto.']);
}
?>
