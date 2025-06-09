<?php
include 'auth.php';
$title = 'Lista de Tarjetas';
include 'header.php';
include 'navbar.php';
include 'db.php';

// Obtener los programas y áreas para los select
$programas_result = $conn->query("SELECT programa_id, descripcion_programa FROM programa");
$areas_result = $conn->query("SELECT area_id, descripcion_area FROM area");
?>

<div class="d-flex justify-content-center align-items-center mb-3">
    <h2 style="font-weight: bold;color:white;">Tarjas</h2>
</div>

<form class="row g-3 mb-4" method="GET">
    <div class="col-md-3">
        <label class="form-label" style="font-weight: bold;color:white;">Fecha</label>
        <input type="date" name="fecha" class="form-control" value="<?= $_GET['fecha'] ?? '' ?>">
    </div>
    <div class="col-md-3">
        <label class="form-label" style="font-weight: bold;color:white;">Programa</label>
        <select name="programa" class="form-control" required>
          <?php while ($row = $programas_result->fetch_assoc()): ?>
            <option value="<?= htmlspecialchars($row['programa_id']) ?>" <?= $row['programa_id'] == ($_GET['programa'] ?? '') ? 'selected' : '' ?>>
              <?= htmlspecialchars($row['descripcion_programa']) ?>
            </option>
          <?php endwhile; ?>
        </select>
    </div>
    <div class="col-md-3">
        <label class="form-label" style="font-weight: bold;color:white;">Área</label>
        <select name="area" class="form-control" required>
          <?php while ($row = $areas_result->fetch_assoc()): ?>
            <option value="<?= htmlspecialchars($row['area_id']) ?>" <?= $row['area_id'] == ($_GET['area'] ?? '') ? 'selected' : '' ?>>
              <?= htmlspecialchars($row['descripcion_area']) ?>
            </option>
          <?php endwhile; ?>
        </select>
    </div>
    <div class="col-md-3 align-self-end">
        <button class="btn btn-green">Buscar</button>
    </div>
</form>

<?php
if (!empty($_GET['fecha']) && !empty($_GET['programa']) && !empty($_GET['area'])) {
    $fecha = $_GET['fecha'];
    $programa = $_GET['programa'];
    $area = $_GET['area'];

    $stmt = $conn->prepare("
        SELECT td.*, e.empleado_nombre, l.descripcion_labor 
        FROM tarjas_detalle td
        LEFT JOIN empleados e ON td.tarjas_d_empleado = e.empleado_id
        LEFT JOIN labor l ON td.tarjas_d_labor = l.labor_id
        WHERE td.tarjas_d_fecha = ? AND td.tarjas_d_programa = ? AND td.tarjas_d_area = ?
    ");
    $stmt->bind_param("sss", $fecha, $programa, $area);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        ?>
        <div class="d-flex justify-content-left align-items-center mb-3">
            <a href="tarja_form.php?fecha=<?= urlencode($fecha) ?>&programa=<?= urlencode($programa) ?>&area=<?= urlencode($area) ?>" class="btn btn-green mt-2">
                Ingresar Tarjas para esta combinación
            </a>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-responsive">
                <thead>
                    <tr>
                        <th>Empleado</th>
                        <th>Labor</th>
                        <th>Horas Normales</th>
                        <th>Horas Extras</th>
                        <th>Tratos</th>
                        <th>Ausencia</th>
                    </tr>
                </thead>
                <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['empleado_nombre'] ?? 'Desconocido') ?></td>
                        <td><?= htmlspecialchars($row['descripcion_labor'] ?? 'Desconocida') ?></td>
                        <td><?= $row['tarjas_d_horas_normales'] ?></td>
                        <td><?= $row['tarjas_d_horas_extras'] ?></td>
                        <td><?= $row['tarjas_d_tratos'] ?></td>
                        <td><?= $row['tarjas_d_ausencia'] ? 'Sí' : 'No' ?></td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        <?php
    } else {
        echo '<div class="alert alert-warning mt-4">No se encontraron registros para los criterios seleccionados.</div>';

        $fechaAnterior = date('Y-m-d', strtotime($fecha . ' -1 day'));

        $stmtAnt = $conn->prepare("
            SELECT DISTINCT e.empleado_nombre, p.descripcion_programa, a.descripcion_area 
            FROM tarjas_detalle td
            LEFT JOIN empleados e ON td.tarjas_d_empleado = e.empleado_id
			LEFT JOIN programa p ON td.tarjas_d_programa = p.programa_id
            LEFT JOIN area a ON td.tarjas_d_area = a.area_id
            WHERE td.tarjas_d_fecha = ? AND td.tarjas_d_area = ?
        ");
        $stmtAnt->bind_param("ss", $fechaAnterior, $area);
        $stmtAnt->execute();
        $resultAnt = $stmtAnt->get_result();

        if ($resultAnt->num_rows > 0) {
            ?>
            <h5 class="mt-4" style="font-weight: bold;color:white;">Sugerencia de trabajadores según el día anterior (<?= $fechaAnterior ?>)</h5>
            <div class="d-flex justify-content-left align-items-center mb-3">
                <a href="tarja_form.php?fecha=<?= urlencode($fecha) ?>&programa=<?= urlencode($programa) ?>&area=<?= urlencode($area) ?>" class="btn btn-green mt-2">
                    Ingresar Tarjas para esta combinación
                </a>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="table-secondary">
                        <tr>
                            <th>Empleado</th>
                            <th>Programa</th>
							<th>Area</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php while ($rowAnt = $resultAnt->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($rowAnt['empleado_nombre'] ?? 'Desconocido') ?></td>
							<td><?= htmlspecialchars($rowAnt['descripcion_programa'] ?? 'Desconocida') ?></td>
                            <td><?= htmlspecialchars($rowAnt['descripcion_area'] ?? 'Desconocida') ?></td>
                        </tr>
                    <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
            <?php
        } else {
            // Buscar por programa y área sin considerar fecha
            $stmtArea = $conn->prepare("
                SELECT DISTINCT e.empleado_nombre, p.descripcion_programa, a.descripcion_area 
                FROM empleados e
                LEFT JOIN programa p ON e.empleado_programa = p.programa_id
				LEFT JOIN area a ON e.empleado_area = a.area_id
                WHERE e.empleado_programa = ? AND e.empleado_area = ?
            ");
            $stmtArea->bind_param("ss", $programa, $area);
            $stmtArea->execute();
            $resultArea = $stmtArea->get_result();

            if ($resultArea->num_rows > 0) {
                ?>
                <h5 class="mt-4" style="font-weight: bold;color:white;">Sugerencia de trabajadores que trabajaron en ese programa y área</h5>
                <div class="d-flex justify-content-left align-items-center mb-3">
                    <a href="tarja_form.php?fecha=<?= urlencode($fecha) ?>&programa=<?= urlencode($programa) ?>&area=<?= urlencode($area) ?>" class="btn btn-green mt-2">
                        Ingresar Tarjas para esta combinación
                    </a>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="table-secondary">
                            <tr>
                                <th>Empleado</th>
                                <th>Programa</th>
								<th>Area</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php while ($rowArea = $resultArea->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($rowArea['empleado_nombre'] ?? 'Desconocido') ?></td>
                                <td><?= htmlspecialchars($rowArea['descripcion_programa'] ?? 'Desconocida') ?></td>
								<td><?= htmlspecialchars($rowArea['descripcion_area'] ?? 'Desconocida') ?></td>
                            </tr>
                        <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
                <?php
            } else {
                echo '<div class="alert alert-info mt-3">No se encontraron sugerencias del día anterior ni del área en registros anteriores.</div>';
            }
        }
    }
}
?>

<?php include 'footer.php'; ?>
