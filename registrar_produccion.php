<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Registrar Producción</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <div class="container mt-5 col-md-6">
    <form action="procesar_produccion.php" method="POST" class="card p-4 shadow">
      <h3 class="mb-3">🧵 Registrar Producción</h3>

      <input type="text" name="producto" class="form-control mb-2" placeholder="Nombre del producto" required>
      <input type="text" name="tela" class="form-control mb-2" placeholder="Tipo de tela" required>
      <input type="number" step="0.01" name="precio_tela" class="form-control mb-2" placeholder="Precio por metro de tela" required>
      <input type="number" step="0.01" name="metros_usados" class="form-control mb-2" placeholder="Metros usados" required>
      <input type="number" step="0.01" name="mano_obra" class="form-control mb-2" placeholder="Costo de mano de obra" required>
      <input type="number" step="0.01" name="otros_costos" class="form-control mb-2" placeholder="Otros costos (botones, hilos...)" required>
      <input type="number" step="0.01" name="precio_venta" class="form-control mb-2" placeholder="Precio de venta" required>

      <button type="submit" class="btn btn-primary w-100">Registrar</button>
    </form>
  </div>
</body>
</html>
