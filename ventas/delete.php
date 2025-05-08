<?php
// DELETE: eliminar una venta y sus detalles relacionados
header("Content-Type: application/json");
require_once("../config/conexion.php");

if (!empty($_POST['id'])) {
    $id = $_POST['id'];

    $database = new Database();
    $db = $database->getConnection();

    try {
        // Iniciar transacción
        $db->beginTransaction();

        // Eliminar detalles de la venta primero
        $query_detalle = "DELETE FROM detalle_ventas WHERE venta_id = :venta_id";
        $stmt_detalle = $db->prepare($query_detalle);
        $stmt_detalle->bindParam(":venta_id", $id);
        $stmt_detalle->execute();

        // Eliminar la venta principal
        $query_venta = "DELETE FROM ventas WHERE id = :id";
        $stmt_venta = $db->prepare($query_venta);
        $stmt_venta->bindParam(":id", $id);
        $stmt_venta->execute();

        // Confirmar la transacción
        $db->commit();

        echo json_encode(["mensaje" => "Venta eliminada correctamente."]);
    } catch (Exception $e) {
        $db->rollBack();
        echo json_encode(["error" => "Error al eliminar la venta: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["error" => "ID no proporcionado."]);
}
