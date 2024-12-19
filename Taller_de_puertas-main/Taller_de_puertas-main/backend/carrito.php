<?php
session_start();
header('Content-Type: application/json');
if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}
 
$method = $_SERVER['REQUEST_METHOD'];
 
if ($method === 'POST') {
    // Agregar producto al carrito
    $input = json_decode(file_get_contents('php://input'), true);
    if (isset($input['id'], $input['nombre'], $input['precio'], $input['cantidad'])) {
        $id = $input['id'];
        $nombre = $input['nombre'];
        $precio = $input['precio'];
        $cantidad = $input['cantidad'];
 
        if (isset($_SESSION['carrito'][$id])) {
            $_SESSION['carrito'][$id]['cantidad'] += $cantidad;
        } else {
            $_SESSION['carrito'][$id] = [
                'id' => $id,
                'nombre' => $nombre,
                'precio' => $precio,
                'cantidad' => $cantidad
            ];
        }
        echo json_encode(["message" => "Producto agregado al carrito"]);
    } else {
        http_response_code(400);
        echo json_encode(["error" => "Datos incompletos"]);
    }
} elseif ($method === 'GET') {
    // Mostrar todos los productos del carrito
    echo json_encode($_SESSION['carrito']);
} elseif ($method === 'DELETE') {
    // Eliminar un producto del carrito
    $input = json_decode(file_get_contents('php://input'), true);
    if (isset($input['id'])) {
        $id = $input['id'];
        if (isset($_SESSION['carrito'][$id])) {
            unset($_SESSION['carrito'][$id]);
            echo json_encode(["message" => "Producto eliminado del carrito"]);
        } else {
            http_response_code(404);
            echo json_encode(["error" => "Producto no encontrado en el carrito"]);
        }
    } else {
        http_response_code(400);
        echo json_encode(["error" => "ID del producto no especificado"]);
    }
} elseif ($method === 'PUT') {
    // Opcion para realizar la compra
    $_SESSION['carrito'] = [];
    echo json_encode(["message" => "Compra realizada con éxito"]);
} else {
    http_response_code(405);
    echo json_encode(["error" => "Método no permitido"]);
}
?>