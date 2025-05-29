<?php session_start(); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body class="text-center">
<div class="container vh-100 d-flex justify-content-center align-items-center">
    <div class="col-md-5 login-card">
        <div class="text-center">
            <img src="assets/img/cropped-Logo-1.png" alt="Logo Pinto Piga" class="logo">
            <h4 class="h3 mb-3 font-weight-normal login-title">Programa de Tarjas</h4>
        </div>

        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger mt-2"><?= htmlspecialchars($_GET['error']) ?></div>
        <?php endif; ?>

        <form action="login_check.php" method="post" class="mt-4 form-signin">
                <label for="usuario" class="sr-only login-title">Usuario</label>
                <input type="text" name="usuario" id="usuario" class="form-control" placeholder="Usuario" required>
            
                <label for="clave" class="sr-only login-title">Clave</label>
                <input type="password" name="clave" id="clave" class="form-control" placeholder="Clave" required>
            
            <button type="submit" class="btn btn-lg btn-green w-100 btn-block">Ingresar</button>
        </form>
    </div>
</div>
</body>
</html>
