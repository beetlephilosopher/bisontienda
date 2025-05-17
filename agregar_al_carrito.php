<?php
include 'conexion.php';
session_start(); 

header('Content-Type: application/json');


if (!isset($_SESSION['id_usuario'])) {
    echo json_encode(['success' => false, 'message' => 'Usuario no autenticado.']);
    exit;
}


$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['id_producto'])) {
    echo json_encode(['success' => false, 'message' => 'Producto no especificado.']);
    exit;
}

$id_producto = $data['id_producto'];
$id_usuario = $_SESSION['id_usuario'];
$cantidad = 1; 

try {
    $db = new Conexion();
    $conn = $db->conn;

    
    $query_carrito = "SELECT id_carrito FROM carrito WHERE id_usuario = ?";
    $stmt = $conn->prepare($query_carrito);
    $stmt->bind_param("i", $id_usuario);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        
        $row = $result->fetch_assoc();
        $id_carrito = $row['id_carrito'];
    } else {
        
        $query_nuevo_carrito = "INSERT INTO carrito (id_usuario) VALUES (?)";
        $stmt = $conn->prepare($query_nuevo_carrito);
        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();
        $id_carrito = $conn->insert_id; 
    }

    
    $query_detalle = "SELECT cantidad FROM carrito_detalle WHERE id_carrito = ? AND id_producto = ?";
    $stmt = $conn->prepare($query_detalle);
    $stmt->bind_param("ii", $id_carrito, $id_producto);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        
        $row = $result->fetch_assoc();
        $nueva_cantidad = $row['cantidad'] + $cantidad;
        $query_actualizar = "UPDATE carrito_detalle SET cantidad = ? WHERE id_carrito = ? AND id_producto = ?";
        $stmt = $conn->prepare($query_actualizar);
        $stmt->bind_param("iii", $nueva_cantidad, $id_carrito, $id_producto);
    } else {
        
        $query_precio = "SELECT precio FROM productos WHERE id_producto = ?";
        $stmt_precio = $conn->prepare($query_precio);
        $stmt_precio->bind_param("i", $id_producto);
        $stmt_precio->execute();
        $result_precio = $stmt_precio->get_result();
        
        if ($result_precio->num_rows > 0) {
            $row_precio = $result_precio->fetch_assoc();
            $precio = $row_precio['precio'];
        } else {
            echo json_encode(['success' => false, 'message' => 'Producto no encontrado.']);
            exit;
        }

        $query_insertar = "INSERT INTO carrito_detalle (id_carrito, id_producto, cantidad, precio) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($query_insertar);
        $stmt->bind_param("iiid", $id_carrito, $id_producto, $cantidad, $precio);
    }

    
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Producto agregado al carrito.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al agregar el producto al carrito.']);
    }

    $stmt->close();
    $conn->close();
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}
?>
