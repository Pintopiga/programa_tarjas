<?php 
include 'auth.php';
include 'header.php';
include 'navbar.php';
include 'db.php';
?>

<div class="container mt-4">
  <h2 class="mb-3" style="font-weight: bold;color:white;">Reporte de Tarjas</h2>

  <form method="GET" class="row g-3 mb-4">
    <div class="col-md-3">
      <label style="font-weight: bold;color:white;">Fecha</label>
      <input type="date" name="fecha" class="form-control" required value="<?= isset($_GET['fecha']) ? htmlspecialchars($_GET['fecha']) : '' ?>">
    </div>
    <div class="col-md-3">
      <label style="font-weight: bold;color:white;">Programa</label>
      <select name="programa" class="form-select">
        <option value="">Todos</option>
        <?php
        $res = $conn->query("SELECT programa_id, descripcion_programa FROM programa");
        while ($row = $res->fetch_assoc()):
          $selected = (isset($_GET['programa']) && $_GET['programa'] == $row['programa_id']) ? 'selected' : '';
        ?>
          <option value="<?= $row['programa_id'] ?>" <?= $selected ?>><?= $row['descripcion_programa'] ?></option>
        <?php endwhile; ?>
      </select>
    </div>
    <div class="col-md-3">
      <label style="font-weight: bold;color:white;">Área</label>
      <select name="area" class="form-select">
        <option value="">Todas</option>
        <?php
        $res = $conn->query("SELECT area_id, descripcion_area FROM area");
        while ($row = $res->fetch_assoc()):
          $selected = (isset($_GET['area']) && $_GET['area'] == $row['area_id']) ? 'selected' : '';
        ?>
          <option value="<?= $row['area_id'] ?>" <?= $selected ?>><?= $row['descripcion_area'] ?></option>
        <?php endwhile; ?>
      </select>
    </div>
    <div class="col-md-3">
      <label style="font-weight: bold;color:white;">Labor</label>
      <select name="labor" class="form-select">
        <option value="">Todas</option>
        <?php
        $res = $conn->query("SELECT labor_id, descripcion_labor FROM labor");
        while ($row = $res->fetch_assoc()):
          $selected = (isset($_GET['labor']) && $_GET['labor'] == $row['labor_id']) ? 'selected' : '';
        ?>
          <option value="<?= $row['labor_id'] ?>" <?= $selected ?>><?= $row['descripcion_labor'] ?></option>
        <?php endwhile; ?>
      </select>
    </div>
    <div class="col-md-12 d-flex justify-content-end">
      <button type="submit" class="btn btn-green">Buscar</button>
    </div>
  </form>

  <?php if ($_GET): ?>
    <!-- Aquí va el código para mostrar los resultados del reporte -->
    <div class="table-responsive">
      <table class="table table-bordered table-sm">
        <thead class="table-success">
          <tr>
            <th rowspan="2">Programa</th>
            <th rowspan="2">Área</th>
            <th rowspan="2">Labor</th>
            <th rowspan="2">Empleado</th>
            <th rowspan="2">Normales</th>
            <th rowspan="2">Extras</th>
            <th rowspan="2">Tratos</th>
            <th rowspan="2">Ausencias</th>
            <th colspan="5">Centro Costo</th>
          </tr>
          <tr>
            <th>1</th>
            <th>2</th>
            <th>3</th>
            <th>4</th>
            <th>5</th>
          </tr>
        </thead>
        <tbody>
          <?php
          // Aquí debes construir el SQL dinámicamente según los filtros
          // Ejemplo básico:
          $sql = "SELECT p.descripcion_programa AS programa, a.descripcion_area AS area, l.descripcion_labor AS labor,
              e.empleado_nombre AS empleado, t.tarjas_d_horas_normales AS horas_normales, t.tarjas_d_horas_extras AS horas_extras, 
              t.tarjas_d_tratos AS tratos, (case when t.tarjas_d_ausencia=0 then 'NO APLICA' when t.tarjas_d_ausencia=1 then 'CON GOCE' when t.tarjas_d_ausencia=2 then 'SIN GOCE' end) AS ausencia, l.cc1, l.cc2, l.cc3, l.cc4, l.cc5
          FROM tarjas_detalle t
          JOIN programa p ON t.tarjas_d_programa = p.programa_id
          JOIN area a ON t.tarjas_d_area = a.area_id
          JOIN labor l ON t.tarjas_d_labor = l.labor_id
          JOIN empleados e ON t.tarjas_d_empleado = e.empleado_id
          WHERE 1=1";
          if (!empty($_GET['fecha'])) $sql .= " AND t.tarjas_d_fecha = '" . $conn->real_escape_string($_GET['fecha']) . "'";
          if (!empty($_GET['programa'])) $sql .= " AND p.programa_id = '" . $conn->real_escape_string($_GET['programa']) . "'";
          if (!empty($_GET['area'])) $sql .= " AND a.area_id = " . intval($_GET['area']);
          if (!empty($_GET['labor'])) $sql .= " AND l.labor_id = " . intval($_GET['labor']);
          $res = $conn->query($sql);
          $totales = ['normales' => 0, 'extras' => 0, 'tratos' => 0, 'ausencias' => 0];
          while ($r = $res->fetch_assoc()):
            $totales['normales'] += $r['horas_normales'];
            $totales['extras'] += $r['horas_extras'];
            $totales['tratos'] += $r['tratos'];
          ?>
          <tr>
            <td><?= htmlspecialchars($r['programa']) ?></td>
            <td><?= htmlspecialchars($r['area']) ?></td>
            <td><?= htmlspecialchars($r['labor']) ?></td>
            <td><?= htmlspecialchars($r['empleado']) ?></td>
            <td><?= $r['horas_normales'] ?></td>
            <td><?= $r['horas_extras'] ?></td>
            <td><?= $r['tratos'] ?></td>
            <td><?= $r['ausencia'] ?></td>
            <td><?= $r['cc1'] ?></td><td><?= $r['cc2'] ?></td><td><?= $r['cc3'] ?></td><td><?= $r['cc4'] ?></td><td><?= $r['cc5'] ?></td>
          </tr>
          <?php endwhile; ?>
        </tbody>
        <tfoot class="table-light fw-bold">
          <tr>
            <td colspan="4">TOTALES</td>
            <td><?= $totales['normales'] ?></td>
            <td><?= $totales['extras'] ?></td>
            <td><?= $totales['tratos'] ?></td>
            <td colspan="6"></td>
          </tr>
        </tfoot>
      </table>
    </div>

    <div class="d-flex justify-content-end gap-2 mt-3">
      <a href="export_reporte_excel.php?<?= http_build_query($_GET) ?>" class="btn btn-success">Exportar Excel</a>
    </div>
  <?php endif; ?>
</div>

<?php include 'footer.php'; ?>