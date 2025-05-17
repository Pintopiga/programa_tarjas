<?php include 'auth.php'; ?>
<?php include 'db.php'; ?>

<?php
$id = $_GET['id'];
$stmt = $conn->prepare("DELETE FROM programa WHERE programa_id = ?");
$stmt->bind_param("s", $id);
$stmt->execute();
header("Location: programa_list.php");
?>
