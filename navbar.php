<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['usuario'])) header("Location: login.php");

// Convertimos el rol único en array para tratar todo igual
$accesos = isset($_SESSION['accesos']) ? $_SESSION['accesos'] : null;
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark navbar-cloud px-3">
  <a class="navbar-brand" href="#">Tarjas</a>

  <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent"
          aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarContent">
    <!-- Enlaces de navegación -->
    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
      <?php if (in_array('ALL', $accesos)) : ?>
        <li class="nav-item"><a class="nav-link" href="programa_list.php">Programas</a></li>
        <li class="nav-item"><a class="nav-link" href="area_list.php">Áreas</a></li>
        <li class="nav-item"><a class="nav-link" href="labor_list.php">Labores</a></li>
        <li class="nav-item"><a class="nav-link" href="empleado_list.php">Empleados</a></li>
        <li class="nav-item"><a class="nav-link" href="tarja_list.php">Tarjas</a></li>
        <li class="nav-item"><a class="nav-link" href="reporte.php">Reporte</a></li>
        <li class="nav-item"><a class="nav-link" href="parametro_list.php">Parámetros</a></li>
        <li class="nav-item"><a class="nav-link" href="usuario_list.php">Usuarios</a></li>
      <?php endif; ?>
      <?php if (in_array('TARJAS', $accesos)) : ?>
        <li class="nav-item"><a class="nav-link" href="tarja_list.php">Tarjas</a></li>
      <?php endif; ?>
      <?php if (in_array('REPORTE', $accesos)) : ?>
        <li class="nav-item"><a class="nav-link" href="reporte.php">Reporte</a></li>
      <?php endif; ?>
    </ul>

    <!-- Usuario y logout -->
    <div class="d-flex align-items-center text-white">
      <span class="me-3">Usuario: <strong><?php echo htmlspecialchars($_SESSION['usuario']); ?></strong></span>
      <a href="logout.php" class="btn btn-outline-light btn-sm">Cerrar sesión</a>
    </div>
  </div>
</nav>
