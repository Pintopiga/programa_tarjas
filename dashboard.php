<?php 
include 'auth.php';
verificar_acceso('dashboard');
$title = 'Dashboard';
include 'header.php';
include 'db.php';
?>

<div class="dashboard-container">
  <div class="sidebar">
    <div class="text-center">
        <img src="assets/img/cropped-Logo-1.png" alt="Logo Pinto Piga" class="logo">
    </div>
    <a href="tarja_list.php">📝 Módulo de Tarjas</a>
    <!-- Puedes agregar más módulos aquí -->
    <!-- <a href="reporte.php">📊 Reportes</a> -->
  </div>

  <div class="dashboard-content">
    <h2 class="mb-3">Bienvenido</h2>
    <h4>Selecciona una opción del menú para continuar.</h4>
  </div>
</div>

<?php include 'footer.php'; ?>