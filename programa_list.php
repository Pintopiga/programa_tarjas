<?php 
include 'auth.php';
$title = 'Lista de Programas';
include 'header.php';
include 'navbar.php';
include 'db.php';
?>
  <div class="d-flex justify-content-center align-items-center mb-3">
    <h2 style="font-weight: bold;color:white;">Programas</h2>
  </div>
  <div class="d-flex justify-content-end align-items-center mb-3">
    <a href="programa_form.php" class="btn btn-green">+ Agregar Programa</a>
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
        $result = $conn->query("SELECT * FROM programa");
        while ($row = $result->fetch_assoc()):
      ?>
      <tr>
        <td><?= htmlspecialchars($row['programa_id']) ?></td>
        <td><?= htmlspecialchars($row['descripcion_programa']) ?></td>
        <td>
          <a href="programa_form.php?id=<?= urlencode($row['programa_id']) ?>" class="btn btn-sm btn-warning">Editar</a>
          <a href="programa_delete.php?id=<?= urlencode($row['programa_id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar?')">Eliminar</a>
        </td>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
  </div>
<?php include 'footer.php'; ?>
