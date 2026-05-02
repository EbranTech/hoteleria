<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión — Hotelería</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<div class="login-wrapper">
    <div class="login-card">
        <h1 class="login-title">Sistema Hotelería</h1>
        <p class="login-subtitle">Ingrese sus credenciales para continuar</p>

        <?php if (!empty($error)): ?>
            <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form action="index.php?action=login_post" method="POST">
            <div class="form-group">
                <label for="username">Usuario</label>
                <input type="text" id="username" name="username"
                       value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>"
                       required autocomplete="username">
            </div>

            <div class="form-group">
                <label for="clave">Contraseña</label>
                <input type="password" id="clave" name="clave"
                       required autocomplete="current-password">
            </div>

            <div class="form-group" style="margin-top: 24px;">
                <button type="submit" class="btn-login">Ingresar</button>
            </div>
        </form>
    </div>
</div>

</body>
</html>

