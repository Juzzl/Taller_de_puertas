<?php
session_start();
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verificar que hay productos en el carrito
    if (!isset($_SESSION['carrito']) || empty($_SESSION['carrito'])) {
        http_response_code(400);
        echo json_encode(["error" => "El carrito está vacío"]);
        exit;
    }

    // Procesar la facturación
    $carrito = $_SESSION['carrito'];
    $total = 0;
    $detalles = [];

    foreach ($carrito as $producto) {
        $total += $producto['precio'] * $producto['cantidad'];
        $detalles[] = [
            'nombre' => $producto['nombre'],
            'cantidad' => $producto['cantidad'],
            'precio' => $producto['precio'],
            'subtotal' => $producto['precio'] * $producto['cantidad']
        ];
    }

    // Generar datos de la factura
    $factura = [
        'id_factura' => uniqid('FAC-'),
        'fecha' => date('Y-m-d H:i:s'),
        'detalles' => $detalles,
        'total' => $total
    ];

    // Limpiar el carrito después de la facturación
    $_SESSION['carrito'] = [];

    // Enviar respuesta con los datos de la factura
    echo json_encode(["message" => "Factura generada con éxito", "factura" => $factura]);
} else {
    http_response_code(405);
    echo json_encode(["error" => "Método no permitido"]);
}
?>
