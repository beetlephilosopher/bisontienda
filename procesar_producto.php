<?php
session_start();
include 'conexion.php'; 


$db = new Conexion();
$conn = $db->conn; 


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = htmlspecialchars($_POST['nombre'], ENT_QUOTES, 'UTF-8');
    $descripcion = htmlspecialchars($_POST['descripcion'], ENT_QUOTES, 'UTF-8');
    $tipo = htmlspecialchars($_POST['tipo'], ENT_QUOTES, 'UTF-8');
    $categoria = (int) $_POST['categoria'];
    $precio = isset($_POST['precio']) ? (float) $_POST['precio'] : null;
    $cantidad = (int) $_POST['cantidad'];
    $valoracion = (float) $_POST['valoracion'];
    $comentarios = htmlspecialchars($_POST['comentarios'], ENT_QUOTES, 'UTF-8');
    $creado_por = (int) $_SESSION['id_usuario'];

    
    if ($valoracion < 1.00 || $valoracion > 10.00) {
        echo "Error: La valoración debe estar entre 1.00 y 10.00.";
        exit();
    }

    
    $uploadDir = 'uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    
    $imagenes = $_FILES['imagenes'];
    $imagePaths = [];

    
    foreach ($imagenes['tmp_name'] as $index => $tmpName) {
        if (!empty($tmpName)) {
            $imageName = basename($imagenes['name'][$index]);
            $imagePath = $uploadDir . $imageName;
            if (move_uploaded_file($tmpName, $imagePath)) {
                $imagePaths[] = $imagePath;
            } else {
                echo "Error al subir la imagen: " . $imagenes['name'][$index];
                exit();
            }
        }
    }

    // Convertir las rutas de las imágenes en un solo string separado por comas
    $imagenesString = implode(',', $imagePaths);

    // Subir video (opcional)
    $videoPath = null;
    if (!empty($_FILES['video']['name'])) {
        $videoName = basename($_FILES['video']['name']);
        $videoPath = $uploadDir . $videoName;
        if (!move_uploaded_file($_FILES['video']['tmp_name'], $videoPath)) {
            echo "Error al subir el video.";
            exit();
        }
    }

    // Insertar los datos en la tabla productos
    $sql = "INSERT INTO productos (nombre, descripcion, precio, cantidad_disponible, valoracion, imagen, video, tipo, id_categoria, creado_por) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssdisissii", $nombre, $descripcion, $precio, $cantidad, $valoracion, $imagenesString, $videoPath, $tipo, $categoria, $creado_por);

    if ($stmt->execute()) {
        echo "Producto registrado con éxito.";
        header("Location: home.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
