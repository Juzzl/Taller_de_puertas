<?php
require_once 'db.php';
header('Content-Type: application/json');


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $cantidad = $_POST['cantidad'];
    $categoria = $_POST['categoria'];
    $tipo = $_POST['tipo'];

    // Manejo de la imagen
    $imagenRuta = '';
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $imagenTmp = $_FILES['imagen']['tmp_name'];
        $imagenNombre = uniqid() . '-' . basename($_FILES['imagen']['name']);
        $imagenDestino = "../images/" . $imagenNombre;

        if (move_uploaded_file($imagenTmp, $imagenDestino)) {
            $imagenRuta = "images/" . $imagenNombre;
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Error al guardar la imagen']);
            exit;
        }
    } else {
        http_response_code(400);
        echo json_encode(['error' => 'La imagen no se recibió correctamente']);
        exit;
    }


    // Guardar el producto en la base de datos
    try {
        $sql = "INSERT INTO Producto (nombre, descripcion, precio, cantidad, categoria_id, tipo_id, imagen)
                VALUES (:nombre, :descripcion, :precio, :cantidad, :categoria, :tipo, :imagen)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':nombre' => $nombre,
            ':descripcion' => $descripcion,
            ':precio' => $precio,
            ':cantidad' => $cantidad,
            ':categoria' => $categoria,
            ':tipo' => $tipo,
            ':imagen' => $imagenRuta
        ]);
        echo json_encode(['message' => 'Producto guardado exitosamente']);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Error al guardar el producto: ' . $e->getMessage()]);
    }
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Método no permitido']);
}
?>
