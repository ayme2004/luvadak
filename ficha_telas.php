<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
  header("Location: login.php");
  exit();
}

include("conexion.php");

$buscar = isset($_GET['buscar']) ? trim($_GET['buscar']) : '';
$ordenar = isset($_GET['ordenar']) ? $_GET['ordenar'] : '';

$sql = "
SELECT DISTINCT nombre_tela FROM (
  SELECT nombre_tela FROM telas
  UNION
  SELECT tela AS nombre_tela FROM produccion
) AS todas_telas
";

if (!empty($buscar)) {
  $sql .= " WHERE nombre_tela LIKE '%$buscar%'";
}

$sql .= " ORDER BY nombre_tela";
$telas = $conexion->query($sql);

$total_comprados = $total_usados = $total_stock = $total_ganancia = $total_productos = 0;
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Ficha de Telas - Luvadak</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      background-color: #f4f6f9;
    }
    .card-container {
      background-color: #fff;
      padding: 30px;
      border-radius: 16px;
      box-shadow: 0 6px 16px rgba(0,0,0,0.08);
      margin-top: 50px;
    }
    h3 {
      color: #0d6efd;
      font-weight: bold;
    }
  </style>
</head>
<body>
<div class="container">
  <div class="card-container">
    <h3 class="mb-4 text-center"><i class="bi bi-file-earmark-text-fill me-2"></i>Ficha Resumen de Telas</h3>

    <form method="GET" class="row g-3 mb-4">
      <div class="col-md-4">
        <input type="text" name="buscar" class="form-control" placeholder="🔍 Buscar tela..." value="<?= htmlspecialchars($buscar) ?>">
      </div>
      <div class="col-md-3">
        <select name="ordenar" class="form-select">
          <option value="">Ordenar por...</option>
          <option value="comprados" <?= $ordenar == 'comprados' ? 'selected' : '' ?>>📥 Metros comprados</option>
          <option value="usados" <?= $ordenar == 'usados' ? 'selected' : '' ?>>✂️ Metros usados</option>
          <option value="ganancia" <?= $ordenar == 'ganancia' ? 'selected' : '' ?>>📈 Ganancia generada</option>
        </select>
      </div>
      <div class="col-md-2">
        <button class="btn btn-primary w-100"><i class="bi bi-funnel-fill me-1"></i>Aplicar</button>
      </div>
      <div class="col-md-3 text-end">
        <a href="dashboard_admin.php" class="btn btn-secondary"><i class="bi bi-arrow-left-circle me-1"></i>Volver</a>
      </div>
    </form>

    <div class="table-responsive">
      <table class="table table-bordered table-hover text-center align-middle">
        <thead class="table-dark">
          <tr>
            <th>🧵 Tela</th>
            <th>📥 Comprados</th>
            <th>✂️ Usados</th>
            <th>📦 Stock</th>
            <th>💰 Costo Prom.</th>
            <th>👕 Productos</th>
            <th>📈 Ganancia</th>
            <th>% Usado</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $data_filas = [];
          while ($fila = $telas->fetch_assoc()) {
            $nombre = $fila['nombre_tela'];

            $c = $conexion->query("SELECT SUM(metros_comprados) AS metros, SUM(precio_total) AS total FROM compras_telas WHERE nombre_tela = '$nombre'")->fetch_assoc();
            $metros_comprados = $c['metros'] ?? 0;
            $precio_total = $c['total'] ?? 0;
            $costo_promedio = ($metros_comprados > 0) ? ($precio_total / $metros_comprados) : 0;

            $u = $conexion->query("SELECT SUM(metros_usados) AS usados, SUM(cantidad) AS productos, SUM(ganancia) AS ganancia FROM produccion WHERE tela = '$nombre'")->fetch_assoc();
            $metros_usados = $u['usados'] ?? 0;
            $productos = $u['productos'] ?? 0;
            $ganancia = $u['ganancia'] ?? 0;

            $s = $conexion->query("SELECT metros_disponibles FROM telas WHERE nombre_tela = '$nombre'")->fetch_assoc();
            $stock_actual = $s['metros_disponibles'] ?? 0;

            $porcentaje_usado = ($metros_comprados > 0) ? ($metros_usados / $metros_comprados) * 100 : 0;

            $data_filas[] = [
              'nombre' => $nombre,
              'comprados' => $metros_comprados,
              'usados' => $metros_usados,
              'stock' => $stock_actual,
              'costo' => $costo_promedio,
              'productos' => $productos,
              'ganancia' => $ganancia,
              'porcentaje' => $porcentaje_usado
            ];

            $total_comprados += $metros_comprados;
            $total_usados += $metros_usados;
            $total_stock += $stock_actual;
            $total_ganancia += $ganancia;
            $total_productos += $productos;
          }

          if ($ordenar) {
            usort($data_filas, function($a, $b) use ($ordenar) {
              return $b[$ordenar] <=> $a[$ordenar];
            });
          }

          foreach ($data_filas as $fila) {
            $clase_fila = ($fila['stock'] < 5) ? "table-danger" : "";
            echo "<tr class='$clase_fila'>
              <td>" . htmlspecialchars($fila['nombre']) . "</td>
              <td>" . number_format($fila['comprados'], 2) . " m</td>
              <td>" . number_format($fila['usados'], 2) . " m</td>
              <td>" . number_format($fila['stock'], 2) . " m</td>
              <td>S/ " . number_format($fila['costo'], 2) . "</td>
              <td>{$fila['productos']}</td>
              <td><span class='text-success fw-bold'>S/ " . number_format($fila['ganancia'], 2) . "</span></td>
              <td>" . number_format($fila['porcentaje'], 1) . "%</td>
            </tr>";
          }
          ?>
        </tbody>
        <tfoot class="table-secondary fw-bold text-center">
          <tr>
            <td>Total</td>
            <td><?= number_format($total_comprados, 2); ?> m</td>
            <td><?= number_format($total_usados, 2); ?> m</td>
            <td><?= number_format($total_stock, 2); ?> m</td>
            <td>-</td>
            <td><?= $total_productos; ?></td>
            <td><span class="text-success">S/ <?= number_format($total_ganancia, 2); ?></span></td>
            <td>-</td>
          </tr>
        </tfoot>
      </table>
    </div>
  </div>
</div>
</body>
</html>
