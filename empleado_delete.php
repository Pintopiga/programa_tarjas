<?php include 'auth.php'; ?>
<?php include 'db.php'; ?>

<?php
$id = $_GET['id'];
$stmt = $conn->prepare("DELETE FROM empleados WHERE empleado_id = ?");
$stmt->bind_param("s", $id);
$stmt->execute();
header("Location: empleado_list.php");
?>
