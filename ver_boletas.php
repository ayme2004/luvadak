<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'empleado') {
    header("Location: login.php");
    exit();
}

include("conexion.php");

$id_usuario = $_SESSION['id_usuario'];

$query = "SELECT v.id_venta, v.total, v.fecha
          FROM ventas v
          WHERE v.id_usuario = $id_usuario
          ORDER BY v.fecha DESC";
$resultado = $conexion->query($query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Mis Boletas - Luvadak</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      background-color: #f4f6f8;
    }

    .card-container {
      background-color: #fff;
      padding: 30px;
      border-radius: 1rem;
      box-shadow: 0 6px 18px rgba(0, 0, 0, 0.07);
      margin-top: 60px;
    }

    h3 {
      color: #0d6efd;
      font-weight: 600;
    }

    .table thead th {
      vertical-align: middle;
    }

    .btn-sm {
      font-size: 13px;
      border-radius: 1.5rem;
      padding: 0.3rem 0.9rem;
    }

    .btn-secondary {
      border-radius: 2rem;
    }

    .text-muted {
      font-size: 14px;
    }
  </style>
</head>
<body>
<div class="container">
  <div class="card-container">
    <h3 class="mb-4 text-center">
      <i class="bi bi-receipt-cutoff me-2"></i>Historial de Boletas Emitidas
    </h3>

    <?php if ($resultado->num_rows > 0): ?>
      <div class="table-responsive">
        <table class="table table-striped table-bordered text-center align-middle">
          <thead class="table-dark">
            <tr>
              <th>ID Boleta</th>
              <th>Total</th>
              <th>Fecha</th>
              <th>Acción</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($boleta = $resultado->fetch_assoc()): ?>
              <tr>
                <td><?= $boleta['id_venta'] ?></td>
                <td><strong>S/ <?= number_format($boleta['total'], 2) ?></strong></td>
                <td><?= date("d/m/Y H:i", strtotime($boleta['fecha'])) ?></td>
                <td>
                  <a href="generar_boleta.php?id=<?= $boleta['id_venta'] ?>" target="_blank" class="btn btn-danger btn-sm">
                    <i class="bi bi-file-earmark-pdf"></i> Ver PDF
                  </a>
                </td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    <?php else: ?>
      <p class="text-center text-muted">🔕 No has emitido ninguna boleta aún.</p>
    <?php endif; ?>

    <div class="d-flex justify-content-center mt-4">
      <a href="dashboard_empleado.php" class="btn btn-secondary">
        <i class="bi bi-arrow-left-circle"></i> Volver al Panel
      </a>
    </div>
  </div>
</div>
</body>
</html>
