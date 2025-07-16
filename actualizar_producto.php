<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    header("Location: login.php");
    exit();
}

include("conexion.php");

$id = $_POST['id_producto'];
$nombre = $_POST['nombre_producto'];
$descripcion = $_POST['descripcion'];
$talla = $_POST['talla'];
$color = $_POST['color'];
$precio = $_POST['precio'];
$stock = $_POST['stock'];
$id_categoria = $_POST['id_categoria'];

$stmt = $conexion->prepare("UPDATE productos SET nombre_producto=?, descripcion=?, talla=?, color=?, precio=?, stock=?, id_categoria=? WHERE id_producto=?");
$stmt->bind_param("ssssdiii", $nombre, $descripcion, $talla, $color, $precio, $stock, $id_categoria, $id);

if ($stmt->execute()) {
    echo "<script>alert('Producto actualizado correctamente'); window.location.href='ver_productos.php';</script>";
} else {
    echo "Error al actualizar: " . $conexion->error;
}

$stmt->close();
?>
