<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
  header("Location: login.php");
  exit();
}

include("conexion.php");

if (isset($_POST['registrar'])) {
  $nombre = trim($_POST['nombre_completo']);
  $dni = trim($_POST['dni']);
  $telefono = trim($_POST['telefono']);
  $correo = trim($_POST['correo']);
  $direccion = trim($_POST['direccion']);

  $stmt = $conexion->prepare("INSERT INTO clientes (nombre_completo, dni, telefono, correo, direccion) VALUES (?, ?, ?, ?, ?)");
  $stmt->bind_param("sssss", $nombre, $dni, $telefono, $correo, $direccion);
  if ($stmt->execute()) {
    echo "<script>alert('✅ Cliente registrado correctamente'); window.location='clientes.php';</script>";
  } else {
    echo "<script>alert('❌ Error al registrar cliente');</script>";
  }
}

$busqueda = isset($_GET['buscar']) ? trim($_GET['buscar']) : "";
$sql = "SELECT * FROM clientes";
if (!empty($busqueda)) {
  $sql .= " WHERE nombre_completo LIKE '%" . $conexion->real_escape_string($busqueda) . "%'";
}
$sql .= " ORDER BY fecha_registro DESC";
$clientes = $conexion->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Gestión de Clientes - Luvadak</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background-color: #f7f9fc; }
    .card-container {
      background-color: #fff;
      padding: 30px;
      border-radius: 16px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.08);
      margin-top: 40px;
    }
    .btn-whatsapp {
      background-color: #25D366;
      color: white;
    }
    .btn-whatsapp:hover {
      background-color: #1EBE54;
      color: white;
    }
  </style>
</head>
<body>
<div class="container">
  <div class="card-container">
    <h3 class="mb-4 text-center">👤 Gestión de Clientes</h3>

    <div class="mb-4 text-end">
      <a href="dashboard_admin.php" class="btn btn-secondary">← Volver al Panel</a>
    </div>

    <form method="GET" class="d-flex mb-4">
      <input type="text" name="buscar" class="form-control me-2" placeholder="Buscar por nombre..." value="<?= htmlspecialchars($busqueda); ?>">
      <button type="submit" class="btn btn-primary">Buscar</button>
    </form>

    <div class="table-responsive">
      <table class="table table-bordered table-hover text-center align-middle">
        <thead class="table-dark">
          <tr>
            <th>👤 Nombre</th>
            <th>DNI</th>
            <th>📱 Teléfono</th>
            <th>📧 Correo</th>
            <th>📍 Dirección</th>
            <th>📆 Registro</th>
            <th>⚙️ Opciones</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($clientes->num_rows === 0): ?>
            <tr><td colspan="7" class="text-muted">🚫 No se encontraron clientes.</td></tr>
          <?php else: ?>
            <?php while ($fila = $clientes->fetch_assoc()) { ?>
              <tr>
                <td><?= htmlspecialchars($fila['nombre_completo']); ?></td>
                <td><?= $fila['dni']; ?></td>
                <td><?= $fila['telefono']; ?></td>
                <td><?= $fila['correo']; ?></td>
                <td><?= $fila['direccion']; ?></td>
                <td><?= date("d/m/Y", strtotime($fila['fecha_registro'])); ?></td>
                <td>
                  <div class="d-flex flex-wrap gap-1 justify-content-center">
                    <a href="historial_cliente.php?id=<?= $fila['id_cliente'] ?>" class="btn btn-sm btn-info text-white">📑 Historial</a>
                    <a href="exportar_pdf_cliente.php?id=<?= $fila['id_cliente'] ?>" class="btn btn-sm btn-danger">📄 PDF</a>
                    <?php if (!empty($fila['telefono'])): ?>
                      <a target="_blank" href="https://wa.me/51<?= preg_replace('/\D/', '', $fila['telefono']) ?>" class="btn btn-sm btn-whatsapp">💬 WhatsApp</a>
                    <?php endif; ?>
                  </div>
                </td>
              </tr>
            <?php } ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>

    <hr class="mt-5">
    <h4 class="text-center mb-4">📝 Registrar Nuevo Cliente</h4>
    <form method="POST" class="row g-3">
      <div class="col-md-6">
        <label class="form-label">Nombre completo</label>
        <input type="text" name="nombre_completo" class="form-control" required>
      </div>
      <div class="col-md-6">
        <label class="form-label">DNI</label>
        <input type="text" name="dni" class="form-control" required>
      </div>
      <div class="col-md-6">
        <label class="form-label">Teléfono</label>
        <input type="text" name="telefono" class="form-control">
      </div>
      <div class="col-md-6">
        <label class="form-label">Correo</label>
        <input type="email" name="correo" class="form-control">
      </div>
      <div class="col-12">
        <label class="form-label">Dirección</label>
        <input type="text" name="direccion" class="form-control">
      </div>
      <div class="col-12 text-center">
        <button type="submit" name="registrar" class="btn btn-success px-5">✅ Registrar Cliente</button>
      </div>
    </form>
  </div>
</div>
</body>
</html>
