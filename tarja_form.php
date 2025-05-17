<?php 
include 'auth.php';
$title = 'Formulario Tarjeta';
include 'header.php';
include 'navbar.php';
include 'db.php';

$fecha = $_GET['fecha'] ?? '';
$programa = $_GET['programa'] ?? '';
$area = $_GET['area'] ?? '';

$detalles = [];
if ($fecha && $programa && $area) {
    // Buscar registros exactos
    $stmt = $conn->prepare("SELECT * FROM tarjas_detalle WHERE tarjas_d_fecha = ? AND tarjas_d_programa = ? AND tarjas_d_area = ?");
    $stmt->bind_param("sss", $fecha, $programa, $area);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $detalles = $result->fetch_all(MYSQLI_ASSOC);
    } else {
        // Buscar la última fecha anterior
        $stmt = $conn->prepare("SELECT MAX(tarjas_d_fecha) as ultima_fecha FROM tarjas_detalle WHERE tarjas_d_fecha < ? AND tarjas_d_programa = ? AND tarjas_d_area = ?");
        $stmt->bind_param("sss", $fecha, $programa, $area);
        $stmt->execute();
        $fecha_anterior = $stmt->get_result()->fetch_assoc()['ultima_fecha'];
        if ($fecha_anterior) {
            $stmt = $conn->prepare("SELECT * FROM tarjas_detalle WHERE tarjas_d_fecha = ? AND tarjas_d_programa = ? AND tarjas_d_area = ?");
            $stmt->bind_param("sss", $fecha_anterior, $programa, $area);
            $stmt->execute();
            $detalles = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        } else {
            // Buscar empleados
            $stmt = $conn->prepare("SELECT empleado_id FROM empleados WHERE empleado_programa = ? AND empleado_area = ?");
            $stmt->bind_param("ss", $programa, $area);
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                $detalles[] = [
                    'tarjas_d_empleado' => $row['empleado_id'],
                    'tarjas_d_labor' => '',
                    'tarjas_d_horas_normales' => '',
                    'tarjas_d_horas_extras' => '',
                    'tarjas_d_tratos' => '',
                    'tarjas_d_ausencia' => '0'
                ];
            }
        }
    }
}
?>

<div class="container mt-4">
    <h2>Ingreso de Tarjas</h2>

    <form method="POST" action="tarja_save.php">
        <div class="row mb-3">
            <div class="col">
                <label>Fecha</label>
                <input type="date" name="fecha" class="form-control" value="<?= $fecha ?>" required>
            </div>
            <div class="col">
                <label>Programa</label>
                <input type="text" name="programa" class="form-control" value="<?= $programa ?>" required>
            </div>
            <div class="col">
                <label>Área</label>
                <input type="text" name="area" class="form-control" value="<?= $area ?>" required>
            </div>
        </div>

        <table class="table table-bordered table-responsive" id="detalleTable">
            <thead>
                <tr>
                    <th>Empleado</th>
                    <th>Labor</th>
                    <th>Horas Normales</th>
                    <th>Horas Extras</th>
                    <th>Tratos</th>
                    <th>Ausencia</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($detalles as $d): ?>
                <tr>
                    <td><input type="text" name="empleado[]" class="form-control" value="<?= $d['tarjas_d_empleado'] ?>"></td>
                    <td><input type="text" name="labor[]" class="form-control" value="<?= $d['tarjas_d_labor'] ?>"></td>
                    <td><input type="number" step="0.1" name="horas_normales[]" class="form-control" value="<?= $d['tarjas_d_horas_normales'] ?>" max="9"></td>
                    <td><input type="number" step="0.1" name="horas_extras[]" class="form-control" value="<?= $d['tarjas_d_horas_extras'] ?>" max="2"></td>
                    <td><input type="number" step="0.001" name="tratos[]" class="form-control" value="<?= $d['tarjas_d_tratos'] ?>"></td>
                    <td>
                        <select name="ausencia[]" class="form-control">
                            <option value="0" <?= $d['tarjas_d_ausencia'] == '0' ? 'selected' : '' ?>>No aplica</option>
                            <option value="1" <?= $d['tarjas_d_ausencia'] == '1' ? 'selected' : '' ?>>Con goce</option>
                            <option value="2" <?= $d['tarjas_d_ausencia'] == '2' ? 'selected' : '' ?>>Sin goce</option>
                        </select>
                    </td>
                    <td><button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)">X</button></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

        <button type="button" class="btn btn-secondary" onclick="addRow()">+ Añadir fila</button>
        <button type="submit" class="btn btn-primary float-end">Guardar</button>
    </form>
</div>

<script>
function addRow() {
    const row = `
        <tr>
            <td><input type="text" name="empleado[]" class="form-control"></td>
            <td><input type="text" name="labor[]" class="form-control"></td>
            <td><input type="number" step="0.1" name="horas_normales[]" class="form-control" max="9"></td>
            <td><input type="number" step="0.1" name="horas_extras[]" class="form-control" max="2"></td>
            <td><input type="number" step="0.001" name="tratos[]" class="form-control"></td>
            <td>
                <select name="ausencia[]" class="form-control">
                    <option value="0">No aplica</option>
                    <option value="1">Con goce</option>
                    <option value="2">Sin goce</option>
                </select>
            </td>
            <td><button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)">X</button></td>
        </tr>
    `;
    document.querySelector("#detalleTable tbody").insertAdjacentHTML('beforeend', row);
}

function removeRow(btn) {
    btn.closest("tr").remove();
}
</script>

<?php include 'footer.php'; ?>