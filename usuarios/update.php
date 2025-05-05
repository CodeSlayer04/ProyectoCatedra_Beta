<?php
header("Content-Type: application/json");
require_once("../config/conexion.php");

$data = json_decode(file_get_contents("php://input"), true);

if (
    isset($data['id']) &&
    !empty($data['usuario']) &&
    !empty($data['nombre']) &&
    !empty($data['correo']) &&
    !empty($data['rol'])
) {
    $database = new Database();
    $db = $database->getConnection();

    $query = "UPDATE usuarios SET
                usuario = :usuario,
                nombre = :nombre,
                correo = :correo,
                rol = :rol
              WHERE id = :id";
    $stmt = $db->prepare($query);

    $stmt->bindParam(":usuario", $data['usuario']);
    $stmt->bindParam(":nombre", $data['nombre']);
    $stmt->bindParam(":correo", $data['correo']);
    $stmt->bindParam(":rol", $data['rol']);
    $stmt->bindParam(":id", $data['id']);

    if ($stmt->execute()) {
        echo json_encode(["mensaje" => "Usuario actualizado correctamente."]);
    } else {
        echo json_encode(["error" => "No se pudo actualizar el usuario."]);
    }
} else {
    echo json_encode(["error" => "Datos incompletos."]);
}
?>