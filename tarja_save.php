<?php
include 'auth.php';
include 'db.php';

$fecha = $_POST['fecha'];
$programa = $_POST['programa'];
$area = $_POST['area'];

$empleados = $_POST['empleado'];
$labor = $_POST['labor'];
$horas_normales = $_POST['horas_normales'];
$horas_extras = $_POST['horas_extras'];
$tratos = $_POST['tratos'];
$ausencias = $_POST['ausencia'];

// Validaciones generales
foreach ($horas_normales as $hn) {
    if ($hn !== '' && floatval($hn) > 9) {
        die("Error: No se pueden ingresar más de 9 horas normales.");
    }
}
foreach ($horas_extras as $he) {
    if ($he !== '' && floatval($he) > 2) {
        die("Error: No se pueden ingresar más de 2 horas extras.");
    }
}

// Guardar cabecera si no existe
$stmt = $conn->prepare("SELECT COUNT(*) as count FROM tarjas WHERE tarjas_fecha = ? AND tarjas_programa = ? AND tarjas_area = ?");
$stmt->bind_param("sss", $fecha, $programa, $area);
$stmt->execute();
$count = $stmt->get_result()->fetch_assoc()['count'];

if ($count == 0) {
    $stmt = $conn->prepare("INSERT INTO tarjas (tarjas_fecha, tarjas_programa, tarjas_area) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $fecha, $programa, $area);
    $stmt->execute();
}

// Eliminar detalles previos
$stmt = $conn->prepare("DELETE FROM tarjas_detalle WHERE tarjas_d_fecha = ? AND tarjas_d_programa = ? AND tarjas_d_area = ?");
$stmt->bind_param("sss", $fecha, $programa, $area);
$stmt->execute();

// Insertar nuevos detalles
$stmt = $conn->prepare("INSERT INTO tarjas_detalle (
    tarjas_d_fecha, tarjas_d_programa, tarjas_d_area,
    tarjas_d_empleado, tarjas_d_labor,
    tarjas_d_horas_normales, tarjas_d_horas_extras,
    tarjas_d_tratos, tarjas_d_ausencia
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");

for ($i = 0; $i < count($empleados); $i++) {
    $e = trim($empleados[$i]);
    $l = trim($labor[$i]);
    $hn = $horas_normales[$i] !== '' ? floatval($horas_normales[$i]) : 0;
    $he = $horas_extras[$i] !== '' ? floatval($horas_extras[$i]) : 0;
    $t = $tratos[$i] !== '' ? floatval($tratos[$i]) : 0;
    $a = intval($ausencias[$i]);

    if ($e === '') continue; // Saltar filas vacías

    $stmt->bind_param("ssssssddi", $fecha, $programa, $area, $e, $l, $hn, $he, $t, $a);
    $stmt->execute();
}

header("Location: tarja_list.php");
exit;
