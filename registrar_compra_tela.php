<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Registrar Compra de Tela - Luvadak</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      background-color: #f3f6f9;
    }
    .form-card {
      background-color: #ffffff;
      padding: 30px;
      border-left: 6px solid #198754;
      border-radius: 16px;
      box-shadow: 0 6px 16px rgba(0,0,0,0.08);
      margin-top: 50px;
    }
    h3 {
      color: #198754;
      font-weight: 600;
    }
    .form-label {
      font-weight: 500;
    }
  </style>
</head>
<body>
  <div class="container d-flex justify-content-center align-items-center min-vh-100">
    <form action="procesar_compra_tela.php" method="POST" class="form-card col-md-6">
      <h3 class="text-center mb-4"><i class="bi bi-cart-plus-fill me-2"></i>Registrar Compra de Tela</h3>

      <div class="mb-3">
        <label class="form-label"><i class="bi bi-tag"></i> Nombre de la tela</label>
        <input type="text" name="nombre_tela" class="form-control" placeholder="Ej: Algodón stretch" required>
      </div>

      <div class="mb-3">
        <label class="form-label"><i class="bi bi-rulers"></i> Unidad de medida</label>
        <select name="unidad" class="form-select" required>
          <option value="">-- Selecciona unidad --</option>
          <option value="metro">Metro</option>
          <option value="kilo">Kilo</option>
        </select>
      </div>

      <div class="mb-3">
        <label class="form-label"><i class="bi bi-arrow-down-up"></i> Cantidad comprada</label>
        <input type="number" step="0.01" name="metros_comprados" class="form-control" placeholder="Ej: 25.5" required>
      </div>

      <div class="mb-3">
        <label class="form-label"><i class="bi bi-currency-dollar"></i> Precio total (S/)</label>
        <input type="number" step="0.01" name="precio_total" class="form-control" placeholder="Ej: 150.00" required>
      </div>

      <div class="mb-3">
        <label class="form-label"><i class="bi bi-truck"></i> Proveedor (opcional)</label>
        <input type="text" name="proveedor" class="form-control" placeholder="Nombre del proveedor">
      </div>

      <div class="mb-4">
        <label class="form-label"><i class="bi bi-chat-left-dots"></i> Observaciones (opcional)</label>
        <textarea name="observaciones" class="form-control" rows="3" placeholder="Ej: Compra con descuento, pago al contado..."></textarea>
      </div>

      <div class="d-grid gap-2">
        <button type="submit" class="btn btn-success"><i class="bi bi-check-circle-fill me-1"></i>Registrar Compra</button>
        <a href="dashboard_admin.php" class="btn btn-secondary"><i class="bi bi-arrow-left-circle me-1"></i>Volver al Panel</a>
      </div>
    </form>
  </div>
</body>
</html>
