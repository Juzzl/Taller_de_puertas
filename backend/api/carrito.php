<?php

header('Content-Type: application/json');
include_once '../db.php';

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    // Obtener productos en el carrito
    $query = "SELECT cp.id_carrito_producto AS id, i.nombre, cp.cantidad, cp.subtotal
              FROM Carrito_producto cp
              JOIN Inventario i ON cp.id_producto = i.id_producto";
    $result = $pdo->query($query);

    $carrito = [];
    if ($result->rowCount() > 0) {
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $carrito[] = $row;
        }
    }

    echo json_encode($carrito);
} elseif ($method === 'DELETE') {
    // Eliminar un producto del carrito
    $id = $_GET['id'] ?? null;
    if ($id) {
        $query = "DELETE FROM Carrito_producto WHERE id_carrito_producto = ?";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$id]);

        echo json_encode(["success" => $stmt->rowCount() > 0]);
    } else {
        echo json_encode(["error" => "ID no proporcionado"]);
    }
} else {
    echo json_encode(["error" => "Método no permitido"]);
}
?>
