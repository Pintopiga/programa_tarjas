<?php
if (session_status() === PHP_SESSION_NONE) session_start();

if (!isset($_SESSION['usuario'])) {
  header("Location: login.php");
  exit;
}

// Unificamos acceso en arreglo
$accesos = isset($_SESSION['accesos']) ? $_SESSION['accesos'] : null;

/**
 * Verifica si el usuario tiene acceso al módulo actual.
 * @param string $modulo
 */
function verificar_acceso($modulo) {
  global $accesos;
  if (!in_array($modulo, $accesos) && !in_array('ALL', $accesos)) {
    // Acceso denegado, redirigir o mostrar error
    http_response_code(403);
    echo "<div style='color:red; padding:20px; font-weight:bold;'>Acceso denegado al módulo: $modulo</div>";
    exit;
  }
}
?>
