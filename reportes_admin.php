<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    header("Location: login.php");
    exit();
}
include("conexion.php");

$ventas_dia = $conexion->query("SELECT SUM(dv.cantidad) AS total FROM detalle_venta dv JOIN ventas v ON dv.id_venta = v.id_venta WHERE DATE(v.fecha) = CURDATE()")->fetch_assoc()['total'] ?? 0;
$ventas_mes = $conexion->query("SELECT SUM(dv.cantidad) AS total FROM detalle_venta dv JOIN ventas v ON dv.id_venta = v.id_venta WHERE MONTH(v.fecha) = MONTH(CURDATE()) AND YEAR(v.fecha) = YEAR(CURDATE())")->fetch_assoc()['total'] ?? 0;
$ganancias = $conexion->query("SELECT SUM(dv.precio_unitario * dv.cantidad) AS total FROM detalle_venta dv JOIN ventas v ON dv.id_venta = v.id_venta WHERE MONTH(v.fecha) = MONTH(CURDATE()) AND YEAR(v.fecha) = YEAR(CURDATE())")->fetch_assoc()['total'] ?? 0;
$ganancias_dia = $conexion->query("SELECT SUM(dv.precio_unitario * dv.cantidad) AS total FROM detalle_venta dv JOIN ventas v ON dv.id_venta = v.id_venta WHERE DATE(v.fecha) = CURDATE()")->fetch_assoc()['total'] ?? 0;
$compras_mes = $conexion->query("SELECT SUM(metros_comprados) AS total FROM compras_telas WHERE MONTH(fecha_compra) = MONTH(CURDATE()) AND YEAR(fecha_compra) = YEAR(CURDATE())")->fetch_assoc()['total'] ?? 0;
$pagos = $conexion->query("SELECT SUM(monto) AS total FROM pagos_empleados WHERE MONTH(fecha_pago) = MONTH(CURDATE()) AND YEAR(fecha_pago) = YEAR(CURDATE())")->fetch_assoc()['total'] ?? 0;
$vendedores = $conexion->query("SELECT u.nombre_completo, SUM(dv.cantidad) AS total_vendidos FROM ventas v JOIN usuarios u ON v.id_usuario = u.id_usuario JOIN detalle_venta dv ON v.id_venta = dv.id_venta WHERE MONTH(v.fecha) = MONTH(CURDATE()) AND YEAR(v.fecha) = YEAR(CURDATE()) GROUP BY u.id_usuario ORDER BY total_vendidos DESC LIMIT 3");
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Panel de Reportes - Luvadak</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f5f7fa;
      font-family: 'Segoe UI', sans-serif;
    }
    .title {
      font-weight: 600;
      color: #343a40;
    }
    .card-report {
      border: none;
      border-radius: 16px;
      background: white;
      box-shadow: 0 4px 12px rgba(0,0,0,0.08);
      transition: 0.3s ease;
    }
    .card-report:hover {
      transform: translateY(-4px);
      box-shadow: 0 6px 18px rgba(0,0,0,0.1);
    }
    .card-icon {
      font-size: 1.5rem;
      margin-right: 10px;
    }
    .card-title {
      font-weight: 500;
      color: #6c757d;
    }
    .card-value {
      font-size: 1.8rem;
      font-weight: 600;
      color: #212529;
    }
    .top-vendedores li {
      margin-bottom: 0.5rem;
    }
    .btn-back {
      border-radius: 8px;
    }
  </style>
</head>
<body>
<div class="container py-5">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="title">📊 Panel de Reportes Administrativos</h3>
    <a href="dashboard_admin.php" class="btn btn-outline-secondary btn-back">← Volver</a>
  </div>

  <div class="row g-4">
    <?php
    $reportes = [
      ["📆", "Ventas del Día", "$ventas_dia unidades", "primary"],
      ["📅", "Ventas del Mes", "$ventas_mes unidades", "success"],
      ["📦", "Compras del Mes", "$compras_mes metros", "info"],
      ["💰", "Ganancias del Mes", "S/ " . number_format($ganancias ?? 0, 2), "warning"],
      ["💵", "Ganancias del Día", "S/ " . number_format($ganancias_dia ?? 0, 2), "success"],
      ["👥", "Pagos a Empleados", "S/ " . number_format($pagos ?? 0, 2), "danger"],
    ];
    foreach ($reportes as [$icon, $titulo, $valor, $color]):
    ?>
    <div class="col-md-4">
      <div class="card card-report">
        <div class="card-body d-flex flex-column align-items-start">
          <span class="card-title text-<?= $color ?>"><span class="card-icon"><?= $icon ?></span> <?= $titulo ?></span>
          <span class="card-value"><?= $valor ?></span>
        </div>
      </div>
    </div>
    <?php endforeach; ?>

    <div class="col-md-12">
      <div class="card card-report">
        <div class="card-body">
          <h5 class="card-title text-dark">🏆 Top 3 Vendedores del Mes</h5>
          <?php if ($vendedores->num_rows > 0): ?>
            <ol class="top-vendedores">
              <?php while ($v = $vendedores->fetch_assoc()): ?>
                <li><strong><?= htmlspecialchars($v['nombre_completo']) ?></strong> – <?= number_format($v['total_vendidos']) ?> unidades</li>
              <?php endwhile; ?>
            </ol>
          <?php else: ?>
            <p class="text-muted">No se han registrado ventas este mes.</p>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</div>
</body>
</html>
