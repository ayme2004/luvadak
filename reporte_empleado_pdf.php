<?php
require_once 'libs/dompdf/autoload.inc.php';
use Dompdf\Dompdf;
use Dompdf\Options;

include("conexion.php");

if (!isset($_GET['id_empleado']) || !is_numeric($_GET['id_empleado'])) {
    die("ID de empleado inválido.");
}

$id_empleado = intval($_GET['id_empleado']);
$empleado = $conexion->query("SELECT nombre_completo FROM usuarios WHERE id_usuario = $id_empleado")->fetch_assoc();

$ventas = $conexion->query("
    SELECT 
        v.fecha AS fecha_venta,
        p.nombre_producto,
        p.talla,
        p.color,
        dv.cantidad,
        dv.precio_unitario,
        (dv.cantidad * dv.precio_unitario) AS total
    FROM ventas v
    JOIN detalle_venta dv ON v.id_venta = dv.id_venta
    JOIN productos p ON dv.id_producto = p.id_producto
    WHERE v.id_usuario = $id_empleado
    ORDER BY v.fecha DESC
");

ob_start(); 
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        h2 { text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #444; padding: 6px; text-align: center; }
        th { background-color: #f0f0f0; }
    </style>
</head>
<body>
    <h2>🧾 Reporte de Ventas - <?= htmlspecialchars($empleado['nombre_completo']) ?></h2>

    <table>
        <thead>
            <tr>
                <th>🗓 Fecha</th>
                <th>👕 Producto</th>
                <th>📏 Talla</th>
                <th>🎨 Color</th>
                <th>📦 Cantidad</th>
                <th>💵 Precio Unitario</th>
                <th>💰 Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $suma_total = 0;
            if ($ventas->num_rows > 0):
                while ($row = $ventas->fetch_assoc()):
                    $suma_total += $row['total'];
            ?>
                <tr>
                    <td><?= date("d/m/Y H:i", strtotime($row['fecha_venta'])) ?></td>
                    <td><?= htmlspecialchars($row['nombre_producto']) ?></td>
                    <td><?= htmlspecialchars($row['talla']) ?></td>
                    <td><?= htmlspecialchars($row['color']) ?></td>
                    <td><?= $row['cantidad'] ?></td>
                    <td>S/ <?= number_format($row['precio_unitario'], 2) ?></td>
                    <td><strong>S/ <?= number_format($row['total'], 2) ?></strong></td>
                </tr>
            <?php 
                endwhile;
            else:
            ?>
                <tr>
                    <td colspan="7">⚠️ Este empleado aún no ha realizado ventas.</td>
                </tr>
            <?php endif; ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="6"><strong>Total Vendido:</strong></td>
                <td><strong>S/ <?= number_format($suma_total, 2) ?></strong></td>
            </tr>
        </tfoot>
    </table>
</body>
</html>

<?php
$html = ob_get_clean(); 

$options = new Options();
$options->set('isRemoteEnabled', true);

$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

$dompdf->stream("reporte_empleado.pdf", ["Attachment" => false]); 
?>
