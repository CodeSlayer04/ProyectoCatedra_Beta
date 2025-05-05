<?php
header("Content-Type: application/json");
require_once("../config/conexion.php");

$database = new Database();
$db = $database->getConnection();

$query = "SELECT id, nombre, descripcion FROM categorias";
$stmt = $db->prepare($query);
$stmt->execute();

$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($resultado);
?>