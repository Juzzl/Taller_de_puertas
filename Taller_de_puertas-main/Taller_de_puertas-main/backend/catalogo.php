<?php
require_once 'db.php';
header('Content-Type: application/json');
 
$action = isset($_GET['action']) ? $_GET['action'] : 'productos';
 
try {
    if ($action === 'filtros') {
        // Obtener categorías y tipos
        $categorias = $pdo->query("SELECT id, nombre FROM Categoria")->fetchAll(PDO::FETCH_ASSOC);
        $tipos = $pdo->query("SELECT id, nombre FROM TipoProducto")->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['categorias' => $categorias, 'tipos' => $tipos]);
    } elseif ($action === 'productos') {
        // Obtener productos con filtros opcionales
        $categoria = isset($_GET['categoria']) ? intval($_GET['categoria']) : null;
        $tipo = isset($_GET['tipo']) ? intval($_GET['tipo']) : null;
 
        $sql = "SELECT p.id, p.nombre, p.descripcion, p.precio, c.nombre AS categoria, t.nombre AS tipo, p.imagen
        FROM Producto p
        INNER JOIN Categoria c ON p.categoria_id = c.id
        INNER JOIN TipoProducto t ON p.tipo_id = t.id";
 
        if ($categoria || $tipo) {
            $sql .= " WHERE 1=1";
            if ($categoria) {
                $sql .= " AND p.categoria_id = :categoria";
            }
            if ($tipo) {
                $sql .= " AND p.tipo_id = :tipo";
            }
        }
 
        $stmt = $pdo->prepare($sql);
 
        if ($categoria) {
            $stmt->bindParam(':categoria', $categoria, PDO::PARAM_INT);
        }
        if ($tipo) {
            $stmt->bindParam(':tipo', $tipo, PDO::PARAM_INT);
        }
 
        $stmt->execute();
        $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($productos);
    } else {
        // Acción no válida
        http_response_code(400);
        echo json_encode(["error" => "Acción no válida"]);
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["error" => "Error al procesar la solicitud: " . $e->getMessage()]);
}
?>