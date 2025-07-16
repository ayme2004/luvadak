<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

include("conexion.php");

$id_venta = $_GET['id'];

$venta = $conexion->query("
  SELECT v.*, u.nombre_completo 
  FROM ventas v 
  JOIN usuarios u ON v.id_usuario = u.id_usuario 
  WHERE v.id_venta = $id_venta
")->fetch_assoc();

$detalles = $conexion->query("
  SELECT d.*, p.nombre_producto 
  FROM detalle_venta d 
  JOIN productos p ON d.id_producto = p.id_producto 
  WHERE d.id_venta = $id_venta
");
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Boleta #<?= $venta['id_venta'] ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5 col-md-8">
  <div class="card shadow p-4">
    <h4 class="mb-3">🧾 Boleta #<?= $venta['id_venta'] ?></h4>
    <p><strong>Vendedor:</strong> <?= $venta['nombre_completo'] ?></p>
    <p><strong>Fecha:</strong> <?= $venta['fecha_venta'] ?></p>

    <table class="table table-bordered mt-3">
      <thead class="table-light">
        <tr>
          <th>Producto</th>
          <th>Cantidad</th>
          <th>Precio Unitario</th>
          <th>Subtotal</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($fila = $detalles->fetch_assoc()) { ?>
          <tr>
            <td><?= $fila['nombre_producto'] ?></td>
            <td><?= $fila['cantidad'] ?></td>
            <td>S/ <?= number_format($fila['precio_unitario'], 2) ?></td>
            <td>S/ <?= number_format($fila['cantidad'] * $fila['precio_unitario'], 2) ?></td>
          </tr>
        <?php } ?>
      </tbody>
    </table>

    <h5 class="text-end mt-3">Total: <strong>S/ <?= number_format($venta['total'], 2) ?></strong></h5>
  </div>

  <div class="mt-4">
    <a href="ver_boletas.php" class="btn btn-secondary">← Volver</a>
  </div>
</div>
</body>
</html>
