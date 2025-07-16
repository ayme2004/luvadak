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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nuevaContrasena = $_POST['nueva_contrasena'];
    $confirmarContrasena = $_POST['confirmar_contrasena'];

    if ($nuevaContrasena !== $confirmarContrasena) {
        $error = "❌ Las contraseñas no coinciden.";
    } else {
        $hash = password_hash($nuevaContrasena, PASSWORD_DEFAULT);
        $sql = "UPDATE usuarios SET contrasena = ? WHERE id_usuario = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("si", $hash, $id);

        if ($stmt->execute()) {
            $mensaje = "✅ Contraseña actualizada correctamente.";
        } else {
            $error = "❌ Error al actualizar la contraseña: " . $stmt->error;
        }
        $stmt->close();
    }
}

$sql = "SELECT nombre_completo FROM usuarios WHERE id_usuario = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Usuario no encontrado.";
    exit();
}

$usuario = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cambiar Contraseña - <?= htmlspecialchars($usuario['nombre_completo']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        .form-card {
            max-width: 500px;
            margin: 50px auto;
            padding: 30px;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
<div class="container">
    <div class="form-card">
        <h4 class="mb-4 text-center">🔐 Cambiar contraseña de: <br><strong><?= htmlspecialchars($usuario['nombre_completo']) ?></strong></h4>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <?php if (isset($mensaje)): ?>
            <div class="alert alert-success"><?= htmlspecialchars($mensaje) ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label for="nueva_contrasena" class="form-label">🔑 Nueva Contraseña</label>
                <input type="password" class="form-control" id="nueva_contrasena" name="nueva_contrasena" required>
            </div>
            <div class="mb-3">
                <label for="confirmar_contrasena" class="form-label">🔁 Confirmar Contraseña</label>
                <input type="password" class="form-control" id="confirmar_contrasena" name="confirmar_contrasena" required>
            </div>
            <div class="d-flex justify-content-between">
                <a href="ver_empleados.php" class="btn btn-secondary">⬅️ Cancelar</a>
                <button type="submit" class="btn btn-primary">Guardar Cambios</button>
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
