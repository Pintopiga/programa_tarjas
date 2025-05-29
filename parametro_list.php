<?php 
include 'auth.php';
$title = 'Lista de Parametros';
include 'header.php';
include 'navbar.php';
include 'db.php';
?>
<div class="d-flex justify-content-center align-items-center mb-3">
  <h2 style="font-weight: bold;color:white;">Parámetros</h2>
</div>
<div class="d-flex justify-content-end align-items-center mb-3">
  <a href="parametro_form.php" class="btn btn-green">+ Agregar Parámetro</a>
</div>
<div class="table-responsive">
<table class="table table-bordered table-striped">
  <thead class="table-light">
    <tr>
      <th>ID</th>
      <th>Descripción</th>
      <th>Valor</th>
      <th>Acciones</th>
    </tr>
  </thead>
  <tbody>
<?php
$result = $conn->query("SELECT * FROM parametro");
while ($row = $result->fetch_assoc()):
?>
    <tr>
      <td><?= htmlspecialchars($row['clave'] ?? '') ?></td>
      <td><?= htmlspecialchars($row['descripcion'] ?? '') ?></td>
      <td><?= htmlspecialchars($row['valor'] ?? '') ?></td>
      <td>
        <?php if (!is_null($row['clave'])): ?>
            <a href="parametro_form.php?id=<?= urlencode($row['clave']) ?>" class="btn btn-sm btn-warning">Editar</a>
            <a href="parametro_delete.php?id=<?= urlencode($row['clave']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar este parámetro?')">Eliminar</a>
        <?php else: ?>
            <span class="text-muted">ID no válido</span>
        <?php endif; ?>
      </td>
    </tr>
<?php endwhile; ?>
  </tbody>
</table>
</div>
<?php include 'footer.php'; ?>