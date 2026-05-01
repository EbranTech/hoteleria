<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuarios — Hotelería</title>
</head>
<body>
<?php require_once __DIR__ . '/../layout/navbar.php'; ?>
<div class="page-container">
    <h1>Listado de Usuarios</h1>

    <?php if (isset($_GET['msg']) && $_GET['msg'] === 'acceso_denegado'): ?>
        <div class="alert alert-warning">No tienes permiso para acceder a esa sección.</div>
    <?php endif; ?>

    <a href="?action=usuario_new" class="btn btn-primary mb-3">Nuevo Usuario</a>

    <table class="data-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Username</th>
                <th>Rol</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($usuarios)): ?>
                <?php foreach ($usuarios as $u): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($u['id']); ?></td>
                        <td><?php echo htmlspecialchars($u['nombre'] ?? ''); ?></td>
                        <td><?php echo htmlspecialchars($u['username']); ?></td>
                        <td><?php echo htmlspecialchars($u['rol'] ?? 'operativo'); ?></td>
                        <td><?php echo $u['estado'] == '1' ? 'Activo' : 'Inactivo'; ?></td>
                        <td>
                            <a href="?action=editar&id=<?php echo urlencode($u['id']); ?>" class="btn btn-primary btn-sm">Editar</a>
                            <a href="?action=eliminar&id=<?php echo urlencode($u['id']); ?>"
                               onclick="return confirm('¿Seguro que deseas eliminar este usuario?');"
                               class="btn-danger-link" style="margin-left:8px;">Eliminar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="6" style="text-align:center; padding:16px; color:#7f8c8d;">No hay usuarios registrados.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
</body>
</html>