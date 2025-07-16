<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    header("Location: login.php");
    exit();
}

include("conexion.php");

$productos = $conexion->query("SELECT id_producto, nombre_producto, talla, color, stock FROM productos");
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Registrar Movimiento - Luvadak</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      background-color: #f4f6f8;
      font-family: 'Segoe UI', sans-serif;
    }

    .form-container {
      border-left: 6px solid #0d6efd;
      border-radius: 16px;
      background-color: #fff;
      padding: 30px;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05);
      width: 100%;
      max-width: 600px;
    }

    .form-title {
      color: #0d6efd;
      font-weight: 600;
      text-align: center;
      margin-bottom: 1.5rem;
    }

    .form-label {
      font-size: 14px;
      margin-bottom: 0.4rem;
    }

    .form-select,
    .form-control {
      border-radius: 0.5rem;
      font-size: 14px;
    }

    option.sin-stock {
      background-color: #ffe6e6;
      color: #dc3545;
    }

    .btn {
      border-radius: 30px;
      font-size: 14px;
    }
  </style>
</head>
<body>
  <div class="container d-flex justify-content-center align-items-center min-vh-100">
    <form action="procesar_movimiento.php" method="POST" class="form-container">

      <h3 class="form-title"><i class="bi bi-arrow-left-right"></i> Registrar Movimiento</h3>

      <div class="mb-3">
        <label class="form-label"><i class="bi bi-box-seam"></i> Producto (con stock actual)</label>
        <select name="id_producto" class="form-select" required>
          <option value="">-- Selecciona un producto --</option>
          <?php while ($prod = $productos->fetch_assoc()) {
            $sin_stock = $prod['stock'] <= 0;
            $clase = $sin_stock ? "sin-stock" : "";
            $texto = $prod['nombre_producto'] . " (Talla: " . $prod['talla'] . ", Color: " . $prod['color'] . ")";
            $texto .= $sin_stock ? " ⚠️ SIN STOCK" : " | Stock: " . $prod['stock'];
          ?>
            <option value="<?= $prod['id_producto'] ?>" class="<?= $clase ?>"><?= $texto ?></option>
          <?php } ?>
        </select>
      </div>

      <div class="mb-3">
        <label class="form-label"><i class="bi bi-shuffle"></i> Tipo de movimiento</label>
        <select name="tipo_movimiento" class="form-select" required>
          <option value="">-- Selecciona tipo --</option>
          <option value="entrada">📥 Entrada (Agregar al inventario)</option>
          <option value="salida">📤 Salida (Quitar del inventario)</option>
        </select>
      </div>

      <div class="mb-3">
        <label class="form-label"><i class="bi bi-hash"></i> Cantidad</label>
        <input type="number" name="cantidad" class="form-control" min="1" required placeholder="Ej: 10">
      </div>

      <div class="mb-4">
        <label class="form-label"><i class="bi bi-pencil-square"></i> Observaciones (opcional)</label>
        <textarea name="observaciones" class="form-control" rows="3" placeholder="Ej: Reposición, error, venta directa..."></textarea>
      </div>

      <div class="d-grid gap-2">
        <button type="submit" class="btn btn-primary">
          ✅ Registrar Movimiento
        </button>
        <a href="dashboard_admin.php" class="btn btn-secondary">
          ⬅️ Volver al Panel
        </a>
      </div>

    </form>
  </div>
</body>
</html>
