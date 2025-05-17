<?php include 'auth.php'; ?>
<?php include 'db.php'; ?>

$clave = $_POST['clave'];
$valor = $_POST['valor'];
$descripcion = $_POST['descripcion'];
$usuario = $_SESSION['usuario'];

if (isset($_POST['original_clave'])) {
    // Update
    $stmt = $conn->prepare("UPDATE parametro SET valor = ?, descripcion = ?, actualizado_por = ?, actualizado_en = NOW() WHERE clave = ?");
    $stmt->bind_param("ssss", $valor, $descripcion, $usuario, $clave);
} else {
    // Insert
    $stmt = $conn->prepare("INSERT INTO parametro (clave, valor, descripcion, creado_por) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $clave, $valor, $descripcion, $usuario);
}

$stmt->execute();
header("Location: parametro_list.php");
