<?php
session_start();
include 'db.php';

$usuario = $_POST['usuario'];
$clave = hash('sha256', $_POST['clave']);

$stmt = $conn->prepare("SELECT * FROM usuarios WHERE usuario = ? AND clave = ?");
$stmt->bind_param("ss", $usuario, $clave);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    $_SESSION['usuario_id'] = $row['usuario_id'];
    $_SESSION['usuario'] = $row['usuario'];
    $_SESSION['rol'] = $row['rol'];
    header("Location: programa_list.php"); // o el inicio principal
} else {
    header("Location: login.php?error=Credenciales incorrectas");
}
