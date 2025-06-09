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
    $stmt = $conn->prepare("SELECT * FROM tarjas_detalle WHERE tarjas_d_fecha = ? AND tarjas_d_programa = ? AND tarjas_d_area = ?");
    $stmt->bind_param("sss", $fecha, $programa, $area);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $detalles = $result->fetch_all(MYSQLI_ASSOC);
    } else {
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
            $stmt = $conn->prepare("SELECT empleado_id FROM empleados WHERE empleado_programa = ? AND empleado_area = ?");
            $stmt->bind_param("ss", $programa, $area);
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                $detalles[] = [
                    'tarjas_d_empleado' => $row['empleado_id'],
                    'tarjas_d_labor' => '',
                    'tarjas_d_horas_normales' => '9.0',
                    'tarjas_d_horas_extras' => '',
                    'tarjas_d_tratos' => '',
                    'tarjas_d_ausencia' => '0'
                ];
            }
        }
    }
}

// Obtener los programas y áreas
$programas_result = $conn->query("SELECT programa_id, descripcion_programa FROM programa");
$areas_result = $conn->query("SELECT area_id, descripcion_area FROM area");

// Cargar empleados y labores como arrays
$empleados = [];

if ($area) {
    $stmt = $conn->prepare("SELECT empleado_id, empleado_nombre FROM empleados");
    //$stmt->bind_param("s", $area);
    $stmt->execute();
    $empleados_result = $stmt->get_result();
    while ($row = $empleados_result->fetch_assoc()) {
        $empleados[] = $row;
    }
}

$labores_result = $conn->query("SELECT labor_id, descripcion_labor FROM labor");
$labores = [];
while ($row = $labores_result->fetch_assoc()) {
    $labores[] = $row;
}

// Obtener parámetros desde la tabla parametro
$horas_max = 9; // Valor por defecto
$horas_extras_max = 2; // Valor por defecto

$parametros_result = $conn->query("SELECT clave, valor FROM parametro WHERE clave IN ('HORAS_MAX', 'HORAS_EXTRAS_MAX')");
while ($row = $parametros_result->fetch_assoc()) {
    if ($row['clave'] === 'HORAS_MAX') {
        $horas_max = (float) $row['valor'];
    } elseif ($row['clave'] === 'HORAS_EXTRAS_MAX') {
        $horas_extras_max = (float) $row['valor'];
    }
}
?>

<div class="container mt-4">
    <h2 style="font-weight: bold;color:white;">Ingreso de Tarjas</h2>

    <form method="POST" action="tarja_save.php">
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <label class="form-label" style="font-weight: bold;color:white;">Fecha</label>
                <input type="date" name="fecha" class="form-control" value="<?= $fecha ?>" required>
            </div>
            <div class="col-md-3">
                <label class="form-label" style="font-weight: bold;color:white;">Programa</label>
                <select name="programa" id="programa" class="form-control" required>
                <?php while ($row = $programas_result->fetch_assoc()): ?>
                    <option value="<?= $row['programa_id'] ?>" <?= $row['programa_id'] == $programa ? 'selected' : '' ?>><?= htmlspecialchars($row['descripcion_programa']) ?></option>
                <?php endwhile; ?>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label" style="font-weight: bold;color:white;">Área</label>
                <select name="area" id="area" class="form-control" required>
                <?php while ($row = $areas_result->fetch_assoc()): ?>
                    <option value="<?= $row['area_id'] ?>" <?= $row['area_id'] == $area ? 'selected' : '' ?>><?= htmlspecialchars($row['descripcion_area']) ?></option>
                <?php endwhile; ?>
                </select>
            </div>
            <div class="col-md-3 align-self-end">
                <a href="tarja_list.php?fecha=<?= urlencode($fecha) ?>&programa=<?= urlencode($programa) ?>&area=<?= urlencode($area) ?>" class="btn btn-secondary">Volver</a>
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
                    <td>
                        <select name="empleado[]" class="form-control" required>
                        <?php foreach ($empleados as $e): ?>
                            <option value="<?= $e['empleado_id'] ?>" <?= $e['empleado_id'] == ($d['tarjas_d_empleado'] ?? '') ? 'selected' : '' ?>>
                                <?= htmlspecialchars($e['empleado_nombre']) ?>
                            </option>
                        <?php endforeach; ?>
                        </select>
                    </td>
                    <td>
                        <select name="labor[]" class="form-control" required>
                        <?php foreach ($labores as $l): ?>
                            <option value="<?= $l['labor_id'] ?>" <?= $l['labor_id'] == ($d['tarjas_d_labor'] ?? '') ? 'selected' : '' ?>>
                                <?= htmlspecialchars($l['descripcion_labor']) ?>
                            </option>
                        <?php endforeach; ?>
                        </select>
                    </td>
                    <td><input type="number" step="0.1" name="horas_normales[]" class="form-control" value="<?= $d['tarjas_d_horas_normales'] ?>" max="<?= $horas_max ?>"></td>
                    <td><input type="number" step="0.1" name="horas_extras[]" class="form-control" value="<?= $d['tarjas_d_horas_extras'] ?>" max="<?= $horas_extras_max ?>"></td>
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
        <button type="submit" class="btn btn-green float-end">Guardar</button>
    </form>
</div>

<script>
const empleados = <?= json_encode($empleados) ?>;
const labores = <?= json_encode($labores) ?>;
const maxHorasNormales = <?= $horas_max ?>;
const maxHorasExtras = <?= $horas_extras_max ?>;

function addRow() {
    let empleadoOptions = '';
    empleados.forEach(e => {
        empleadoOptions += `<option value="${e.empleado_id}">${e.empleado_nombre}</option>`;
    });

    let laborOptions = '';
    labores.forEach(l => {
        laborOptions += `<option value="${l.labor_id}">${l.descripcion_labor}</option>`;
    });

    const row = `
        <tr>
            <td>
                <select name="empleado[]" class="form-control" required>
                    ${empleadoOptions}
                </select>
            </td>
            <td>
                <select name="labor[]" class="form-control" required>
                    ${laborOptions}
                </select>
            </td>
            <td><input type="number" step="0.1" name="horas_normales[]" class="form-control" value="9.0" max="${maxHorasNormales}"></td>
            <td><input type="number" step="0.1" name="horas_extras[]" class="form-control" max="${maxHorasExtras}"></td>
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
