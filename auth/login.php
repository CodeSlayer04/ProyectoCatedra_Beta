<?php
session_start();
header("Content-Type: application/json");
require_once("../config/conexion.php");

//Esto decodifica los datos enviados en formato JSON
//$data = json_decode(file_get_contents("php://input"), true);

//Vamos a recibir datos enviados con formData por lo que necesitamos entonces:


if (!empty($_POST['usuario']) && !empty($_POST['contrasena'])) {
    $usuario = $_POST['usuario'];
    $contrasena = $_POST['contrasena'];

    $database = new Database();
    $db = $database->getConnection();

    $query = "SELECT * FROM usuarios WHERE usuario = :usuario";
    $stmt = $db->prepare($query);
    $stmt->bindParam(":usuario", $usuario);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($contrasena, $user['contrasena'])) {
        $_SESSION['usuario'] = [
            "id" => $user['id'],
            "usuario" => $user['usuario'],
            "nombre" => $user['nombre'],
            "correo" => $user['correo'],
            "rol" => $user['rol']
        ];
        $_SESSION['LAST_ACTIVITY'] = time(); // ⏰ Guardamos la hora de última actividad
        echo json_encode([
            "mensaje" => "Inicio de sesión exitoso",
            "usuario" => $_SESSION['usuario']
        ]);
    } else {
        echo json_encode(["error" => "Credenciales incorrectas"]);
    }
} else {
    echo json_encode(["error" => "Usuario y contraseña requeridos"]);
}
?>