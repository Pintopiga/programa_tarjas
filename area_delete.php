<?php include 'auth.php'; ?>
<?php include 'db.php'; ?>

<?php
$id = $_GET['id'];
$stmt = $conn->prepare("DELETE FROM area WHERE area_id = ?");
$stmt->bind_param("s", $id);
$stmt->execute();
header("Location: area_list.php");
?>
