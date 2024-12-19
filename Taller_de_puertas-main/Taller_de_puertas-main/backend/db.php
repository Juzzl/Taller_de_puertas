<?php
$host = 'localhost';
$dbname = 'taller_de_puertas';
$user = 'root';
$password = 'pass';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    logDebug("DB: Conexion Exitosa");
    
} catch (PDOException $e) {
    logError($e-> getMessage());
    die("Error de conexión: " . $e->getMessage());
}
