<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    header("Location: login.php");
    exit();
}

include("conexion.php");

$sql = "SELECT m.*, p.nombre_producto 
        FROM movimientosinventario m 
        JOIN productos p ON m.id_producto = p.id_producto 
        ORDER BY m.fecha_movimiento DESC";

$movimientos = $conexion->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Movimientos de Inventario - Luvadak</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      background-color: #f4f6f8;
      font-family: 'Segoe UI', sans-serif;
    }

    .card-movimientos {
      background-color: #fff;
      padding: 30px;
      border-radius: 16px;
      box-shadow: 0 6px 16px rgba(0,0,0,0.08);
      margin-top: 50px;
    }

    h3 {
      font-weight: 600;
      color: #0d6efd;
    }

    .table th, .table td {
      vertical-align: middle;
      font-size: 14px;
    }

    .table th {
      background-color: #212529;
      color: white;
    }

    .btn-volver {
      border-radius: 30px;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="card-movimientos">
      <div class="d-flex justify-content-between align-items-center mb-4">
        <h3><i class="bi bi-clipboard-data"></i> Historial de Movimientos</h3>
        <a href="dashboard_admin.php" class="btn btn-secondary btn-volver">
          <i class="bi bi-arrow-left"></i> Volver al Panel
        </a>
      </div>

      <div class="table-responsive">
        <table class="table table-striped table-bordered text-center align-middle">
          <thead>
            <tr>
              <th><i class="bi bi-calendar-event"></i> Fecha</th>
              <th><i class="bi bi-box-seam"></i> Producto</th>
              <th><i class="bi bi-arrow-repeat"></i> Tipo</th>
              <th><i class="bi bi-hash"></i> Cantidad</th>
              <th><i class="bi bi-chat-left-text"></i> Observaciones</th>
            </tr>
          </thead>
          <tbody>
            <?php if ($movimientos->num_rows > 0): ?>
              <?php while ($mov = $movimientos->fetch_assoc()): ?>
                <tr>
                  <td><?= date("d/m/Y H:i", strtotime($mov['fecha_movimiento'])); ?></td>
                  <td><?= htmlspecialchars($mov['nombre_producto']); ?></td>
                  <td><?= ucfirst($mov['tipo_movimiento']); ?></td>
                  <td><?= $mov['cantidad']; ?></td>
                  <td><?= htmlspecialchars($mov['observaciones']); ?></td>
                </tr>
              <?php endwhile; ?>
            <?php else: ?>
              <tr>
                <td colspan="5" class="text-muted">🔍 No hay movimientos registrados.</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</body>
</html>
