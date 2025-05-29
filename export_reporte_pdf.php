<?php
require 'vendor/autoload.php';
include 'auth.php';
include 'db.php';

// Capturar parámetros
$fecha = $_GET['fecha'] ?? '';
$programa = $_GET['programa'] ?? '';
$area = $_GET['area'] ?? '';
$labor = $_GET['labor'] ?? '';

// Consulta
$sql = "SELECT p.descripcion_programa AS programa, a.descripcion_area AS area, l.descripcion_labor AS labor,
                e.empleado_nombre AS empleado, t.tarjas_d_horas_normales AS horas_normales, t.tarjas_d_horas_extras AS horas_extras, 
                t.tarjas_d_tratos AS tratos, t.tarjas_d_ausencia AS ausencia, l.cc1, l.cc2, l.cc3, l.cc4, l.cc5
        FROM tarjas_detalle t
        JOIN programa p ON t.tarjas_d_programa = p.programa_id
        JOIN area a ON t.tarjas_d_area = a.area_id
        JOIN labor l ON t.tarjas_d_labor = l.labor_id
        JOIN empleados e ON t.tarjas_d_empleado = e.empleado_id
        WHERE 1=1";

if ($fecha) $sql .= " AND t.tarjas_d_fecha = '$fecha'";
if ($programa) $sql .= " AND p.programa_id = '$programa'";
if ($area) $sql .= " AND a.area_id = '$area'";
if ($labor) $sql .= " AND l.labor_id = '$labor'";

$result = $conn->query($sql);

// Crear PDF
$pdf = new TCPDF();
$pdf->AddPage();
$pdf->SetFont('helvetica', '', 10);

$html = '<h3>Reporte de Tarjas</h3>';
$html .= '<table border="1" cellpadding="4">
<thead>
<tr style="background-color:#f2f2f2;">
<th>Programa</th><th>Área</th><th>Labor</th><th>Empleado</th>
<th>Normales</th><th>Extras</th><th>Tratos</th><th>Ausencia</th>
</tr>
</thead><tbody>';

while ($r = $result->fetch_assoc()) {
    $html .= '<tr>';
    foreach ($r as $cell) {
        $html .= '<td>' . htmlspecialchars($cell) . '</td>';
    }
    $html .= '</tr>';
}

$html .= '</tbody></table>';
$pdf->writeHTML($html, true, false, true, false, '');
$pdf->Output('reporte_tarjas.pdf', 'D');
exit;
