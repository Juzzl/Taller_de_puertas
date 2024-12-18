<?php

require 'db.php';
session_start();

if (!isset($_SESSION['usuario_id'])) {
    die("Usuario no autenticado.");
}

$usuario_id = $_SESSION['usuario_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $carrito_id = $_POST['carrito_id'] ?? null;

    if (!$carrito_id) {
        die("Carrito no especificado.");
    }

    try {
        $stmt = $pdo->prepare("SELECT SUM(cp.cantidad * cp.precio_unitario) AS total 
                               FROM CarritoProducto cp 
                               WHERE cp.carrito_id = ?");
        $stmt->execute([$carrito_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$result || !$result['total']) {
            die("El carrito está vacío o no existe.");
        }

        $total = $result['total'];

        $stmt = $pdo->prepare("INSERT INTO Factura (usuario_id, carrito_id, total) VALUES (?, ?, ?)");
        $stmt->execute([$usuario_id, $carrito_id, $total]);
        $factura_id = $pdo->lastInsertId();

        echo "Factura generada con éxito. ID de factura: " . $factura_id;
    } catch (PDOException $e) {
        echo "Error al generar la factura: " . $e->getMessage();
    }
}
?>
