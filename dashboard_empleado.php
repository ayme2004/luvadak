<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] !== 'empleado') {
    header("Location: login.php");
    exit();
}

$usuario = htmlspecialchars($_SESSION['usuario']);

$opciones = [
    ["ver_productos.php", "📦", "Ver Productos"],
    ["punto_venta.php", "🛒", "Punto de Venta"],
    ["ver_boletas.php", "📄", "Historial de Ventas"],
    ["reporte_mis_ventas_pdf.php", "📊", "Reporte Personal"],
    ["historial_clientes_empleado.php", "📁", "Historial del Cliente"],
    ["enviar_comentario.php", "💬", "Enviar Comentario"],
    ["logout.php", "🚪", "Cerrar Sesión", "logout"]
];
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Panel Empleado - Luvadak</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #eef2f7;
    }
    .sidebar {
      min-height: 100vh;
      background-color: #1f1f2e;
      color: #fff;
      padding: 20px 15px;
      position: fixed;
      width: 240px;
    }
    .sidebar h2 {
      font-size: 1.5rem;
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
    .sidebar .nav-link.active {
      background-color: #4a4a8a;
      color: #fff;
      font-weight: bold;
      border-left: 4px solid #fff;
    }
    .sidebar .logout {
      background-color: #ffe8e8;
      color: #b20000 !important;
    }
    .content {
      margin-left: 260px;
      padding: 30px 20px;
    }
.banner {
  height: 450px;
  overflow: hidden;
  border-radius: 12px;
  margin-bottom: 20px;
}

.banner img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  border-radius: 12px;
}

    .alert-primary {
      background: linear-gradient(to right, #dbeafe, #bfdbfe);
      color: #1e3a8a;
    }
    .info-box {
      background: #fff;
      border-radius: 12px;
      padding: 30px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.05);
      margin-bottom: 20px;
    }
    .info-box h4 {
      color: #4a4a8a;
      margin-bottom: 15px;
    }
    .info-box p {
      color: #555;
      line-height: 1.6;
    }
    .cards-section {
      display: flex;
      gap: 20px;
      flex-wrap: wrap;
    }
    .card-info {
      background: #fff;
      flex: 1 1 250px;
      border-radius: 12px;
      padding: 20px;
      text-align: center;
      box-shadow: 0 3px 8px rgba(0,0,0,0.05);
      transition: transform 0.2s, box-shadow 0.2s;
    }
    .card-info:hover {
      transform: translateY(-4px);
      box-shadow: 0 6px 16px rgba(0,0,0,0.1);
    }
    .card-info img {
      width: 60px;
      margin-bottom: 10px;
    }
    .card-info h5 {
      color: #333;
      margin-bottom: 10px;
    }
    @media(max-width: 768px) {
      .sidebar {
        position: relative;
        width: 100%;
        min-height: auto;
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
  <div class="mb-4">Hola, <strong><?php echo $usuario; ?></strong></div>
  <nav class="nav flex-column">
    <?php
    $paginaActual = basename($_SERVER['PHP_SELF']);
    foreach ($opciones as $opcion) {
      $archivo = $opcion[0];
      $texto = $opcion[2];
      $claseExtra = isset($opcion[3]) ? $opcion[3] : '';
      $activeClass = ($paginaActual == $archivo) ? 'active' : '';
      echo "<a class='nav-link $activeClass $claseExtra' href='$archivo'>{$opcion[1]} $texto</a>";
    }
    ?>
  </nav>
</div>

<div class="content">

  <div class="banner">
    <img src="logo/logo.jpg" alt="Banner Empresa">
  </div>

  <div class="alert alert-primary rounded-4 shadow-sm p-4 text-center">
    <h4 class="fw-bold">👋 Bienvenid@, <?php echo $usuario; ?> </h4>
    <p class="mt-3">
      Este es el panel principal de <strong class="text-decoration-underline">Luvadak</strong>, una empresa dedicada a ofrecer productos y servicios de calidad para nuestros clientes.
    </p>
    <p>
      Aquí podrás gestionar tus tareas diarias, acceder a los registros de ventas, clientes y reportes, además de mantener contacto con tu equipo.
    </p>
    <p class="fst-italic mt-4">💡 *"La calidad no es un acto, es un hábito."*</p>
  </div>


  <div class="row text-center mt-5">
 
    <div class="col-md-4 mb-4">
      <div class="card border-0 shadow-sm h-100 rounded-4 card-info">
        <div class="card-body">
          <img src="https://cdn-icons-png.flaticon.com/512/3135/3135715.png" alt="Misión">
          <h5 class="fw-bold">Misión</h5>
          <p>Brindar soluciones innovadoras que satisfagan las necesidades de nuestros clientes con excelencia y compromiso.</p>
        </div>
      </div>
    </div>

    <div class="col-md-4 mb-4">
      <div class="card border-0 shadow-sm h-100 rounded-4 card-info">
        <div class="card-body">
          <img src="https://cdn-icons-png.flaticon.com/512/3135/3135768.png" alt="Visión">
          <h5 class="fw-bold">Visión</h5>
          <p>Ser la empresa líder en nuestro sector, reconocida por la calidad de nuestro servicio y la satisfacción de nuestros clientes.</p>
        </div>
      </div>
    </div>
 
    <div class="col-md-4 mb-4">
      <div class="card border-0 shadow-sm h-100 rounded-4 card-info">
        <div class="card-body">
          <img src="https://cdn-icons-png.flaticon.com/512/3135/3135792.png" alt="Valores">
          <h5 class="fw-bold">Valores</h5>
          <p>Compromiso, integridad, innovación, trabajo en equipo y responsabilidad social.</p>
        </div>
      </div>
    </div>
  </div>
</div>

</body>
</html>
