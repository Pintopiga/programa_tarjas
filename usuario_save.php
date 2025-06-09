<?php
// usuarios_save.php
include 'auth.php';
verificar_acceso('ALL');
include 'db.php';

$id = $_POST['id'] ?? null;
$rut = $_POST['rut'];
$nombre = $_POST['nombre'];
$usuario = $_POST['usuario'];
$clave = $_POST['clave'];
$area = $_POST['area'];
$accesos = $_POST['accesos'] ?? [];

if ($id) {
  if (!empty($clave)) {
    $clave_hash = hash('sha256', $clave);
    $stmt = $conn->prepare("UPDATE usuarios SET tarjas_user_rut=?, tarjas_user_nombre=?, tarjas_user_usuario=?, tarjas_user_clave=?, tarjas_user_area=? WHERE tarjas_user_id=?");
    $stmt->bind_param("isssii", $rut, $nombre, $usuario, $clave_hash, $area, $id);
  } else {
    $stmt = $conn->prepare("UPDATE usuarios SET tarjas_user_rut=?, tarjas_user_nombre=?, tarjas_user_usuario=?, tarjas_user_area=? WHERE tarjas_user_id=?");
    $stmt->bind_param("issii", $rut, $nombre, $usuario, $area, $id);
  }
} else {
  $clave_hash = hash('sha256', $clave);
  $stmt = $conn->prepare("INSERT INTO usuarios (tarjas_user_rut, tarjas_user_nombre, tarjas_user_usuario, tarjas_user_clave, tarjas_user_area) VALUES (?, ?, ?, ?, ?)");
  $stmt->bind_param("isssi", $rut, $nombre, $usuario, $clave_hash, $area);
}
$stmt->execute();

$conn->query("DELETE FROM accesos WHERE tarjas_user_rut = $rut");
foreach ($accesos as $acc) {
  $stmt2 = $conn->prepare("INSERT INTO accesos (tarjas_user_rut, tarjas_user_acceso) VALUES (?, ?)");
  $stmt2->bind_param("is", $rut, $acc);
  $stmt2->execute();
}

header("Location: usuario_list.php");
exit;

?>