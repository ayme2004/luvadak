<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    header("Location: login.php");
    exit();
}

include("conexion.php");

if (isset($_GET['eliminar'])) {
    $idEliminar = intval($_GET['eliminar']);
    $stmt = $conexion->prepare("DELETE FROM comentarios WHERE id_comentario = ?");
    $stmt->bind_param("i", $idEliminar);
    $stmt->execute();
    $stmt->close();
    header("Location: ver_comentarios.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['mensaje'])) {
    $mensaje = trim($_POST['mensaje']);
    $id_destino = $_POST['id_usuario'];

    if (!empty($mensaje)) {
        if ($id_destino === "todos") {
            $usuarios = $conexion->query("SELECT id_usuario FROM usuarios WHERE rol = 'empleado'");
            while ($u = $usuarios->fetch_assoc()) {
                $stmt = $conexion->prepare("INSERT INTO comentarios (id_usuario, mensaje) VALUES (?, ?)");
                $stmt->bind_param("is", $u['id_usuario'], $mensaje);
                $stmt->execute();
                $stmt->close();
            }
            $mensaje_enviado = "Mensaje enviado a todos los empleados.";
        } else {
            $stmt = $conexion->prepare("INSERT INTO comentarios (id_usuario, mensaje) VALUES (?, ?)");
            $stmt->bind_param("is", $id_destino, $mensaje);
            if ($stmt->execute()) {
                $mensaje_enviado = "Mensaje enviado correctamente.";
            } else {
                $error_envio = "Error al enviar mensaje.";
            }
            $stmt->close();
        }
    } else {
        $error_envio = "El mensaje no puede estar vacío.";
    }
}

$sql = "SELECT c.id_comentario, c.mensaje, c.fecha_envio, u.nombre_completo 
        FROM comentarios c 
        JOIN usuarios u ON c.id_usuario = u.id_usuario 
        ORDER BY c.fecha_envio DESC";
$resultado = $conexion->query($sql);

$empleados = $conexion->query("SELECT id_usuario, nombre_completo FROM usuarios WHERE rol = 'empleado'");
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>💬 Comentarios de Empleados</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet" />
  <style>
    body {
      background-color: #f5f7fa;
    }
    .card-style {
      background-color: #fff;
      border-radius: 16px;
      padding: 30px;
      box-shadow: 0 6px 16px rgba(0,0,0,0.08);
      margin-top: 40px;
    }
  </style>
</head>
<body>
<div class="container">
  <div class="card-style">
    <h3 class="mb-4 text-center"><i class="bi bi-chat-dots-fill me-2"></i>Comentarios de Empleados</h3>

    <?php if (isset($mensaje_enviado)): ?>
      <div class="alert alert-success"><?= $mensaje_enviado ?></div>
    <?php elseif (isset($error_envio)): ?>
      <div class="alert alert-danger"><?= $error_envio ?></div>
    <?php endif; ?>

    <form method="POST" class="card p-3 shadow-sm mb-4">
      <h5 class="mb-3">✉️ Enviar nuevo mensaje</h5>
      <div class="row g-2">
        <div class="col-md-4">
          <select name="id_usuario" class="form-select" required>
            <option value="todos">📢 Todos los empleados</option>
            <?php while ($e = $empleados->fetch_assoc()): ?>
              <option value="<?= $e['id_usuario'] ?>"><?= $e['nombre_completo'] ?></option>
            <?php endwhile; ?>
          </select>
        </div>
        <div class="col-md-6">
          <textarea name="mensaje" class="form-control" rows="1" placeholder="Escribe tu mensaje..." required></textarea>
        </div>
        <div class="col-md-2 d-grid">
          <button class="btn btn-primary"><i class="bi bi-send-fill me-1"></i>Enviar</button>
        </div>
      </div>
    </form>

    <?php if ($resultado->num_rows > 0): ?>
      <div class="list-group">
        <?php while ($row = $resultado->fetch_assoc()): ?>
          <div class="list-group-item d-flex justify-content-between align-items-start">
            <div>
              <h6 class="fw-bold mb-1"><?= htmlspecialchars($row['nombre_completo']) ?></h6>
              <p class="mb-1"><?= htmlspecialchars($row['mensaje']) ?></p>
              <small class="text-muted">📅 <?= date("d/m/Y H:i", strtotime($row['fecha_envio'])) ?></small>
            </div>
            <div>
              <a href="?eliminar=<?= $row['id_comentario'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar este comentario?');" title="Eliminar"><i class="bi bi-trash-fill"></i></a>
            </div>
          </div>
        <?php endwhile; ?>
      </div>
    <?php else: ?>
      <div class="alert alert-info mt-3 text-center">No hay comentarios registrados aún.</div>
    <?php endif; ?>

    <div class="text-end mt-4">
      <a href="dashboard_admin.php" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Volver al Panel</a>
    </div>
  </div>
</div>
</body>
</html>
