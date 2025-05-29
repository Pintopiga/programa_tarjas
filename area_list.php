<?php 
include 'auth.php';
$title = 'Lista de Areas';
include 'header.php';
include 'navbar.php';
include 'db.php';
?>

  <div class="d-flex justify-content-center align-items-center mb-3">
    <h2 style="font-weight: bold;color:white;">Áreas</h2>
  </div>
  <div class="d-flex justify-content-end align-items-center mb-3">
    <a href="area_form.php" class="btn btn-green">+ Agregar Área</a>
  </div>
  <div class="table-responsive">
  <table class="table table-bordered">
    <thead class="table-light">
      <tr>
        <th>ID</th>
        <th>Descripción</th>
        <th>Acciones</th>
      </tr>
    </thead>
    <tbody>
      <?php
        $result = $conn->query("SELECT * FROM area");
        while ($row = $result->fetch_assoc()):
      ?>
      <tr>
        <td><?= htmlspecialchars($row['area_id']) ?></td>
        <td><?= htmlspecialchars($row['descripcion_area']) ?></td>
        <td>
          <a href="area_form.php?id=<?= urlencode($row['area_id']) ?>" class="btn btn-sm btn-warning">Editar</a>
          <a href="area_delete.php?id=<?= urlencode($row['area_id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar?')">Eliminar</a>
        </td>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
  </div>
<?php include 'footer.php'; ?>
