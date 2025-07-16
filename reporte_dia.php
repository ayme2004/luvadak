<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
  header("Location: login.php");
  exit();
}

include("conexion.php");

$condiciones = [];
if (!empty($_GET['fecha_desde'])) {
  $desde = $_GET['fecha_desde'];
  $condiciones[] = "DATE(v.fecha) >= '$desde'";
}
if (!empty($_GET['fecha_hasta'])) {
  $hasta = $_GET['fecha_hasta'];
  $condiciones[] = "DATE(v.fecha) <= '$hasta'";
}
if (!empty($_GET['buscar_fecha'])) {
  $buscar = $_GET['buscar_fecha'];
  $condiciones[] = "DATE(v.fecha) = '$buscar'";
}

$where = count($condiciones) > 0 ? 'WHERE ' . implode(' AND ', $condiciones) : '';

$ventas_dia = $conexion->query("
  SELECT DATE(v.fecha) AS fecha_dia, 
         SUM(v.total) AS total_dia, 
         COUNT(*) AS cantidad_ventas
  FROM ventas v
  $where
  GROUP BY DATE(v.fecha)
  ORDER BY fecha_dia DESC
");
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Reporte Diario / Ventas - Luvadak</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f8fafc;
    }
    .card {
      border-radius: 16px;
    }
    .table thead th {
      vertical-align: middle;
    }
  </style>
</head>
<body>
<div class="container mt-5">
  <div class="card shadow p-4">
    <h2 class="text-center mb-4">📊 Reporte Diario de Ventas</h2>
    <p class="text-center text-muted">Resumen de ventas por día con opción de exportación a PDF.</p>

    <form method="GET" class="row g-3 mb-4">
      <div class="col-md-3">
        <label class="form-label">Desde:</label>
        <input type="date" name="fecha_desde" value="<?= $_GET['fecha_desde'] ?? '' ?>" class="form-control">
      </div>
      <div class="col-md-3">
        <label class="form-label">Hasta:</label>
        <input type="date" name="fecha_hasta" value="<?= $_GET['fecha_hasta'] ?? '' ?>" class="form-control">
      </div>
      <div class="col-md-3">
        <label class="form-label">Fecha exacta:</label>
        <input type="date" name="buscar_fecha" value="<?= $_GET['buscar_fecha'] ?? '' ?>" class="form-control">
      </div>
      <div class="col-md-3 d-flex align-items-end">
        <div class="d-flex gap-2 w-100">
          <button type="submit" class="btn btn-primary w-50">🔍 Buscar</button>
          <a href="reporte_dia.php" class="btn btn-secondary w-50">🔁 Limpiar</a>
        </div>
      </div>
    </form>

    <?php if ($ventas_dia && $ventas_dia->num_rows > 0): ?>
      <div class="table-responsive">
        <table class="table table-bordered table-hover text-center align-middle">
          <thead class="table-dark">
            <tr>
              <th>📆 Fecha</th>
              <th>💵 Total del Día</th>
              <th>🧾 N° de Ventas</th>
              <th>🔎 Ver Detalles</th>
              <th>📄 PDF</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($row = $ventas_dia->fetch_assoc()): ?>
              <tr>
                <td><?= date("d/m/Y", strtotime($row['fecha_dia'])) ?></td>
                <td>S/ <?= number_format($row['total_dia'], 2) ?></td>
                <td><?= $row['cantidad_ventas'] ?></td>
                <td>
                  <a href="ver_ventas_por_fecha.php?fecha=<?= $row['fecha_dia'] ?>" class="btn btn-sm btn-primary">📊 Ver</a>
                </td>
                <td>
                  <a href="exportar_pdf_dia.php?fecha=<?= $row['fecha_dia'] ?>" class="btn btn-sm btn-danger" target="_blank">📄 PDF</a>
                </td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    <?php else: ?>
      <div class="alert alert-warning text-center">⚠️ No se encontraron ventas para los filtros aplicados.</div>
    <?php endif; ?>

    <div class="text-center mt-4">
      <a href="dashboard_admin.php" class="btn btn-secondary">← Volver al Panel</a>
    </div>
  </div>
</div>
</body>
</html>
