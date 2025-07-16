<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

include("conexion.php");

$productos = $conexion->query("SELECT id_producto, nombre_producto, precio, stock, talla, color FROM productos");
$clientes = $conexion->query("SELECT id_cliente, nombre_completo FROM clientes");
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Punto de Venta</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<style>
  body {
    background-color: #f4f6f8;
    font-size: 14px;
  }

  .card {
    border-radius: 1rem;
    overflow: hidden;
    box-shadow: 0 6px 20px rgba(0,0,0,0.08);
  }

  .card-header {
    padding: 0.75rem 1.25rem;
    font-weight: 600;
    font-size: 15px;
  }

  .form-label {
    font-size: 13px;
    margin-bottom: 0.35rem;
  }

  .form-control,
  .form-select {
    font-size: 13px;
    border-radius: 0.5rem;
    padding: 0.35rem 0.6rem;
  }

  .form-check-label {
    font-size: 13px;
  }

  .btn {
    font-size: 13px;
    border-radius: 2rem;
    padding: 0.4rem 1rem;
  }

  .btn-success {
    background-color: #198754;
    border: none;
  }

  .btn-danger {
    background-color: #dc3545;
    border: none;
  }

  .btn-secondary {
    background-color: #6c757d;
    border: none;
  }

  .table th, .table td {
    vertical-align: middle;
    font-size: 13px;
    padding: 0.45rem;
  }

  .table thead {
    background-color: #343a40;
    color: #fff;
  }

  h3 {
    font-size: 1.5rem;
    color: #0d6efd;
    font-weight: 600;
  }

  .table .btn-sm {
    border-radius: 1rem;
    font-size: 12px;
  }

  #total {
    font-size: 1.5rem;
    font-weight: bold;
    color: #198754;
  }
</style>

</head>
<body>

<div class="container py-4">
  <h3 class="text-center mb-4">🧾 Punto de Venta - Boleta</h3>
  <form action="procesar_venta.php" method="POST">

    <div class="row g-3">
      <div class="col-lg-6 d-flex flex-column gap-3">

        <div class="card flex-fill">
          <div class="card-header bg-primary text-white">Cliente</div>
          <div class="card-body">
            <label class="form-label">Seleccionar cliente</label>
            <select name="id_cliente" class="form-select mb-3" id="cliente_select" required>
              <option value="">Seleccione un cliente</option>
              <?php while ($c = $clientes->fetch_assoc()) { ?>
                <option value="<?= $c['id_cliente']; ?>"><?= $c['nombre_completo']; ?></option>
              <?php } ?>
            </select>
            <div class="form-check">
              <input class="form-check-input" type="checkbox" id="nuevoClienteCheck" onclick="toggleNuevoCliente()">
              <label class="form-check-label" for="nuevoClienteCheck">Registrar nuevo cliente</label>
            </div>
          </div>
        </div>

        <div id="nuevoClienteForm" style="display: none;" class="card flex-fill">
          <div class="card-header bg-info text-white">🧍 Datos del nuevo cliente</div>
          <div class="card-body">
            <div class="row g-2">
              <div class="col-md-6">
                <label>Nombre completo</label>
                <input type="text" name="nuevo_nombre" class="form-control">
              </div>
              <div class="col-md-6">
                <label>DNI</label>
                <input type="text" name="nuevo_dni" class="form-control">
              </div>
              <div class="col-md-6">
                <label>Teléfono</label>
                <input type="text" name="nuevo_telefono" class="form-control">
              </div>
              <div class="col-md-6">
                <label>Correo</label>
                <input type="email" name="nuevo_correo" class="form-control">
              </div>
              <div class="col-12">
                <label>Dirección</label>
                <textarea name="nuevo_direccion" class="form-control" rows="2"></textarea>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-6 d-flex flex-column gap-3">
        <div class="card flex-fill">
          <div class="card-header bg-success text-white">Producto</div>
          <div class="card-body">
            <div class="row g-2 align-items-end">
              <div class="col-md-6">
                <label class="form-label">Producto</label>
                <select id="producto" class="form-select">
                  <option value="">Seleccione un producto</option>
                  <?php while ($p = $productos->fetch_assoc()) { ?>
                    <option 
                      value="<?= $p['id_producto']; ?>" 
                      data-nombre="<?= $p['nombre_producto']; ?>"
                      data-precio="<?= $p['precio']; ?>"
                      data-stock="<?= $p['stock']; ?>"
                      data-talla="<?= $p['talla']; ?>"
                      data-color="<?= $p['color']; ?>"
                    >
                      <?= $p['nombre_producto']; ?> - T:<?= $p['talla']; ?> - C:<?= $p['color']; ?> - S/<?= number_format($p['precio'], 2); ?> (Stock: <?= $p['stock']; ?>)
                    </option>
                  <?php } ?>
                </select>
              </div>
              <div class="col-md-3">
                <label class="form-label">Cantidad</label>
                <input type="number" id="cantidad" class="form-control" min="1">
              </div>
              <div class="col-md-3">
                <button type="button" onclick="agregarProducto()" class="btn btn-primary w-100">➕ Agregar</button>
              </div>
            </div>
          </div>
        </div>

        <div class="card flex-fill">
          <div class="card-header bg-warning">Carrito</div>
          <div class="card-body table-responsive">
            <table class="table table-bordered text-center" id="tabla-carrito">
              <thead class="table-dark">
                <tr>
                  <th>Producto</th>
                  <th>Precio Unitario</th>
                  <th>Cantidad</th>
                  <th>Subtotal</th>
                  <th></th>
                </tr>
              </thead>
              <tbody></tbody>
            </table>
          </div>
        </div>

      </div>
    </div>

    <div class="row mt-3">
      <div class="col-12">
        <div class="card">
          <div class="card-header bg-secondary text-white">Total</div>
          <div class="card-body text-end">
            <h4>Total: <span id="total">S/ 0.00</span></h4>
            <input type="hidden" name="total" id="total_input">
          </div>
        </div>
      </div>
    </div>
    
<div class="d-grid gap-2 mt-3">
  <button type="submit" class="btn btn-success">
    <i class="bi bi-receipt-cutoff me-1"></i> Generar Boleta
  </button>
  <a href="dashboard_empleado.php" class="btn btn-secondary">
    <i class="bi bi-arrow-left-circle me-1"></i> Volver al Panel
  </a>
</div>

  </form>
</div>

<script>
function agregarProducto() {
  const select = document.getElementById("producto");
  const id = select.value;
  const nombre = select.options[select.selectedIndex].dataset.nombre;
  const precio = parseFloat(select.options[select.selectedIndex].dataset.precio);
  const stock = parseInt(select.options[select.selectedIndex].dataset.stock);
  const talla = select.options[select.selectedIndex].dataset.talla;
  const color = select.options[select.selectedIndex].dataset.color;
  const cantidad = parseInt(document.getElementById("cantidad").value);

  if (!id || isNaN(cantidad) || cantidad <= 0) {
    alert("Selecciona un producto válido y una cantidad.");
    return;
  }

  if (cantidad > stock) {
    alert("No hay suficiente stock disponible.");
    return;
  }

  const tabla = document.getElementById("tabla-carrito").getElementsByTagName("tbody")[0];
  const filas = tabla.getElementsByTagName("tr");
  for (let i = 0; i < filas.length; i++) {
    const inputId = filas[i].querySelector('input[name="id_producto[]"]');
    if (inputId && inputId.value === id) {
      const inputCant = filas[i].querySelector('input[name="cantidad[]"]');
      const nuevaCantidad = parseInt(inputCant.value) + cantidad;
      if (nuevaCantidad > stock) {
        alert("No puedes superar el stock disponible.");
        return;
      }
      inputCant.value = nuevaCantidad;
      filas[i].querySelector('.cantidad-visible').textContent = nuevaCantidad;
      filas[i].querySelector('.subtotal').textContent = "S/ " + (nuevaCantidad * precio).toFixed(2);
      actualizarTotal();
      return;
    }
  }

  const fila = tabla.insertRow();
  fila.innerHTML = `
    <td>
      <input type="hidden" name="id_producto[]" value="${id}">
      <input type="hidden" name="talla[]" value="${talla}">
      <input type="hidden" name="color[]" value="${color}">
      ${nombre}<br><small><strong>Talla:</strong> ${talla} - <strong>Color:</strong> ${color}</small>
    </td>
    <td><input type="hidden" name="precio_unitario[]" value="${precio}">S/ ${precio.toFixed(2)}</td>
    <td>
      <input type="hidden" name="cantidad[]" value="${cantidad}">
      <span class="cantidad-visible">${cantidad}</span>
    </td>
    <td class="subtotal">S/ ${(cantidad * precio).toFixed(2)}</td>
    <td><button type="button" onclick="eliminarFila(this)" class="btn btn-sm btn-danger">❌</button></td>
  `;
  actualizarTotal();
}

function eliminarFila(btn) {
  const fila = btn.parentNode.parentNode;
  fila.remove();
  actualizarTotal();
}

function actualizarTotal() {
  const subtotales = document.querySelectorAll(".subtotal");
  let total = 0;
  subtotales.forEach(celda => {
    total += parseFloat(celda.textContent.replace("S/ ", "")) || 0;
  });
  document.getElementById("total").textContent = "S/ " + total.toFixed(2);
  document.getElementById("total_input").value = total.toFixed(2);
}

function toggleNuevoCliente() {
  const check = document.getElementById("nuevoClienteCheck");
  const form = document.getElementById("nuevoClienteForm");
  const clienteSelect = document.getElementById("cliente_select");

  if (check.checked) {
    form.style.display = "block";
    clienteSelect.disabled = true;
    clienteSelect.required = false;
  } else {
    form.style.display = "none";
    clienteSelect.disabled = false;
    clienteSelect.required = true;
  }
}
</script>
</body>
</html>
