<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Login Administrador - Luvadak</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body {
  background: linear-gradient(135deg, #e2e8f0, #f8fafc);
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 100vh;
  font-family: 'Segoe UI', sans-serif;
}
.login-card {
  background-color: #fff;
  padding: 40px 30px;
  border-radius: 16px;
  box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
  width: 100%;
  max-width: 400px;
  animation: fadeIn 0.6s ease;
  text-align: center;
}
.login-logo img {
  width: 80px;
  margin-bottom: 15px;
}
.login-title {
  font-weight: 700;
  font-size: 1.4rem;
  color: #1f2937;
  margin-bottom: 20px;
}
.form-label {
  font-weight: 600;
  color: #374151;
}
.btn-custom {
  background-color: #0d6efd;
  color: #fff;
  font-weight: bold;
  transition: background-color 0.3s ease;
}
.btn-custom:hover {
  background-color: #0b5ed7;
}
@keyframes fadeIn {
  from { opacity: 0; transform: translateY(-10px); }
  to { opacity: 1; transform: translateY(0); }
}
</style>
</head>
<body>

<div class="login-card">
  <div class="login-logo">
    <img src="logo/logo.jpg" alt="Logo Luvadak">
  </div>
  <h2 class="login-title">Iniciar Sesión <br><span style="color:#0d6efd">Administrador</span></h2>

  <form action="procesar_login.php" method="POST">
    <div class="mb-3 text-start">
      <label for="usuario" class="form-label">👤 Usuario</label>
      <input class="form-control" type="text" id="usuario" name="usuario" placeholder="Ingrese su usuario" required>
    </div>

    <div class="mb-4 text-start">
      <label for="contrasena" class="form-label">🔒 Contraseña</label>
      <input class="form-control" type="password" id="contrasena" name="contrasena" placeholder="Ingrese su contraseña" required>
    </div>

    <button class="btn btn-custom w-100" type="submit">➡️ Ingresar</button>
  </form>
</div>

</body>
</html>
