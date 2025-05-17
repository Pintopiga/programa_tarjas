<?php include 'auth.php'; ?>
<?php include 'db.php'; ?>

<?php
$id = $_GET['id'];
$stmt = $conn->prepare("DELETE FROM tarjas WHERE tarjas_id = ?");
$stmt->bind_param("s", $id);
$stmt->execute();
header("Location: tarja_list.php");
?>
