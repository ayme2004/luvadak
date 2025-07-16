<?php
include("conexion.php");

$empleados = $conexion->query("SELECT id_usuario, nombre_completo FROM usuarios WHERE rol = 'empleado'");
$lista_empleados = [];
while ($row = $empleados->fetch_assoc()) {
    $lista_empleados[] = $row;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Reporte por Empleado - Luvadak</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      background-color: #f4f6f8;
    }
    .card {
      max-width: 900px;
      margin: 50px auto;
      padding: 30px;
      border-radius: 16px;
      box-shadow: 0 6px 14px rgba(0,0,0,0.08);
    }
    .btn-primary {
      background-color: #6c63ff;
      border: none;
    }
    .btn-primary:hover {
      background-color: #574dd4;
    }
    .btn-pdf {
      background-color: #dc3545;
      color: white;
    }
    .btn-pdf:hover {
      background-color: #b02a37;
    }
    .form-control:focus {
      box-shadow: 0 0 0 0.2rem rgba(108, 99, 255, 0.25);
    }
  </style>
  <script>
    function filtrarEmpleados() {
      const input = document.getElementById("filtro");
      const filtro = input.value.toLowerCase();
      const filas = document.querySelectorAll(".fila-empleado");

      filas.forEach(fila => {
        const nombre = fila.querySelector("td").textContent.toLowerCase();
        fila.style.display = nombre.includes(filtro) ? "" : "none";
      });
    }
  </script>
</head>
<body>
  <div class="container">
    <div class="card bg-white">
      <h3 class="mb-4 text-center"><i class="bi bi-person-lines-fill me-2"></i>Reporte por Empleado</h3>

      <div class="mb-4">
        <input type="text" id="filtro" class="form-control" placeholder="🔍 Buscar empleado por nombre..." onkeyup="filtrarEmpleados()">
      </div>

      <?php if (count($lista_empleados) > 0): ?>
        <div class="table-responsive">
          <table class="table table-bordered table-hover text-center align-middle">
            <thead class="table-dark">
              <tr>
                <th><i class="bi bi-person-circle"></i> Nombre del Empleado</th>
                <th><i class="bi bi-gear"></i> Acciones</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($lista_empleados as $emp): ?>
              <tr class="fila-empleado">
                <td><?= htmlspecialchars($emp['nombre_completo']) ?></td>
                <td>
                  <a href="reporte_por_empleado.php?id_empleado=<?= $emp['id_usuario'] ?>" class="btn btn-sm btn-primary me-2">
                    <i class="bi bi-bar-chart-line-fill"></i> Ver
                  </a>
                  <a href="reporte_empleado_pdf.php?id_empleado=<?= $emp['id_usuario'] ?>" class="btn btn-sm btn-pdf" target="_blank">
                    <i class="bi bi-file-earmark-pdf-fill"></i> PDF
                  </a>
                </td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php else: ?>
        <div class="alert alert-warning text-center mt-3">⚠️ No hay empleados registrados en el sistema.</div>
      <?php endif; ?>

      <div class="text-center mt-4">
        <a href="dashboard_admin.php" class="btn btn-secondary">
          <i class="bi bi-arrow-left-circle"></i> Volver al Panel
        </a>
      </div>
    </div>
  </div>
</body>
</html>
