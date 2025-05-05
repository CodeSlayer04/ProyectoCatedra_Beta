<?php
header("Content-Type: application/json");
require_once("../config/conexion.php");

$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['id'])) {
    $database = new Database();
    $db = $database->getConnection();

    $query = "DELETE FROM categorias WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(":id", $data['id']);

    if ($stmt->execute()) {
        echo json_encode(["mensaje" => "Categoria eliminada correctamente."]);
    } else {
        echo json_encode(["error" => "No se pudo eliminar la categoria."]);
    }
} else {
    echo json_encode(["error" => "ID de categoria no proporcionado."]);
}
?>