<?php 
include 'auth.php';
$title = 'Formulario Empleado';
include 'header.php';
include 'navbar.php';
include 'db.php';
?>

<?php
$edit = false;
$empleado_id = '';
$empleado_nombre = '';
$empleado_programa = '';
$empleado_area = '';

// Obtener los programas y áreas para los select
$programas_result = $conn->query("SELECT programa_id, descripcion_programa FROM programa");
$areas_result = $conn->query("SELECT area_id, descripcion_area FROM area");

if (isset($_GET['id'])) {
    $edit = true;
    $stmt = $conn->prepare("SELECT * FROM empleados WHERE empleado_id = ?");
    $stmt->bind_param("s", $_GET['id']);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_assoc();
    $empleado_id = $res['empleado_id'];
    $empleado_nombre = $res['empleado_nombre'];
    $empleado_programa = $res['empleado_programa'];
    $empleado_area = $res['empleado_area'];
}
?>

  <h2 style="font-weight: bold;color:white;"><?= $edit ? 'Editar' : 'Agregar' ?> Empleado</h2>
  <form method="POST" action="empleado_save.php">
    <?php if ($edit): ?>
      <input type="hidden" name="original_id" value="<?= htmlspecialchars($empleado_id) ?>">
    <?php endif; ?>
    <div class="row mb-3 align-items-end">
      <label for="empleado_id" class="form-label" style="font-weight: bold;color:white;">RUT Empleado</label>
      <div class="col-sm-3">
        <input type="text" name="empleado_id" id="rut" maxlength="8" class="form-control" required value="<?= htmlspecialchars($empleado_id) ?>" <?= $edit ? 'readonly' : '' ?>>
      </div>
      <div class="col-sm-1">
        <label class="form-label" style="font-weight: bold;color:white;">-</label>
      </div>
      <div class="col-sm-1">
        <label id="dv" class="form-label" style="font-weight: bold;color:white;">-</label>
      </div>
    </div>
    <div class="mb-3 col-md-5">
      <label for="empleado_nombre" class="form-label" style="font-weight: bold;color:white;">Nombre</label>
      <input type="text" name="empleado_nombre" id="empleado_nombre" maxlength="30" class="form-control" required value="<?= htmlspecialchars($empleado_nombre) ?>">
    </div>
    <div class="mb-3 col-md-3">
      <label for="empleado_programa" class="form-label" style="font-weight: bold;color:white;">Programa</label>
      <select name="empleado_programa" id="empleado_programa" class="form-control" required>
        <?php while ($row = $programas_result->fetch_assoc()): ?>
          <option value="<?= htmlspecialchars($row['programa_id']) ?>" <?= $row['programa_id'] == $empleado_programa ? 'selected' : '' ?>><?= htmlspecialchars($row['descripcion_programa']) ?></option>
        <?php endwhile; ?>
      </select>
    </div>
    <div class="mb-3 col-md-3">
      <label for="empleado_area" class="form-label" style="font-weight: bold;color:white;">Área</label>
      <select name="empleado_area" id="empleado_area" class="form-control" required>
        <?php while ($row = $areas_result->fetch_assoc()): ?>
          <option value="<?= htmlspecialchars($row['area_id']) ?>" <?= $row['area_id'] == $empleado_area ? 'selected' : '' ?>><?= htmlspecialchars($row['descripcion_area']) ?></option>
        <?php endwhile; ?>
      </select>
    </div>
    <button type="submit" class="btn btn-success">Guardar</button>
    <a href="empleado_list.php" class="btn btn-secondary">Volver</a>
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
