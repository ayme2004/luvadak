<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    header("Location: login.php");
    exit();
}

include("conexion.php");

$id = $_GET['id'];

$verificar = $conexion->prepare("SELECT * FROM productos WHERE id_producto = ?");
$verificar->bind_param("i", $id);
$verificar->execute();
$resultado = $verificar->get_result();

if ($resultado->num_rows === 0) {
    echo "<script>alert('❌ Producto no encontrado'); window.location.href='ver_productos.php';</script>";
    exit();
}

$movs = $conexion->prepare("SELECT COUNT(*) FROM movimientosinventario WHERE id_producto = ?");
$movs->bind_param("i", $id);
$movs->execute();
$movs->bind_result($totalMovs);
$movs->fetch();
$movs->close();

if ($totalMovs > 0) {
    echo "<script>alert('❌ No se puede eliminar este producto porque tiene movimientos registrados.'); window.location.href='ver_productos.php';</script>";
    exit();
}

$eliminar = $conexion->prepare("DELETE FROM productos WHERE id_producto = ?");
$eliminar->bind_param("i", $id);

if ($eliminar->execute()) {
    echo "<script>alert('✅ Producto eliminado correctamente'); window.location.href='ver_productos.php';</script>";
} else {
    echo "<script>alert('❌ Error al eliminar el producto'); window.location.href='ver_productos.php';</script>";
}

$eliminar->close();
?>
    