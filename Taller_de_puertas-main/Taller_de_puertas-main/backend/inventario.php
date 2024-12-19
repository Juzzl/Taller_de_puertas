<?php

require 'db.php';
session_start();

if (!isset($_SESSION['usuario_id']) || $_SESSION['rol_id'] != 1) { // Solo administradores (rol_id = 1)
    die("Acceso denegado.");
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Obtener todos los productos
    try {
        $stmt = $pdo->query("SELECT p.id, p.nombre, p.descripcion, p.precio, p.cantidad, c.nombre AS categoria, t.nombre AS tipo 
                             FROM Producto p
                             JOIN Categoria c ON p.categoria_id = c.id
                             JOIN TipoProducto t ON p.tipo_id = t.id");
        $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        header('Content-Type: application/json');
        echo json_encode($productos);
    } catch (PDOException $e) {
        echo "Error al obtener el inventario: " . $e->getMessage();
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Agregar un nuevo producto
    $nombre = $_POST['nombre'] ?? null;
    $descripcion = $_POST['descripcion'] ?? null;
    $precio = $_POST['precio'] ?? null;
    $cantidad = $_POST['cantidad'] ?? null;
    $categoria_id = $_POST['categoria_id'] ?? null;
    $tipo_id = $_POST['tipo_id'] ?? null;

    if (!$nombre || !$precio || !$cantidad || !$categoria_id || !$tipo_id) {
        die("Por favor, completa todos los campos obligatorios.");
    }

    try {
        $stmt = $pdo->prepare("INSERT INTO Producto (nombre, descripcion, precio, cantidad, categoria_id, tipo_id) 
                               VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$nombre, $descripcion, $precio, $cantidad, $categoria_id, $tipo_id]);
        echo "Producto añadido al inventario.";
    } catch (PDOException $e) {
        echo "Error al añadir el producto: " . $e->getMessage();
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Actualizar un producto existente
    parse_str(file_get_contents("php://input"), $put_vars);
    $id = $put_vars['id'] ?? null;
    $nombre = $put_vars['nombre'] ?? null;
    $descripcion = $put_vars['descripcion'] ?? null;
    $precio = $put_vars['precio'] ?? null;
    $cantidad = $put_vars['cantidad'] ?? null;
    $categoria_id = $put_vars['categoria_id'] ?? null;
    $tipo_id = $put_vars['tipo_id'] ?? null;

    if (!$id) {
        die("ID del producto es obligatorio.");
    }

    try {
        $stmt = $pdo->prepare("UPDATE Producto SET nombre = ?, descripcion = ?, precio = ?, cantidad = ?, categoria_id = ?, tipo_id = ? WHERE id = ?");
        $stmt->execute([$nombre, $descripcion, $precio, $cantidad, $categoria_id, $tipo_id, $id]);
        echo "Producto actualizado.";
    } catch (PDOException $e) {
        echo "Error al actualizar el producto: " . $e->getMessage();
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Eliminar un producto
    parse_str(file_get_contents("php://input"), $delete_vars);
    $id = $delete_vars['id'] ?? null;

    if (!$id) {
        die("ID del producto es obligatorio.");
    }

    try {
        $stmt = $pdo->prepare("DELETE FROM Producto WHERE id = ?");
        $stmt->execute([$id]);
        echo "Producto eliminado.";
    } catch (PDOException $e) {
        echo "Error al eliminar el producto: " . $e->getMessage();
    }
}
?>
