<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
  header("Location: login.php");
  exit();
}

include("conexion.php");

$telas = $conexion->query("SELECT * FROM telas WHERE metros_disponibles > 0");

$categorias = $conexion->query("SELECT * FROM categorias");
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Producción con Tela - Luvadak</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      background-color: #f4f6f8;
    }
    .form-container {
      background: #fff;
      border-left: 6px solid #0d6efd;
      border-radius: 16px;
      padding: 30px;
      box-shadow: 0 6px 16px rgba(0, 0, 0, 0.08);
    }
    h3 {
      color: #0d6efd;
      font-weight: 600;
    }
    .form-control,
    .form-select {
      font-size: 14px;
    }
    .form-label {
      font-weight: 500;
      color: #333;
    }
  </style>
</head>
<body>

<div class="container d-flex justify-content-center align-items-center min-vh-100">
  <form action="procesar_produccion_tela.php" method="POST" class="form-container col-lg-7 shadow">
    <h3 class="text-center mb-4"><i class="bi bi-scissors"></i> Producción con Tela del Inventario</h3>

    <div class="mb-3">
      <label class="form-label"><i class="bi bi-tag"></i> Nombre del producto</label>
      <input type="text" name="producto" class="form-control" placeholder="Ej: Polo básico mujer" required>
    </div>

    <div class="mb-3">
      <label class="form-label"><i class="bi bi-card-text"></i> Descripción</label>
      <input type="text" name="descripcion" class="form-control" placeholder="Ej: Algodón 100% con cuello redondo" required>
    </div>

    <div class="mb-3">
      <label class="form-label"><i class="bi bi-palette"></i> Color</label>
      <input type="text" name="color" class="form-control" placeholder="Ej: Rosa pastel" required>
    </div>

    <div class="mb-3">
      <label class="form-label"><i class="bi bi-box-fill"></i> Selecciona la tela</label>
      <select name="id_tela" class="form-select" required>
        <option value="">-- Selecciona --</option>
        <?php while ($fila = $telas->fetch_assoc()) { ?>
          <option value="<?= $fila['id_tela']; ?>">
            <?= $fila['nombre_tela'] . " (S/{$fila['precio_por_metro']} - {$fila['metros_disponibles']} m)" ?>
          </option>
        <?php } ?>
      </select>
    </div>

    <div class="row">
      <div class="col-md-6 mb-3">
        <label class="form-label"><i class="bi bi-rulers"></i> Talla</label>
        <select name="talla" class="form-select" required>
          <option value="">Selecciona una talla</option>
          <?php foreach (["XS", "S", "M", "L", "XL"] as $talla): ?>
            <option value="<?= $talla ?>"><?= $talla ?></option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="col-md-6 mb-3">
        <label class="form-label"><i class="bi bi-tags"></i> Categoría</label>
        <select name="id_categoria" class="form-select" required>
          <option value="">-- Selecciona una categoría --</option>
          <?php while ($cat = $categorias->fetch_assoc()): ?>
            <option value="<?= $cat['id_categoria']; ?>"><?= $cat['nombre_categoria']; ?></option>
          <?php endwhile; ?>
        </select>
      </div>
    </div>

    <div class="row">
      <div class="col-md-6 mb-3">
        <label class="form-label"><i class="bi bi-123"></i> Cantidad de productos</label>
        <input type="number" step="1" name="cantidad_productos" class="form-control" required>
      </div>
      <div class="col-md-6 mb-3">
        <label class="form-label"><i class="bi bi-rulers"></i>Material empleado</label>
        <input type="number" step="0.01" name="metros_usados" class="form-control" required>
      </div>
    </div>

    <div class="row">
      <div class="col-md-6 mb-3">
        <label class="form-label"><i class="bi bi-person-workspace"></i> Costo mano de obra</label>
        <input type="number" step="0.01" name="mano_obra" class="form-control" required>
      </div>
      <div class="col-md-6 mb-3">
        <label class="form-label"><i class="bi bi-tools"></i> Otros costos</label>
        <input type="number" step="0.01" name="otros_costos" class="form-control" required>
      </div>
    </div>

    <div class="mb-3">
      <label class="form-label"><i class="bi bi-cash-coin"></i> Precio de venta por unidad</label>
      <input type="number" step="0.01" name="precio_venta" class="form-control" required>
    </div>

    <div class="d-grid gap-2 mt-3">
      <button type="submit" class="btn btn-primary w-100">✅ Registrar Producción</button>
      <a href="dashboard_admin.php" class="btn btn-secondary">⬅️ Volver al Panel</a>
    </div>
  </form>
</div>

</body>
</html>
