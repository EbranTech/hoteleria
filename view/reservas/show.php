<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Reserva</title>
</head>
<body>
<?php require_once __DIR__ . '/../layout/navbar.php'; ?>
<div class="page-container">
    <h2>Editar Reserva</h2>
    <form action="index.php?action=reserva_update" method="POST">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($reserva['id_reserva']); ?>">

        <div style="margin-bottom: 10px;">
            <label for="id_huesped">Huésped:</label><br>
            <select id="id_huesped" name="id_huesped" required>
                <option value="">-- Seleccione un huésped --</option>
                <?php foreach ($huespedes as $h): ?>
                    <option value="<?php echo htmlspecialchars($h['id_huesped']); ?>"
                        <?php echo (int)$reserva['id_huesped'] === (int)$h['id_huesped'] ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($h['nombre_completo']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div style="margin-bottom: 10px;">
            <label for="id_habitacion">Habitación:</label><br>
            <select id="id_habitacion" name="id_habitacion" required>
                <option value="">-- Seleccione una habitación --</option>
                <?php foreach ($habitaciones as $hab): ?>
                    <option value="<?php echo htmlspecialchars($hab['id_habitacion']); ?>"
                        <?php echo (int)$reserva['id_habitacion'] === (int)$hab['id_habitacion'] ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($hab['descripcion']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div style="margin-bottom: 10px;">
            <label for="fecha_entrada">Fecha de Entrada:</label><br>
            <input type="date" id="fecha_entrada" name="fecha_entrada" value="<?php echo htmlspecialchars($reserva['fecha_entrada']); ?>" required>
        </div>

        <div style="margin-bottom: 10px;">
            <label for="fecha_salida">Fecha de Salida:</label><br>
            <input type="date" id="fecha_salida" name="fecha_salida" value="<?php echo htmlspecialchars($reserva['fecha_salida']); ?>" required>
        </div>

        <div style="margin-bottom: 10px;">
            <label for="precio_acordado">Precio Acordado (Q):</label><br>
            <input type="number" id="precio_acordado" name="precio_acordado" value="<?php echo htmlspecialchars($reserva['precio_acordado']); ?>" step="0.01" min="0" required>
        </div>

        <div style="margin-bottom: 10px;">
            <label for="estado">Estado:</label><br>
            <select id="estado" name="estado">
                <option value="P" <?php echo $reserva['estado'] === 'P' ? 'selected' : ''; ?>>Pendiente</option>
                <option value="C" <?php echo $reserva['estado'] === 'C' ? 'selected' : ''; ?>>Confirmada</option>
                <option value="X" <?php echo $reserva['estado'] === 'X' ? 'selected' : ''; ?>>Cancelada</option>
                <option value="T" <?php echo $reserva['estado'] === 'T' ? 'selected' : ''; ?>>Completada</option>
            </select>
        </div>

        <div style="margin-bottom: 10px;">
            <label for="observaciones">Observaciones:</label><br>
            <textarea id="observaciones" name="observaciones" rows="3" cols="40"><?php echo htmlspecialchars($reserva['observaciones'] ?? ''); ?></textarea>
        </div>

        <div class="form-actions">
            <input type="submit" value="Actualizar" class="btn btn-primary">
            <a href="index.php?action=reservas" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>
</body>
</html>
