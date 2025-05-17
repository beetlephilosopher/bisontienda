<?php
session_start();
include 'conexion.php'; 

if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'administrador') {
    echo "<p class='text-danger'>No tienes permisos para realizar esta acción.</p>";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['productos'])) {
    $productos = $_POST['productos'];

    $db = new Conexion();
    $conn = $db->conn;

    $placeholders = implode(',', array_fill(0, count($productos), '?'));
    $sql = "UPDATE productos SET aprobado = 1 WHERE id_producto IN ($placeholders)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param(str_repeat('i', count($productos)), ...$productos);

    if ($stmt->execute()) {
        echo "<p class='text-success'>Productos aprobados con éxito.</p>";
    } else {
        echo "<p class='text-danger'>Error al aprobar los productos.</p>";
    }

    $stmt->close();
    $conn->close();
    header("Location: modo_admin.php");
    exit();
} else {
    echo "<p class='text-danger'>No se seleccionó ningún producto para aprobar.</p>";
}
?>
