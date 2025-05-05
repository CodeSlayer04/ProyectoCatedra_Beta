<?php
header("Content-Type: application/json");
require_once("../config/conexion.php");

$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['id'])) {
    $database = new Database();
    $db = $database->getConnection();

    $query = "DELETE FROM usuarios WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(":id", $data['id']);

    if ($stmt->execute()) {
        echo json_encode(["mensaje" => "Usuario eliminado correctamente."]);
    } else {
        echo json_encode(["error" => "No se pudo eliminar el usuario."]);
    }
} else {
    echo json_encode(["error" => "ID del usuario no proporcionado."]);
}
?>