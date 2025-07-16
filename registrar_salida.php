<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'empleado') {
    header("Location: login.php");
    exit();
}

include("conexion.php");

$productos = $conexion->query("SELECT id_producto, nombre_producto, stock, talla FROM productos");
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Registrar Salida de Producto</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script>
    function actualizarStock() {
      const select = document.getElementById("producto");
      const option = select.options[select.selectedIndex];
      const stock = option.getAttribute("data-stock");
      const talla = option.getAttribute("data-talla");

      document.getElementById("stock_disponible").innerText = stock ? "📦 Stock disponible: " + stock : "";
      document.getElementById("talla_disponible").innerText = talla ? "📏 Talla: " + talla : "";
    }
  </script>
</head>
<body>
  <div class="container mt-5 col-md-6">
    <form action="procesar_salida.php" method="POST" class="card p-4 shadow" onsubmit="return confirm('¿Confirmar salida del producto?');">
      <h3 class="mb-3">📤 Registrar Salida de Producto</h3>
      <p>Empleado: <strong><?= $_SESSION['usuario']; ?></strong></p>

      <label for="producto" class="form-label">Producto</label>
      <select name="id_producto" id="producto" class="form-select mb-2" onchange="actualizarStock()" required>
        <option value="">Selecciona un producto</option>
        <?php while ($prod = $productos->fetch_assoc()) { ?>
          <option 
            value="<?= $prod['id_producto'] ?>" 
            data-stock="<?= $prod['stock'] ?>" 
            data-talla="<?= $prod['talla'] ?>">
            <?= $prod['nombre_producto'] ?>
          </option>
        <?php } ?>
      </select>
      <div id="stock_disponible" class="text-muted mb-1"></div>
      <div id="talla_disponible" class="text-muted mb-2"></div>

      <label for="cantidad" class="form-label">Cantidad</label>
      <input type="number" name="cantidad" id="cantidad" class="form-control mb-2" placeholder="Cantidad a retirar" required min="1">

      <label for="motivo" class="form-label">Motivo (opcional)</label>
      <select name="motivo" id="motivo" class="form-select mb-2">
        <option value="">Selecciona un motivo</option>
        <option value="venta">Venta</option>
        <option value="muestra">Muestra</option>
        <option value="error">Error</option>
        <option value="obsequio">Obsequio</option>
      </select>

      <label for="observaciones" class="form-label">Observaciones</label>
      <textarea name="observaciones" id="observaciones" class="form-control mb-3" placeholder="Observaciones (opcional)"></textarea>

      <button type="submit" class="btn btn-primary w-100">Registrar salida</button>
    </form>
  </div>
</body>
</html>
