<?php
header("Content-Type: application/json");
require_once("../config/conexion.php");

//$_POST = json_decode(file_get_contents("php://input"), true);

if (
    isset($_POST['id']) &&
    !empty($_POST['usuario']) &&
    !empty($_POST['nombre']) &&
    !empty($_POST['correo']) &&
    !empty($_POST['rol'])
) {
    $database = new Database();
    $db = $database->getConnection();

    // Verifica si se envió una nueva contraseña
    if (!empty($_POST['contrasena'])) {
        $contrasenaHasheada = password_hash($_POST['contrasena'], PASSWORD_DEFAULT);

        $query = "UPDATE usuarios SET
                    usuario = :usuario,
                    nombre = :nombre,
                    correo = :correo,
                    contrasena = :contrasena,
                    rol = :rol
                  WHERE id = :id";

        $stmt = $db->prepare($query);
        $stmt->bindParam(":contrasena", $contrasenaHasheada);

    } else {
        // No actualizar contraseña
        $query = "UPDATE usuarios SET
                    usuario = :usuario,
                    nombre = :nombre,
                    correo = :correo,
                    rol = :rol
                  WHERE id = :id";

        $stmt = $db->prepare($query);
    }

    $stmt->bindParam(":usuario", $_POST['usuario']);
    $stmt->bindParam(":nombre", $_POST['nombre']);
    $stmt->bindParam(":correo", $_POST['correo']);
    $stmt->bindParam(":rol", $_POST['rol']);
    $stmt->bindParam(":id", $_POST['id']);

    if ($stmt->execute()) {
        echo json_encode(["mensaje" => "Usuario actualizado correctamente."]);
    } else {
        echo json_encode(["error" => "No se pudo actualizar el usuario."]);
    }
} else {
    echo json_encode(["error" => "Datos incompletos."]);
}
?>