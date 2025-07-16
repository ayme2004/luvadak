<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    header("Location: login.php");
    exit();
}

include("conexion.php");

$empleados = $conexion->query("SELECT id_usuario, nombre_completo FROM usuarios WHERE rol = 'empleado'");
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Registrar Pago - Luvadak</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet" />
  <style>
    body {
      background-color: #f4f6f8;
    }
    .form-card {
      background: #fff;
      border-radius: 16px;
      padding: 30px;
      box-shadow: 0 6px 16px rgba(0,0,0,0.08);
      margin-top: 60px;
    }
  </style>
</head>
<body>
<div class="container col-md-6">
  <div class="form-card">

    <h3 class="mb-4 text-center">
      <i class="bi bi-cash-coin me-2"></i>Registrar Pago a Empleado
    </h3>

    <form action="procesar_pago.php" method="POST">
      <div class="mb-3">
        <label class="form-label">👤 Empleado</label>
        <select name="id_usuario" class="form-select" required>
          <option value="">Selecciona un empleado</option>
          <?php while ($emp = $empleados->fetch_assoc()): ?>
            <option value="<?= $emp['id_usuario']; ?>">
              <?= htmlspecialchars($emp['nombre_completo']); ?>
            </option>
          <?php endwhile; ?>
        </select>
      </div>

      <div class="mb-3">
        <label class="form-label">💰 Monto (S/)</label>
        <input type="number" step="0.01" name="monto" class="form-control" placeholder="Ej: 850.00" required>
      </div>

      <div class="mb-4">
        <label class="form-label">📅 Fecha de pago</label>
        <input type="date" name="fecha_pago" class="form-control" required>
      </div>

      <div class="d-grid gap-2">
        <button type="submit" class="btn btn-success">
          <i class="bi bi-check2-circle me-1"></i> Registrar Pago
        </button>
        <a href="dashboard_admin.php" class="btn btn-secondary">
          <i class="bi bi-arrow-left me-1"></i> Volver al Panel
        </a>
      </div>
    </form>
  </div>
</div>
</body>
</html>
