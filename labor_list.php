<?php 
include 'auth.php';
$title = 'Lista de Labores';
include 'header.php';
include 'navbar.php';
include 'db.php';
?>

  <div class="d-flex justify-content-center align-items-center mb-3">
    <h2 style="font-weight: bold;color:white;">Labores</h2>
  </div>
  <div class="d-flex justify-content-end align-items-center mb-3">
    <a href="labor_form.php" class="btn btn-green">+ Agregar Labor</a>
  </div>
  <div class="table-responsive">
  <table class="table table-bordered">
    <thead class="table-light">
      <tr>
        <th>Area</th>
        <th>ID Labor</th>
        <th>Descripción</th>
        <th>CC1</th>
        <th>CC2</th>
        <th>CC3</th>
        <th>CC4</th>
        <th>CC5</th>
        <th>Acciones</th>
      </tr>
    </thead>
    <tbody>
      <?php
        $result = $conn->query("SELECT l.*, a.descripcion_area FROM labor l inner join area a on (l.area_id=a.area_id)");
        while ($row = $result->fetch_assoc()):
      ?>
      <tr>
        <td><?= htmlspecialchars($row['descripcion_area']) ?></td>
        <td><?= htmlspecialchars($row['labor_id']) ?></td>
        <td><?= htmlspecialchars($row['descripcion_labor']) ?></td>
        <td><?= htmlspecialchars($row['cc1']) ?></td>
        <td><?= htmlspecialchars($row['cc2']) ?></td>
        <td><?= htmlspecialchars($row['cc3']) ?></td>
        <td><?= htmlspecialchars($row['cc4']) ?></td>
        <td><?= htmlspecialchars($row['cc5']) ?></td>
        <td>
          <a href="labor_form.php?id=<?= urlencode($row['labor_id']) ?>" class="btn btn-sm btn-warning">Editar</a>
          <a href="labor_delete.php?id=<?= urlencode($row['labor_id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar?')">Eliminar</a>
        </td>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
  </div>
<?php include 'footer.php'; ?>
