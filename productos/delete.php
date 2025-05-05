<?php
//DELETE: Elimina un producto
header("Content-Type: application/json");
require_once("../config/conexion.php");

$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['id'])) {
    $database = new Database();
    $db = $database->getConnection();

    $query = "DELETE FROM productos WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(":id", $data['id']);

    if ($stmt->execute()) {
        echo json_encode(["mensaje" => "Producto eliminado correctamente."]);
    } else {
        echo json_encode(["error" => "No se pudo eliminar el producto."]);
    }
} else {
    echo json_encode(["error" => "ID del producto no proporcionado."]);
}
?>