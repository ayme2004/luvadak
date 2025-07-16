<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'admin') {
    header("Location: login.php");
    exit();
}

include("conexion.php");

$comentarios_no_leidos = $conexion->query("SELECT COUNT(*) FROM comentarios WHERE visto = 0")->fetch_row()[0];
$ventas_hoy = $conexion->query("SELECT COUNT(*) FROM ventas WHERE DATE(fecha) = CURDATE()")->fetch_row()[0];
$total_empleados = $conexion->query("SELECT COUNT(*) FROM usuarios WHERE rol='empleado'")->fetch_row()[0];
$total_clientes = $conexion->query("SELECT COUNT(*) FROM clientes")->fetch_row()[0];

$ventasPorDia = [];
$labels = [];
for ($i = 6; $i >= 0; $i--) {
    $fecha = date('Y-m-d', strtotime("-$i days"));
    $dia = date('D', strtotime("-$i days"));
    switch ($dia) {
        case 'Mon': $labels[] = 'Lun'; break;
        case 'Tue': $labels[] = 'Mar'; break;
        case 'Wed': $labels[] = 'Mié'; break;
        case 'Thu': $labels[] = 'Jue'; break;
        case 'Fri': $labels[] = 'Vie'; break;
        case 'Sat': $labels[] = 'Sáb'; break;
        case 'Sun': $labels[] = 'Dom'; break;
    }
    $sql = $conexion->query("SELECT IFNULL(SUM(total),0) FROM ventas WHERE DATE(fecha)='$fecha'");
    $ventasPorDia[] = $sql->fetch_row()[0];
}

$usuario = htmlspecialchars($_SESSION['usuario']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Panel de Administrador - Luvadak</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
body {
  font-family: 'Segoe UI', sans-serif;
  background-color: #f4f6f9;
}
.sidebar {
  height: 100vh;
  background-color: #1f1f2e;
  color: #fff;
  padding: 20px;
  position: fixed;
  width: 240px;
  overflow-y: auto;
}
.sidebar h2 {
  font-size: 1.4rem;
  margin-bottom: 20px;
  font-weight: bold;
}
.sidebar .nav-link {
  color: #bbb;
  margin-bottom: 12px;
  transition: all 0.3s ease;
  font-size: 1rem;
  border-radius: 6px;
  padding: 8px 12px;
}
.sidebar .nav-link:hover {
  color: #fff;
  background-color: #34344a;
}
.sidebar .logout {
  background-color: #ffe8e8;
  color: #b20000 !important;
}
.content {
  margin-left: 260px;
  padding: 30px 20px;
}
.stats-box {
  background: #fff;
  border-radius: 12px;
  padding: 20px;
  text-align: center;
  box-shadow: 0 3px 8px rgba(0,0,0,0.1);
  transition: transform 0.2s;
}
.stats-box:hover {
  transform: translateY(-4px);
}
@media(max-width: 768px) {
  .sidebar {
    position: relative;
    width: 100%;
    height: auto;
  }
  .content {
    margin-left: 0;
  }
}
</style>
</head>
<body>

<div class="sidebar">
  <h2>👔 Luvadak</h2>
  <p>Hola, <strong><?php echo $usuario; ?></strong> (Admin)</p>
  <nav class="nav flex-column">
    <a class="nav-link" href="dashboard_admin.php"><i class="fas fa-home"></i> Dashboard</a>
    <a class="nav-link" href="agregar_producto.php"><i class="fas fa-plus"></i> Agregar Producto</a>
    <a class="nav-link" href="ver_productos.php"><i class="fas fa-box"></i> Ver Productos</a>
    <a class="nav-link" href="registrar_movimiento.php"><i class="fas fa-sync"></i> Entrada/Salida</a>
    <a class="nav-link" href="ver_movimientos.php"><i class="fas fa-chart-line"></i> Movimientos Inventario</a>
    <a class="nav-link" href="registrar_produccion_tela.php"><i class="fas fa-cut"></i> Producción Telas</a>
    <a class="nav-link" href="ver_produccion.php"><i class="fas fa-clipboard"></i> Historial Producción</a>
    <a class="nav-link" href="registrar_compra_tela.php"><i class="fas fa-receipt"></i> Compra Tela</a>
    <a class="nav-link" href="ver_compras_telas.php"><i class="fas fa-book"></i> Compras Telas</a>
    <a class="nav-link" href="ficha_telas.php"><i class="fas fa-clipboard-list"></i> Ficha Telas</a>
    <a class="nav-link" href="ver_empleados.php"><i class="fas fa-users"></i> Empleados</a>
    <a class="nav-link" href="ver_comentarios.php">
      <i class="fas fa-comments"></i> Comentarios
      <?php if($comentarios_no_leidos>0): ?>
        <span class="badge bg-danger"><?php echo $comentarios_no_leidos; ?></span>
      <?php endif; ?>
    </a>
    <a class="nav-link" href="registrar_pago.php"><i class="fas fa-money-bill"></i> Registrar Pago</a>
    <a class="nav-link" href="ver_pagos.php"><i class="fas fa-file-invoice-dollar"></i> Pagos</a>
    <a class="nav-link" href="buscar_empleado_reporte.php"><i class="fas fa-clipboard"></i> Reporte Empleado</a>
    <a class="nav-link" href="clientes.php"><i class="fas fa-address-book"></i> Clientes</a>
    <a class="nav-link" href="reportes_admin.php"><i class="fas fa-chart-pie"></i> Reportes Mes</a>
    <a class="nav-link" href="reporte_dia.php"><i class="fas fa-calendar-day"></i> Ventas Día</a>
    <a class="nav-link logout" href="logout.php"><i class="fas fa-sign-out-alt"></i> Cerrar Sesión</a>
  </nav>
</div>

<div class="content">
  <h3>Panel de Administración</h3>
  <p>Bienvenido al panel de control donde puedes gestionar productos, empleados, clientes y ver reportes.</p>

  <div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
      <div class="stats-box">
        <i class="fas fa-cash-register fa-2x mb-2"></i>
        <h6>Ventas Hoy</h6>
        <p><strong><?php echo $ventas_hoy; ?></strong></p>
      </div>
    </div>
    <div class="col-6 col-md-3">
      <div class="stats-box">
        <i class="fas fa-comments fa-2x mb-2"></i>
        <h6>Comentarios</h6>
        <p><strong><?php echo $comentarios_no_leidos; ?></strong></p>
      </div>
    </div>
    <div class="col-6 col-md-3">
      <div class="stats-box">
        <i class="fas fa-users fa-2x mb-2"></i>
        <h6>Empleados</h6>
        <p><strong><?php echo $total_empleados; ?></strong></p>
      </div>
    </div>
    <div class="col-6 col-md-3">
      <div class="stats-box">
        <i class="fas fa-user-friends fa-2x mb-2"></i>
        <h6>Clientes</h6>
        <p><strong><?php echo $total_clientes; ?></strong></p>
      </div>
    </div>
  </div>

  <div class="card p-3">
    <h5>Ventas últimos 7 días</h5>
    <canvas id="ventasChart" height="100"></canvas>
  </div>
</div>

<script>
const ventasData = <?php echo json_encode($ventasPorDia); ?>;
const etiquetas = <?php echo json_encode($labels); ?>;

const ctx = document.getElementById('ventasChart').getContext('2d');
new Chart(ctx, {
  type: 'bar',
  data: {
    labels: etiquetas,
    datasets: [{
      label: 'Ventas',
      data: ventasData,
      backgroundColor: 'rgba(75, 192, 192, 0.7)'
    }]
  },
  options: {
    responsive: true,
    scales: { y: { beginAtZero: true } }
  }
});
</script>
</body>
</html>
