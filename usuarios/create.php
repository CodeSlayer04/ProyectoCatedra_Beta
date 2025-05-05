<?php
// Crear un nuevo usuario
header("Content-Type: application/json");
require_once("../config/conexion.php");

//$data = json_decode(file_get_contents("php://input"), true);

if (
    !empty($_POST['usuario']) &&
    !empty($_POST['nombre']) &&
    !empty($_POST['correo']) &&
    !empty($_POST['contrasena']) &&
    !empty($_POST['rol'])
) {
    $database = new Database();
    $db = $database->getConnection();

    $hashed_password = password_hash($_POST['contrasena'], PASSWORD_DEFAULT);

    $query = "INSERT INTO usuarios (usuario, nombre, correo, contrasena, rol)
              VALUES (:usuario, :nombre, :correo, :contrasena, :rol)";
    $stmt = $db->prepare($query);

    $stmt->bindParam(":usuario", $_POST['usuario']);
    $stmt->bindParam(":nombre", $_POST['nombre']);
    $stmt->bindParam(":correo", $_POST['correo']);
    $stmt->bindParam(":contrasena", $hashed_password);
    $stmt->bindParam(":rol", $_POST['rol']);

    if ($stmt->execute()) {
        echo json_encode(["mensaje" => "Usuario creado correctamente."]);
    } else {
        echo json_encode(["error" => "Error al crear usuario."]);
    }
} else {
    echo json_encode(["error" => "Datos incompletos."]);
}
?>