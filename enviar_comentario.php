<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'empleado') {
    header("Location: login.php");
    exit();
}

include("conexion.php");

$usuario = $_SESSION['usuario'];
$stmt = $conexion->prepare("SELECT id_usuario FROM usuarios WHERE usuario = ?");
$stmt->bind_param("s", $usuario);
$stmt->execute();
$resultado = $stmt->get_result();
$id_usuario = $resultado->fetch_assoc()['id_usuario'] ?? null;
$stmt->close();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mensaje = trim($_POST['mensaje']);

    if (!empty($mensaje)) {
        $insert = $conexion->prepare("INSERT INTO comentarios (id_usuario, mensaje) VALUES (?, ?)");
        $insert->bind_param("is", $id_usuario, $mensaje);

        if ($insert->execute()) {
            $exito = "✅ Comentario enviado correctamente.";
        } else {
            $error = "❌ Error al enviar el comentario.";
        }
        $insert->close();
    } else {
        $error = "⚠️ El mensaje no puede estar vacío.";
    }
}

$comentarios_admin = $conexion->query("
    SELECT mensaje, fecha_envio 
    FROM comentarios 
    WHERE id_usuario = $id_usuario 
    ORDER BY fecha_envio DESC
");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Enviar Comentario - Luvadak</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #f1f3f5;
        }

        .card {
            border: none;
            border-radius: 1rem;
        }

        .btn-primary {
            background-color: #0d6efd;
            border: none;
        }

        .form-control {
            border-radius: 0.75rem;
        }

        textarea {
            resize: none;
        }

        .alert-info {
            background-color: #e8f0fe;
            border-left: 4px solid #0d6efd;
            border-radius: 0.75rem;
        }

        .title-icon {
            font-size: 1.5rem;
            margin-right: 0.5rem;
            color: #0d6efd;
        }

        .text-muted i {
            margin-right: 0.4rem;
        }
    </style>
</head>
<body>
<div class="container mt-5 col-lg-8">
    <div class="card shadow-sm mb-4 p-4">
        <h4 class="mb-3">
            <i class="bi bi-chat-dots-fill title-icon"></i>Enviar comentario al administrador
        </h4>

        <?php if (isset($exito)): ?>
            <div class="alert alert-success"><?= $exito ?></div>
        <?php elseif (isset($error)): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST" novalidate>
            <div class="mb-3">
                <label for="mensaje" class="form-label fw-semibold">Escribe tu mensaje:</label>
                <textarea name="mensaje" id="mensaje" class="form-control" rows="4" placeholder="¿Tienes una sugerencia o necesitas ayuda?" required></textarea>
            </div>
            <div class="d-flex justify-content-between mt-3">
                <a href="dashboard_empleado.php" class="btn btn-secondary rounded-pill px-4">
                    <i class="bi bi-arrow-left"></i> Volver
                </a>
                <button type="submit" class="btn btn-primary rounded-pill px-4">
                    <i class="bi bi-send-fill"></i> Enviar mensaje
                </button>
            </div>
        </form>
    </div>

    <div class="card shadow-sm p-4">
        <h5 class="mb-3">
            <i class="bi bi-envelope-paper-fill title-icon"></i>Mensajes del Administrador
        </h5>
        <?php if ($comentarios_admin->num_rows > 0): ?>
            <?php while ($comentario = $comentarios_admin->fetch_assoc()): ?>
                <div class="alert alert-info mb-3">
                    <div class="small text-secondary mb-1">
                        <i class="bi bi-clock"></i> <?= date("d/m/Y H:i", strtotime($comentario['fecha_envio'])) ?>
                    </div>
                    <div><?= nl2br(htmlspecialchars($comentario['mensaje'])) ?></div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p class="text-muted"><i class="bi bi-bell-slash-fill"></i> Aún no tienes mensajes del administrador.</p>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
