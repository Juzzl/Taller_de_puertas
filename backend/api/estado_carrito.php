<?php

header('Content-Type: application/json');
include_once '../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $data = json_decode(file_get_contents('php://input'), true);
    $id_carrito = $data['id_carrito'] ?? null;
    $id_estado_carrito = $data['id_estado_carrito'] ?? null;

    if ($id_carrito && $id_estado_carrito) {
        $query = "UPDATE Carrito SET id_estado_carrito = ? WHERE id_carrito = ?";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$id_estado_carrito, $id_carrito]);

        echo json_encode(["success" => true, "message" => "Estado del carrito actualizado correctamente."]);
    } else {
        echo json_encode(["success" => false, "message" => "Datos incompletos para actualizar el estado."]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Método no permitido."]);
}
?>
