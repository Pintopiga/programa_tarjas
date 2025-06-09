<?php 
include 'auth.php';
$title = 'Formulario Parametro';
include 'header.php';
include 'navbar.php';
include 'db.php';
?>

<?php
$clave = $valor = $descripcion = '';
$edit = false;

if (isset($_GET['id'])) {
    $edit = true;
    $stmt = $conn->prepare("SELECT * FROM parametro WHERE clave = ?");
    $stmt->bind_param("s", $_GET['id']);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_assoc();
    $clave = $res['clave'];
    $valor = $res['valor'];
    $descripcion = $res['descripcion'];

    
}


?>

<div class="container mt-4">
  <h2 style="font-weight: bold;color:white;"><?= $edit ? 'Editar' : 'Agregar' ?> Parámetro</h2>
  <form method="POST" action="parametro_save.php">
    <?php if ($edit): ?>
      <input type="hidden" name="original_clave" value="<?= htmlspecialchars($clave) ?>">
    <?php endif; ?>
    <div class="mb-3 col-md-3">
      <label class="form-label" style="font-weight: bold;color:white;">Clave</label>
      <input type="text" name="clave" class="form-control" required maxlength="50" value="<?= htmlspecialchars($clave) ?>" <?= $edit ? 'readonly' : '' ?>>
    </div>
    <div class="mb-3 col-md-2">
      <label class="form-label" style="font-weight: bold;color:white;">Valor</label>
      <input type="text" name="valor" class="form-control" required maxlength="100" value="<?= htmlspecialchars($valor) ?>">
    </div>
    <div class="mb-3 col-md-5">
      <label class="form-label" style="font-weight: bold;color:white;">Descripción</label>
      <textarea name="descripcion" class="form-control"><?= htmlspecialchars($descripcion) ?></textarea>
    </div>
    <button type="submit" class="btn btn-success">Guardar</button>
    <a href="parametro_list.php" class="btn btn-secondary">Volver</a>
  </form>
</div>
<?php include 'footer.php'; ?>