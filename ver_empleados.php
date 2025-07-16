<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    header("Location: login.php");
    exit();
}

include("conexion.php");

$busqueda = isset($_GET['buscar']) ? $_GET['buscar'] : "";

$sql = "SELECT * FROM usuarios WHERE nombre_completo LIKE ? OR correo LIKE ? OR usuario LIKE ? ORDER BY id_usuario ASC";
$stmt = $conexion->prepare($sql);
$param = "%$busqueda%";
$stmt->bind_param("sss", $param, $param, $param);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <title>Gestión de Empleados - Luvadak</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      background-color: #f4f6f9;
    }
    .form-card {
      background: #fff;
      border-radius: 16px;
      padding: 30px;
      box-shadow: 0 6px 16px rgba(0,0,0,0.08);
      margin-top: 40px;
    }
    .btn-sm {
      padding: 0.3rem 0.6rem;
      font-size: 0.85rem;
    }
    h2 {
      color: #0d6efd;
      font-weight: bold;
    }
  </style>
</head>
<body>
<div class="container">
  <div class="form-card">
    <h2 class="mb-4 text-center"><i class="bi bi-people-fill me-2"></i>Gestión de Empleados</h2>

    <form class="row g-3 mb-4 justify-content-between" method="GET">
      <div class="col-md-6">
        <input type="text" class="form-control" name="buscar" placeholder="🔍 Buscar por nombre, correo o usuario" value="<?= htmlspecialchars($busqueda) ?>" />
      </div>
      <div class="col-md-2 d-grid">
        <button type="submit" class="btn btn-primary"><i class="bi bi-search me-1"></i>Buscar</button>
      </div>
      <div class="col-md-2 d-grid">
        <a href="agregar_empleado.php" class="btn btn-success"><i class="bi bi-plus-circle me-1"></i>Agregar</a>
      </div>
      <div class="col-md-2 d-grid">
        <a href="dashboard_admin.php" class="btn btn-secondary"><i class="bi bi-arrow-left me-1"></i>Volver</a>
      </div>
    </form>

    <div class="table-responsive">
      <table class="table table-bordered table-hover align-middle text-center">
        <thead class="table-dark">
          <tr>
            <th>#</th>
            <th>👤 Nombre</th>
            <th>📧 Correo</th>
            <th>🧾 Usuario</th>
            <th>🔐 Rol</th>
            <th>📅 Registro</th>
            <th>⚙️ Acciones</th>
          </tr>
        </thead>
        <tbody>
          <?php while($row = $result->fetch_assoc()): ?>
          <tr>
            <td><?= $row['id_usuario'] ?></td>
            <td><?= htmlspecialchars($row['nombre_completo']) ?></td>
            <td><?= htmlspecialchars($row['correo']) ?></td>
            <td><?= htmlspecialchars($row['usuario']) ?></td>
            <td><?= htmlspecialchars($row['rol']) ?></td>
            <td><?= date("d/m/Y", strtotime($row['fecha_registro'])) ?></td>
            <td>
              <div class="d-flex justify-content-center gap-1 flex-wrap">
                <a href="editar_empleado.php?id=<?= $row['id_usuario'] ?>" class="btn btn-warning btn-sm" title="Editar"><i class="bi bi-pencil-fill"></i></a>
                <a href="cambiar_contrasena.php?id=<?= $row['id_usuario'] ?>" class="btn btn-info btn-sm text-white" title="Cambiar contraseña"><i class="bi bi-key-fill"></i></a>
                <a href="eliminar_empleado.php?id=<?= $row['id_usuario'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Seguro que deseas eliminar este empleado?');" title="Eliminar"><i class="bi bi-trash-fill"></i></a>
              </div>
            </td>
          </tr>
          <?php endwhile; ?>

          <?php if($result->num_rows === 0): ?>
          <tr><td colspan="7" class="text-center text-muted">No se encontraron empleados.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
</body>
</html>

<?php
$stmt->close();
$conexion->close();
?>
