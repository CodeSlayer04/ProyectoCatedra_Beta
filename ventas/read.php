<?php
header("Content-Type: application/json");
require_once("../config/conexion.php");

$database = new Database();
$db = $database->getConnection();

// Si viene un ID específico
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Obtener los datos generales de la venta
    $query = "SELECT v.*, u.nombre AS nombre_usuario
              FROM ventas v
              LEFT JOIN usuarios u ON v.usuario_id = u.id
              WHERE v.id = ?";
    $stmt = $db->prepare($query);
    $stmt->execute([$id]);
    $venta = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$venta) {
        echo json_encode(["error" => "Venta no encontrada."]);
        exit;
    }

    // Obtener el detalle de la venta
    $queryDetalle = "SELECT dv.*, p.nombre AS nombre_producto
                     FROM detalle_ventas dv
                     LEFT JOIN productos p ON dv.producto_id = p.id
                     WHERE dv.venta_id = ?";
    $stmtDetalle = $db->prepare($queryDetalle);
    $stmtDetalle->execute([$id]);
    $detalle = $stmtDetalle->fetchAll(PDO::FETCH_ASSOC);

    $venta['detalles'] = $detalle;

    echo json_encode($venta);
    exit;
}

// Filtros opcionales
$fecha_inicio = $_GET['fecha_inicio'] ?? null;
$fecha_fin = $_GET['fecha_fin'] ?? null;
$usuario = $_GET['usuario'] ?? null;
$metodo_pago = $_GET['metodo_pago'] ?? null;

// Construir consulta base
$query = "SELECT v.id, v.usuario_id, u.nombre AS nombre_usuario, v.total, v.metodo_pago, v.fecha
          FROM ventas v
          LEFT JOIN usuarios u ON v.usuario_id = u.id
          WHERE 1=1";
$params = [];

// Agregar condiciones dinámicamente
if ($fecha_inicio) {
    $query .= " AND v.fecha >= ?";
    $params[] = $fecha_inicio . " 00:00:00";
}
if ($fecha_fin) {
    $query .= " AND v.fecha <= ?";
    $params[] = $fecha_fin . " 23:59:59";
}
if ($usuario) {
    $query .= " AND u.nombre LIKE ?";
    $params[] = "%" . $usuario . "%";
}
if ($metodo_pago) {
    $query .= " AND v.metodo_pago = ?";
    $params[] = $metodo_pago;
}

$query .= " ORDER BY v.fecha DESC";

$stmt = $db->prepare($query);
$stmt->execute($params);

$ventas = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Añadir detalles a cada venta
foreach ($ventas as &$venta) {
    $queryDetalle = "SELECT dv.*, p.nombre AS nombre_producto
                     FROM detalle_ventas dv
                     LEFT JOIN productos p ON dv.producto_id = p.id
                     WHERE dv.venta_id = ?";
    $stmtDetalle = $db->prepare($queryDetalle);
    $stmtDetalle->execute([$venta['id']]);
    $detalle = $stmtDetalle->fetchAll(PDO::FETCH_ASSOC);

    $venta['detalles'] = $detalle;
}

echo json_encode($ventas);
