<?php
require_once 'db.php';
header('Content-Type: application/json');

try {
    $sql = "SELECT id, nombre, descripcion, precio FROM Producto ORDER BY RAND()";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($productos);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["error" => "Error al cargar los productos: " . $e->getMessage()]);
}
?>
