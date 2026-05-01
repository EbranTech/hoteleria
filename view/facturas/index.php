<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facturas — Hotelería</title>
</head>
<body>
<?php require_once __DIR__ . '/../layout/navbar.php'; ?>
<div class="page-container">
    <h1>Listado de Facturas</h1>
    <a href="?action=factura_new" class="btn btn-primary mb-3">Nueva Factura</a>

    <?php
    $labelPago   = ['EF' => 'Efectivo', 'TC' => 'Tarjeta Crédito', 'TR' => 'Transferencia'];
    $labelEstado = ['P'  => 'Pendiente', 'PA' => 'Pagada', 'AN' => 'Anulada'];
    ?>
    <table class="data-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>N° Factura</th>
                <th>Huésped</th>
                <th>Reserva</th>
                <th>Subtotal</th>
                <th>Impuesto</th>
                <th>Total</th>
                <th>Método Pago</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($facturas)): ?>
                <?php foreach ($facturas as $f): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($f['id_factura']); ?></td>
                        <td><?php echo htmlspecialchars($f['num_factura']); ?></td>
                        <td><?php echo htmlspecialchars($f['nombre_huesped']); ?></td>
                        <td>#<?php echo htmlspecialchars($f['id_reserva']); ?></td>
                        <td>Q<?php echo number_format($f['subtotal'], 2); ?></td>
                        <td>Q<?php echo number_format($f['impuesto'], 2); ?></td>
                        <td><strong>Q<?php echo number_format($f['total'], 2); ?></strong></td>
                        <td><?php echo $labelPago[$f['metodo_pago']] ?? $f['metodo_pago']; ?></td>
                        <td><?php echo $labelEstado[$f['estado']] ?? $f['estado']; ?></td>
                        <td>
                            <?php if (tieneRol(['admin','gerente'])): ?>
                                <a href="?action=factura_editar&id=<?php echo urlencode($f['id_factura']); ?>"
                                   class="btn btn-primary btn-sm">Editar</a>
                                <a href="?action=factura_eliminar&id=<?php echo urlencode($f['id_factura']); ?>"
                                   onclick="return confirm('¿Eliminar esta factura?');"
                                   class="btn-danger-link" style="margin-left:8px;">Eliminar</a>
                            <?php else: ?>
                                <span class="text-muted">Solo lectura</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="10" style="text-align:center;padding:16px;color:#7f8c8d;">No hay facturas registradas.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
</body>
</html>
