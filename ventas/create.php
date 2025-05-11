<?php
header("Content-Type: application/json");
require_once("../config/conexion.php");

if (
    isset($_POST['usuario_id']) &&
    isset($_POST['total']) &&
    isset($_POST['metodo_pago']) &&
    !empty($_POST['detalle']) // El detalle es un JSON con los productos
) {
    $usuario_id = $_POST['usuario_id'];
    $total = $_POST['total'];
    $metodo_pago = $_POST['metodo_pago'];
    $detalle = json_decode($_POST['detalle'], true); // Array de productos

    $database = new Database();
    $db = $database->getConnection();

    try {
        // Iniciar transacción
        $db->beginTransaction();

        // Insertar la venta
        $queryVenta = "INSERT INTO ventas (usuario_id, total, metodo_pago) VALUES (:usuario_id, :total, :metodo_pago)";
        $stmtVenta = $db->prepare($queryVenta);
        $stmtVenta->bindParam(":usuario_id", $usuario_id);
        $stmtVenta->bindParam(":total", $total);
        $stmtVenta->bindParam(":metodo_pago", $metodo_pago);
        $stmtVenta->execute();

        $venta_id = $db->lastInsertId();

        // Insertar detalle de venta y actualizar stock
        $queryDetalle = "INSERT INTO detalle_ventas (venta_id, producto_id, cantidad, precio_unitario, subtotal)
                         VALUES (:venta_id, :producto_id, :cantidad, :precio_unitario, :subtotal)";
        $stmtDetalle = $db->prepare($queryDetalle);

        foreach ($detalle as $item) {
            $producto_id = $item['producto_id'];
            $cantidad = $item['cantidad'];
            $precio_unitario = $item['precio_unitario'];
            $subtotal = $item['subtotal'];

            // Insertar en detalle_ventas
            $stmtDetalle->execute([
                ':venta_id' => $venta_id,
                ':producto_id' => $producto_id,
                ':cantidad' => $cantidad,
                ':precio_unitario' => $precio_unitario,
                ':subtotal' => $subtotal
            ]);

            // Verificar y actualizar stock
            $stmtStock = $db->prepare("SELECT stock FROM productos WHERE id = :id");
            $stmtStock->bindParam(":id", $producto_id);
            $stmtStock->execute();
            $producto = $stmtStock->fetch(PDO::FETCH_ASSOC);

            if (!$producto || $producto['stock'] < $cantidad) {
                $db->rollBack();
                echo json_encode(["error" => "Stock insuficiente para el producto ID $producto_id"]);
                exit;
            }

            $nuevo_stock = $producto['stock'] - $cantidad;

            $stmtUpdateStock = $db->prepare("UPDATE productos SET stock = :stock WHERE id = :id");
            $stmtUpdateStock->bindParam(":stock", $nuevo_stock);
            $stmtUpdateStock->bindParam(":id", $producto_id);
            $stmtUpdateStock->execute();
        }

        // Confirmar transacción
        $db->commit();
        echo json_encode([
            "mensaje" => "Venta registrada correctamente.",
            "id" => $venta_id
        ]);

    } catch (Exception $e) {
        $db->rollBack();
        echo json_encode(["error" => "Error al registrar la venta: " . $e->getMessage()]);
    }

} else {
    echo json_encode(["error" => "Datos incompletos o incorrectos."]);
}
