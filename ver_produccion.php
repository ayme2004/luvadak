<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    header("Location: login.php");
    exit();
}

include("conexion.php");

$sql = "SELECT fecha_registro, producto, tela, precio_tela, metros_usados, mano_obra, otros_costos, costo_total, precio_venta, ganancia, cantidad 
        FROM produccion 
        ORDER BY fecha_registro DESC";
$resultado = $conexion->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Historial de Producción - Luvadak</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      background-color: #f3f6f9;
    }
    .main-card {
      background: white;
      padding: 30px;
      border-radius: 16px;
      box-shadow: 0 6px 16px rgba(0, 0, 0, 0.08);
      margin-top: 50px;
    }
    h3 {
      color: #0d6efd;
      font-weight: 600;
    }
    .table th[title] {
      cursor: help;
    }
    .ganancia-unitaria {
      font-size: 0.85rem;
      color: #6c757d;
    }
  </style>
</head>
<body>

<div class="container">
  <div class="main-card">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h3 class="m-0"><i class="bi bi-clipboard2-check"></i> Historial de Producción Registrada</h3>
      <a href="dashboard_admin.php" class="btn btn-secondary">⬅️ Volver al Panel</a>
    </div>

    <div class="table-responsive">
      <table class="table table-bordered table-hover align-middle text-center">
        <thead class="table-dark">
          <tr>
            <th>🗓 Fecha</th>
            <th>👕 Producto</th>
            <th>🧵 Tela</th>
            <th title="Precio por metro">Precio Tela</th>
            <th>Metros Usados</th>
            <th title="Costo por mano de obra total">Mano de Obra</th>
            <th title="Otros gastos como hilos, etiquetas, etc.">Otros Costos</th>
            <th title="Costo total de producir todo el lote">Costo Total</th>
            <th title="Precio de venta por unidad">Precio Venta</th>
            <th title="Ganancia total del lote">Ganancia</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($resultado->num_rows > 0): ?>
            <?php while ($fila = $resultado->fetch_assoc()): ?>
              <tr>
                <td><?= $fila['fecha_registro']; ?></td>
                <td><?= htmlspecialchars($fila['producto']); ?></td>
                <td><?= htmlspecialchars($fila['tela']); ?></td>
                <td>S/ <?= number_format($fila['precio_tela'], 2); ?></td>
                <td><?= number_format($fila['metros_usados'], 2); ?> m</td>
                <td>S/ <?= number_format($fila['mano_obra'], 2); ?></td>
                <td>S/ <?= number_format($fila['otros_costos'], 2); ?></td>
                <td><strong>S/ <?= number_format($fila['costo_total'], 2); ?></strong></td>
                <td>
                  <span>S/ <?= number_format($fila['precio_venta'], 2); ?></span><br>
                  <small class="text-muted">(x <?= $fila['cantidad']; ?> uds)</small>
                </td>
                <td style="color:green;">
                  <strong>S/ <?= number_format($fila['ganancia'], 2); ?></strong><br>
                  <small class="ganancia-unitaria">(S/ <?= number_format($fila['ganancia'] / max($fila['cantidad'], 1), 2); ?> c/u)</small>
                </td>
              </tr>
            <?php endwhile; ?>
          <?php else: ?>
            <tr>
              <td colspan="10" class="text-center text-muted">No hay registros de producción aún.</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

</body>
</html>
