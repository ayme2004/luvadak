<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    header("Location: login.php");
    exit();
}
include("conexion.php");

$id_cliente = isset($_GET['id']) ? intval($_GET['id']) : 0;
$cliente = $conexion->query("SELECT * FROM clientes WHERE id_cliente = $id_cliente")->fetch_assoc();

$sql = "
SELECT v.id_venta, v.fecha, p.nombre_producto, d.cantidad, d.precio_unitario
FROM ventas v
JOIN detalle_venta d ON v.id_venta = d.id_venta
JOIN productos p ON d.id_producto = p.id_producto
WHERE v.id_cliente = $id_cliente
ORDER BY v.fecha DESC
";
$resultado = $conexion->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Historial del Cliente</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
  <h3 class="mb-4">🧾 Historial de Compras: <?= htmlspecialchars($cliente['nombre_completo']) ?></h3>

  <?php if ($resultado->num_rows === 0): ?>
    <div class="alert alert-warning">Este cliente no tiene compras registradas.</div>
  <?php else: ?>
    <table class="table table-bordered table-hover text-center">
      <thead class="table-dark">
        <tr>
          <th>Fecha</th>
          <th>ID Venta</th>
          <th>Producto</th>
          <th>Cantidad</th>
          <th>Precio Unitario</th>
          <th>Total</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $total_general = 0;
        while ($row = $resultado->fetch_assoc()):
          $total = $row['cantidad'] * $row['precio_unitario'];
          $total_general += $total;
        ?>
          <tr>
            <td><?= date("d/m/Y", strtotime($row['fecha'])) ?></td>
            <td><?= $row['id_venta'] ?></td>
            <td><?= $row['nombre_producto'] ?></td>
            <td><?= $row['cantidad'] ?></td>
            <td>S/ <?= number_format($row['precio_unitario'], 2) ?></td>
            <td>S/ <?= number_format($total, 2) ?></td>
          </tr>
        <?php endwhile; ?>
        <tr class="table-secondary fw-bold">
          <td colspan="5" class="text-end">TOTAL GENERAL</td>
          <td>S/ <?= number_format($total_general, 2) ?></td>
        </tr>
      </tbody>
    </table>
  <?php endif; ?>

  <a href="clientes.php" class="btn btn-secondary mt-4">← Volver</a>
</div>
</body>
</html>

