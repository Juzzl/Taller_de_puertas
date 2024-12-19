<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');

require_once 'db.php';

// Habilitar errores para depuración
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Función para registrar un usuario
function userRegistry($nombre, $apellido_paterno, $apellido_materno, $password, $email, $rol_id) {
    try {
        global $pdo;

        $passwordHashed = password_hash($password, PASSWORD_BCRYPT);

        $sql = "INSERT INTO Usuario (nombre, apellido_paterno, apellido_materno, password, email, rol_id)
                VALUES (:nombre, :apellido_paterno, :apellido_materno, :password, :email, :rol_id)";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':nombre' => $nombre,
            ':apellido_paterno' => $apellido_paterno,
            ':apellido_materno' => $apellido_materno ?? null,
            ':password' => $passwordHashed,
            ':email' => $email,
            ':rol_id' => $rol_id
        ]);

        return true;
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) {
            echo json_encode(["error" => "El email ya está registrado."]);
        } else {
            echo json_encode(["error" => "Error al registrar el usuario: " . $e->getMessage()]);
        }
        return false;
    }
}

$input = json_decode(file_get_contents("php://input"), true);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (
        isset($input['nombre']) &&
        isset($input['apellido_paterno']) &&
        isset($input['email']) &&
        isset($input['password'])
    ) {
        $nombre = $input['nombre'];
        $apellido_paterno = $input['apellido_paterno'];
        $apellido_materno = $input['apellido_materno'] ?? null;
        $email = $input['email'];
        $password = $input['password'];
        $rol_id = 2;

        if (userRegistry($nombre, $apellido_paterno, $apellido_materno, $password, $email, $rol_id)) {
            http_response_code(200);
            echo json_encode(["message" => "Registro exitoso"]);
        } else {
            http_response_code(500);
            echo json_encode(["error" => "No se pudo registrar el usuario"]);
        }
    } else {
        http_response_code(400);
        echo json_encode(["error" => "Faltan campos obligatorios: nombre, apellido_paterno, email, password"]);
    }
} else {
    http_response_code(405);
    echo json_encode(["error" => "Método no permitido"]);
}
?>
