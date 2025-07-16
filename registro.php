<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Registro - Luvadak</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f1f3f5;
    }
    .registro-card {
      border-radius: 16px;
      border-left: 6px solid #0d6efd;
    }
    .form-title {
      font-weight: bold;
      color: #0d6efd;
    }
    .form-control:focus, .form-select:focus {
      box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
      border-color: #0d6efd;
    }
  </style>
</head>
<body>
  <div class="container d-flex justify-content-center align-items-center min-vh-100">
    <form action="procesar_registro.php" method="POST" class="card p-4 shadow registro-card col-md-6">
      <h2 class="mb-3 form-title text-center">📝 Registro de Usuario</h2>

      <div class="mb-3">
        <label class="form-label">Nombre completo</label>
        <input type="text" class="form-control" name="nombre_completo" placeholder="Ej: manuel vidal" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Correo electrónico</label>
        <input type="email" class="form-control" name="correo" placeholder="Ej: ejemplo@correo.com" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Nombre de usuario</label>
        <input type="text" class="form-control" name="usuario" placeholder="Ej: manuel2004" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Contraseña</label>
        <input type="password" class="form-control" name="contrasena" placeholder="Ingresa una contraseña segura" required>
      </div>

      <div class="mb-4">
        <label class="form-label">Rol de usuario</label>
        <select name="rol" class="form-select" required>
          <option value="">Selecciona un rol</option>
          <option value="empleado">Empleado</option>
          <option value="admin">Administrador</option>
        </select>
      </div>

      <button type="submit" class="btn btn-primary w-100">✅ Registrarse</button>
    </form>
  </div>
</body>
</html>
