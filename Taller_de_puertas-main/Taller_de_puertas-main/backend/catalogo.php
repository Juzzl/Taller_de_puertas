<?php
require_once 'db.php';
header('Content-Type: application/json');

try {
    $sql = "SELECT p.id, p.nombre, p.descripcion, p.precio, c.nombre AS categoria, t.nombre AS tipo
            FROM Producto p
            INNER JOIN Categoria c ON p.categoria_id = c.id
            INNER JOIN TipoProducto t ON p.tipo_id = t.id";

    // Ejecutar la consulta
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($productos);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["error" => "Error al cargar el catálogo: " . $e->getMessage()]);
}
?>
