<?php
// Crear un nuevo usuario
header("Content-Type: application/json");
require_once("../config/conexion.php");

$data = json_decode(file_get_contents("php://input"), true);

if (
    !empty($data['usuario']) &&
    !empty($data['nombre']) &&
    !empty($data['correo']) &&
    !empty($data['contrasena']) &&
    !empty($data['rol'])
) {
    $database = new Database();
    $db = $database->getConnection();

    $hashed_password = password_hash($data['contrasena'], PASSWORD_DEFAULT);

    $query = "INSERT INTO usuarios (usuario, nombre, correo, contrasena, rol)
              VALUES (:usuario, :nombre, :correo, :contrasena, :rol)";
    $stmt = $db->prepare($query);

    $stmt->bindParam(":usuario", $data['usuario']);
    $stmt->bindParam(":nombre", $data['nombre']);
    $stmt->bindParam(":correo", $data['correo']);
    $stmt->bindParam(":contrasena", $hashed_password);
    $stmt->bindParam(":rol", $data['rol']);

    if ($stmt->execute()) {
        echo json_encode(["mensaje" => "Usuario creado correctamente."]);
    } else {
        echo json_encode(["error" => "Error al crear usuario."]);
    }
} else {
    echo json_encode(["error" => "Datos incompletos."]);
}
?>