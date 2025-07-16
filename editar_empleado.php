<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    header("Location: login.php");
    exit();
}

include("conexion.php");

if (!isset($_GET['id'])) {
    header("Location: ver_empleados.php");
    exit();
}

$id = intval($_GET['id']);

$sql = "SELECT id_usuario, nombre_completo, correo, usuario, rol FROM usuarios WHERE id_usuario = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Empleado no encontrado.";
    exit();
}

$empleado = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre_completo'];
    $correo = $_POST['correo'];
    $usuario = $_POST['usuario'];
    $rol = $_POST['rol'];

    $updateSql = "UPDATE usuarios SET nombre_completo = ?, correo = ?, usuario = ?, rol = ? WHERE id_usuario = ?";
    $stmtUpdate = $conexion->prepare($updateSql);
    $stmtUpdate->bind_param("ssssi", $nombre, $correo, $usuario, $rol, $id);

    if ($stmtUpdate->execute()) {
        header("Location: ver_empleados.php?msg=empleado_actualizado");
        exit();
    } else {
        echo "Error al actualizar: " . $stmtUpdate->error;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <title>Editar Empleado - Luvadak</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <style>
    .form-card {
      max-width: 600px;
      margin: auto;
      margin-top: 50px;
      padding: 30px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
      border-radius: 12px;
      background-color: #fff;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="form-card">
      <h3 class="mb-4 text-center">✏️ Editar Empleado</h3>
      <form method="POST">
        <div class="mb-3">
          <label for="nombre_completo" class="form-label">👤 Nombre completo</label>
          <input type="text" class="form-control" id="nombre_completo" name="nombre_completo"
                 value="<?= htmlspecialchars($empleado['nombre_completo']) ?>" required>
        </div>

        <div class="mb-3">
          <label for="correo" class="form-label">📧 Correo</label>
          <input type="email" class="form-control" id="correo" name="correo"
                 value="<?= htmlspecialchars($empleado['correo']) ?>" required>
        </div>

        <div class="mb-3">
          <label for="usuario" class="form-label">🧾 Usuario</label>
          <input type="text" class="form-control" id="usuario" name="usuario"
                 value="<?= htmlspecialchars($empleado['usuario']) ?>" required>
        </div>

        <div class="mb-3">
          <label for="rol" class="form-label">🔐 Rol</label>
          <select class="form-select" id="rol" name="rol" required>
            <option value="empleado" <?= $empleado['rol'] === 'empleado' ? 'selected' : '' ?>>Empleado</option>
            <option value="admin" <?= $empleado['rol'] === 'admin' ? 'selected' : '' ?>>Administrador</option>
          </select>
        </div>

        <div class="d-flex justify-content-between">
          <a href="ver_empleados.php" class="btn btn-secondary">Cancelar</a>
          <button type="submit" class="btn btn-primary">Guardar cambios</button>
        </div>
      </form>
    </div>
  </div>
</body>
</html>

<?php
$stmt->close();
$conexion->close();
?>
