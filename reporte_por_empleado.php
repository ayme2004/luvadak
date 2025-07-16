<?php
include("conexion.php");

if (!isset($_GET['id_empleado']) || !is_numeric($_GET['id_empleado'])) {
    header("Location: buscar_empleado_reporte.php");
    exit();
}

$id_empleado = intval($_GET['id_empleado']);
$empleado = $conexion->query("SELECT nombre_completo FROM usuarios WHERE id_usuario = $id_empleado")->fetch_assoc();

$ventas = $conexion->query("
    SELECT 
        v.fecha, 
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
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Reporte de Ventas del Empleado</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      background-color: #f4f6f8;
    }
    .card {
      padding: 30px;
      border-radius: 16px;
      box-shadow: 0 6px 12px rgba(0,0,0,0.08);
    }
    .titulo {
      font-weight: bold;
      font-size: 1.6rem;
      text-align: center;
      margin-bottom: 25px;
    }
    .table th, .table td {
      font-size: 0.95rem;
      vertical-align: middle;
    }
    .total-final {
      font-size: 1.2rem;
      color: #000;
    }
  </style>
</head>
<body>
<div class="container mt-5">
  <div class="card bg-white">
    <h2 class="titulo"><i class="bi bi-clipboard-data"></i> Reporte de Ventas de: <?= htmlspecialchars($empleado['nombre_completo']) ?></h2>
    
    <?php if ($ventas->num_rows > 0): ?>
      <div class="table-responsive">
        <table class="table table-bordered table-hover text-center align-middle">
          <thead class="table-dark">
            <tr>
              <th><i class="bi bi-calendar-event"></i> Fecha</th>
              <th><i class="bi bi-bag-check-fill"></i> Producto</th>
              <th><i class="bi bi-rulers"></i> Talla</th>
              <th><i class="bi bi-palette-fill"></i> Color</th>
              <th><i class="bi bi-box-seam"></i> Cantidad</th>
              <th><i class="bi bi-cash-coin"></i> Precio Unitario</th>
              <th><i class="bi bi-currency-exchange"></i> Subtotal</th>
            </tr>
          </thead>
          <tbody>
            <?php 
            $suma_total = 0;
            while ($row = $ventas->fetch_assoc()):
              $suma_total += $row['total'];
            ?>
              <tr>
                <td><?= date("d/m/Y H:i", strtotime($row['fecha'])) ?></td>
                <td><?= htmlspecialchars($row['nombre_producto']) ?></td>
                <td><?= htmlspecialchars($row['talla']) ?></td>
                <td><?= htmlspecialchars($row['color']) ?></td>
                <td><?= $row['cantidad'] ?></td>
                <td>S/ <?= number_format($row['precio_unitario'], 2) ?></td>
                <td><strong>S/ <?= number_format($row['total'], 2) ?></strong></td>
              </tr>
            <?php endwhile; ?>
          </tbody>
          <tfoot class="table-secondary">
            <tr>
              <td colspan="6" class="text-end total-final"><strong>Total Vendido:</strong></td>
              <td class="total-final"><strong>S/ <?= number_format($suma_total, 2) ?></strong></td>
            </tr>
          </tfoot>
        </table>
      </div>
    <?php else: ?>
      <div class="alert alert-warning text-center mt-3">
        ⚠️ Este empleado aún no ha realizado ventas.
      </div>
    <?php endif; ?>

    <div class="text-center mt-4">
      <a href="buscar_empleado_reporte.php" class="btn btn-secondary">
        <i class="bi bi-arrow-left-circle"></i> Volver
      </a>
    </div>
  </div>
</div>
</body>
</html>
