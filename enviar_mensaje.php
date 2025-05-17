<?php
include 'conexion.php';
session_start(); 

header('Content-Type: application/json');

if (!isset($_SESSION['id_usuario'])) {
    echo json_encode(['success' => false, 'message' => 'Usuario no autenticado.']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$id_cotizacion = $data['id_cotizacion'] ?? null;
$mensaje = $data['mensaje'] ?? null;
$id_emisor = $_SESSION['id_usuario'];

if (!$id_cotizacion || !$mensaje) {
    echo json_encode(['success' => false, 'message' => 'Datos insuficientes para enviar el mensaje.']);
    exit;
}

if (!$id_usuario || !$id_usuario) {
    echo json_encode(['success' => false, 'message' => 'para que quieres comprar lo que vendes?']);
    exit;
}

$db = new Conexion();
$conn = $db->conn;

$sql = "INSERT INTO mensajes_cotizacion (id_cotizacion, id_emisor, mensaje) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iis", $id_cotizacion, $id_emisor, $mensaje);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Error al enviar el mensaje.']);
}

$stmt->close();
$conn->close();
?>
