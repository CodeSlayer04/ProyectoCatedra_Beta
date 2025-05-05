<?php
header("Content-Type: application/json");
require_once("../config/conexion.php");

$database = new Database();
$db = $database->getConnection();

$query = "SELECT id, usuario, nombre, correo, rol FROM usuarios";
$stmt = $db->prepare($query);
$stmt->execute();

$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($resultado);
?>