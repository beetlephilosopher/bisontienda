
<?php
include 'conexion.php';

$db = new Conexion();
if ($db->conn) {
    echo "Conexión exitosa a MySQL.";
} else {
    echo "Error de conexión.";
}
?>