<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuario — Hotelería</title>
</head>
<body>
<?php require_once __DIR__ . '/../layout/navbar.php'; ?>
<div class="page-container">
    <h2>Editar Usuario</h2>
    <div class="form-card">
        <form action="index.php?action=usuario_update" method="POST">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($usuario['id']); ?>">

            <div class="form-group">
                <label for="nombre">Nombre completo</label>
                <input type="text" id="nombre" name="nombre"
                       value="<?php echo htmlspecialchars($usuario['nombre'] ?? ''); ?>" required>
            </div>
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username"
                       value="<?php echo htmlspecialchars($usuario['username']); ?>" required>
            </div>
            <div class="form-group">
                <label for="rol">Rol</label>
                <select id="rol" name="rol">
                    <option value="operativo" <?php echo ($usuario['rol'] ?? '') === 'operativo' ? 'selected' : ''; ?>>Operativo</option>
                    <option value="gerente"   <?php echo ($usuario['rol'] ?? '') === 'gerente'   ? 'selected' : ''; ?>>Gerente</option>
                    <option value="admin"     <?php echo ($usuario['rol'] ?? '') === 'admin'     ? 'selected' : ''; ?>>Administrador</option>
                </select>
            </div>
            <div class="form-group">
                <label for="estado">Estado</label>
                <select id="estado" name="estado">
                    <option value="1" <?php echo $usuario['estado'] == '1' ? 'selected' : ''; ?>>Activo</option>
                    <option value="0" <?php echo $usuario['estado'] == '0' ? 'selected' : ''; ?>>Inactivo</option>
                </select>
            </div>
            <p class="text-muted" style="margin-bottom:16px;">La contraseña no se puede modificar desde este formulario.</p>
            <div class="form-actions">
                <input type="submit" value="Actualizar Usuario" class="btn btn-primary">
                <a href="index.php?action=usuarios" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
</body>
</html>
