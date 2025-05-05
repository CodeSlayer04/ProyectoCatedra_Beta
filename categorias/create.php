<?php
// Crear un nuevo usuario
header("Content-Type: application/json");
require_once("../config/conexion.php");

//$data = json_decode(file_get_contents("php://input"), true);

if (!empty($_POST['nombre'])) {

    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];

    $database = new Database();
    $db = $database->getConnection();

    $query = "INSERT INTO categorias (nombre, descripcion)
              VALUES (:nombre, :descripcion)";
    $stmt = $db->prepare($query);

    $stmt->bindParam(":nombre", $nombre);
    $stmt->bindParam(":descripcion", $descripcion);

    if ($stmt->execute()) {
        echo json_encode(["mensaje" => "Categoría creada correctamente"]);
    } else {
        echo json_encode(["error" => "Error al crear la categoría."]);
    }
} else {
    echo json_encode(["error" => "Datos incompletos."]);
}
?>