<?php 
include 'auth.php';
verificar_acceso('area');
$title = 'Lista de Empleados';
include 'header.php';
include 'navbar.php';
include 'db.php';
?>

  <div class="d-flex justify-content-center align-items-center mb-3">
    <h2 style="font-weight: bold;color:white;">Empleados</h2>
  </div>
  <div class="d-flex justify-content-end align-items-center mb-3">
    <a href="empleado_form.php" class="btn btn-green">+ Agregar Empleado</a>
  </div>
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
        $result = $conn->query("SELECT * FROM empleados e INNER JOIN programa p ON e.empleado_programa=p.programa_id INNER JOIN area a ON e.empleado_area=a.area_id");
        while ($row = $result->fetch_assoc()):
      ?>
      <tr>
        <td><?= htmlspecialchars($row['empleado_id']) ?></td>
        <td><?= htmlspecialchars($row['empleado_nombre']) ?></td>
        <td><?= htmlspecialchars($row['descripcion_programa']) ?></td>
        <td><?= htmlspecialchars($row['descripcion_area']) ?></td>
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
