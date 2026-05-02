<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservas — Hotelería</title>
</head>
<body>
<?php require_once __DIR__ . '/../layout/navbar.php'; ?>
<div class="page-container">
    <h1>Listado de Reservas</h1>
    <?php if (tieneRol(['admin', 'operativo'])): ?>
    <a href="?action=reserva_new" class="btn btn-primary mb-3">Nueva Reserva</a>
    <?php endif; ?>

    <?php if (isset($_GET['msg']) && $_GET['msg'] === 'acceso_denegado'): ?>
        <div class="alert alert-warning">No tienes permiso para acceder a esa sección.</div>
    <?php endif; ?>

    <?php $labelEstado = ['P' => 'Pendiente', 'C' => 'Confirmada', 'X' => 'Cancelada', 'T' => 'Completada']; ?>
    <table class="data-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Huésped</th>
                <th>Habitación</th>
                <th>Entrada</th>
                <th>Salida</th>
                <th>Precio</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($reservas)): ?>
                <?php foreach ($reservas as $r): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($r['id_reserva']); ?></td>
                        <td><?php echo htmlspecialchars($r['nombre_huesped']); ?></td>
                        <td>Hab. <?php echo htmlspecialchars($r['numero_habitacion']); ?></td>
                        <td><?php echo htmlspecialchars($r['fecha_entrada']); ?></td>
                        <td><?php echo htmlspecialchars($r['fecha_salida']); ?></td>
                        <td>Q<?php echo number_format($r['precio_acordado'], 2); ?></td>
                        <td><?php echo $labelEstado[$r['estado']] ?? $r['estado']; ?></td>
                        <td>
                            <?php if (tieneRol(['admin'])): ?>
                                <a href="?action=reserva_editar&id=<?php echo urlencode($r['id_reserva']); ?>"
                                   class="btn btn-primary btn-sm">Editar</a>
                                <a href="?action=reserva_eliminar&id=<?php echo urlencode($r['id_reserva']); ?>"
                                   onclick="return confirm('¿Eliminar esta reserva?');"
                                   class="btn-danger-link" style="margin-left:8px;">Eliminar</a>
                            <?php else: ?>
                                <span class="text-muted">Solo lectura</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="8" style="text-align:center;padding:16px;color:#7f8c8d;">No hay reservas registradas.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
</body>
</html>
