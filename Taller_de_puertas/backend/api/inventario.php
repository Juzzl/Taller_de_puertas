<?php
header('Content-Type: application/json');
include_once '../db.php';

$method = $_SERVER['REQUEST_METHOD'];

// Función para verificar si un producto existe
function verificarProductoExistente($pdo, $id) {
    $query = "SELECT id_producto FROM Inventario WHERE id_producto = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$id]);
    return $stmt->rowCount() > 0;
}

try {
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

        if (empty($nombre) || empty($precio) || empty($cantidad)) {
            http_response_code(400); // Bad Request
            echo json_encode(["success" => false, "message" => "Todos los campos son requeridos."]);
            exit;
        }

        $query = "INSERT INTO Inventario (nombre, precio_unitario, descripcion, cantidad_inventario) VALUES (?, ?, ?, ?)";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$nombre, $precio, $descripcion, $cantidad]);

        echo json_encode(["success" => true, "message" => "Producto agregado correctamente."]);
    } elseif ($method === 'PUT') {
        // Actualizar un producto existente en el inventario
        $data = json_decode(file_get_contents('php://input'), true);
        $id = $data['id_producto'] ?? null;
        $nombre = $data['nombre'] ?? null;
        $precio = $data['precio_unitario'] ?? null;
        $descripcion = $data['descripcion'] ?? null;
        $cantidad = $data['cantidad_inventario'] ?? null;

        if (empty($id) || empty($nombre) || empty($precio) || empty($cantidad)) {
            http_response_code(400); 
            echo json_encode(["success" => false, "message" => "Todos los campos son requeridos."]);
            exit;
        }

        if (!verificarProductoExistente($pdo, $id)) {
            http_response_code(404); 
            echo json_encode(["success" => false, "message" => "Producto no encontrado."]);
            exit;
        }

        $query = "UPDATE Inventario SET nombre = ?, precio_unitario = ?, descripcion = ?, cantidad_inventario = ? WHERE id_producto = ?";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$nombre, $precio, $descripcion, $cantidad, $id]);

        echo json_encode(["success" => true, "message" => "Producto actualizado correctamente."]);
    } elseif ($method === 'DELETE') {
        // Eliminar un producto del inventario
        $id = $_GET['id_producto'] ?? null;

        if (empty($id)) {
            http_response_code(400); 
            echo json_encode(["success" => false, "message" => "ID del producto no proporcionado."]);
            exit;
        }

        if (!verificarProductoExistente($pdo, $id)) {
            http_response_code(404); 
            echo json_encode(["success" => false, "message" => "Producto no encontrado."]);
            exit;
        }

        $query = "DELETE FROM Inventario WHERE id_producto = ?";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$id]);

        echo json_encode(["success" => true, "message" => "Producto eliminado correctamente."]);
    } else {
        http_response_code(405); 
        echo json_encode(["success" => false, "message" => "Método no permitido."]);
    }
} catch (PDOException $e) {
    http_response_code(500); 
    echo json_encode(["success" => false, "message" => "Error en la base de datos: " . $e->getMessage()]);
}
?>
