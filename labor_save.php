<?php include 'auth.php'; ?>
<?php include 'db.php'; ?>

<?php
$area_id = $_POST['area_id'];
$labor_id = $_POST['labor_id'];
$descripcion_labor = $_POST['descripcion_labor'];
$cc1 = $_POST['cc1'];
$cc2 = $_POST['cc2'];
$cc3 = $_POST['cc3'];
$cc4 = $_POST['cc4'];
$cc5 = $_POST['cc5'];

if (isset($_POST['original_id'])) {
    // Editar
    $stmt = $conn->prepare("UPDATE labor SET area_id = ?, descripcion_labor = ?, cc1 = ?, cc2 = ?, cc3 = ?, cc4 = ?, cc5 = ? WHERE labor_id = ?");
    $stmt->bind_param("ssssssss", $area_id, $descripcion_labor, $cc1, $cc2, $cc3, $cc4, $cc5, $labor_id);
} else {
    // Insertar
    $stmt = $conn->prepare("INSERT INTO labor (area_id, labor_id, descripcion_labor, cc1, cc2, cc3, cc4, cc5) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssss", $area_id, $labor_id, $descripcion_labor, $cc1, $cc2, $cc3, $cc4, $cc5);
}
$stmt->execute();
header("Location: labor_list.php");
?>
