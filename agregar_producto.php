<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
  header("Location: login.php");
  exit();
}
include("conexion.php");
$categorias = $conexion->query("SELECT id_categoria, nombre_categoria FROM categorias");
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Agregar Producto - Luvadak</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      background: #f4f6f8;
      font-family: 'Segoe UI', sans-serif;
    }

    .form-card {
      background-color: #fff;
      border-radius: 1rem;
      padding: 30px;
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.06);
      width: 100%;
      max-width: 900px;
    }

    h3 {
      color: #0d6efd;
      font-weight: 600;
    }

    .form-label i {
      margin-right: 6px;
      color: #0d6efd;
    }

    .form-control,
    .form-select {
      border-radius: 0.5rem;
      font-size: 14px;
    }

    .btn {
      border-radius: 2rem;
      font-size: 14px;
    }

    #preview {
      max-width: 100%;
      max-height: 180px;
      border-radius: 8px;
      margin-top: 10px;
      display: none;
      border: 1px solid #dee2e6;
      padding: 4px;
    }

    .input-group .form-control-color {
      height: 38px;
    }
  </style>
</head>
<body>
  <div class="container d-flex justify-content-center align-items-center min-vh-100">
    <div class="form-card shadow">
      <h3 class="text-center mb-4"><i class="bi bi-bag-plus-fill"></i> Agregar Producto</h3>

      <form action="procesar_producto.php" method="POST" enctype="multipart/form-data">
        <div class="row g-4">

          <div class="col-md-6">
            <label class="form-label"><i class="bi bi-tag"></i>Nombre</label>
            <input type="text" name="nombre_producto" class="form-control" maxlength="120" placeholder="Ej: Polera oversize" required>

            <label class="form-label mt-3"><i class="bi bi-textarea-t"></i>Descripción</label>
            <textarea name="descripcion" class="form-control" rows="4" maxlength="255" placeholder="Detalles, composición, etc."></textarea>

            <label class="form-label mt-3"><i class="bi bi-card-list"></i>Talla</label>
            <select name="talla" class="form-select" required>
              <option hidden value="">Selecciona talla</option>
              <?php foreach (["XS","S","M","L","XL"] as $talla): ?>
                <option value="<?= $talla ?>"><?= $talla ?></option>
              <?php endforeach; ?>
            </select>

            <label class="form-label mt-3"><i class="bi bi-palette"></i>Color</label>
            <div class="input-group">
              <input type="color" value="#000000" class="form-control form-control-color" style="max-width:55px" title="Elige un color">
              <input type="text" name="color" class="form-control" placeholder="Nombre o código de color" required>
            </div>
          </div>

          <div class="col-md-6">
            <label class="form-label"><i class="bi bi-cash-coin"></i>Precio (S/)</label>
            <input type="number" name="precio" step="0.01" min="0" class="form-control" placeholder="Ej: 49.90" required>

            <label class="form-label mt-3"><i class="bi bi-stack"></i>Stock inicial</label>
            <input type="number" name="stock" min="0" class="form-control" placeholder="Ej: 15" required>

            <label class="form-label mt-3"><i class="bi bi-tags"></i>Categoría</label>
            <select name="id_categoria" class="form-select" required>
              <option hidden value="">Selecciona categoría</option>
              <?php while ($cat = $categorias->fetch_assoc()): ?>
                <option value="<?= $cat['id_categoria'] ?>"><?= $cat['nombre_categoria'] ?></option>
              <?php endwhile; ?>
            </select>

            <label class="form-label mt-3"><i class="bi bi-image"></i>Imagen</label>
            <input type="file" name="imagen" class="form-control" accept="image/*" id="fileInput">
            <img id="preview" alt="Vista previa">
          </div>
        </div>

        <div class="d-flex justify-content-between mt-4">
          <button type="reset" class="btn btn-outline-secondary">
            <i class="bi bi-x-circle"></i> Cancelar
          </button>
          <button type="submit" class="btn btn-primary">
            <i class="bi bi-check-circle"></i> Registrar Producto
          </button>
          <a href="dashboard_admin.php" class="btn btn-secondary">
            <i class="bi bi-arrow-left-circle"></i> Volver al Panel
          </a>
        </div>
      </form>
    </div>
  </div>

  <script>
    const fileInput = document.getElementById('fileInput');
    const preview = document.getElementById('preview');
    fileInput.addEventListener('change', e => {
      const file = e.target.files[0];
      if (file) {
        preview.src = URL.createObjectURL(file);
        preview.style.display = 'block';
      } else {
        preview.src = '';
        preview.style.display = 'none';
      }
    });
  </script>
</body>
</html>
