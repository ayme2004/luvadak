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

$sql = "DELETE FROM usuarios WHERE id_usuario = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    header("Location: ver_empleados.php?msg=empleado_eliminado");
    exit();
} else {
    echo "Error al eliminar empleado: " . $stmt->error;
}

$stmt->close();
$conexion->close();
?>
