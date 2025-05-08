<?php
// PUT: actualizar una venta
//No se actualizar el detalle ventas en este archivo
header("Content-Type: application/json");
require_once("../config/conexion.php");

if (
    !empty($_POST['id']) &&
    isset($_POST['usuario_id']) &&
    isset($_POST['total']) &&
    isset($_POST['metodo_pago'])
) {
    $id = $_POST['id'];
    $usuario_id = $_POST['usuario_id'];
    $total = $_POST['total'];
    $metodo_pago = $_POST['metodo_pago'];

    $metodos_validos = ['efectivo', 'tarjeta', 'transferencia', 'otro'];
    if (!in_array($metodo_pago, $metodos_validos)) {
        echo json_encode(["error" => "Método de pago inválido."]);
        exit;
    }

    $database = new Database();
    $db = $database->getConnection();

    $query = "UPDATE ventas
              SET usuario_id = :usuario_id, total = :total, metodo_pago = :metodo_pago
              WHERE id = :id";

    $stmt = $db->prepare($query);
    $stmt->bindParam(":usuario_id", $usuario_id);
    $stmt->bindParam(":total", $total);
    $stmt->bindParam(":metodo_pago", $metodo_pago);
    $stmt->bindParam(":id", $id);

    if ($stmt->execute()) {
        echo json_encode(["mensaje" => "Venta actualizada correctamente."]);
    } else {
        echo json_encode(["error" => "No se pudo actualizar la venta."]);
    }
} else {
    echo json_encode(["error" => "Datos incompletos."]);
}
