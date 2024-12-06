<?php

header('Content-Type: application/json');
include_once '../db.php';

// Consulta para obtener todos los productos del inventario
$query = "SELECT id_producto AS id, nombre, precio_unitario AS precio, descripcion FROM Inventario";
$result = $pdo->query($query);

$productos = [];
if ($result->rowCount() > 0) {
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        $productos[] = $row;
    }
}

echo json_encode($productos);
?>
