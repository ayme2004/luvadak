<?php
include("conexion.php");

$buscar = "";
$resultado = null;

if (isset($_GET['buscar'])) {
    $buscar = $conexion->real_escape_string($_GET['buscar']);
    $sql = "SELECT c.nombre_completo, v.id_venta, v.fecha, v.total
            FROM clientes c
            JOIN ventas v ON c.id_cliente = v.id_cliente
            WHERE c.nombre_completo LIKE '%$buscar%'
            ORDER BY v.fecha DESC";
    $resultado = $conexion->query($sql);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Clientes - Luvadak</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      background-color: #f8f9fa;
    }

    .card {
      border: none;
      border-radius: 1rem;
    }

    .btn-primary {
      background-color: #0d6efd;
      border: none;
    }

    .btn-outline-secondary:hover {
      background-color: #e2e6ea;
    }

    .table th, .table td {
      vertical-align: middle;
    }

    .search-input {
      border-radius: 2rem;
      padding-left: 1rem;
    }

    .search-btn {
      border-radius: 2rem;
    }

    .title-icon {
      font-size: 1.5rem;
      margin-right: 0.5rem;
      color: #0d6efd;
    }
  </style>
</head>
<body>
<div class="container mt-5">
  <div class="card shadow p-5">
    <h2 class="text-center mb-4">
      <i class="bi bi-receipt-cutoff title-icon"></i>
      Historial de Compras por Cliente
    </h2>

    <form method="GET" class="d-flex justify-content-center mb-4 gap-2">
      <input type="text" name="buscar" class="form-control search-input w-50" placeholder="Buscar cliente..." value="<?= htmlspecialchars($buscar) ?>" required>
      <button type="submit" class="btn btn-primary search-btn"><i class="bi bi-search"></i></button>
      <a href="historial_clientes_empleado.php" class="btn btn-outline-secondary search-btn"><i class="bi bi-arrow-clockwise"></i></a>
    </form>

    <?php if ($resultado && $resultado->num_rows > 0): ?>
      <div class="table-responsive">
        <table class="table table-hover align-middle text-center">
          <thead class="table-light">
            <tr>
              <th>Cliente</th>
              <th>ID Venta</th>
              <th>Fecha</th>
              <th>Total</th>
            </tr>
          </thead>
          <tbody>
            <?php while ($row = $resultado->fetch_assoc()): ?>
              <tr>
                <td><?= $row['nombre_completo'] ?></td>
                <td>#<?= $row['id_venta'] ?></td>
                <td><?= date("d/m/Y", strtotime($row['fecha'])) ?></td>
                <td><strong>S/ <?= number_format($row['total'], 2) ?></strong></td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    <?php elseif ($buscar): ?>
      <div class="alert alert-warning text-center">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>
        No se encontraron compras para el cliente <strong><?= htmlspecialchars($buscar) ?></strong>.
      </div>
    <?php endif; ?>

    <div class="text-center mt-4">
      <a href="dashboard_empleado.php" class="btn btn-secondary rounded-pill px-4">
        <i class="bi bi-arrow-left"></i> Volver al Panel
      </a>
    </div>
  </div>
</div>
</body>
</html>
