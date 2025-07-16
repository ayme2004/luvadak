<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    header("Location: login.php");
    exit();
}

include("conexion.php");

$id_producto = $_GET['id'];
$sql = "SELECT * FROM productos WHERE id_producto = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id_producto);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows === 0) {
    echo "Producto no encontrado.";
    exit();
}

$producto = $resultado->fetch_assoc();

$categorias = $conexion->query("SELECT id_categoria, nombre_categoria FROM categorias");
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Editar Producto</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <div class="container mt-5 col-md-6">
    <form action="actualizar_producto.php" method="POST" class="card p-4 shadow">
      <h3 class="mb-3">Editar Producto</h3>

      <input type="hidden" name="id_producto" value="<?php echo $producto['id_producto']; ?>">

      <input type="text" name="nombre_producto" class="form-control mb-2" value="<?php echo $producto['nombre_producto']; ?>" required>
      <textarea name="descripcion" class="form-control mb-2"><?php echo $producto['descripcion']; ?></textarea>

      <select name="talla" class="form-select mb-2" required>
        <option value="">Selecciona una talla</option>
        <?php
        $tallas = ['XS','S','M','L','XL'];
        foreach ($tallas as $talla) {
            $selected = ($producto['talla'] === $talla) ? "selected" : "";
            echo "<option value='$talla' $selected>$talla</option>";
        }
        ?>
      </select>

      <input type="text" name="color" class="form-control mb-2" value="<?php echo $producto['color']; ?>" required>
      <input type="number" step="0.01" name="precio" class="form-control mb-2" value="<?php echo $producto['precio']; ?>" required>
      <input type="number" name="stock" class="form-control mb-2" value="<?php echo $producto['stock']; ?>" required>

      <select name="id_categoria" class="form-select mb-3" required>
        <option value="">Selecciona una categoría</option>
        <?php while ($cat = $categorias->fetch_assoc()) {
          $selected = ($producto['id_categoria'] == $cat['id_categoria']) ? "selected" : "";
          echo "<option value='{$cat['id_categoria']}' $selected>{$cat['nombre_categoria']}</option>";
        } ?>
      </select>

      <button type="submit" class="btn btn-success w-100">Guardar Cambios</button>
    </form>
  </div>
</body>
</html>
