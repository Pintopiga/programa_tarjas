<?php include 'auth.php'; ?>
<?php include 'db.php'; ?>

<?php
$programa_id = $_POST['programa_id'];
$descripcion = $_POST['descripcion'];

if (isset($_POST['original_id'])) {
    // Editar
    $stmt = $conn->prepare("UPDATE programa SET descripcion_programa = ? WHERE programa_id = ?");
    $stmt->bind_param("ss", $descripcion, $programa_id);
} else {
    // Insertar
    $stmt = $conn->prepare("INSERT INTO programa (programa_id, descripcion_programa) VALUES (?, ?)");
    $stmt->bind_param("ss", $programa_id, $descripcion);
}
$stmt->execute();
header("Location: programa_list.php");
?>
