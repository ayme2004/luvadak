<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}
include("conexion.php");

$busqueda = isset($_GET['buscar']) ? trim($_GET['buscar']) : '';

$sql = "SELECT p.id_producto, p.nombre_producto, p.descripcion, p.talla, p.color, 
               p.precio, p.stock, c.nombre_categoria 
        FROM productos p 
        LEFT JOIN categorias c ON p.id_categoria = c.id_categoria";

if (!empty($busqueda)) {
    $sql .= " WHERE p.nombre_producto LIKE '%$busqueda%' 
              OR c.nombre_categoria LIKE '%$busqueda%'";
}

$resultado = $conexion->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Productos - Luvadak</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      background-color: #f4f6f8;
    }

    .card-container {
      background-color: #ffffff;
      padding: 40px;
      border-radius: 1rem;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
      margin-top: 60px;
    }

    .form-control {
      border-radius: 2rem;
    }

    .form-control:focus {
      box-shadow: 0 0 0 0.2rem rgba(13,110,253,0.25);
    }

    .btn-primary {
      border-radius: 2rem;
      padding-left: 20px;
      padding-right: 20px;
    }

    .btn-outline-secondary {
      border-radius: 2rem;
    }

    .table thead th {
      vertical-align: middle;
    }

    .table td, .table th {
      vertical-align: middle;
    }
  </style>
</head>
<body>
<div class="container">
  <div class="card-container">
    <h2 class="mb-4 text-center">
      <i class="bi bi-box-seam-fill text-primary me-2"></i>
      Lista de Productos Registrados
    </h2>

    <form method="GET" class="d-flex mb-4" role="search">
      <input type="text" name="buscar" class="form-control me-2" placeholder="🔍 Buscar por nombre o categoría..." value="<?= htmlspecialchars($busqueda) ?>">
      <button class="btn btn-primary" type="submit">
        <i class="bi bi-search"></i> Buscar
      </button>
    </form>

    <div class="mb-3 text-end">
      <a href="<?= $_SESSION['rol'] === 'admin' ? 'dashboard_admin.php' : 'dashboard_empleado.php' ?>" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Volver al Panel <?= $_SESSION['rol'] === 'admin' ? 'Admin' : 'Empleado' ?>
      </a>
    </div>

    <div class="table-responsive">
      <table class="table table-hover table-bordered align-middle text-center">
        <thead class="table-light">
          <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Descripción</th>
            <th>Talla</th>
            <th>Color</th>
            <th>Precio</th>
            <th>Stock</th>
            <th>Categoría</th>
            <?php if ($_SESSION['rol'] === 'admin') echo "<th>Acciones</th>"; ?>
          </tr>
        </thead>
        <tbody>
          <?php if ($resultado->num_rows > 0): ?>
            <?php while ($fila = $resultado->fetch_assoc()): ?>
              <tr>
                <td><?= $fila['id_producto']; ?></td>
                <td><?= htmlspecialchars($fila['nombre_producto']); ?></td>
                <td><?= htmlspecialchars($fila['descripcion']); ?></td>
                <td><?= $fila['talla']; ?></td>
                <td><?= $fila['color']; ?></td>
                <td><strong>S/ <?= number_format($fila['precio'], 2); ?></strong></td>
                <td><?= $fila['stock']; ?></td>
                <td><?= $fila['nombre_categoria']; ?></td>
                <?php if ($_SESSION['rol'] === 'admin'): ?>
                  <td>
                    <a href="editar_producto.php?id=<?= $fila['id_producto']; ?>" class="btn btn-warning btn-sm rounded-pill me-1">
                      <i class="bi bi-pencil-square"></i> Editar
                    </a>
                    <a href="eliminar_producto.php?id=<?= $fila['id_producto']; ?>" class="btn btn-danger btn-sm rounded-pill" onclick="return confirm('¿Estás segura de eliminar este producto?');">
                      <i class="bi bi-trash-fill"></i> Eliminar
                    </a>
                  </td>
                <?php endif; ?>
              </tr>
            <?php endwhile; ?>
          <?php else: ?>
            <tr>
              <td colspan="<?= $_SESSION['rol'] === 'admin' ? '9' : '8' ?>" class="text-center text-muted">
                <i class="bi bi-search"></i> No se encontraron productos con ese criterio.
              </td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
</body>
</html>
