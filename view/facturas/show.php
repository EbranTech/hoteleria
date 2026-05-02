<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Factura</title>
</head>
<body>
<?php require_once __DIR__ . '/../layout/navbar.php'; ?>
<div class="page-container">
    <h2>Editar Factura</h2>
    <form action="index.php?action=factura_update" method="POST">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($factura['id_factura']); ?>">

        <div style="margin-bottom: 10px;">
            <label for="id_reserva">Reserva:</label><br>
            <select id="id_reserva" name="id_reserva" required>
                <option value="">-- Seleccione una reserva --</option>
                <?php foreach ($reservas as $res): ?>
                    <option value="<?php echo htmlspecialchars($res['id_reserva']); ?>"
                        <?php echo (int)$factura['id_reserva'] === (int)$res['id_reserva'] ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($res['descripcion']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div style="margin-bottom: 10px;">
            <label for="id_huesped">Huésped:</label><br>
            <select id="id_huesped" name="id_huesped" required>
                <option value="">-- Seleccione un huésped --</option>
                <?php foreach ($huespedes as $h): ?>
                    <option value="<?php echo htmlspecialchars($h['id_huesped']); ?>"
                        <?php echo (int)$factura['id_huesped'] === (int)$h['id_huesped'] ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($h['nombre_completo']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div style="margin-bottom: 10px;">
            <label for="num_factura">N° Factura:</label><br>
            <input type="text" id="num_factura" name="num_factura" value="<?php echo htmlspecialchars($factura['num_factura']); ?>" required maxlength="20">
        </div>

        <div style="margin-bottom: 10px;">
            <p style="color: #555; font-style: italic;">* El total (Q <?php echo htmlspecialchars($factura['total']); ?>) se recalculará automáticamente basado en la reserva.</p>
        </div>

        <div style="margin-bottom: 10px;">
            <label for="metodo_pago">Método de Pago:</label><br>
            <select id="metodo_pago" name="metodo_pago">
                <option value="EF" <?php echo $factura['metodo_pago'] === 'EF' ? 'selected' : ''; ?>>Efectivo</option>
                <option value="TC" <?php echo $factura['metodo_pago'] === 'TC' ? 'selected' : ''; ?>>Tarjeta Crédito</option>
                <option value="TR" <?php echo $factura['metodo_pago'] === 'TR' ? 'selected' : ''; ?>>Transferencia</option>
            </select>
        </div>

        <div style="margin-bottom: 10px;">
            <label for="estado">Estado:</label><br>
            <select id="estado" name="estado">
                <option value="P"  <?php echo $factura['estado'] === 'P'  ? 'selected' : ''; ?>>Pendiente</option>
                <option value="PA" <?php echo $factura['estado'] === 'PA' ? 'selected' : ''; ?>>Pagada</option>
                <option value="AN" <?php echo $factura['estado'] === 'AN' ? 'selected' : ''; ?>>Anulada</option>
            </select>
        </div>

        <div class="form-actions">
            <input type="submit" value="Actualizar" class="btn btn-primary">
            <a href="index.php?action=facturas" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>
</body>
</html>
