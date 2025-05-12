<?php
header("Content-Type: application/json");
require_once("../config/conexion.php");

$database = new Database();
$db = $database->getConnection();

// Productos con bajo stock (menor a 5) (incluyendo SKU y categoría)
$queryBajoStock = "SELECT p.nombre, p.sku, p.stock, c.nombre AS categoria
                   FROM productos p
                   LEFT JOIN categorias c ON p.categoria_id = c.id
                   WHERE p.stock <= 5";
$stmtBajo = $db->prepare($queryBajoStock);
$stmtBajo->execute();
$bajoStock = $stmtBajo->fetchAll(PDO::FETCH_ASSOC);

// Productos próximos a caducar (dentro de 7 días y fecha válida)
$queryCaducidad = "SELECT p.nombre, p.sku, p.fecha_caducidad, c.nombre AS categoria
                   FROM productos p
                   LEFT JOIN categorias c ON p.categoria_id = c.id
                   WHERE p.fecha_caducidad != '0000-00-00'
                   AND p.fecha_caducidad <= DATE_ADD(CURDATE(), INTERVAL 7 DAY)";
$stmtCaduca = $db->prepare($queryCaducidad);
$stmtCaduca->execute();
$porCaducar = $stmtCaduca->fetchAll(PDO::FETCH_ASSOC);

// Unificar en array de notificaciones
$notificaciones = [];

foreach ($bajoStock as $prod) {
    $notificaciones[] = "⚠️ <strong>{$prod['nombre']}</strong> (SKU: <code>{$prod['sku']}</code>, Categoría: {$prod['categoria']}) tiene stock bajo: <strong>{$prod['stock']}</strong>";
}

foreach ($porCaducar as $prod) {
    $fecha = date("d/m/Y", strtotime($prod['fecha_caducidad']));
    $notificaciones[] = "⏳ <strong>{$prod['nombre']}</strong> (SKU: <code>{$prod['sku']}</code>, Categoría: {$prod['categoria']}) caduca pronto: <strong>{$fecha}</strong>";
}

echo json_encode($notificaciones);

