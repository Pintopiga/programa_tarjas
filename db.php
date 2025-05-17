<?php
$host = '127.0.0.1';
$db = 'gestion_tarjas';
$user = 'root';
$pass = 'tomodachi426'; // Cambia si tu contraseña no está vacía

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>
