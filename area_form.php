<?php 
include 'auth.php';
$title = 'Formulario Area';
include 'header.php';
include 'navbar.php';
include 'db.php';
?>

<?php
$edit = false;
$area_id = '';
$descripcion = '';

if (isset($_GET['id'])) {
    $edit = true;
    $stmt = $conn->prepare("SELECT * FROM area WHERE area_id = ?");
    $stmt->bind_param("s", $_GET['id']);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_assoc();
    $area_id = $res['area_id'];
    $descripcion = $res['descripcion_area'];
}
?>

  <h2><?= $edit ? 'Editar' : 'Agregar' ?> Área</h2>
  <form method="POST" action="area_save.php">
    <?php if ($edit): ?>
      <input type="hidden" name="original_id" value="<?= htmlspecialchars($area_id) ?>">
    <?php endif; ?>
    <div class="mb-3 col-md-1">
      <label for="area_id" class="form-label">ID Área</label>
      <input type="text" name="area_id" id="area_id" maxlength="5" class="form-control" required value="<?= htmlspecialchars($area_id) ?>" <?= $edit ? 'readonly' : '' ?>>
    </div>
    <div class="mb-3 col-md-3">
      <label for="descripcion" class="form-label">Descripción</label>
      <input type="text" name="descripcion" id="descripcion" maxlength="30" class="form-control" required value="<?= htmlspecialchars($descripcion) ?>">
    </div>
    <button type="submit" class="btn btn-success">Guardar</button>
    <a href="area_list.php" class="btn btn-secondary">Volver</a>
  </form>
<?php include 'footer.php'; ?>
