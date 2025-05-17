<?php include 'auth.php'; ?>
<?php include 'db.php'; ?>

<?php
$area_id = $_POST['area_id'];
$descripcion = $_POST['descripcion'];

if (isset($_POST['original_id'])) {
    // Editar
    $stmt = $conn->prepare("UPDATE area SET descripcion_area = ? WHERE area_id = ?");
    $stmt->bind_param("ss", $descripcion, $area_id);
} else {
    // Insertar
    $stmt = $conn->prepare("INSERT INTO area (area_id, descripcion_area) VALUES (?, ?)");
    $stmt->bind_param("ss", $area_id, $descripcion);
}
$stmt->execute();
header("Location: area_list.php");
?>
