<?php
if (isset($_GET['url'])) {
    $url_imagen = htmlspecialchars($_GET['url']); 
} else {
    die("Imagen no encontrada.");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ver Imagen</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f0f8ff;
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .image-container {
            max-width: 90%;
            max-height: 90%;
        }
        .image-container img {
            width: 100%;
            height: auto;
        }
    </style>
</head>
<body>
    <div class="image-container">
        <img src="<?php echo $url_imagen; ?>" alt="Imagen del Producto">
    </div>
</body>
</html>
