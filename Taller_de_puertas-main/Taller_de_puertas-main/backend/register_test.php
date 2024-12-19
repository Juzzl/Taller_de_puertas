<?php
require_once 'register.php';
require_once 'db.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

$nombre = "Jaaaa";
$apellido1 = "aaaaa";
$apellido2 = "aaaa"; 
$password = "123456";
$email = "aaaaaa@example.com";
$id_rol = 2;

try {
    echo "=== Prueba de Registro de Usuario ===\n";

    $result = userRegistry($nombre, $apellido1, $apellido2, $password, $email, $id_rol);

    if ($result) {
        echo "Prueba exitosa: El usuario '{$email}' fue registrado correctamente.\n";
    } else {
        echo "Prueba fallida: No se pudo registrar al usuario.\n";
    }

} catch (Exception $e) {
    echo "Error durante la prueba: " . $e->getMessage() . "\n";
}
?>



