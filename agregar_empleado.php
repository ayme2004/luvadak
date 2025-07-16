<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <title>Agregar Empleado - Luvadak</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <style>
    .form-card {
      max-width: 600px;
      margin: 50px auto;
      background-color: #fff;
      padding: 35px;
      border-radius: 16px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
  </style>
</head>
<body>
<div class="container">
  <div class="form-card">
    <h3 class="text-center mb-4">➕ Agregar Nuevo Empleado</h3>
    <form action="procesar_agregar_empleado.php" method="POST">
      <div class="mb-3">
        <label for="nombre_completo" class="form-label">👤 Nombre completo</label>
        <input type="text" class="form-control" id="nombre_completo" name="nombre_completo" required>
      </div>

      <div class="mb-3">
        <label for="correo" class="form-label">📧 Correo</label>
        <input type="email" class="form-control" id="correo" name="correo" required>
      </div>

      <div class="mb-3">
        <label for="usuario" class="form-label">🧾 Usuario</label>
        <input type="text" class="form-control" id="usuario" name="usuario" required>
      </div>

      <div class="mb-3">
        <label for="contrasena" class="form-label">🔒 Contraseña</label>
        <input type="password" class="form-control" id="contrasena" name="contrasena" required>
      </div>

      <div class="mb-4">
        <label for="rol" class="form-label">⚙️ Rol</label>
        <select class="form-select" id="rol" name="rol" required>
          <option value="empleado">Empleado</option>
          <option value="admin">Administrador</option>
        </select>
      </div>

      <div class="d-flex justify-content-between">
        <a href="ver_empleados.php" class="btn btn-secondary">⬅️ Cancelar</a>
        <a href="dashboard_admin.php" class="btn btn-secondary">⬅️ Volver al Panel</a>
        <button type="submit" class="btn btn-success">💾 Guardar</button>
      </div>
    </form>
  </div>
</div>
</body>
</html>
