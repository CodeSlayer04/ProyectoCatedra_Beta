<?php
require_once(__DIR__ . '/../vendor/autoload.php');
require_once '../config/conexion.php'; // Asegúrate de que la ruta sea correcta

use Dompdf\Dompdf;
use Dompdf\Options;

// Crear una instancia de la clase Database
$database = new Database();
$pdo = $database->getConnection(); // Obtiene la conexión a la base de datos

// Verifica que la conexión esté establecida
if ($pdo === null) {
    die("No se pudo establecer una conexión con la base de datos.");
}

// Recoger filtros
$fechaInicio = $_GET['fecha_inicio'] ?? '';
$fechaFin = $_GET['fecha_fin'] ?? '';
$usuario = $_GET['usuario'] ?? '';
$metodoPago = $_GET['metodo_pago'] ?? '';
$ventaId = $_GET['venta_id'] ?? '';

// Construir consulta SQL
$sql = "
    SELECT 
        p.nombre AS producto,
        p.precio_compra,
        p.precio_venta,
        SUM(dv.cantidad) AS cantidad_total,
        SUM(dv.cantidad * (p.precio_venta - p.precio_compra)) AS ganancia_total
    FROM detalle_ventas dv
    JOIN productos p ON dv.producto_id = p.id
    JOIN ventas v ON dv.venta_id = v.id
    JOIN usuarios u ON v.usuario_id = u.id
    WHERE 1=1
";


$params = [];

if ($fechaInicio) {
    $sql .= " AND v.fecha >= ?";
    $params[] = $fechaInicio;
}
if ($fechaFin) {
    $sql .= " AND v.fecha <= ?";
    $params[] = $fechaFin;
}
if ($usuario) {
    $sql .= " AND u.nombre LIKE ?";
    $params[] = "%$usuario%";
}
if ($metodoPago) {
    $sql .= " AND v.metodo_pago = ?";
    $params[] = $metodoPago;
}
if ($ventaId) {
    $sql .= " AND v.id = ?";
    $params[] = $ventaId;
}

$sql .= " GROUP BY p.id";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$datos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Generar HTML del informe
$html = '
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        h2 { text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #444; padding: 8px; text-align: center; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h2>Informe de Productos Vendidos</h2>
    <table>
        <thead>
            <tr>
                <th>Producto</th>
                <th>Precio Compra</th>
                <th>Precio Venta</th>
                <th>Cantidad Vendida</th>
                <th>Ganancia</th>
            </tr>
        </thead>
        <tbody>';

$totalGanancia = 0;
$totalCantidad = 0;

foreach ($datos as $fila) {
    $html .= '<tr>
        <td>' . htmlspecialchars($fila['producto']) . '</td>
        <td>$' . number_format($fila['precio_compra'], 2) . '</td>
        <td>$' . number_format($fila['precio_venta'], 2) . '</td>
        <td>' . $fila['cantidad_total'] . '</td>
        <td>$' . number_format($fila['ganancia_total'], 2) . '</td>
    </tr>';

    $totalGanancia += $fila['ganancia_total'];
    $totalCantidad += $fila['cantidad_total'];
}

$html .= '
        </tbody>
        <tfoot>
            <tr>
                <th colspan="3">Totales</th>
                <th>' . $totalCantidad . '</th>
                <th>$' . number_format($totalGanancia, 2) . '</th>
            </tr>
        </tfoot>
    </table>
</body>
</html>';

// Crear y renderizar el PDF con Dompdf
$options = new Options();
$options->set('isHtml5ParserEnabled', true); // Habilitar soporte HTML5
$options->set('isPhpEnabled', true); // Habilitar PHP en el HTML (si es necesario)
$options->set('isRemoteEnabled', true); // Permite cargar recursos remotos
$dompdf = new Dompdf($options);
$dompdf->loadHtml($html); // Cargar el HTML generado
$dompdf->setPaper('A4', 'portrait'); // Establecer el tamaño de papel y la orientación
$dompdf->render(); // Renderizar el PDF
$dompdf->stream("informe_ventas.pdf", ["Attachment" => false]); // Enviar al navegador sin descargar
?>
