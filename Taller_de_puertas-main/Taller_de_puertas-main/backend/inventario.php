<?php
header('Content-Type: application/json');
include_once '../db.php';

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    // Obtener productos del inventario
    $query = "SELECT id_producto, nombre, precio_unitario, descripcion, cantidad_inventario FROM Inventario";
    $result = $pdo->query($query);

    $inventario = [];
    if ($result->rowCount() > 0) {
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            $inventario[] = $row;
        }
    }

    echo json_encode($inventario);
} elseif ($method === 'POST') {
    // Agregar un producto nuevo al inventario
    $data = json_decode(file_get_contents('php://input'), true);
    $nombre = $data['nombre'] ?? null;
    $precio = $data['precio_unitario'] ?? null;
    $descripcion = $data['descripcion'] ?? null;
    $cantidad = $data['cantidad_inventario'] ?? null;

    if ($nombre && $precio && $cantidad) {
        $query = "INSERT INTO Inventario (nombre, precio_unitario, descripcion, cantidad_inventario) VALUES (?, ?, ?, ?)";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$nombre, $precio, $descripcion, $cantidad]);

        echo json_encode(["success" => true, "message" => "Producto agregado correctamente."]);
    } else {
        echo json_encode(["success" => false, "message" => "Todos los campos son requeridos."]);
    }
} elseif ($method === 'PUT') {
    // Actualizar un producto existente en el inventario
    $data = json_decode(file_get_contents('php://input'), true);
    $id = $data['id_producto'] ?? null;
    $nombre = $data['nombre'] ?? null;
    $precio = $data['precio_unitario'] ?? null;
    $descripcion = $data['descripcion'] ?? null;
    $cantidad = $data['cantidad_inventario'] ?? null;

    if ($id && $nombre && $precio && $cantidad) {
        $query = "UPDATE Inventario SET nombre = ?, precio_unitario = ?, descripcion = ?, cantidad_inventario = ? WHERE id_producto = ?";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$nombre, $precio, $descripcion, $cantidad, $id]);

        echo json_encode(["success" => true, "message" => "Producto actualizado correctamente."]);
    } else {
        echo json_encode(["success" => false, "message" => "Todos los campos son requeridos."]);
    }
} elseif ($method === 'DELETE') {
    // Eliminar un producto del inventario
    $id = $_GET['id_producto'] ?? null;

    if ($id) {
        $query = "DELETE FROM Inventario WHERE id_producto = ?";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$id]);

        echo json_encode(["success" => true, "message" => "Producto eliminado correctamente."]);
    } else {
        echo json_encode(["success" => false, "message" => "ID del producto no proporcionado."]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Método no permitido."]);
}
?>
