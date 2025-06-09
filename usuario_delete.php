<?php
// usuarios_delete.php
include 'auth.php';
verificar_acceso('ALL');
include 'db.php';

$id = $_GET['id'];
$stmt = $conn->prepare("SELECT tarjas_user_rut FROM usuarios WHERE tarjas_user_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();
if ($row = $res->fetch_assoc()) {
  $rut = $row['tarjas_user_rut'];
  $conn->query("DELETE FROM accesos WHERE tarjas_user_rut = $rut");
  $conn->query("DELETE FROM usuarios WHERE tarjas_user_id = $id");
}

header("Location: usuarios_list.php");
exit;
?>
