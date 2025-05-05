<?php

//GET: Obtiene todos los productos

header("Content-Type: application/json");
require_once("../config/conexion.php");

$database = new Database();
$db = $database->getConnection();

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "SELECT * FROM productos WHERE id = ?";
    $stmt = $db->prepare($query);
    $stmt->execute([$id]);

    $producto = $stmt->fetch(PDO::FETCH_ASSOC);
    echo json_encode($producto);
    exit;
}

$query = "SELECT p.*, c.nombre AS categoria_nombre FROM productos p
          LEFT JOIN categorias c ON p.categoria_id = c.id";
$stmt = $db->prepare($query);
$stmt->execute();

$productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($productos);
?>