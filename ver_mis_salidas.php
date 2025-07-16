<?php
session_start();
if (!isset($_SESSION['usuario']) || ($_SESSION['rol'] !== 'empleado' && $_SESSION['rol'] !== 'admin')) {
    header("Location: login.php");
    exit();
}

include("conexion.php");

$rol = $_SESSION['rol'];
$id_usuario = $_SESSION['id_usuario'];

if ($rol === 'admin') {
    $sql = "SELECT m.fecha_movimiento, p.nombre_producto, m.cantidad, m.observaciones, u.nombre_completo AS usuario
            FROM movimientosinventario m
            INNER JOIN productos p ON m.id_producto = p.id_producto
            LEFT JOIN usuarios u ON m.id_usuario = u.id_usuario
            WHERE m.tipo_movimiento = 'salida'
            ORDER BY m.fecha_movimiento DESC";

    $resultado = $conexion->query($sql);
} else {
    $sql = "SELECT m.fecha_movimiento, p.nombre_producto, m.cantidad, m.observaciones
            FROM movimientosinventario m
            INNER JOIN productos p ON m.id_producto = p.id_producto
            WHERE m.tipo_movimiento = 'salida' AND m.id_usuario = ?
            ORDER BY m.fecha_movimiento DESC";

    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id_usuario);
    $stmt->execute();
    $resultado = $stmt->get_result();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Salidas de Inventario</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
  <h3 class="mb-4">📤 <?php echo ($rol === 'admin') ? 'Todas las Salidas' : 'Mis Salidas'; ?> de Inventario</h3>
  <a href="<?php echo ($rol === 'admin') ? 'dashboard_admin.php' : 'dashboard_empleado.php'; ?>" class="btn btn-secondary mb-3">← Volver al Panel</a>

  <table class="table table-bordered table-hover">
    <thead class="table-dark">
      <tr>
        <th>Fecha</th>
        <th>Producto</th>
        <th>Cantidad</th>
        <?php if ($rol === 'admin') echo "<th>Usuario</th>"; ?>
        <th>Observaciones</th>
      </tr>
    </thead>
    <tbody>
      <?php if ($resultado->num_rows === 0): ?>
        <tr>
          <td colspan="<?php echo ($rol === 'admin') ? '5' : '4'; ?>" class="text-center text-muted">
            🚫 No se encontraron salidas registradas.
          </td>
        </tr>
      <?php else: ?>
        <?php while ($fila = $resultado->fetch_assoc()) { ?>
          <tr>
            <td><?php echo $fila['fecha_movimiento']; ?></td>
            <td><?php echo $fila['nombre_producto']; ?></td>
            <td><?php echo $fila['cantidad']; ?></td>
            <?php if ($rol === 'admin') echo "<td>{$fila['usuario']}</td>"; ?>
            <td><?php echo $fila['observaciones']; ?></td>
          </tr>
        <?php } ?>
      <?php endif; ?>
    </tbody>
  </table>
</div>
</body>
</html>
