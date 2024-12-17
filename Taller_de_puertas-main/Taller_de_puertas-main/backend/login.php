<?php
session_start();
require 'db.php'; 

function login($email, $password){
    try{
        global $pdo;

        $sql = "SELECT * FROM Usuario WHERE email = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['email' => $email]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if($user){
            if(password_verify($password, $user['contraseña'])){
                $_SESSION['user_id'] = $user["id_usuario"];
                $_SESSION['user_role'] = $user["id_rol"]; 
                return true;
            }
        }
        return false;
    }catch(Exception $e){
        logError($e->getMessage());
        return false;
    }
}

$method = $_SERVER['REQUEST_METHOD'];

if($method == 'POST'){
    if(isset($_POST['email']) && isset($_POST['contraseña'])){
        $email = $_POST['email'];
        $password = $_POST['contraseña'];

        if(login($email, $password)){
            http_response_code(200);
            echo json_encode(["message" => "Login exitoso"]);
        }else{
            http_response_code(401);
            echo json_encode(["error" => "Email o contraseña incorrectos"]);
        }

    }else{
        http_response_code(400);
        echo json_encode(["error" => "Email y contraseña son requeridos"]);
    }

}else{
    http_response_code(405);
    echo json_encode(["error" => "Método no permitido"]);
}
?>
