<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'empleado') {
    header("Location: login.php");
    exit();
}

include("conexion.php");

$id_usuario = $_SESSION['id_usuario'];

$ventas = $conexion->query("
    SELECT v.id_venta, v.total, v.fecha, GROUP_CONCAT(CONCAT(p.nombre_producto, ' x', dv.cantidad) SEPARATOR '<br>') AS productos
    FROM ventas v
    JOIN detalle_venta dv ON v.id_venta = dv.id_venta
    JOIN productos p ON dv.id_producto = p.id_producto
    WHERE v.id_usuario = $id_usuario
    GROUP BY v.id_venta
    ORDER BY v.fecha DESC
");
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Mis Ventas - Luvadak</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <div class="container mt-5">
    <h2 class="mb-4">📈 Mis Ventas Realizadas</h2>

    <?php if ($ventas->num_rows > 0): ?>
      <table class="table table-bordered">
        <thead class="table-dark">
          <tr>
            <th>ID Venta</th>
            <th>Fecha</th>
            <th>Productos Vendidos</th>
            <th>Total</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($venta = $ventas->fetch_assoc()): ?>
            <tr>
              <td><?= $venta['id_venta']; ?></td>
              <td><?= $venta['fecha']; ?></td>
              <td><?= $venta['productos']; ?></td>
              <td>S/ <?= number_format($venta['total'], 2); ?></td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    <?php else: ?>
      <div class="alert alert-info">No has realizado ninguna venta todavía.</div>
    <?php endif; ?>
  </div>
</body>
</html>
