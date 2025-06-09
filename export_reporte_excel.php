<?php
require 'vendor/autoload.php';
include 'auth.php';
include 'db.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Capturar parámetros
$fecha = $_GET['fecha'] ?? '';
$programa = $_GET['programa'] ?? '';
$area = $_GET['area'] ?? '';
$labor = $_GET['labor'] ?? '';

// Consulta con filtros
$sql = "SELECT p.descripcion_programa AS programa, a.descripcion_area AS area, l.descripcion_labor AS labor,
                e.empleado_nombre AS empleado, t.tarjas_d_horas_normales AS horas_normales, t.tarjas_d_horas_extras AS horas_extras, 
                t.tarjas_d_tratos AS tratos, (case when t.tarjas_d_ausencia=0 then 'NO APLICA' when t.tarjas_d_ausencia=1 then 'CON GOCE' when t.tarjas_d_ausencia=2 then 'SIN GOCE' end) AS ausencia, l.cc1, l.cc2, l.cc3, l.cc4, l.cc5
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

// Crear Excel
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Encabezados
$sheet->fromArray(['Programa', 'Área', 'Labor', 'Empleado', 'Normales', 'Extras', 'Tratos', 'Ausencia', 'Centro Costo 1', 'Centro Costo 2', 'Centro Costo 3', 'Centro Costo 4', 'Centro Costo 5'], NULL, 'A1');

// Datos
$row = 2;
while ($r = $result->fetch_assoc()) {
    $sheet->fromArray(array_values($r), NULL, "A$row");
    $row++;
}

// Descargar
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="reporte_tarjas.xlsx"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
