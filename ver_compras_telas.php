<?php
include("conexion.php");

$busqueda = isset($_GET['buscar']) ? trim($_GET['buscar']) : '';
$mes = isset($_GET['mes']) ? $_GET['mes'] : '';
$anio = isset($_GET['anio']) ? $_GET['anio'] : '';

$condiciones = [];

if (!empty($busqueda)) {
    $condiciones[] = "nombre_tela LIKE '%$busqueda%'";
}
if (!empty($mes)) {
    $condiciones[] = "MONTH(fecha_compra) = $mes";
}
if (!empty($anio)) {
    $condiciones[] = "YEAR(fecha_compra) = $anio";
}

$whereSQL = "";
if (!empty($condiciones)) {
    $whereSQL = "WHERE " . implode(" AND ", $condiciones);
}

$compras = $conexion->query("SELECT * FROM compras_telas $whereSQL ORDER BY fecha_compra DESC");
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Historial de Compras de Telas</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      background-color: #f3f6f9;
    }
    .main-box {
      background: #fff;
      padding: 30px;
      border-radius: 16px;
      box-shadow: 0 6px 16px rgba(0, 0, 0, 0.08);
      margin-top: 50px;
    }
    h3 {
      color: #0d6efd;
      font-weight: 600;
    }
    .form-label {
      font-weight: 500;
    }
  </style>
</head>
<body>
<div class="container">
  <div class="main-box">
    <h3 class="mb-4"><i class="bi bi-book-fill me-2"></i>Historial de Compras de Telas</h3>
    <a href="dashboard_admin.php" class="btn btn-secondary mb-3"><i class="bi bi-arrow-left-circle me-1"></i>Volver al Panel</a>

    <form method="GET" class="row g-2 mb-4">
      <div class="col-md-4">
        <input type="text" name="buscar" class="form-control" placeholder="🔍 Buscar por nombre" value="<?= htmlspecialchars($busqueda) ?>">
      </div>
      <div class="col-md-3">
        <select name="mes" class="form-select">
          <option value="">📅 Mes</option>
          <?php
          $meses = [
            1 => "Enero", 2 => "Febrero", 3 => "Marzo", 4 => "Abril",
            5 => "Mayo", 6 => "Junio", 7 => "Julio", 8 => "Agosto",
            9 => "Septiembre", 10 => "Octubre", 11 => "Noviembre", 12 => "Diciembre"
          ];
          foreach ($meses as $num => $nombre) {
            $selected = ($mes == $num) ? 'selected' : '';
            echo "<option value='$num' $selected>$nombre</option>";
          }
          ?>
        </select>
      </div>
      <div class="col-md-3">
        <select name="anio" class="form-select">
          <option value="">📆 Año</option>
          <?php
          $anioActual = date("Y");
          for ($a = $anioActual; $a >= 2020; $a--) {
            $selected = ($anio == $a) ? 'selected' : '';
            echo "<option value='$a' $selected>$a</option>";
          }
          ?>
        </select>
      </div>
      <div class="col-md-2">
        <button type="submit" class="btn btn-primary w-100"><i class="bi bi-funnel-fill me-1"></i>Filtrar</button>
      </div>
    </form>

    <div class="table-responsive">
      <?php if ($compras->num_rows > 0): ?>
      <table class="table table-bordered table-hover align-middle text-center">
        <thead class="table-dark">
          <tr>
            <th>#</th>
            <th>Nombre de Tela</th>
            <th>Cantidad</th>
            <th>Precio Total (S/)</th>
            <th>Proveedor</th>
            <th>Observaciones</th>
            <th>Fecha de Compra</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $contador = 1;
          while ($fila = $compras->fetch_assoc()) {
            $unidad = $fila['unidad'];
            $cantidad = $fila['metros_comprados'];
            $texto_cantidad = ($unidad === 'kilo') ? "{$cantidad} kg" : "{$cantidad} m";
            $precio = number_format($fila['precio_total'], 2);
            echo "<tr>
                    <td>{$contador}</td>
                    <td>" . htmlspecialchars($fila['nombre_tela']) . "</td>
                    <td>{$texto_cantidad}</td>
                    <td>S/ {$precio}</td>
                    <td>" . htmlspecialchars($fila['proveedor']) . "</td>
                    <td>" . htmlspecialchars($fila['observaciones']) . "</td>
                    <td>" . date("d/m/Y", strtotime($fila['fecha_compra'])) . "</td>
                  </tr>";
            $contador++;
          }
          ?>
        </tbody>
      </table>
      <?php else: ?>
        <div class="alert alert-warning text-center mt-4"><i class="bi bi-exclamation-triangle-fill me-2"></i>No se encontraron compras con esos filtros.</div>
      <?php endif; ?>
    </div>
  </div>
</div>
</body>
</html>
