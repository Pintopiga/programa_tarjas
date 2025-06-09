<?php 
include 'auth.php';
$title = 'Formulario Programa';
include 'header.php';
include 'navbar.php';
include 'db.php';
?>

<?php
$edit = false;
$programa_id = '';
$descripcion = '';

if (isset($_GET['id'])) {
    $edit = true;
    $stmt = $conn->prepare("SELECT * FROM programa WHERE programa_id = ?");
    $stmt->bind_param("s", $_GET['id']);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_assoc();
    $programa_id = $res['programa_id'];
    $descripcion = $res['descripcion_programa'];
}
?>

  <h2 style="font-weight: bold;color:white;"><?= $edit ? 'Editar' : 'Agregar' ?> Programa</h2>
  <form method="POST" action="programa_save.php">
    <?php if ($edit): ?>
      <input type="hidden" name="original_id" value="<?= htmlspecialchars($programa_id) ?>">
    <?php endif; ?>
    <div class="mb-3 col-md-2">
      <label for="programa_id" class="form-label" style="font-weight: bold;color:white;">ID Programa</label>
      <input type="text" name="programa_id" id="programa_id" maxlength="5" class="form-control" required value="<?= htmlspecialchars($programa_id) ?>" <?= $edit ? 'readonly' : '' ?>>
    </div>
    <div class="mb-3 col-md-3">
      <label for="descripcion" class="form-label" style="font-weight: bold;color:white;">Descripción</label>
      <input type="text" name="descripcion" id="descripcion" maxlength="30" class="form-control" required value="<?= htmlspecialchars($descripcion) ?>">
    </div>
    <button type="submit" class="btn btn-success">Guardar</button>
    <a href="programa_list.php" class="btn btn-secondary">Volver</a>
  </form>
<?php include 'footer.php'; ?>
