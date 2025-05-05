<?php
header("Content-Type: application/json");
require_once("../config/conexion.php");

$data = json_decode(file_get_contents("php://input"), true);

if (
    isset($data['id']) &&
    !empty($data['nombre'])
) {
    $database = new Database();
    $db = $database->getConnection();

    $query = "UPDATE categorias SET
                nombre = :nombre,
                descripcion = :descripcion
              WHERE id = :id";
    $stmt = $db->prepare($query);

    $stmt->bindParam(":nombre", $data['nombre']);
    $stmt->bindParam(":descripcion", $data['descripcion']);
    $stmt->bindParam(":id", $data['id']);

    if ($stmt->execute()) {
        echo json_encode(["mensaje" => "Categoria actualizada correctamente."]);
    } else {
        echo json_encode(["error" => "No se pudo actualizar la categoria"]);
    }
} else {
    echo json_encode(["error" => "Datos incompletos."]);
}
?>