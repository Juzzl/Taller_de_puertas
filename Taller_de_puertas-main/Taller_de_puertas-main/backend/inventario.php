<?php
<<<<<<< Updated upstream
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
=======
require_once 'db.php';
header('Content-Type: application/json');

$request_method = $_SERVER['REQUEST_METHOD'];

try {
    switch ($request_method) {
        case 'GET':
            if (isset($_GET['id'])) {
                // Obtener un producto por ID
                $stmt = $pdo->prepare("SELECT * FROM Producto WHERE id = :id");
                $stmt->execute([':id' => $_GET['id']]);
                echo json_encode($stmt->fetch(PDO::FETCH_ASSOC));
            } elseif (isset($_GET['categorias'])) {
                // Obtener categorías
                $stmt = $pdo->query("SELECT id, nombre FROM Categoria");
                echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
            } elseif (isset($_GET['tipos'])) {
                // Obtener tipos de producto
                $stmt = $pdo->query("SELECT id, nombre FROM TipoProducto");
                echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
            } else {
                // Obtener todos los productos
                $stmt = $pdo->query("SELECT p.id, p.nombre, p.descripcion, p.precio, p.cantidad, c.nombre AS categoria, t.nombre AS tipo
                                     FROM Producto p
                                     INNER JOIN Categoria c ON p.categoria_id = c.id
                                     INNER JOIN TipoProducto t ON p.tipo_id = t.id");
                echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
            }
            break;

            case 'POST':
                $data = json_decode(file_get_contents('php://input'), true);
            
                // Verificar que todos los campos requeridos estén presentes
                if (!isset($data['nombre'], $data['descripcion'], $data['precio'], $data['cantidad'], $data['categoria'], $data['tipo_producto'])) {
                    http_response_code(400); // Bad Request
                    echo json_encode(['error' => 'Faltan datos obligatorios para crear el producto.']);
                    exit;
                }
            
                try {
                    $stmt = $pdo->prepare("INSERT INTO Producto (nombre, descripcion, precio, cantidad, categoria_id, tipo_id)
                                            VALUES (:nombre, :descripcion, :precio, :cantidad, :categoria_id, :tipo_id)");
                    $stmt->execute([
                        ':nombre' => $data['nombre'],
                        ':descripcion' => $data['descripcion'],
                        ':precio' => $data['precio'],
                        ':cantidad' => $data['cantidad'],
                        ':categoria_id' => $data['categoria'],
                        ':tipo_id' => $data['tipo_producto']
                    ]);
                    echo json_encode(['message' => 'Producto agregado exitosamente']);
                } catch (PDOException $e) {
                    http_response_code(500); // Internal Server Error
                    echo json_encode(['error' => 'Error en la base de datos: ' . $e->getMessage()]);
                }
                break;
            


        case 'PUT':
            // Actualizar un producto
            $data = json_decode(file_get_contents('php://input'), true);
            $stmt = $pdo->prepare("UPDATE Producto SET nombre = :nombre, descripcion = :descripcion, precio = :precio,
                                   cantidad = :cantidad, categoria_id = :categoria_id, tipo_id = :tipo_id WHERE id = :id");
            $stmt->execute([
                ':id' => $data['id'],
                ':nombre' => $data['nombre'],
                ':descripcion' => $data['descripcion'],
                ':precio' => $data['precio'],
                ':cantidad' => $data['cantidad'],
                ':categoria_id' => $data['categoria'],
                ':tipo_id' => $data['tipo_producto']
            ]);
            echo json_encode(['message' => 'Producto actualizado exitosamente']);
            break;

        case 'DELETE':
            // Eliminar un producto
            $data = json_decode(file_get_contents('php://input'), true);
            $stmt = $pdo->prepare("DELETE FROM Producto WHERE id = :id");
            $stmt->execute([':id' => $data['id']]);
            echo json_encode(['message' => 'Producto eliminado exitosamente']);
            break;

        default:
            http_response_code(405);
            echo json_encode(['message' => 'Método no permitido']);
            break;
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Error en la operación: ' . $e->getMessage()]);
>>>>>>> Stashed changes
}
?>
