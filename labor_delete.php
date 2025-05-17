<?php include 'auth.php'; ?>
<?php include 'db.php'; ?>

<?php
$id = $_GET['id'];
$stmt = $conn->prepare("DELETE FROM labor WHERE labor_id = ?");
$stmt->bind_param("s", $id);
$stmt->execute();
header("Location: labor_list.php");
?>
