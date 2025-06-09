<?php
session_start();
include 'db.php';

$usuario = $_POST['usuario'];
$clave = hash('sha256', $_POST['clave']);

$stmt = $conn->prepare("SELECT * FROM usuarios u LEFT JOIN accesos a ON u.tarjas_user_rut = a.tarjas_user_rut WHERE u.tarjas_user_usuario = ? AND u.tarjas_user_clave = ?");
$stmt->bind_param("ss", $usuario, $clave);
$stmt->execute();
$result = $stmt->get_result();

$accesos = [];
$usuario_info = null;

while ($row = $result->fetch_assoc()) {
    if (!$usuario_info) {
        $usuario_info = $row;
    }
    if (!empty($row['tarjas_user_acceso'])) {
        $accesos[] = $row['tarjas_user_acceso'];
    }
}

if ($usuario_info) {
    $_SESSION['usuario_id'] = $usuario_info['tarjas_user_id'];
    $_SESSION['usuario'] = $usuario_info['tarjas_user_usuario'];
    $_SESSION['accesos'] = $accesos;
    header("Location: tarja_list.php");
} else {
    header("Location: login.php?error=Credenciales incorrectas");
}
