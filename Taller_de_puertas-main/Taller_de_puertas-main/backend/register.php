<?php
require_once 'db.php';
require_once 'message_log.php';

// Habilitar errores para depuración
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Función para registrar un usuario
function userRegistry($nombre, $apellido1, $apellido2, $password, $email, $id_rol)
{
    try {
        global $pdo;

        $passwordHashed = password_hash($password, PASSWORD_BCRYPT);

        $sql = "INSERT INTO Usuario (nombre, apellido1, apellido2, password, email, id_rol) 
                VALUES (:nombre, :apellido1, :apellido2, :password, :email, :id_rol)";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            'nombre' => $nombre,
            'apellido1' => $apellido1,
            'apellido2' => $apellido2 ?? null, 
            'password' => $passwordHashed, 
            'email' => $email,
            'id_rol' => $id_rol
        ]);

        logDebug("Usuario registrado correctamente: {$email}");
        return true;

    } catch (PDOException $e) {
        if ($e->getCode() == 23000) { 
            logError("Error: El email ya está registrado.");
            echo json_encode(["error" => "El email ya está registrado."]);
        } else {
            logError("Error registrando usuario: " . $e->getMessage());
            echo json_encode(["error" => "Error al registrar usuario: " . $e->getMessage()]);
        }
        return false;
    }
}

if (php_sapi_name() !== 'cli') { 
    header('Content-Type: application/json');
    $method = $_SERVER['REQUEST_METHOD'];

    if ($method == 'POST') {
        if (
            isset($_POST['nombre']) &&
            isset($_POST['apellido1']) &&
            isset($_POST['email']) &&
            isset($_POST['password'])
        ) {
            $nombre = $_POST['nombre'];
            $apellido1 = $_POST['apellido1'];
            $apellido2 = $_POST['apellido2'] ?? null; 
            $email = $_POST['email'];
            $password = $_POST['password'];
            $id_rol = 2; 

            if (userRegistry($nombre, $apellido1, $apellido2, $password, $email, $id_rol)) {
                http_response_code(200);
                echo json_encode(["message" => "Registro exitoso"]);
            } else {
                http_response_code(500);
                echo json_encode(["error" => "No se pudo registrar el usuario"]);
            }
        } else {
            http_response_code(400);
            echo json_encode(["error" => "Faltan campos obligatorios: nombre, apellido1, email, password"]);
        }
    } else {
        http_response_code(405);
        echo json_encode(["error" => "Método no permitido"]);
    }
}
?>



