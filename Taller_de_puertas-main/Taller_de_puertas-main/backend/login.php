<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');

require_once 'db.php';
require_once 'message_log.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

function userLogin($email, $password) {
    try {
        global $pdo;

        $sql = "SELECT id, nombre, apellido_paterno, apellido_materno, password, rol_id 
                FROM Usuario 
                WHERE email = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':email' => $email]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        } else {
            return null;
        }
    } catch (PDOException $e) {
        error_log("Error al iniciar sesión: " . $e->getMessage());
        return null;
    }
}

// Procesar la solicitud POST
$input = json_decode(file_get_contents("php://input"), true);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($input['email']) && isset($input['password'])) {
        $email = $input['email'];
        $password = $input['password'];

        $user = userLogin($email, $password);

        if ($user) {
            session_start();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['nombre'] = $user['nombre'];
            $_SESSION['rol_id'] = $user['rol_id'];

            http_response_code(200);
            echo json_encode([
                "message" => "Inicio de sesión exitoso",
                "user" => [
                    "id" => $user['id'],
                    "nombre" => $user['nombre'],
                    "apellido_paterno" => $user['apellido_paterno'],
                    "apellido_materno" => $user['apellido_materno'],
                    "email" => $email,
                    "rol_id" => $user['rol_id']
                ]
            ]);
        } else {
            http_response_code(401);
            echo json_encode(["error" => "Credenciales incorrectas"]);
        }

    }else{
        http_response_code(400);
        echo json_encode(["error" => "Faltan campos obligatorios: email, password"]);
    }
} else {
    http_response_code(405);
    echo json_encode(["error" => "Método no permitido"]);
}
?>
