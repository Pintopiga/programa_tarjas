<?php
include 'auth.php';
verificar_acceso('ALL');
$title = 'Formulario de Usuario';
include 'header.php';
include 'navbar.php';
include 'db.php';

$id = $_GET['id'] ?? null;
$usuario = [
  'tarjas_user_rut' => '', 'tarjas_user_nombre' => '',
  'tarjas_user_usuario' => '', 'tarjas_user_area' => ''
];
$accesos = [];

if ($id) {
  $stmt = $conn->prepare("SELECT * FROM usuarios WHERE tarjas_user_id = ?");
  $stmt->bind_param("i", $id);
  $stmt->execute();
  $res = $stmt->get_result();
  if ($res->num_rows) $usuario = $res->fetch_assoc();

  $rut = $usuario['tarjas_user_rut'];
  $res = $conn->query("SELECT tarjas_user_acceso FROM accesos WHERE tarjas_user_rut = $rut");
  while ($row = $res->fetch_assoc()) $accesos[] = $row['tarjas_user_acceso'];
}

$areas_result = $conn->query("SELECT area_id, descripcion_area FROM area");
?>

<form method="POST" action="usuario_save.php">
  <input type="hidden" name="id" value="<?= $id ?>">
  <div class="row mb-3 align-items-end"></div>
  <div class="row mb-3 align-items-end">
    <label class="col-sm-1 col-form-label" style="font-weight: bold;color:white;">RUT</label>
    <div class="col-sm-3">
      <input type="text" id="rut" name="rut" class="form-control" value="<?= htmlspecialchars($rut ?? '') ?>" maxlength="8" required>
    </div>
    <div class="col-sm-1">
      <label class="form-label" style="font-weight: bold;color:white;">-</label>
    </div>
    <div class="col-sm-1">
      <label id="dv" class="form-label" style="font-weight: bold;color:white;">-</label>
    </div>
  </div>
  <div class="row mb-3 align-items-end">
    <label class="col-sm-1 col-form-label" style="font-weight: bold;color:white;">NOMBRE</label>
    <div class="col-sm-3">
        <input type="text" name="nombre" class="form-control" required value="<?= $usuario['tarjas_user_nombre'] ?>">
    </div>
  </div>
  <div class="row mb-3 align-items-end">
    <label class="col-sm-1 col-form-label" style="font-weight: bold;color:white;">USUARIO</label>
    <div class="col-sm-2">
        <input type="text" name="usuario" class="form-control" required value="<?= $usuario['tarjas_user_usuario'] ?>">
    </div>
  </div>
  <div class="row mb-3 align-items-end">
    <label class="col-sm-1 col-form-label" style="font-weight: bold;color:white;">CLAVE</label>
    <div class="col-sm-2">
        <input type="password" name="clave" class="form-control">
    </div>
    <div class="col-sm-3">
        <label class="form-label" style="font-weight: bold;color:white;">* dejar vacío para no cambiar</label>
    </div>
  </div>
  <div class="row mb-3 align-items-end">
    <label class="col-sm-1 col-form-label" style="font-weight: bold;color:white;">ÁREA</label>
    <div class="col-sm-3">
      <select name="area" id="area" class="form-control" required>
        <option value="0">TODAS</option>
        <?php while ($row = $areas_result->fetch_assoc()): ?>
          <option value="<?= htmlspecialchars($row['area_id']) ?>" <?= $row['area_id'] == $usuario['tarjas_user_area'] ? 'selected' : '' ?>><?= htmlspecialchars($row['descripcion_area']) ?></option>
        <?php endwhile; ?>
      </select>
    </div>
  </div>
  <div class="row mb-3 align-items-end">
    <label class="col-sm-1 col-form-label" style="font-weight: bold;color:white;">ACCESOS</label>
    <div class="col-sm-6">
    <?php foreach (["ALL", "TARJAS", "REPORTE"] as $opcion): ?>
      <div class="form-check form-check-inline">
        <input class="form-check-input" type="checkbox" name="accesos[]" value="<?= $opcion ?>" <?= in_array($opcion, $accesos) ? 'checked' : '' ?>>
        <label class="form-check-label" style="font-weight: bold;color:white;"><?= $opcion ?></label>
      </div>
    <?php endforeach; ?>
    </div>
  </div>
  <button type="submit" class="btn btn-green">Guardar</button>
  <a href="usuario_list.php" class="btn btn-secondary">Cancelar</a>
</form>

<script>
function calcularDV(rut) {
    let suma = 0, mul = 2;
    for (let i = rut.length - 1; i >= 0; i--) {
        suma += parseInt(rut.charAt(i)) * mul;
        mul = mul === 7 ? 2 : mul + 1;
    }
    const res = 11 - (suma % 11);
    return res === 11 ? '0' : res === 10 ? 'K' : res.toString();
}

document.addEventListener('DOMContentLoaded', function () {
    const rutInput = document.getElementById('rut');
    const dvLabel = document.getElementById('dv');

    function actualizarDV() {
        const rut = rutInput.value.replace(/\D/g, '');
        dvLabel.textContent = rut.length > 0 ? calcularDV(rut) : '-';
    }

    rutInput.addEventListener('input', actualizarDV);

    // Calcular el DV inicial si el rut viene precargado
    actualizarDV();
});
</script>
<?php include 'footer.php'; ?>