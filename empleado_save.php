<?php include 'auth.php'; ?>
<?php include 'db.php'; ?>

<?php
$empleado_id = $_POST['empleado_id'];
$empleado_nombre = $_POST['empleado_nombre'];
$empleado_programa = $_POST['empleado_programa'];
$empleado_area = $_POST['empleado_area'];

if (isset($_POST['original_id'])) {
    // Editar
    $stmt = $conn->prepare("UPDATE empleados SET empleado_nombre = ?, empleado_programa = ?, empleado_area = ? WHERE empleado_id = ?");
    $stmt->bind_param("ssss", $empleado_nombre, $empleado_programa, $empleado_area, $empleado_id);
} else {
    // Insertar
    $stmt = $conn->prepare("INSERT INTO empleados (empleado_id, empleado_nombre, empleado_programa, empleado_area) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $empleado_id, $empleado_nombre, $empleado_programa, $empleado_area);
}
$stmt->execute();
header("Location: empleado_list.php");
?>
