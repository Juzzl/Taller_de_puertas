<?php
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
                if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
                    $imagenTmp = $_FILES['imagen']['tmp_name'];
                    $imagenNombre = uniqid() . '-' . basename($_FILES['imagen']['name']);
                    $imagenDestino = "../images/" . $imagenNombre;
            
                    if (!move_uploaded_file($imagenTmp, $imagenDestino)) {
                        http_response_code(500); 
                        echo json_encode(['error' => 'Error al guardar la imagen.']);
                        exit;
                    }
                    $imagenRuta = "images/" . $imagenNombre;
                } else {
                    $imagenRuta = null; 
                }
            
                
                if (!isset($_POST['nombre'], $_POST['descripcion'], $_POST['precio'], $_POST['cantidad'], $_POST['categoria'], $_POST['tipo_producto'])) {
                    http_response_code(400); 
                    echo json_encode(['error' => 'Faltan datos obligatorios para crear el producto.']);
                    exit;
                }
            
                try {
                    $sql = "INSERT INTO Producto (nombre, descripcion, precio, cantidad, categoria_id, tipo_id, imagen)
                            VALUES (:nombre, :descripcion, :precio, :cantidad, :categoria_id, :tipo_id, :imagen)";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([
                        ':nombre' => $_POST['nombre'],
                        ':descripcion' => $_POST['descripcion'],
                        ':precio' => $_POST['precio'],
                        ':cantidad' => $_POST['cantidad'],
                        ':categoria_id' => $_POST['categoria'],
                        ':tipo_id' => $_POST['tipo_producto'],
                        ':imagen' => $imagenRuta
                    ]);
                    echo json_encode(['message' => 'Producto agregado exitosamente']);
                } catch (PDOException $e) {
                    http_response_code(500); 
                    echo json_encode(['error' => 'Error en la base de datos: ' . $e->getMessage()]);
                }
                break;
            


        case 'PUT':
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
}
?>
