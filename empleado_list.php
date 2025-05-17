<?php 
include 'auth.php';
$title = 'Lista de Empleados';
include 'header.php';
include 'navbar.php';
include 'db.php';
?>

  <h2>Lista de Empleados</h2>
  <a href="empleado_form.php" class="btn btn-primary mb-3">+ Agregar Empleado</a>
  <div class="table-responsive">
  <table class="table table-bordered">
    <thead class="table-light">
      <tr>
        <th>ID</th>
        <th>Nombre</th>
        <th>Programa</th>
        <th>Área</th>
        <th>Acciones</th>
      </tr>
    </thead>
    <tbody>
      <?php
        $result = $conn->query("SELECT * FROM empleados");
        while ($row = $result->fetch_assoc()):
      ?>
      <tr>
        <td><?= htmlspecialchars($row['empleado_id']) ?></td>
        <td><?= htmlspecialchars($row['empleado_nombre']) ?></td>
        <td><?= htmlspecialchars($row['empleado_programa']) ?></td>
        <td><?= htmlspecialchars($row['empleado_area']) ?></td>
        <td>
          <a href="empleado_form.php?id=<?= urlencode($row['empleado_id']) ?>" class="btn btn-sm btn-warning">Editar</a>
          <a href="empleado_delete.php?id=<?= urlencode($row['empleado_id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar?')">Eliminar</a>
        </td>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
  </div>
<?php include 'footer.php'; ?>
