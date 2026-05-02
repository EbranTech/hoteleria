<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Huéspedes — Hotelería</title>
</head>
<body>
<?php require_once __DIR__ . '/../layout/navbar.php'; ?>
<div class="page-container">
    <h1>Listado de Huéspedes</h1>
    <?php if (tieneRol(['admin'])): ?>
    <a href="?action=huesped_new" class="btn btn-primary mb-3">Nuevo Huésped</a>
    <?php endif; ?>

    <table class="data-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>DPI</th>
                <th>Teléfono</th>
                <th>Email</th>
                <th>Nacionalidad</th>
                <th>Estado</th>
                <th>Registrado por</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($huespedes)): ?>
                <?php foreach ($huespedes as $h): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($h['id_huesped']); ?></td>
                        <td><?php echo htmlspecialchars($h['nombre']); ?></td>
                        <td><?php echo htmlspecialchars($h['apellido']); ?></td>
                        <td><?php echo htmlspecialchars($h['dpi']); ?></td>
                        <td><?php echo htmlspecialchars($h['telefono'] ?? '—'); ?></td>
                        <td><?php echo htmlspecialchars($h['email'] ?? '—'); ?></td>
                        <td><?php echo htmlspecialchars($h['nacionalidad']); ?></td>
                        <td><?php echo $h['estado'] === 'A' ? 'Activo' : 'Inactivo'; ?></td>
                        <td><?php echo htmlspecialchars($h['registrado_por'] ?? '—'); ?></td>
                        <td>
                            <?php if (tieneRol(['admin'])): ?>
                                <a href="?action=huesped_editar&id=<?php echo urlencode($h['id_huesped']); ?>"
                                   class="btn btn-primary btn-sm">Editar</a>
                                <a href="?action=huesped_eliminar&id=<?php echo urlencode($h['id_huesped']); ?>"
                                   onclick="return confirm('¿Eliminar este huésped?');"
                                   class="btn-danger-link" style="margin-left:8px;">Eliminar</a>
                            <?php else: ?>
                                <span class="text-muted">Solo lectura</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="10" style="text-align:center;padding:16px;color:#7f8c8d;">No hay huéspedes registrados.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
</body>
</html>
