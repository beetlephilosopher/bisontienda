<?php
session_start();
session_destroy(); // Destruye todas las sesiones activas
header("Location: home.php"); // Redirige al menú principal (home)
exit();
?>