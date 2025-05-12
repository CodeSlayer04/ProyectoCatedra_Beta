<?php

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

// Construir consulta base
$query = "SELECT p.*, c.nombre AS categoria_nombre 
          FROM productos p 
          LEFT JOIN categorias c ON p.categoria_id = c.id 
          WHERE 1=1";

$params = [];

// Filtros dinámicos
if (!empty($_GET['filtro_nombre'])) {
    $query .= " AND p.nombre LIKE ?";
    $params[] = "%" . $_GET['filtro_nombre'] . "%";
}

if (!empty($_GET['filtro_sku'])) {
    $query .= " AND p.sku LIKE ?";
    $params[] = "%" . $_GET['filtro_sku'] . "%";
}

if (!empty($_GET['filtro_precio_min'])) {
    $query .= " AND p.precio_venta >= ?";
    $params[] = $_GET['filtro_precio_min'];
}

if (!empty($_GET['filtro_precio_max'])) {
    $query .= " AND p.precio_venta <= ?";
    $params[] = $_GET['filtro_precio_max'];
}

if (!empty($_GET['filtro_categoria'])) {
    $query .= " AND p.categoria_id = ?";
    $params[] = $_GET['filtro_categoria'];
}

$query .= " ORDER BY p.id DESC";

$stmt = $db->prepare($query);
$stmt->execute($params);

$productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($productos);
