<?php 
include 'auth.php';
$title = 'Lista de Tarjetas';
include 'header.php';
include 'navbar.php';
include 'db.php';
?>

<h2 class="mb-4">Consulta de Tarjas</h2>

<form class="row g-3 mb-4" method="GET">
    <div class="col-md-3">
        <label class="form-label">Fecha</label>
        <input type="date" name="fecha" class="form-control" value="<?= $_GET['fecha'] ?? '' ?>">
    </div>
    <div class="col-md-3">
        <label class="form-label">Programa</label>
        <input type="text" name="programa" class="form-control" value="<?= $_GET['programa'] ?? '' ?>">
    </div>
    <div class="col-md-3">
        <label class="form-label">Área</label>
        <input type="text" name="area" class="form-control" value="<?= $_GET['area'] ?? '' ?>">
    </div>
    <div class="col-md-3 align-self-end">
        <button class="btn btn-primary w-100">Buscar</button>
    </div>
</form>

<?php
if (!empty($_GET['fecha']) && !empty($_GET['programa']) && !empty($_GET['area'])):
    $fecha = $_GET['fecha'];
    $programa = $_GET['programa'];
    $area = $_GET['area'];

    $stmt = $conn->prepare("SELECT * FROM tarjas_detalle WHERE tarjas_d_fecha = ? AND tarjas_d_programa = ? AND tarjas_d_area = ?");
    $stmt->bind_param("sss", $fecha, $programa, $area);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0):
?>
    <h5 class="mt-4">Detalles para <?= htmlspecialchars($fecha) ?> - Programa: <?= htmlspecialchars($programa) ?> - Área: <?= htmlspecialchars($area) ?></h5>
    <div class="table-responsive">
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
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
                <td><?= htmlspecialchars($row['tarjas_d_empleado']) ?></td>
                <td><?= htmlspecialchars($row['tarjas_d_labor']) ?></td>
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
        else:
          ?>
              <div class="alert alert-warning mt-4">No se encontraron registros para los criterios seleccionados.</div>
              <a href="tarja_form.php?fecha=<?= urlencode($fecha) ?>&programa=<?= urlencode($programa) ?>&area=<?= urlencode($area) ?>" class="btn btn-success mt-2">
                  Ingresar Tarjas para esta combinación
              </a>
          <?php
              endif;
          
endif;
?>

<?php include 'footer.php'; ?>
