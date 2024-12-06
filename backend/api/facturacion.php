<?php

header('Content-Type: application/json');
include_once '../db.php';

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'POST') {
    // Crear una nueva factura
    $data = json_decode(file_get_contents('php://input'), true);
    $id_usuario = $data['id_usuario'] ?? null;
    $id_carrito = $data['id_carrito'] ?? null;
    $id_metodo_de_pago = $data['id_metodo_de_pago'] ?? null;
    $total = $data['total'] ?? null;

    if ($id_usuario && $id_carrito && $id_metodo_de_pago && $total) {
        $query = "INSERT INTO Facturacion (id_usuario, id_carrito, id_metodo_de_pago, total, id_estado_factura)
                  VALUES (?, ?, ?, ?, 1)";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$id_usuario, $id_carrito, $id_metodo_de_pago, $total]);

        echo json_encode(["success" => true, "message" => "Factura creada exitosamente."]);
    } else {
        echo json_encode(["success" => false, "message" => "Todos los campos son requeridos para crear la factura."]);
    }
} elseif ($method === 'GET') {
    // Consultar facturas
    $id_usuario = $_GET['id_usuario'] ?? null;

    if ($id_usuario) {
        $query = "SELECT f.id_factura, f.fecha_creacion, f.total, e.descripcion AS estado, m.descripcion AS metodo_pago
                  FROM Facturacion f
                  JOIN Estado_factura e ON f.id_estado_factura = e.id_estado_factura
                  JOIN Metodo_de_pago m ON f.id_metodo_de_pago = m.id_metodo_de_pago
                  WHERE f.id_usuario = ?";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$id_usuario]);

        $facturas = [];
        if ($stmt->rowCount() > 0) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $facturas[] = $row;
            }
        }

        echo json_encode($facturas);
    } else {
        echo json_encode(["success" => false, "message" => "ID de usuario no proporcionado."]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Método no permitido."]);
}
?>
