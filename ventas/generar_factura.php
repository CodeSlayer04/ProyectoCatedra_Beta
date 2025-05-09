<?php


// Ajustar ruta a autoload
require_once(__DIR__ . '/../vendor/autoload.php');
require_once(__DIR__ . '/../config/conexion.php');

use Dompdf\Dompdf;
use Dompdf\Options;

// Verificar que se haya recibido un ID de venta
if (!isset($_GET['id'])) {
    die("ID de venta no especificado.");
}

$id = $_GET['id'];
$database = new Database();
$db = $database->getConnection();

// Obtener datos de la venta
$query = "SELECT v.*, u.nombre AS nombre_usuario
          FROM ventas v
          LEFT JOIN usuarios u ON v.usuario_id = u.id
          WHERE v.id = ?";
$stmt = $db->prepare($query);
$stmt->execute([$id]);
$venta = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$venta) {
    die("Venta no encontrada.");
}

// Obtener detalles de la venta
$queryDetalle = "SELECT dv.*, p.nombre AS nombre_producto
                 FROM detalle_ventas dv
                 LEFT JOIN productos p ON dv.producto_id = p.id
                 WHERE dv.venta_id = ?";
$stmtDetalle = $db->prepare($queryDetalle);
$stmtDetalle->execute([$id]);
$detalles = $stmtDetalle->fetchAll(PDO::FETCH_ASSOC);

// Capturar contenido HTML
ob_start();
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Factura Venta #<?= $venta['id'] ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        h1 {
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 5px;
            text-align: left;
        }
    </style>
</head>

<body>
    <h1>Factura - Venta #<?= $venta['id'] ?></h1>
    <p><strong>Fecha:</strong> <?= $venta['fecha'] ?></p>
    <p><strong>Cajero/a:</strong> <?= $venta['nombre_usuario'] ?></p>
    <p><strong>Método de pago:</strong> <?= $venta['metodo_pago'] ?></p>

    <table>
        <thead>
            <tr>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Precio Unitario</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($detalles as $item): ?>
                <tr>
                    <td><?= $item['nombre_producto'] ?></td>
                    <td><?= $item['cantidad'] ?></td>
                    <td>$<?= number_format($item['precio_unitario'], 2) ?></td>
                    <td>$<?= number_format($item['subtotal'], 2) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <h3>Total: $<?= number_format($venta['total'], 2) ?></h3>
</body>

</html>

<?php
$html = ob_get_clean();

// Configurar Dompdf
$options = new Options();
$options->set('isRemoteEnabled', true);
$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

// Enviar el PDF al navegador sin descargar
$dompdf->stream("Factura_Venta_{$venta['id']}.pdf", ["Attachment" => false]);
exit;
