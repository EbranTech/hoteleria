<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Habitaciones — Hotelería</title>
</head>
<body>
<?php require_once __DIR__ . '/../layout/navbar.php'; ?>
<div class="page-container">
    <h1>Listado de Habitaciones</h1>

    <?php if (tieneRol(['admin'])): ?>
    <a href="?action=habitacion_new" class="btn btn-primary mb-3">Nueva Habitación</a>
    <?php endif; ?>

    <table class="data-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Número</th>
                <th>Piso</th>
                <th>Tipo</th>
                <th>Capacidad</th>
                <th>Precio/Noche</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($habitaciones)):
                $estados = ['D' => 'Disponible', 'O' => 'Ocupada', 'M' => 'Mantenimiento'];
                foreach ($habitaciones as $h): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($h['id_habitacion']); ?></td>
                        <td><?php echo htmlspecialchars($h['numero']); ?></td>
                        <td><?php echo htmlspecialchars($h['piso']); ?></td>
                        <td><?php echo htmlspecialchars($h['tipo']); ?></td>
                        <td><?php echo htmlspecialchars($h['capacidad']); ?></td>
                        <td>Q<?php echo number_format($h['precio_noche'], 2); ?></td>
                        <td><?php echo $estados[$h['estado']] ?? $h['estado']; ?></td>
                        <td>
                            <?php if (tieneRol(['admin'])): ?>
                                <a href="?action=habitacion_editar&id=<?php echo urlencode($h['id_habitacion']); ?>"
                                   class="btn btn-primary btn-sm">Editar</a>
                            <?php endif; ?>
                            <?php if (tieneRol(['admin','gerente'])): ?>
                                <a href="?action=habitacion_eliminar&id=<?php echo urlencode($h['id_habitacion']); ?>"
                                   onclick="return confirm('¿Eliminar esta habitación?');"
                                   class="btn-danger-link" style="margin-left:8px;">Eliminar</a>
                            <?php endif; ?>
                            <?php if (!tieneRol(['admin','gerente'])): ?>
                                <span class="text-muted">Solo lectura</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="8" style="text-align:center;padding:16px;color:#7f8c8d;">No hay habitaciones registradas.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
</body>
</html>
