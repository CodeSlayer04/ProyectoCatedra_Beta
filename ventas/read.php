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

    $venta['detalle'] = $detalle;

    echo json_encode($venta);
    exit;
}

// Si no se especifica ID, listar todas las ventas
$query = "SELECT v.id, v.usuario_id, u.nombre AS nombre_usuario, v.total, v.metodo_pago, v.fecha
          FROM ventas v
          LEFT JOIN usuarios u ON v.usuario_id = u.id
          ORDER BY v.fecha DESC";

$stmt = $db->prepare($query);
$stmt->execute();

$ventas = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($ventas);
