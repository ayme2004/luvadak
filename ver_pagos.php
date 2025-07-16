<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    header("Location: login.php");
    exit();
}

include("conexion.php");

$busqueda = isset($_GET['buscar']) ? trim($_GET['buscar']) : "";
$pagos = [];
$total_pagado = 0;

if (!empty($busqueda)) {
    $stmt = $conexion->prepare("
        SELECT p.fecha_pago, u.nombre_completo, p.monto
        FROM pagos_empleados p
        JOIN usuarios u ON p.id_usuario = u.id_usuario
        WHERE u.nombre_completo LIKE ?
        ORDER BY p.fecha_pago DESC
    ");
    $like = "%" . $busqueda . "%";
    $stmt->bind_param("s", $like);
    $stmt->execute();
    $resultado = $stmt->get_result();

    while ($fila = $resultado->fetch_assoc()) {
        $pagos[] = $fila;
        $total_pagado += $fila['monto'];
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Historial de Pagos por Empleado - Luvadak</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet" />
  <style>
    body {
      background-color: #f4f6f8;
    }
    .main-card {
      background: white;
      padding: 30px;
      border-radius: 16px;
      box-shadow: 0 6px 16px rgba(0,0,0,0.08);
      margin-top: 50px;
    }
  </style>
</head>
<body>
<div class="container">
  <div class="main-card">
    <h3 class="text-center mb-4"><i class="bi bi-journal-text me-2"></i>Historial de Pagos por Empleado</h3>

    <form method="GET" class="row g-2 justify-content-center mb-4">
      <div class="col-md-6">
        <input type="text" name="buscar" class="form-control" placeholder="🔍 Buscar por nombre del empleado" value="<?= htmlspecialchars($busqueda) ?>" required>
      </div>
      <div class="col-md-2">
        <button class="btn btn-primary w-100"><i class="bi bi-search"></i> Buscar</button>
      </div>
    </form>

    <?php if (!empty($busqueda)): ?>
      <h5>🔎 Resultados para: <strong><?= htmlspecialchars($busqueda) ?></strong></h5>

      <?php if (count($pagos) > 0): ?>
        <div class="table-responsive mt-3">
          <table class="table table-bordered table-hover text-center align-middle">
            <thead class="table-dark">
              <tr>
                <th><i class="bi bi-calendar-event"></i> Fecha de Pago</th>
                <th><i class="bi bi-person-circle"></i> Empleado</th>
                <th><i class="bi bi-cash-coin"></i> Monto</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($pagos as $p): ?>
                <tr>
                  <td><?= date("d/m/Y", strtotime($p['fecha_pago'])) ?></td>
                  <td><?= htmlspecialchars($p['nombre_completo']) ?></td>
                  <td>S/ <?= number_format($p['monto'], 2) ?></td>
                </tr>
              <?php endforeach; ?>
              <tr class="table-success fw-bold">
                <td colspan="2">💰 Total Pagado</td>
                <td>S/ <?= number_format($total_pagado, 2) ?></td>
              </tr>
            </tbody>
          </table>
        </div>

        <a href="exportar_pagos_empleado_pdf.php?nombre=<?= urlencode($busqueda) ?>" class="btn btn-danger mt-3">
          <i class="bi bi-file-earmark-pdf"></i> Exportar en PDF
        </a>
      <?php else: ?>
        <div class="alert alert-warning mt-3">⚠️ No se encontraron pagos registrados para ese empleado.</div>
      <?php endif; ?>
    <?php endif; ?>

    <div class="text-end mt-4">
      <a href="dashboard_admin.php" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Volver al Panel</a>
    </div>
  </div>
</div>
</body>
</html>
