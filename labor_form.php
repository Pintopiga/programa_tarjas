<?php 
include 'auth.php';
$title = 'Formulario Labor';
include 'header.php';
include 'navbar.php';
include 'db.php';
?>

<?php
$edit = false;
$labor_id = '';
$descripcion_labor = '';
$cc1 = '';
$cc2 = '';
$cc3 = '';
$cc4 = '';
$cc5 = '';

if (isset($_GET['id'])) {
    $edit = true;
    $stmt = $conn->prepare("SELECT * FROM labor WHERE labor_id = ?");
    $stmt->bind_param("s", $_GET['id']);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_assoc();
    $labor_id = $res['labor_id'];
    $descripcion_labor = $res['descripcion_labor'];
    $cc1 = $res['cc1'];
    $cc2 = $res['cc2'];
    $cc3 = $res['cc3'];
    $cc4 = $res['cc4'];
    $cc5 = $res['cc5'];
}

$areas = $conn->query("SELECT area_id, descripcion_area FROM area");
$area_id = $edit ? $res['area_id'] : '';
?>

  <h2 style="font-weight: bold;color:white;"><?= $edit ? 'Editar' : 'Agregar' ?> Labor</h2>
  <form method="POST" action="labor_save.php">
    <?php if ($edit): ?>
      <input type="hidden" name="original_id" value="<?= htmlspecialchars($labor_id) ?>">
    <?php endif; ?>
    <div class="mb-3 col-md-3">
      <label for="area_id" class="form-label" style="font-weight: bold;color:white;">Área</label>
      <select name="area_id" class="form-control" required>
        <option value="">Seleccione un área</option>
        <?php while ($a = $areas->fetch_assoc()): ?>
          <option value="<?= $a['area_id'] ?>" <?= $a['area_id'] == $area_id ? 'selected' : '' ?>>
            <?= htmlspecialchars($a['descripcion_area']) ?>
          </option>
        <?php endwhile; ?>
      </select>
    </div>
    <div class="mb-3 col-md-1">
      <label for="labor_id" class="form-label" style="font-weight: bold;color:white;">ID Labor</label>
      <input type="text" name="labor_id" id="labor_id" maxlength="5" class="form-control" required value="<?= htmlspecialchars($labor_id) ?>" <?= $edit ? 'readonly' : '' ?>>
    </div>
    <div class="mb-3 col-md-3">
      <label for="descripcion_labor" class="form-label" style="font-weight: bold;color:white;">Descripción</label>
      <input type="text" name="descripcion_labor" id="descripcion_labor" maxlength="100" class="form-control" required value="<?= htmlspecialchars($descripcion_labor) ?>">
    </div>
    <div class="mb-3 col-md-3">
      <label for="cc1" class="form-label" style="font-weight: bold;color:white;">CC1</label>
      <input type="text" name="cc1" id="cc1" maxlength="9" class="form-control" required value="<?= htmlspecialchars($cc1) ?>">
    </div>
    <div class="mb-3 col-md-3">
      <label for="cc2" class="form-label" style="font-weight: bold;color:white;">CC2</label>
      <input type="text" name="cc2" id="cc2" maxlength="9" class="form-control" required value="<?= htmlspecialchars($cc2) ?>">
    </div>
    <div class="mb-3 col-md-3">
      <label for="cc3" class="form-label" style="font-weight: bold;color:white;">CC3</label>
      <input type="text" name="cc3" id="cc3" maxlength="9" class="form-control" required value="<?= htmlspecialchars($cc3) ?>">
    </div>
    <div class="mb-3 col-md-3">
      <label for="cc4" class="form-label" style="font-weight: bold;color:white;">CC4</label>
      <input type="text" name="cc4" id="cc4" maxlength="9" class="form-control" required value="<?= htmlspecialchars($cc4) ?>">
    </div>
    <div class="mb-3 col-md-3">
      <label for="cc5" class="form-label" style="font-weight: bold;color:white;">CC5</label>
      <input type="text" name="cc5" id="cc5" maxlength="9" class="form-control" required value="<?= htmlspecialchars($cc5) ?>">
    </div>
    <button type="submit" class="btn btn-success">Guardar</button>
    <a href="labor_list.php" class="btn btn-secondary">Volver</a>
  </form>
<?php include 'footer.php'; ?>