<?php
// usuarios_list.php
include 'auth.php';
verificar_acceso('ALL');
$title = 'Lista de Usuarios';
include 'header.php';
include 'navbar.php';
include 'db.php';

// Función PHP para calcular el DV
function calcularDV($rut) {
    $s = 1;
    $m = 0;
    while ($rut > 0) {
        $s = ($s + $rut % 10 * (9 - $m++ % 6)) % 11;
        $rut = intval($rut / 10);
    }
    return $s ? $s - 1 : 'K';
}
?>

<div class="d-flex justify-content-between align-items-center mb-3">
  <h2 style="font-weight: bold;color:white;">Usuarios</h2>
  <a href="usuario_form.php" class="btn btn-green">+ Agregar Usuario</a>
</div>
<div class="table-responsive">
<table class="table table-bordered">
  <thead class="table-light">
    <tr>
      <th>RUT</th>
      <th>Nombre</th>
      <th>Usuario</th>
      <th>Área</th>
      <th>Accesos</th>
      <th>Acciones</th>
    </tr>
  </thead>
  <tbody>
    <?php
      $query = "SELECT u.*, IFNULL(ar.descripcion_area, 'TODAS') AS descripcion_area, GROUP_CONCAT(a.tarjas_user_acceso) AS accesos FROM usuarios u
                LEFT JOIN accesos a ON u.tarjas_user_rut = a.tarjas_user_rut
                LEFT JOIN area ar ON u.tarjas_user_area=ar.area_id
                GROUP BY u.tarjas_user_id";
      $result = $conn->query($query);
      while ($row = $result->fetch_assoc()):
    ?>
    <tr>
      <td><?= $row['tarjas_user_rut'] . '-' . calcularDV($row['tarjas_user_rut']) ?></td>
      <td><?= htmlspecialchars($row['tarjas_user_nombre']) ?></td>
      <td><?= htmlspecialchars($row['tarjas_user_usuario']) ?></td>
      <td><?= htmlspecialchars($row['descripcion_area']) ?></td>
      <td><?= htmlspecialchars($row['accesos']) ?></td>
      <td>
        <a href="usuario_form.php?id=<?= $row['tarjas_user_id'] ?>" class="btn btn-sm btn-warning">Editar</a>
        <a href="usuario_delete.php?id=<?= $row['tarjas_user_id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar usuario?')">Eliminar</a>
      </td>
    </tr>
    <?php endwhile; ?>
  </tbody>
</table>
</div>
<?php include 'footer.php'; ?>